<?php

namespace CeremonyCrmApp\Modules\Core\Customers\Models;

class Tag extends \CeremonyCrmApp\Core\Model
{
  public string $table = 'tags';
  public string $eloquentClass = Eloquent\Tag::class;
  public ?string $lookupSqlValue = "{%TABLE%}.name";

  public function columns(array $columns = []): array
  {
    return parent::columns(array_merge($columns, [
      "name" => [
        "type" => "varchar",
        "title" => "Name",
      ],
      "color" => [
        "type" => "color",
        "title" => "Color",
      ],
    ]));
  }

  public function tableDescribe(array $description = []): array
  {
    $description = parent::tableDescribe();
    $description['title'] = 'Tags';
    return $description;
  }

}