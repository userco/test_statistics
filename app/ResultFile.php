<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResultFile extends Model
{
    protected $table = 'result_file';
	protected $fillable = [
        'id', 'user_id', 'test_id', 'file_name', 'result_date'
    ];
	protected $primaryKey = 'id';
	public $timestamps = false;
}