<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected $table = 'item';
	protected $fillable = [
        'id', 'count_distractors', 'students_count', 'items_count', 'mean', 'sd', 'disperse', 'min_bal', 'max_bal',
		'reliability', 'kr20', 'sem', 'median'
    ];
	protected $primaryKey = 'id';
}
