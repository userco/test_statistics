<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected $table = 'test';
	protected $fillable = [
        'id', 'user_id', 'count_distractors', 'students_count', 'items_count', 'mean', 'sd', 'disperse', 'min_bal', 'max_bal',
		'min_difficulty', 'max_difficulty', 'min_discrimination', 'max_discrimination',
		'min_rpbis', 'max_rpbis', 'reliability', 'kr20', 'sem', 'median', 'result_processed'
    ];
	public $timestamps = false;
	protected $primaryKey = 'id';
}
