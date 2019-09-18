<?php

namespace Modules\Opx\Counters\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Counter extends Model
{
    use SoftDeletes;

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at',
    ];
}