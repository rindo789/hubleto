<?php

namespace HubletoApp\Community\Mail\Controllers\Api;

class MarkAsRead extends \HubletoMain\Core\Controllers\Controller {

  public int $returnType = \ADIOS\Core\Controller::RETURN_TYPE_JSON;
  public bool $permittedForAllUsers = true;

  public function renderJson(): ?array
  {
    $idMail = $this->main->urlParamAsInteger('idMail');
    $mMail = new \HubletoApp\Community\Mail\Models\Mail($this->main);
    $mMail->record->find($idMail)->update(['datetime_read' => date('Y-m-d H:i:s')]);
    return ['success' => true];
  }

}