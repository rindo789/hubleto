<?php

namespace HubletoApp\Community\Leads\Controllers;

class LeadsArchive extends \HubletoMain\Core\Controller {


  public function getBreadcrumbs(): array
  {
    return array_merge(parent::getBreadcrumbs(), [
      [ 'url' => 'leads', 'content' => $this->translate('Leads') ],
      [ 'url' => '', 'content' => $this->translate('Archive') ],
    ]);
  }

  public function prepareView(): void
  {
    parent::prepareView();
    $this->setView('@app/community/Leads/Views/LeadsArchive.twig');
  }
}