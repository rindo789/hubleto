<?php

namespace CeremonyCrmApp\Modules\Core\Settings\Models;

class Profile extends \CeremonyCrmApp\Core\Model
{
  public string $fullTableSqlName = 'profiles';
  public string $table = 'profiles';
  public string $eloquentClass = Eloquent\Setting::class;
  public ?string $lookupSqlValue = "{%TABLE%}.company";

  public function columns(array $columns = []): array
  {
    return parent::columns([
      'company' => [
        'type' => 'varchar',
        'byte_size' => '250',
        'title' => 'Company',
        'show_column' => true
      ],
    ]);
  }

  public function tableParams(array $params = []): array
  {
    $params = parent::tableParams();
    $params['title'] = 'Profiles';
    return $params;
  }

}