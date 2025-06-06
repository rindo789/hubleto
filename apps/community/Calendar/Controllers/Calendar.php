<?php

namespace HubletoApp\Community\Calendar\Controllers;

class Calendar extends \HubletoMain\Core\Controllers\Controller
{

  public function getBreadcrumbs(): array
  {
    return array_merge(parent::getBreadcrumbs(), [
      [ 'url' => 'calendar', 'content' => $this->translate('Calendar') ],
    ]);
  }

  public function prepareView(): void
  {
    parent::prepareView();
    $calendarManager = $this->main->apps->community('Calendar')->calendarManager;
    foreach ($calendarManager->getCalendars() as $calendarClass => $calendar) {
      if ($calendarClass == "HubletoApp\Community\CalendarSync\Calendar") continue;
      $calendarConfig = $calendar->activitySelectorConfig;
      $calendarConfig['color'] = $calendar->getColor();
      $this->viewParams["calendarConfigs"][] = $calendarConfig;
    }
    $this->setView('@HubletoApp:Community:Calendar/Calendar.twig');
  }

}