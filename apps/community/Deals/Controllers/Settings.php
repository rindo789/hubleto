<?php

namespace HubletoApp\Community\Deals\Controllers;

class Settings extends \HubletoMain\Core\Controllers\Controller
{

  public function getBreadcrumbs(): array
  {
    return array_merge(parent::getBreadcrumbs(), [
      [ 'url' => 'deals', 'content' => $this->translate('Deals') ],
      [ 'url' => 'settings', 'content' => $this->translate('Settings') ],
    ]);
  }

  public function prepareView(): void
  {
    parent::prepareView();

    $settingsChanged = $this->main->urlParamAsBool('settingsChanged');

    if ($settingsChanged) {
      $showMostValuableDealsInDashboard = $this->main->urlParamAsBool('showMostValuableDealsInDashboard');
      $this->hubletoApp->setConfigAsBool('showMostValuableDealsInDashboard', $showMostValuableDealsInDashboard);
      $this->hubletoApp->saveConfig('showMostValuableDealsInDashboard', $showMostValuableDealsInDashboard ? '1' : '0');

      $showDealValueByResultInDashboard = $this->main->urlParamAsBool('showDealValueByResultInDashboard');
      $this->hubletoApp->setConfigAsBool('showDealValueByResultInDashboard', $showDealValueByResultInDashboard);
      $this->hubletoApp->saveConfig('showDealValueByResultInDashboard', $showDealValueByResultInDashboard ? '1' : '0');

      $calendarColor = $this->main->urlParamAsString('calendarColor');
      $this->hubletoApp->setConfigAsString('calendarColor', $calendarColor);
      $this->hubletoApp->saveConfig('calendarColor', $calendarColor);

      $this->viewParams['settingsSaved'] = true;
    }

    $this->setView('@HubletoApp:Community:Deals/Settings.twig');
  }

}