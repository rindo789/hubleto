<?php

namespace CeremonyCrmApp\Modules\Core\Settings\Models\Eloquent;

use CeremonyCrmApp\Modules\Core\Settings\Models\Eloquent\User;
use \Illuminate\Database\Eloquent\Relations\HasMany;
use \Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ContactType extends \ADIOS\Core\Model\Eloquent
{
  public $table = 'contact_types';

}