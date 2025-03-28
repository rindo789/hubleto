<?php

namespace HubletoApp\Community\Leads\Models\Eloquent;

use HubletoApp\Community\Documents\Models\Eloquent\Document;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LeadDocument extends \HubletoMain\Core\ModelEloquent
{
  public $table = 'lead_documents';

  /** @return BelongsTo<Document, covariant LeadDocument> */
  public function DOCUMENT(): BelongsTo {
    return $this->belongsTo(Document::class, 'id_document', 'id');
  }

  /** @return BelongsTo<Lead, covariant LeadDocument> */
  public function LEAD(): BelongsTo {
    return $this->belongsTo(Lead::class, 'id_lookup', 'id');
  }

}
