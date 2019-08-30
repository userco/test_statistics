<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Distractors extends Model
{
    protected $table = 'distractors';
	protected $fillable = [
        'id', 'letter', 'item_id', 'count_answers', 'discrimination'
    ];
	protected $primaryKey = 'id';
}
