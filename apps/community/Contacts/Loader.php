<?php

namespace HubletoApp\Community\Contacts;

class Loader extends \HubletoMain\Core\App
{
  const DEFAULT_INSTALLATION_CONFIG = [
    'sidebarOrder' => 0,
  ];

  public function init(): void
  {
    parent::init();

    $this->main->router->httpGet([
      '/^contacts\/?$/' => Controllers\Persons::class,
      '/^contacts\/get-customer-contacts\/?$/' => Controllers\Api\GetCustomerContacts::class,
      '/^settings\/contact-tags\/?$/' => Controllers\Tags::class,
      '/^settings\/contact-categories\/?$/' => Controllers\ContactCategories::class,
    ]);

    $this->setConfigAsInteger('sidebarOrder', 0);

    $this->main->addSetting(['title' => $this->translate('Contact Categories'), 'icon' => 'fas fa-phone', 'url' => 'settings/contact-categories']);
    $this->main->addSetting([
      'title' => $this->translate('Contact Tags'),
      'icon' => 'fas fa-tags',
      'url' => 'settings/contact-tags',
    ]);
  }


  public function installTables(): void
  {

  }

  public function installDefaultPermissions(): void
  {
    $mPermission = new \HubletoApp\Community\Settings\Models\Permission($this->main);
    $permissions = [];

    foreach ($permissions as $permission) {
      $mPermission->eloquent->create([
        "permission" => $permission
      ]);
    }
  }
}