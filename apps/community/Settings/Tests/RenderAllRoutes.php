<?php

namespace HubletoApp\Community\Settings\Tests;

class RenderAllRoutes extends \HubletoMain\Core\AppTest
{

  public function run(): void
  {
    $routes = [
      'settings',
      'settings/users',
      'settings/user-roles',
      'settings/profiles',
      'settings/general',
      'settings/contact-tags',
      'settings/customer-tags',
      'settings/deal-tags',
      'settings/lead-tags',
      'settings/activity-types',
      'settings/contact-categories',
      'settings/countries',
      'settings/currencies',
      'settings/pipelines',
      'settings/permissions',
      'settings/invoice-profiles',
      'settings/config',
    ];

    foreach ($routes as $route) {
      $this->cli->cyan("Rendering route '{$route}'.\n");
      $this->main->render($route);
    }
  }

}
