<?php

namespace HubletoApp\Community\Customers;

use HubletoApp\Community\Customers\Models\CustomerActivity;

class Calendar extends \HubletoMain\Core\Calendar {

  public array $formComponent = [
    "title" => "Customer",
    "form" => "CustomersFormActivity",
  ];

  public function loadEvents(): array
  {
    $idCustomer = $this->main->urlParamAsInteger('idCustomer');
    $dateStart = '';
    $dateEnd = '';

    if ($this->main->isUrlParam("start") && $this->main->isUrlParam("end")) {
      $dateStart = date("Y-m-d H:i:s", (int) strtotime($this->main->urlParamAsString("start")));
      $dateEnd = date("Y-m-d H:i:s", (int) strtotime($this->main->urlParamAsString("end")));
    } else {
      $dateStart = date("Y-m-d H:i:s");
      $dateEnd = date("Y-m-d H:i:s", strtotime("+1 day"));
    }

    $mCustomerActivity = new CustomerActivity($this->main);

    $activities = $mCustomerActivity->eloquent
      ->select("customer_activities.*", "activity_types.color", "activity_types.name as activity_type")
      ->leftJoin("activity_types", "activity_types.id", "=", "customer_activities.id_activity_type")
      ->where("date_start", ">=", $dateStart)
      ->where("date_start", "<=", $dateEnd)
    ;

    if ($idCustomer > 0) $activities = $activities->where("id_customer", $idCustomer);

    $activities = $activities->get();
    $events = [];

    foreach ($activities as $key => $activity) { //@phpstan-ignore-line

      $dStart = (string) $activity->date_start;
      $tStart = (string) $activity->time_start;
      $dEnd = (string) $activity->date_end;
      $tEnd = (string) $activity->time_end;

      $events[$key]['id'] = $activity->id;

      if ($tStart != '') $events[$key]['start'] = $dStart . " " . $tStart;
      else $events[$key]['start'] = $dStart;

      if ($dEnd != '') {
        if ($tEnd != '') $events[$key]['end'] = $dEnd . " " . $tEnd;
        else $events[$key]['end'] = $dEnd;
      } else if ($tEnd != '') {
        $events[$key]['end'] = $dStart . " " . $tEnd;
      }

      //fix for fullCalendar not showing the last date of an event longer than one day
      if ((!empty($dStart) && !empty($dEnd) && (strtotime($dEnd) > strtotime($dStart)))) {
        if (empty($tEnd) || empty($tStart)) $events[$key]['end'] = date("Y-m-d", strtotime("+ 1 day", strtotime($dEnd)));
      }

      $events[$key]['allDay'] = $activity->all_day == 1 || $tStart == null ? true : false;
      $events[$key]['title'] = $activity->subject;
      $events[$key]['backColor'] = $activity->color;
      $events[$key]['color'] = $activity->color;
      $events[$key]['type'] = $activity->activity_type;
    }

    return $events;
  }

}