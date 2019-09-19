<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
	
    protected $table = 'item';
	protected $fillable = [
        'id','test_id', 'right_answer', 'difficulty', 'discrimination', 'number', 'number_correct',
		'number_incorrect', 'mean_correct', 'mean_incorrect', 'rpbis','disperse_rem', 'mean_rem',
		'kr20_rem', 'test_score_rem'
    ];
	public $timestamps = false;
	protected $primaryKey = 'id';
	
}
