<?php

namespace HubletoMain\Core\Api;

class LogJavascriptError extends \HubletoMain\Core\Controllers\Controller
{
  public int $returnType = \ADIOS\Core\Controller::RETURN_TYPE_JSON;
  public bool $permittedForAllUsers = true;

  public function renderJson(): array
  {
    $logFolder = $this->main->config->getAsString('logFolder');
    $errorRoute = $this->main->urlParamAsString('errorRoute');
    $errors = $this->main->urlParamAsArray('errors');

    if (!is_dir($logFolder)) {
      @mkdir($logFolder);
    }

    $msg = 
      "---------------------------------------------------------\n"
      . date('Y-m-d H:i:s') . ' ' . $errorRoute
    ;
    foreach ($errors as $error) {
      $msg .= "\n   " . trim($error);
    }

    @file_put_contents($logFolder . '/javascript-errors.log', $msg . "\n");

    return [
      'errorRoute' => $errorRoute,
      'errors' => $errors
    ];
  }

}
