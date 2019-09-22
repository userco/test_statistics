<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Distractor extends Model
{
    protected $table = 'distractor';
	protected $fillable = [
        'id', 'letter', 'item_id', 'count_answers', 'discrimination'
    ];
	protected $primaryKey = 'id';
	public $timestamps = false;
}
