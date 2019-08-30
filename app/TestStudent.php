<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestStudent extends Model
{
    protected $table = 'test_student';
	protected $fillable = [
        'id','mark', 'test_score', 'student_id', 'test_id'
    ];
	protected $primaryKey = 'id';
}
