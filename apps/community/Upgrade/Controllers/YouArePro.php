<?php

namespace HubletoApp\Community\Upgrade\Controllers;

class YouArePro extends \HubletoMain\Core\Controller {

  public function prepareView(): void
  {
    parent::prepareView();

    if ($this->main->urlParamAsString('simulate') == 'down') {
      @unlink($this->main->configAsString('accountDir') . '/pro');
      $this->main->router->redirectTo('');
    }

    $this->setView('@app/community/Upgrade/Views/YouArePro.twig');
  }

}