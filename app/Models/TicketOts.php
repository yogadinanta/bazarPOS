<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketOts extends Model
{
    protected $table = 'ticket_ots';

    protected $fillable = [
        'voucher_code',
        'payment_method'
    ];
}