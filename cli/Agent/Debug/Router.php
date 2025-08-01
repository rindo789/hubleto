<?php

namespace HubletoMain\Cli\Agent\Debug;

class Router extends \HubletoMain\Cli\Agent\Command
{
  public array $releaseConfig = [];

  public function run(): void
  {

    $routeToDebug = (string) ($this->arguments[3] ?? '');

    if (empty($routeToDebug)) {
      $routes = $this->main->router->getRoutes(\HubletoMain\Router::HTTP_GET);

      \Hubleto\Terminal::cyan("Available routes (Route -> Controller):\n");
      foreach ($routes as $route => $controller) {
        \Hubleto\Terminal::cyan("  {$route} -> {$controller}\n");
      }
    } else {
      \Hubleto\Terminal::cyan("Debugging route '" . $routeToDebug . "':\n");
      $controller = $this->main->router->findController(\HubletoMain\Router::HTTP_GET, $routeToDebug);
      $variables = $this->main->router->extractRouteVariables(\HubletoMain\Router::HTTP_GET, $routeToDebug);
      \Hubleto\Terminal::cyan("  - Controller: " . $controller . "\n");
      \Hubleto\Terminal::cyan("  - Variables:\n");
      foreach ($variables as $varName => $varValue) {
        \Hubleto\Terminal::cyan("      {$varName} = {$varValue}\n");
      }
    }

  }
}
