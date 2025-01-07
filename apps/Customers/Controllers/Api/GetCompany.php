<?php

namespace HubletoApp\Customers\Controllers\Api;

use HubletoApp\Customers\Models\Company;
use HubletoApp\Customers\Models\Person;
use Exception;

class GetCompany extends \HubletoMain\Core\Controller {

  public function renderJson(): ?array
  {

    $mCompany = new Company($this->app);
    $companies = null;
    $companyArray = [];

    try {
      $companies = $mCompany->eloquent->selectRaw("*, name as _LOOKUP");
      /**
       * The string needs to be at least two characters long for the search to activate
       * due to the lookup inputs not clearing the search parameter when empty
       */
      if ($this->app->params["search"] != "" && strlen($this->app->params["search"]) > 1) {
        $companies
          ->where("name", "LIKE", "%".$this->app->params["search"]."%")
          ->orWhere("tax_id", "LIKE", "%".$this->app->params["search"]."%")
          ->orWhere("company_id", "LIKE", "%".$this->app->params["search"]."%")
          ->orWhere("vat_id", "LIKE", "%".$this->app->params["search"]."%")
        ;
      }

      $companies = $companies->get()->toArray();

    } catch (Exception $e) {
      return [
        "status" => "failed",
        "error" => $e
      ];
    }

    foreach ($companies as $company) {
      $companyArray[$company["id"]] = $company;
    }

    return $companyArray;
  }
}
