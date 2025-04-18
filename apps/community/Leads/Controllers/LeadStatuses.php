<?php

namespace HubletoApp\Community\Leads\Controllers;

class LeadStatuses extends \HubletoMain\Core\Controllers\Controller {


  public function getBreadcrumbs(): array
  {
    return array_merge(parent::getBreadcrumbs(), [
      [ 'url' => 'settings', 'content' => $this->translate('Settings') ],
      [ 'url' => 'lead-statuses', 'content' => $this->translate('Lead Statuses') ],
    ]);
  }

  public function prepareView(): void
  {
    parent::prepareView();
    $this->setView('@HubletoApp:Community:Leads/LeadStatuses.twig');
  }

}