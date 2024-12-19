<?php

namespace CeremonyCrmMod\Settings\Models\Eloquent;

use CeremonyCrmMod\Settings\Models\Eloquent\User;
use \Illuminate\Database\Eloquent\Relations\HasMany;
use \Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Tag extends \ADIOS\Core\Model\Eloquent
{
  public $table = 'tags';

}