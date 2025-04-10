<?php

namespace HubletoApp\Community\Deals\Models\Eloquent;

use HubletoApp\Community\Deals\Models\Eloquent\Tag;
use HubletoApp\Community\Deals\Models\Eloquent\Deal;
use \Illuminate\Database\Eloquent\Relations\HasMany;
use \Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DealTag extends \HubletoMain\Core\ModelEloquent
{
  public $table = 'cross_deal_tags';

  /** @return BelongsTo<Deal, covariant DealTag> */
  public function DEAL(): BelongsTo {
    return $this->belongsTo(Deal::class, 'id_deal', 'id');
  }

  /** @return BelongsTo<Tag, covariant DealTag> */
  public function TAG(): BelongsTo {
    return $this->belongsTo(Tag::class, 'id_tag', 'id');
  }

}
