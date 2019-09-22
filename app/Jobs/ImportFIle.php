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
use App\Distractor;

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
		$user = \Auth::user();
		$userId = $user->id;
		//insert new test
		$test = new Test;
		$test->user_id = $userId;
		$test->save();
        foreach($this->fileContents as $row){
			$j = 0;
			$student_id = null;
			foreach($row as $cell){
				if($i==0 && $j > 2 && $cell){
					//insert item key
					$item = new Item;
					$item->test_id = $test->id;
					$item->number = $j-2;
					$item->right_answer = $cell;
					$item->save();
				}
				else if($i==1 && $j == 3 && $cell){
					//insert count distractors
					$test->count_distractors = $cell;
					$test->save();
					$test_id = $test->id;
					$items = Item::where('test_id', $test_id)->get();
					$letters = [ 'A', 'B', 'C', 'D', 'E'];
					foreach($items as $single_item){
						for($l = 0; $l < $test->count_distractors; $l++){
							$distractor = new Distractor;
							$distractor->letter = $letters[$l];
							$distractor->item_id = $single_item->id;
							$distractor->count_answers = 0;
							$distractor->save();
						}	
					}	
				}
				else if($i>=3 && $j == 0 && $cell){
					//insert student
					$student = new Student;
					$student->test_id = $test->id;
					$student->class_number = $cell;
					$student->save();
					
				}
				else if($i>=3 && $j == 2 && $cell){
					//insert student
					$student->name = $cell;
					$student->save();
					$student_id = $student->id;
				}
				else if($i>=3 && $j > 2 && $cell){
					//insert student item
					$studentItem = new StudentItem;
					$k = $j - 2;
					$item = DB::table('item')
						->select('id')
						->where('test_id', $test->id)
						->where('number', $k)
						->first();
				    $studentItem->answer = $cell;
					$studentItem->item_id = $item->id;
					$studentItem->student_id = $student_id;
					$studentItem->save();
				}
				$j++;
			}
			$i++;	
		}	
    }
}
