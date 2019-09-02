<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected $table = 'test';
	protected $fillable = [
        'id', 'count_distractors', 'students_count', 'items_count', 'mean', 'sd', 'disperse', 'min_bal', 'max_bal',
		'reliability', 'kr20', 'sem', 'median'
    ];
	public $timestamps = false;
	protected $primaryKey = 'id';
}
