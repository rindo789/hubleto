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
      '/^contacts\/check-primary-contact\/?$/' => Controllers\Api\CheckPrimaryContact::class,
      '/^settings\/contact-tags\/?$/' => Controllers\Tags::class,
      '/^settings\/contact-categories\/?$/' => Controllers\ContactCategories::class,
    ]);

    $this->setConfigAsInteger('sidebarOrder', 0);

    $this->main->addSetting($this, ['title' => $this->translate('Contact Categories'), 'icon' => 'fas fa-phone', 'url' => 'settings/contact-categories']);
    $this->main->addSetting($this, [
      'title' => $this->translate('Contact Tags'),
      'icon' => 'fas fa-tags',
      'url' => 'settings/contact-tags',
    ]);
  }


  public function installTables(int $round): void
  {
    if ($round == 1) {
      $mContactCategory = new Models\ContactCategory($this->main);
      $mPerson = new Models\Person($this->main);
      $mContact = new Models\Contact($this->main);
      $mPersonTag = new Models\Tag($this->main);
      $mCrossPersonTag = new Models\PersonTag($this->main);

      $mContactCategory->dropTableIfExists()->install();
      $mPerson->dropTableIfExists()->install();
      $mContact->dropTableIfExists()->install();
      $mPersonTag->dropTableIfExists()->install();
      $mCrossPersonTag->dropTableIfExists()->install();

      $mContactCategory->record->recordCreate([ 'name' => 'Work' ]);
      $mContactCategory->record->recordCreate([ 'name' => 'Home' ]);
      $mContactCategory->record->recordCreate([ 'name' => 'Other' ]);

      $mPersonTag->record->recordCreate([ 'name' => "IT manager", 'color' => '#D33115' ]);
      $mPersonTag->record->recordCreate([ 'name' => "CEO", 'color' => '#4caf50' ]);
      $mPersonTag->record->recordCreate([ 'name' => "Desicion Maker", 'color' => '#fcc203' ]);
      $mPersonTag->record->recordCreate([ 'name' => "Sales", 'color' => '#2196f3' ]);
      $mPersonTag->record->recordCreate([ 'name' => "Support", 'color' => '#03fc8c' ]);
      $mPersonTag->record->recordCreate([ 'name' => "Other", 'color' => '#383838' ]);
    }
  }

  public function installDefaultPermissions(): void
  {
    $mPermission = new \HubletoApp\Community\Settings\Models\Permission($this->main);
    $permissions = [];

    foreach ($permissions as $permission) {
      $mPermission->record->recordCreate([
        "permission" => $permission
      ]);
    }
  }
}