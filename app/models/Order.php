<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    const STATE_CREATED = 'CREATED';
    const STATE_PAYED = 'PAYED';
    const STATE_REJECTED = 'REJECTED';
    
    protected $table = 'orders';
}
