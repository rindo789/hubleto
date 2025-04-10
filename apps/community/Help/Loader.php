<?php

namespace HubletoApp\Community\Help;

class Loader extends \HubletoMain\Core\App
{

  public function init(): void
  {
    parent::init();

    $this->main->router->httpGet([
      '/^help\/?$/' => Controllers\Help::class,
    ]);

    // $this->main->sidebar->addLink(1, 1900, 'help', $this->translate('Help'), 'fas fa-life-ring', str_starts_with($this->main->requestedUri, 'help'));
  }

  public function installDefaultPermissions(): void
  {
    $mPermission = new \HubletoApp\Community\Settings\Models\Permission($this->main);
    $permissions = [
      "HubletoApp/Community/Help/Controllers/Help",
      "HubletoApp/Community/Help/Help",
    ];

    foreach ($permissions as $permission) {
      $mPermission->eloquent->create([
        "permission" => $permission
      ]);
    }
  }

}