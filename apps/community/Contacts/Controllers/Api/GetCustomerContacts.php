<?php

namespace HubletoApp\Community\Contacts\Controllers\Api;

use Exception;
use HubletoApp\Community\Contacts\Models\Person;

class GetCustomerContacts extends \HubletoMain\Core\Controllers\Controller {
  public int $returnType = \ADIOS\Core\Controller::RETURN_TYPE_JSON;

  public function renderJson(): ?array
  {
    $mPerson = new Person($this->main);
    $persons = null;
    $personArray = [];

    try {
      $persons = $mPerson->record->selectRaw("*, CONCAT(first_name, ' ', last_name) as _LOOKUP");
      if ($this->main->urlParamAsInteger("id_customer") > 0) {
        $persons = $persons->where("id_customer", (int) $this->main->urlParamAsInteger("id_customer"));
      }
      if (strlen($this->main->urlParamAsString("search")) > 1) {
        $persons->whereRaw("CONCAT(first_name, ' ', last_name) LIKE '%".$this->main->urlParamAsString("search")."%'");
      }

      $persons = $persons->get()->toArray();

    } catch (Exception $e) {
      return [
        "status" => "failed",
        "error" => $e
      ];
    }

    foreach ($persons as $person) { //@phpstan-ignore-line
      $personArray[$person["id"]] = $person;
    }

    return $personArray;
  }
}
