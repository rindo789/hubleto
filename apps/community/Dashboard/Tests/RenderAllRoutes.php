<?php

namespace HubletoApp\Community\Dashboard\Tests;

class RenderAllRoutes extends \HubletoMain\Core\AppTest
{

  public function run(): void
  {
    $routes = [
      '',
    ];

    foreach ($routes as $route) {
      $this->cli->cyan("Rendering route '{$route}'.\n");
      $this->main->render($route);
    }
  }

}
