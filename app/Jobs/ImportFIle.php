<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use DB;
use App\Test;
use App\Item;
use App\Student;
use App\StudentItem;

class ImportFIle implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
	protected $fileContents;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($array)
    {
        $this->fileContents = $array;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
		$i = 0;
		//insert new test
		$test = new Test;
		$test->save();
        foreach($this->fileContents as $row){
			$j = 0;
			foreach($row as $cell){
				if($i==0 && $j> 2 && $cell){
					//insert item key
					$item = new Item;
					$item->test_id = $test->id;
					$item->number = $j-2;
					$item->right_answer = $cell;
					$item->save();
				}
				if($i==1 && $j == 3 && $cell){
					//insert count distractors
					$test->count_distractors = $cell;
					$test->save();
				}
				if($i>=3 && $j == 0 && $cell){
					//insert student
					$student = new Student;
					$student->test_id = $test->id;
					$student->class_number = $cell;
					$student->save();
				}
				if($i>=3 && $j == 2 && $cell){
					//insert student
					var_dump($cell);
					$student->name = $cell;
					$student->save();
				}
				if($i>=3 && $j > 3 && $cell){
					//insert student item
					$studentItem = new StudentItem;
					$studentItem->answer = $cell;
					$studentItem->item_id = $item->id;
					$studentItem->student_id = $student->id;
					$studentItem->save();
				}
				$j++;
			}
			$i++;	
		}	
    }
}
