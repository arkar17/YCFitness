<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankingInfo extends Model
{
    use HasFactory;
    protected $fillable = ['payment_type','payment_name','bank_account_number','bank_account_holder','phone','account_name'];
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    