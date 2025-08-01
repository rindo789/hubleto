<?php

namespace HubletoApp\Community\Leads\Controllers;

class Levels extends \HubletoMain\Controller
{
  public function prepareView(): void
  {
    parent::prepareView();

    // these parameters are generated by the `php hubleto` command
    $this->viewParams['now'] = date('Y-m-d H:i:s');
    $this->viewParams['randomNumber'] = rand(1, 1000);

    $this->setView('@HubletoApp:Community:Leads/Levels.twig');
  }

}
