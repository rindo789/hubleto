<?php

namespace HubletoApp\Community\Documents\Models;

use HubletoApp\Community\Customers\Models\CompanyDocument;
use HubletoApp\Community\Deals\Models\Deal;
use HubletoApp\Community\Leads\Models\LeadDocument;
use HubletoApp\Community\Deals\Models\DealDocument;
use HubletoApp\Community\Leads\Models\Lead;

class Document extends \HubletoMain\Core\Model
{
  public string $table = 'documents';
  public string $eloquentClass = Eloquent\Document::class;
  public ?string $lookupSqlValue = '{%TABLE%}.name';

  public array $relations = [
    'COMPANY_DOCUMENT' => [ self::HAS_ONE, CompanyDocument::class, 'id_document', 'id' ],
    'LEAD_DOCUMENT' => [ self::HAS_ONE, LeadDocument::class, 'id_document', 'id' ],
    'DEAL_DOCUMENT' => [ self::HAS_ONE, DealDocument::class, 'id_document', 'id' ],
  ];

  public function columns(array $columns = []): array
  {
    return parent::columns(array_merge($columns, [
      "name" => [
        "title" => "Document name",
        "type" => "varchar",
        "required" => true,
      ],
      "file" => [
        "title" => "File",
        "type" => "file",
        "required" => true,
      ],
    ]));
  }

  public function tableDescribe(array $description = []): array
  {
    $description["model"] = $this->fullName;
    $description = parent::tableDescribe($description);
    $description['ui']['title'] = 'Documents';
    $description['ui']['addButtonText'] = 'Add Document';
    $description['ui']['showHeader'] = true;
    return $description;
  }

  public function formDescribe(array $description = []): array
  {
    $description = parent::formDescribe();
    $description['includeRelations'] = ['COMPANY_DOCUMENT', 'LEAD_DOCUMENT', 'DEAL_DOCUMENT'];
    return $description;
  }

  public function onAfterCreate(array $record, $returnValue)
  {
    $mCompanyDocument = new CompanyDocument($this->main);
    $mLead = new Lead($this->main);
    $mDeal = new Deal($this->main);
    $mLeadDocument = new LeadDocument($this->main);
    $mDealDocument = new DealDocument($this->main);

    if (isset($record["creatingForModel"])) {
      if ($record["creatingForModel"] == "Company") {
        $mCompanyDocument->eloquent->create([
          "id_document" => $record["id"],
          "id_company" => $record["creatingForId"]
        ]);
      } else if ($record["creatingForModel"] == "Lead") {

        $lead = $mLead->eloquent->find($record["creatingForId"]);
        $mCompanyDocument->eloquent->create([
          "id_document" => $record["id"],
          "id_company" => $record["creatingForId"]
        ]);
        $mLeadDocument->eloquent->create([
          "id_document" => $record["id"],
          "id_lead" => $lead->id
        ]);
      } else if ($record["creatingForModel"] == "Deal") {

        $deal = $mDeal->eloquent->find($record["creatingForId"]);
        $mCompanyDocument->eloquent->create([
          "id_document" => $record["id"],
          "id_company" => $record["creatingForId"]
        ]);
        $mDealDocument->eloquent->create([
          "id_document" => $record["id"],
          "id_deal" => $deal->id
        ]);
      }
    }
    return $record;
  }

  public function onBeforeUpdate(array $record): array
  {
    $document = $this->eloquent->find($record["id"])->toArray();
    $prevFilename = ltrim($document["file"],"./");
    if (file_exists($this->main->config['uploadDir']."/".$prevFilename)) {
      unlink($this->main->config['uploadDir']."/".$prevFilename);
    }

    return $record;
  }

  public function onBeforeDelete(int $id): int
  {
    $document = $this->eloquent->find($id)->toArray();
    $prevFilename = ltrim($document["file"],"./");
    if (file_exists($this->main->config['uploadDir']."/".$prevFilename)) {
      unlink($this->main->config['uploadDir']."/".$prevFilename);
    }

    return $id;
  }
}
