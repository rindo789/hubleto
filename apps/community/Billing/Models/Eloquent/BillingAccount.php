<?php

namespace HubletoApp\Community\Billing\Models\Eloquent;

use HubletoApp\Community\Customers\Models\Eloquent\Customer;

use \Illuminate\Database\Eloquent\Relations\HasMany;
use \Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BillingAccount extends \HubletoMain\Core\ModelEloquent
{
  public $table = 'billing_accounts';

  /** @return BelongsTo<Customer, covariant BillingAccount> */
  public function CUSTOMER(): BelongsTo {
    return $this->belongsTo(Customer::class, 'id_customer', 'id' );
  }

  /** @return HasMany<BillingAccountService, covariant BillingAccount> */
  public function SERVICES(): HasMany {
    return $this->hasMany(BillingAccountService::class, 'id_billing_account', 'id');
  }
}
