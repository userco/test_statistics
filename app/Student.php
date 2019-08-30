<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'student';
	protected $fillable = [
        'id', 'test_id', 'class_number', 'test_score', 'name'
    ];
	protected $primaryKey = 'id';
}
