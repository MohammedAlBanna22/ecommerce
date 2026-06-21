<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    //
    protected $fillable = [
        'path',
        'type',
        'is_primary',
        'sort_order',
        'mediable_id',
        'mediable_type',
    ];

    public function mediable()
    {
        return $this->morphTo();
    }
}
