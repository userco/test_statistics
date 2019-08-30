<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentItem extends Model
{
    protected $table = 'student_item';
	protected $fillable = [
        'id', 'item_score', 'answer', 'item_id', 'student_id'
    ];
	protected $primaryKey = 'id';
}
