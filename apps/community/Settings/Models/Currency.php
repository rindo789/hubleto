<?php

namespace HubletoApp\Community\Settings\Models;

class Currency extends \HubletoMain\Core\Model
{
  public string $table = 'currencies';
  public string $eloquentClass = Eloquent\Currency::class;
  public ?string $lookupSqlValue = 'CONCAT({%TABLE%}.name ," ","(",{%TABLE%}.code,")")';

  public function columnsLegacy(array $columns = []): array
  {
    return parent::columnsLegacy([
      'name' => [
        'type' => 'varchar',
        'title' => $this->translate('Currency Name'),
      ],
      'code' => [
        'type' => 'varchar',
        'byte_size' => '5',
        'title' => $this->translate('Currency Code'),
      ],
    ]);
  }

  public function tableDescribe(array $description = []): array
  {
    $description = parent::tableDescribe($description);

    if (is_array($description['ui'])) {
      $description['ui']['title'] = 'Currencies';
      $description['ui']['addButtonText'] = 'Add currency';
      $description['ui']['showHeader'] = true;
      $description['ui']['showFooter'] = false;
    }

    return $description;
  }

  public function formDescribe(array $description = []): array
  {
    $description = parent::formDescribe();

    $id = $this->main->urlParamAsInteger('id');

    if (is_array($description['ui'])) {
      $description['ui']['title'] = ($id == -1 ? "New currency" : "Currency");
      $description['ui']['subTitle'] = ($id == -1 ? "Adding" : "Editing");
    }

    return $description;
  }

}
