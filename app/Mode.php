<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mode extends Model
{
    protected $table = 'mode';
	protected $fillable = [
        'id', 'test_id', 'mode'
    ];
	protected $primaryKey = 'id';
}
