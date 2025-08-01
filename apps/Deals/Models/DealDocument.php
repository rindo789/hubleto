<?php

namespace HubletoApp\Community\Deals\Models;

use Hubleto\Framework\Db\Column\Lookup;
use HubletoApp\Community\Documents\Models\Document;

class DealDocument extends \Hubleto\Framework\Models\Model
{
  public string $table = 'deal_documents';
  public string $recordManagerClass = RecordManagers\DealDocument::class;

  public array $relations = [
    'DEAL' => [ self::BELONGS_TO, Deal::class, 'id_lookup', 'id' ],
    'DOCUMENT' => [ self::BELONGS_TO, Document::class, 'id_document', 'id' ],
  ];

  public function describeColumns(): array
  {
    return array_merge(parent::describeColumns(), [
      'id_lookup' => (new Lookup($this, $this->translate('Deal'), Deal::class, "CASCADE"))->setRequired(),
      'id_document' => (new Lookup($this, $this->translate('Document'), Document::class, "CASCADE"))->setRequired(),
    ]);
  }

  public function describeInput(string $columnName): \Hubleto\Framework\Description\Input
  {
    $description = parent::describeInput($columnName);
    switch ($columnName) {
      case 'hyperlink':
        $description->setReactComponent('InputHyperlink');
        break;
    }
    return $description;
  }

  public function describeTable(): \Hubleto\Framework\Description\Table
  {
    $description = parent::describeTable();
    if ($this->main->urlParamAsInteger('idDeal') > 0) {
      // $description->permissions = [
      //   'canRead' => $this->main->permissions->granted($this->fullName . ':Read'),
      //   'canCreate' => $this->main->permissions->granted($this->fullName . ':Create'),
      //   'canUpdate' => $this->main->permissions->granted($this->fullName . ':Update'),
      //   'canDelete' => $this->main->permissions->granted($this->fullName . ':Delete'),
      // ];
      $description->columns = [];
      $description->inputs = [];
      $description->ui = [];
    }

    return $description;
  }

  public function onBeforeDelete(int $id): int
  {
    $idDocument = (int) $this->record->find($id)->toArray()["id_document"];
    (new Document($this->main))->onBeforeDelete($idDocument);
    (new Document($this->main))->record->where("id", $idDocument)->delete();

    return $id;
  }
}
