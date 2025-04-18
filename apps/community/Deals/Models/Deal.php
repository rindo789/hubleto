<?php

namespace HubletoApp\Community\Deals\Models;

use ADIOS\Core\Db\Column\Boolean;
use ADIOS\Core\Db\Column\Date;
use ADIOS\Core\Db\Column\DateTime;
use ADIOS\Core\Db\Column\Decimal;
use ADIOS\Core\Db\Column\Integer;
use ADIOS\Core\Db\Column\Lookup;
use ADIOS\Core\Db\Column\Text;
use ADIOS\Core\Db\Column\Varchar;
use HubletoApp\Community\Contacts\Models\Person;
use HubletoApp\Community\Customers\Models\Customer;
use HubletoApp\Community\Leads\Models\Lead;
use HubletoApp\Community\Settings\Models\Currency;
use HubletoApp\Community\Settings\Models\Pipeline;
use HubletoApp\Community\Settings\Models\PipelineStep;
use HubletoApp\Community\Settings\Models\Setting;
use HubletoApp\Community\Settings\Models\User;
use HubletoMain\Core\Helper;

class Deal extends \HubletoMain\Core\Models\Model
{
  public string $table = 'deals';
  public string $recordManagerClass = RecordManagers\Deal::class;
  public ?string $lookupSqlValue = '{%TABLE%}.title';

  public array $relations = [
    'LEAD' => [ self::BELONGS_TO, Lead::class, 'id_lead', 'id'],
    'CUSTOMER' => [ self::BELONGS_TO, Customer::class, 'id_customer', 'id' ],
    'USER' => [ self::BELONGS_TO, User::class, 'id_user', 'id'],
    'PERSON' => [ self::HAS_ONE, Person::class, 'id', 'id_person'],
    'PIPELINE' => [ self::HAS_ONE, Pipeline::class, 'id', 'id_pipeline'],
    'PIPELINE_STEP' => [ self::HAS_ONE, PipelineStep::class, 'id', 'id_pipeline_step'],
    'CURRENCY' => [ self::HAS_ONE, Currency::class, 'id', 'id_currency'],
    'HISTORY' => [ self::HAS_MANY, DealHistory::class, 'id_deal', 'id'],
    'TAGS' => [ self::HAS_MANY, DealTag::class, 'id_deal', 'id' ],
    'PRODUCTS' => [ self::HAS_MANY, DealProduct::class, 'id_deal', 'id' ],
    'ACTIVITIES' => [ self::HAS_MANY, DealActivity::class, 'id_deal', 'id' ],
    'DOCUMENTS' => [ self::HAS_MANY, DealDocument::class, 'id_lookup', 'id'],
  ];

  public function describeColumns(): array
  {
    return array_merge(parent::describeColumns(), [
      'identifier' => (new Varchar($this, $this->translate('Deal Identifier'))),
      'title' => (new Varchar($this, $this->translate('Title')))->setRequired(),
      'id_customer' => (new Lookup($this, $this->translate('Customer'), Customer::class))->setFkOnUpdate('CASCADE')->setFkOnDelete('RESTRICT')->setRequired(),
      'id_person' => (new Lookup($this, $this->translate('Contact person'), Person::class))->setFkOnUpdate('CASCADE')->setFkOnDelete('SET NULL'),
      'id_lead' => (new Lookup($this, $this->translate('Lead'), Lead::class))->setFkOnUpdate('CASCADE')->setFkOnDelete('SET NULL')->setReadonly(),
      'price' => (new Decimal($this, $this->translate('Price')))->setRequired(),
      'id_currency' => (new Lookup($this, $this->translate('Currency'), Currency::class))->setFkOnUpdate('RESTRICT')->setFkOnDelete('SET NULL')->setRequired()->setReadonly(),
      'date_expected_close' => (new Date($this, $this->translate('Expected close date'))),
      'id_user' => (new Lookup($this, $this->translate('Assigned user'), User::class))->setRequired(),
      'date_created' => (new DateTime($this, $this->translate('Date created')))->setRequired()->setReadonly(),
      'id_pipeline' => (new Lookup($this, $this->translate('Pipeline'), Pipeline::class))->setFkOnUpdate('CASCADE')->setFkOnDelete('SET NULL'),
      'id_pipeline_step' => (new Lookup($this, $this->translate('Pipeline step'), PipelineStep::class))->setFkOnUpdate('CASCADE')->setFkOnDelete('SET NULL'),
      'shared_folder' => new Varchar($this, "Shared folder (online document storage)"),
      'note' => (new Text($this, $this->translate('Notes'))),
      'source_channel' => (new Varchar($this, $this->translate('Source channel'))),
      'is_archived' => (new Boolean($this, $this->translate('Archived'))),
      'deal_result' => (new Integer($this, $this->translate('Deal Result')))
        ->setEnumValues([1 => "Lost", 2 => "Won", 3 => "Pending"])->setDefaultValue(3),
      'date_result_update' => (new DateTime($this, $this->translate('Date of result update')))->setReadonly(),
      'is_new_customer' => new Boolean($this, $this->translate('New Customer')),
      'business_type' => (new Integer($this, $this->translate('Business type')))->setEnumValues(
        [1 => "New", 2 => "Existing"]
      ),
    ]);
  }

