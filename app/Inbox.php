<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inbox extends Model
{
    protected $fillable = [
        'code', 'sender', 'status', 'transaction_id', 'image', 'thumb', 'message', 'tanggal', 'op', 'total', 'terminal', 'identifier'
    ];
}
