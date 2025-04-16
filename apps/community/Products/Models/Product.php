<?php

namespace HubletoApp\Community\Products\Models;

use \ADIOS\Core\Db\Column\Lookup;
use \ADIOS\Core\Db\Column\Varchar;
use \ADIOS\Core\Db\Column\Text;
use \ADIOS\Core\Db\Column\Boolean;
use \ADIOS\Core\Db\Column\Date;
use \ADIOS\Core\Db\Column\Image;
use \ADIOS\Core\Db\Column\Decimal;
use ADIOS\Core\Db\Column\Integer;
use HubletoApp\Community\Settings\Models\Setting;

class Product extends \HubletoMain\Core\Model
{
  public string $table = 'products';
  public string $recordManagerClass = RecordManagers\Product::class;
  public ?string $lookupSqlValue = '{%TABLE%}.title';

  public array $relations = [
    'GROUP' => [ self::HAS_ONE, Group::class, 'id','id_product_group'],
    'SUPPLIER' => [ self::HAS_ONE, Supplier::class, 'id','id_supplier'],
  ];

  public function describeColumns(): array
  {
    return array_merge(parent::describeColumns(), [
      'title' => (new Varchar($this, $this->translate('Title')))->setRequired(),
      'id_product_group' => (new Lookup($this, $this->translate('Product Group'), Group::class))->setFkOnUpdate('CASCADE')->setFkOnDelete('SET NULL'),
      'type' => (new Integer($this, $this->translate('Product Type')))->setRequired()->setEnumValues(
        [1 => "Single Item", 2 => "Service"]
      ),
      'id_supplier' => (new Lookup($this, $this->translate('Supplier'), Supplier::class))->setFkOnUpdate('CASCADE')->setFkOnDelete('SET NULL'),
      'is_on_sale' => new Boolean($this, $this->translate('On sale')),
      'image' => new Image($this, $this->translate('Image') . ' [540x600px]'),
      'description' => new Text($this, $this->translate('Description')),
      'count_in_package' => new Decimal($this, $this->translate('Number of items in package')),
      'unit_price' => (new Decimal($this, $this->translate('Single unit price')))->setRequired(),
      'margin' => (new Decimal($this, $this->translate('Margin')))->setUnit("%"),
      'tax' => (new Decimal($this, $this->translate('Tax')))->setUnit("%")->setRequired(),
      'is_single_order_possible' => new Boolean($this, $this->translate('Single unit order possible')),
      'unit' => new Varchar($this, $this->translate('Unit')),
      'packaging' => new Varchar($this, $this->translate('Packaging')),
      'sale_ended' => new Date($this, $this->translate('Sale ended')),
      'show_price' => new Boolean($this, $this->translate('Show price to customer')),
      'price_after_reweight' => new Boolean($this, $this->translate('Set price after reweight?')),
      'needs_reordering' => new Boolean($this, $this->translate('Needs reordering?')),
      'storage_rules' => new Text($this, $this->translate('Storage rules')),
      'table' => new Text($this, $this->translate('Table')),
    ]);
  }

  public function describeTable(): \ADIOS\Core\Description\Table
  {
    $description = parent::describeTable();

    $description->ui['title'] = 'Products';
    $description->ui["addButtonText"] = $this->translate("Add product");

    $description->columns['unit_price']->setColorScale('green-to-red');
    $description->columns['margin']->setColorScale('light-blue-to-dark-blue');

    unset($description->columns["is_on_sale"]);
    unset($description->columns["image"]);
    unset($description->columns["count_in_package"]);
    unset($description->columns["is_single_order_possible"]);
    unset($description->columns["packaging"]);
    unset($description->columns["show_price"]);
    unset($description->columns["price_after_reweight"]);
    unset($description->columns["needs_reordering"]);
    unset($description->columns["storage_rules"]);
    unset($description->columns["table"]);
    unset($description->columns["description"]);

    return $description;
  }
}
