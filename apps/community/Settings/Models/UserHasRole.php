<?php

namespace HubletoApp\Community\Settings\Models;

use \ADIOS\Core\Db\Column\Lookup;

class UserHasRole extends \HubletoMain\Core\Model
{
  public string $table = 'user_has_roles';
  public string $eloquentClass = Eloquent\UserHasRole::class;

  public function describeColumns(): array
  {
    return array_merge(parent::describeColumns(), [
      'id_user' => (new Lookup($this, $this->translate('User'), User::class)),
      'id_role' => (new Lookup($this, $this->translate('Role'), UserRole::class)),
    ]);
  }

  public function describeTable(): \ADIOS\Core\Description\Table
  {
    $description = parent::describeTable();

    $description->ui['title'] = 'Role Assigments';
    $description->ui['addButtonText'] = 'Assign Roles';
    $description->ui['showHeader'] = true;
    $description->ui['showFulltextSearch'] = true;
    $description->ui['showFooter'] = false;

    return $description;
  }
}