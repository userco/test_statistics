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
		set_time_limit(8000000);
		ini_set("memory_limit", "10056M");
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
		$p = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L','M', 'N','O', 'P', 'Q', 'R', 
		'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z','AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 
		'AL','AM', 'AN','AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ'];
        foreach($this->fileContents as $row){
			$j = 0;
			$student_id = null;
			foreach($row as $cell){
				if($i==0 && $j >= 2 && $cell){
					if(is_string($cell) && strlen($cell) > 1){
						$k = $i + 1;
						
						echo "Некоректен вход - клетка ".$k." ".$p[$j];
						exit(1);
					}
					if(is_array($cell)){
						echo "Некоректен вход - клетка ".$k." ".$p[$j];
						exit(1);
					}	
					//insert item key
					$item = new Item;
					$item->test_id = $test->id;
					$item->number = $j-2;
					$item->right_answer = $cell;
					$item->save();
				}
				else if($i==1 && $j == 2 && $cell){
					if(!$cell){
						echo "Не е попълнен броят на дистракторите във файла-шаблон.";
						exit(1);
					}
					if($cell){
					if($cell > 5){
					
						echo "Броят на дистракторите във файла-шаблон трябва да е до 5 включително.";
						exit(1);
					}
					if($cell < 2){
					
						echo "Броят на дистракторите във файла-шаблон трябва да е най-малко 2.";
						exit(1);
					}
					}
								
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
				else if($i>=3 && $j == 1 && $cell){
					//insert student
					$student->name = $cell;
					$student->save();
					$student_id = $student->id;
				}
				else if($i>=3 && $j > 1 && $cell){
					$k = $i + 1;
					if(is_string($cell) && strlen($cell) > 1){
						echo "Некоректен вход - клетка ".$k ." ".$p[$j];
						exit(1);
					}
					if(is_array($cell)){
						echo "Некоректен вход - клетка ".$k ." ".$p[$j];
						exit(1);
					}	
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
