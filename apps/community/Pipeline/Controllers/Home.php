<?php

namespace HubletoApp\Community\Pipeline\Controllers;

use HubletoApp\Community\Settings\Models\Tag;
use HubletoApp\Community\Settings\Models\Pipeline;
use HubletoApp\Community\Settings\Models\Setting;
use HubletoApp\Community\Deals\Models\Deal;

class Home extends \HubletoMain\Core\Controller {


  public function getBreadcrumbs(): array
  {
    return array_merge(parent::getBreadcrumbs(), [
      [ 'url' => '', 'content' => $this->translate('Pipeline') ],
    ]);
  }

  public function prepareView(): void
  {
    parent::prepareView();

    $mSetting = new Setting($this->main);
    $mPipeline = new Pipeline($this->main);
    $mDeal = new Deal($this->main);
    $mTag = new Tag($this->main);
    $sumPipelinePrice = 0;

    $pipelines = $mPipeline->eloquent->get();

    $defaultPipeline = $mSetting->eloquent
      ->select("value")
      ->where("key", "Apps\Community\Settings\Pipeline\DefaultPipeline")
      ->first()
    ;
    $defaultPipelineId = (int) $defaultPipeline->value;

    $searchPipeline = null;

    if ($this->main->isUrlParam("id_pipeline")) {
      $searchPipeline = (array) $mPipeline->eloquent
        ->where("id", (int) $this->main->urlParamAsInteger("id_pipeline"))
        ->with("PIPELINE_STEPS")
        ->first()
        ->toArray()
      ;
    }
    else {
      $searchPipeline = (array) $mPipeline->eloquent
        ->where("id", $defaultPipelineId)
        ->with("PIPELINE_STEPS")
        ->first()
        ->toArray()
      ;
    }

    foreach ((array) $searchPipeline["PIPELINE_STEPS"] as $key => $step) {
      $step = (array) $step;

      $sumPrice = (float) $mDeal->eloquent
        ->selectRaw("SUM(price) as price")
        ->where("id_pipeline", $searchPipeline["id"])
        ->where("id_pipeline_step", $step["id"])
        ->first()
        ->price
      ;
      $currencyGroups = (array) $mDeal->eloquent
        ->selectRaw("SUM(price) as price, currencies.code")
        ->where("id_pipeline", $searchPipeline["id"])
        ->where("id_pipeline_step", $step["id"])
        ->groupBy("id_currency")
        ->join("currencies", "currencies.id", "=", "deals.id_currency")
        ->get()
        ->toArray();
      ;

      $searchPipeline["PIPELINE_STEPS"][$key]["sum_price"] = $sumPrice;
      $searchPipeline["PIPELINE_STEPS"][$key]["currency_groups"] = $currencyGroups;
      $sumPipelinePrice += $sumPrice;
    }

    $searchPipeline["price"] = $sumPipelinePrice;

    $currencyGroups = $mDeal->eloquent
      ->selectRaw("SUM(price) as price, currencies.code")
      ->where("id_pipeline", $searchPipeline["id"])
      ->groupBy("id_currency")
      ->join("currencies", "currencies.id", "=", "deals.id_currency")
      ->get()
      ->toArray();
    ;

    $deals = $mDeal->eloquent
      ->where("id_pipeline", (int) $searchPipeline["id"])
      ->with("CURRENCY")
      ->with("CUSTOMER")
      ->with("TAGS")
      ->get()
      ->toArray()
    ;

    foreach ((array) $deals as $key => $deal) {
      if (empty($deal["TAGS"])) continue;
      $tag = $mTag->eloquent->find($deal["TAGS"][0]["id_tag"])?->toArray();
      $deals[$key]["TAG"] = $tag;
      unset($deals[$key]["TAGS"]);
    }

    //var_dump($deals); exit;
    $this->viewParams["pipelines"] = $pipelines;
    $this->viewParams["pipeline"] = $searchPipeline;
    $this->viewParams["pipeline"]["currency_groups"] = $currencyGroups;
    $this->viewParams["deals"] = $deals;

    $this->setView('@HubletoApp:Community:Pipeline/Home.twig');
  }

}