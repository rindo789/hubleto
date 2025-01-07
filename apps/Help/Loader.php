<?php

namespace HubletoApp\Help;

class Loader extends \HubletoMain\Core\Module
{

  public function __construct(\HubletoMain $app)
  {
    parent::__construct($app);
  }

  public function init(): void
  {
    $this->app->router->httpGet([
      '/^help\/?$/' => Controllers\Help::class,
    ]);

    // $this->app->sidebar->addLink(1, 1900, 'help', $this->translate('Help'), 'fas fa-life-ring', str_starts_with($this->app->requestedUri, 'help'));
  }

}