  public function describeInput(string $columnName): \ADIOS\Core\Description\Input
  {
    $description = parent::describeInput($columnName);
    switch ($columnName) {
      case 'shared_folder':
        $description
          ->setReactComponent('InputHyperlink')
          ->setDescription($this->translate('Link to shared folder (online storage) with related documents'))
        ;
        break;
      case 'deal_result':
          $description->setEnumCssClasses([
            1 => "!text-red-500",
            2 => "!text-green-500",
            3 => "!text-white-500",
          ]);
        break;
      default:
        break;
    }
    return $description;
  }

  public function describeTable(): \ADIOS\Core\Description\Table
  {
    $description = parent::describeTable();
    if ($this->main->urlParamAsBool("showArchive")) {
      $description->ui['title'] = "Archived deals";
      $description->permissions = [
        "canCreate" => false,
        "canUpdate" => false,
        "canRead" => true,
        "canDelete" => $this->main->permissions->granted($this->fullName . ':Delete')
      ];
    } else {
      $description->ui['title'] = 'Deal';
      $description->ui['addButtonText'] = 'Add Deal';
    }
    $description->ui['showHeader'] = true;
    $description->ui['showFulltextSearch'] = true;
    $description->ui['showFooter'] = false;
    $description->columns['tags'] = ["title" => "Tags"];
    unset($description->columns['note']);
    unset($description->columns['id_person']);
    unset($description->columns['source_channel']);
    unset($description->columns['is_archived']);
    unset($description->columns['id_lead']);
    unset($description->columns['id_pipeline']);
    unset($description->columns['shared_folder']);
    unset($description->columns['date_result_update']);

    if ($this->main->urlParamAsInteger('idCustomer') > 0) {
      $description->permissions = [
        'canRead' => $this->main->permissions->granted($this->fullName . ':Read'),
        'canCreate' => $this->main->permissions->granted($this->fullName . ':Create'),
        'canUpdate' => $this->main->permissions->granted($this->fullName . ':Update'),
        'canDelete' => $this->main->permissions->granted($this->fullName . ':Delete'),
      ];
      $description->columns = [];
      $description->inputs = [];
      $description->ui = [];
    }

    return $description;
  }

  public function describeForm(): \ADIOS\Core\Description\Form
  {
    $mSettings = new Setting($this->main);
    $defaultPipeline = (int) $mSettings->record
      ->where("key", "Apps\Community\Settings\Pipeline\DefaultPipeline")
      ->first()
      ->value
    ;
    $defaultCurrency = (int) $mSettings->record
      ->where("key", "Apps\Community\Settings\Currency\DefaultCurrency")
      ->first()
      ->value
    ;

    $description = parent::describeForm();
    $description->defaultValues['is_new_customer'] = 0;
    $description->defaultValues['business_type'] = 1;
    $description->defaultValues['is_archived'] = 0;
    $description->defaultValues['date_created'] = date("Y-m-d H:i:s");
    $description->defaultValues['id_currency'] = $defaultCurrency;
    $description->defaultValues['id_pipeline'] = $defaultPipeline;
    $description->defaultValues['id_pipeline_step'] = null;
    $description->defaultValues['id_user'] = $this->main->auth->getUserId();

    return $description;
  }

  public function onAfterCreate(array $originalRecord, array $savedRecord): array
  {
    $mDealHistory = new DealHistory($this->main);
    $mDealHistory->record->recordCreate([
      "change_date" => date("Y-m-d"),
      "id_deal" => $savedRecord["id"],
      "description" => "Deal created"
    ]);

    return parent::onAfterCreate($originalRecord, $savedRecord);
  }

  public function onAfterUpdate(array $originalRecord, array $savedRecord): array
  {
    if (isset($originalRecord["TAGS"])) {
      $helper = new Helper($this->main, $this->app);
      $helper->deleteTags(
        array_column($originalRecord["TAGS"], "id"),
        "HubletoApp/Community/Deals/Models/DealTag",
        "id_deal",
        $originalRecord["id"]
      );
    }
    return $savedRecord;
  }

  public function getOwnership(array $record): void
  {
    if ($record["id_customer"] && !isset($record["checkOwnership"])) {
      $mCustomer = new Customer($this->main);
      $customer = $mCustomer->record
        ->where("id", $record["id_customer"])
        ->first()
      ;

      if ($customer->id_user != $record["id_user"]) {
        throw new \Exception("This deal cannot be assigned to the selected user,\nbecause they are not assigned to the selected customer.");
      }
    }
  }

  public function onBeforeCreate(array $record): array
  {
    $this->getOwnership($record);
    return $record;
  }

  public function onBeforeUpdate(array $record): array
  {
    $this->getOwnership($record);

    $deal = $this->record->find($record["id"])->toArray();
    $mDealHistory = new DealHistory($this->main);

    if ((float) $deal["price"] != (float) $record["price"]) {
      $mDealHistory->record->recordCreate([
        "change_date" => date("Y-m-d"),
        "id_deal" => (int) ($record["id"] ?? 0),
        "description" => "Price changed to " . (string) ($record["price"] ?? '')
      ]);
    }
    if ((string) $deal["date_expected_close"] != (string) $record["date_expected_close"]) {
      $mDealHistory->record->recordCreate([
        "change_date" => date("Y-m-d"),
        "id_deal" => $record["id"],
        "description" => "Expected Close Date changed to ".date("d.m.Y", (int) strtotime((string) $record["date_expected_close"]))
      ]);
    }

    return $record;
  }
}
