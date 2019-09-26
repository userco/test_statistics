<?php
use App\Test;
use App\Item;
use App\StudentItem;
use App\Student;
use App\TestStudent;
use App\Distractor;

if (! function_exists('student_item_score')) {
    function student_item_score($test_id)
    {
        $items = Item::where('test_id', $test_id)->get();
		foreach($items as $item){
			
			$right_answer = $item->right_answer;
			$item_id = $item->id;
			$student_items = StudentItem::where('item_id', $item_id)->get();
	
			foreach($student_items as $student_item){
				$item_score = 0;
				$answer = $student_item->answer;
				$student_id = $student_item->student_id;
				if($answer == $right_answer)
					$item_score = 1;
				
				DB::table('student_item')
					->where('item_id', $item_id)
					->where('student_id', $student_id)
					->update(['item_score' => $item_score]);
				
			}
		}	
        return true;
    }
}

if (! function_exists('students_count')) {
    function students_count($test_id)
    {
		$result = DB::table('student')
                ->select( DB::raw('COUNT(*) as students_count'))
				->where('test_id', $test_id)
                ->first();
				
		$students_count = $result->students_count;
		DB::table('test')
            ->where('id', $test_id)
            ->update(['students_count' => $students_count]);
        return true;
    }
}

if (! function_exists('items_count')) {
    function items_count($test_id)
    {
		$result = DB::table('item')
                ->select( DB::raw('COUNT(*) as items_count'))
				->where('test_id', $test_id)
                ->first();
				
		$items_count = $result->items_count;
		DB::table('test')
            ->where('id', $test_id)
            ->update(['items_count' => $items_count]);
        return true;
    }
}
if (! function_exists('calculate_avg_score')) {
    function calculate_avg_score($test_id)
    {
		$result = DB::table('test_student')
                ->select( DB::raw('AVG(test_score) as average'))
				->where('test_id', $test_id)
                ->first();
				
		$average_score = $result->average;
		DB::table('test')
            ->where('id', $test_id)
            ->update(['mean' => $average_score]);
        return true;
    }
}
if (! function_exists('calculate_mean_j_removed')) {
    function calculate_mean_j_removed($test_id)
    {	
		$res =DB::table('test')
				->select('items_count')
				->where('id', $test_id)
				->first();
		$items_count = $res->items_count;		
		$students = Student::where('test_id', $test_id)->get();
		$items = Item::where('test_id', $test_id)->get();
		foreach($items as $item){
			$sum = 0;
			$item_id = $item->id;
			foreach($students as $student){
				$student_id = $student->id;
				$studentItems = StudentItem::where('student_id', $student_id)->get();
				foreach($studentItems as $studentItem){
					if($studentItem->item_id != $item_id){
						$sum += $studentItem->item_score;
					}
				}		
			}
	
			$mean_j_rem = $sum/($items_count -1);
			DB::table('item')
				->where('id', $item_id)
				->update(['mean_rem' => $mean_j_rem]);
		}		
        return true;
    }
}
if (! function_exists('calculate_disperse_j_rem')) {
    function calculate_disperse_j_rem($test_id)
    {	
		$res = DB::table('test')
				->select('items_count')
				->where('id', $test_id)
				->first();
		$items_count = $res->items_count;	
		$items = Item::where('test_id', $test_id)->get();
		foreach($items as $item){
			$item_id = $item->id;
			$testStudents = TestStudent::where('test_id', $test_id)->get();
			$sum = 0;
			$mean_j_rem = $item->mean_rem;
			
			foreach($testStudents as $testStudent){
				$test_score = $testStudent->test_score;
				$sum = ($test_score - $mean_j_rem)*($test_score - $mean_j_rem);
			}	
			$disperse_j_rem = $sum/($items_count -1);
			DB::table('item')
				->where('id', $item_id)
				->update(['disperse_rem' => $disperse_j_rem]);
		}
        return true;
    }
}
if (! function_exists('calculate_kr20_j_rem')) {
    function calculate_kr20_j_rem($test_id)
    {	
		$res = DB::table('test')
				->select('items_count')
				->where('id', $test_id)
				->first();
		$items_count = $res->items_count;	
		$items = Item::where('test_id', $test_id)->get();
		$items2 = Item::where('test_id', $test_id)->get();
		foreach($items as $item){
			$item_id = $item->id;
			$sum = 0;
			foreach($items2 as $item2){
				if($item_id != $item2->id){
					$sum += $item2->difficulty * (1- $item2->difficulty);
				}	
			}
			$temp = $sum/($item->disperse_rem);
			$temp2 = 1 - $temp;
			$temp3 = ($items_count -1)/($items_count - 2);
			$kr20_j_rem = $temp3 * $temp2;
			DB::table('item')
				->where('id', $item_id)
				->update(['kr20_rem' => $kr20_j_rem]);
		}
        return true;
    }
}

if (! function_exists('calculate_min_score')) {
    function calculate_min_score($test_id)
    {
		$result = DB::table('test_student')
                ->select( DB::raw('MIN(test_score) as min_score'))
				->where('test_id', $test_id)
                ->first();
				
		$min_score = $result->min_score;
		DB::table('test')
            ->where('id', $test_id)
            ->update(['min_bal' => $min_score]);
        return true;
    }
}
if (! function_exists('calculate_max_score')) {
    function calculate_max_score($test_id)
    {
		$result = DB::table('test_student')
                ->select( DB::raw('MAX(test_score) as max_score'))
				->where('test_id', $test_id)
                ->first();
				
		$max_score = $result->max_score;
		DB::table('test')
            ->where('id', $test_id)
            ->update(['max_bal' => $max_score]);
        return true;
    }
}
if (! function_exists('calculate_test_score')) {
    function calculate_test_score($test_id)
    {
		$students = Student::where('test_id', $test_id)->get();
		foreach($students as $student){
			$student_id = $student->id;

			$result = DB::table('student_item')
				->select( DB::raw('SUM(item_score) as test_score'))
				->where('student_id', $student_id)
				->first();
			
			$test_score = $result->test_score;
			DB::table('test_student')
				->insert(['test_id' => $test_id, 'student_id'=>$student_id,
				'test_score'=>$test_score]);
		}	
        return true;
    }
}
if (! function_exists('calculate_disperse')) {
    function calculate_disperse($test_id)
    {
		$result = DB::table('student')
				->select( DB::raw('COUNT(*) as count_students'))
				->where('test_id', $test_id)
				->first();
		$std_cnt =  $result->count_students;
		$result2 = DB::table('test')
				->select('mean')
				->where('id', $test_id)
				->first();
		$average = $result2->mean;
		$disperse = 0;
		$students = Student::where('test_id', $test_id)->get();
		foreach($students as $student){
			$student_id = $student->id;

			$result = DB::table('test_student')
				->select( DB::raw('test_score'))
				->where('student_id', $student_id)
				->first();
			
			$test_score = $result->test_score;
			$disperse += ($test_score - $average)*($test_score - $average);
		}
		$disperse /= $std_cnt;
		DB::table('test')
            ->where('id', $test_id)
            ->update(['disperse' => $disperse]);
			
        return true;
    }
}
if (! function_exists('calculate_std_deviation')) {
    function calculate_std_deviation($test_id)
    {
		$result = DB::table('test')
                ->select( DB::raw('disperse'))
				->where('id', $test_id)
                ->first();
				
		$disperse = $result->disperse;
		$sd = sqrt($disperse);
		DB::table('test')
            ->where('id', $test_id)
            ->update(['sd' => $sd]);
        return true;
    }
}
if (! function_exists('calculate_mode')) {
    function calculate_mode($test_id)
    {
		$result = DB::table('test_student')
                ->select( DB::raw('COUNT(test_score), test_score'))
				->where('test_id', $test_id)
				->groupBy('test_score')
				->orderBy('test_score', 'desc')
				->limit(1)
                ->first();
				
		$test_score = $result->test_score;
		DB::table('mode')
            ->insert(['mode' => $test_score, 'test_id' => $test_id]);
        return true;
    }
}

if (! function_exists('calculate_difficulty')) {
    function calculate_difficulty($test_id)
    {
		$items = Item::where('test_id', $test_id)->get();
		foreach($items as $item){
			$item_id = $item->id;
			$result = DB::table('student_item')
                ->select( DB::raw('SUM(item_score) as count_right_answers'))
				->where('item_id', $item_id)
                ->first();
			$count_right_answers = $result->count_right_answers;
		    
			$result2 = DB::table('student_item')
                ->select( DB::raw('COUNT(item_score) as count_answers'))
				->where('item_id', $item_id)
                ->first();
			$count_answers = $result2->count_answers;
			
			if($count_answers == 0)
				$difficulty = 0;
			else
				$difficulty = $count_right_answers/$count_answers;
			
			DB::table('item')
            ->where('id', $item_id)
            ->update(['difficulty' => $difficulty]);
		}
        return true;
    }
}
if (! function_exists('calculate_min_difficulty')) {
    function calculate_min_difficulty($test_id)
    {
		$result = DB::table('item')
                ->select( DB::raw('MIN(difficulty) as min_difficulty'))
				->where('test_id', $test_id)
                ->first();
		$min_difficulty = $result->min_difficulty;
		
		DB::table('test')
            ->where('id', $test_id)
            ->update(['min_difficulty' => $min_difficulty]);
			
        return true;
    }
}
if (! function_exists('calculate_max_difficulty')) {
    function calculate_max_difficulty($test_id)
    {
		$result = DB::table('item')
                ->select( DB::raw('MAX(difficulty) as max_difficulty'))
				->where('test_id', $test_id)
                ->first();
		$max_difficulty = $result->max_difficulty;
			
		DB::table('test')
            ->where('id', $test_id)
            ->update(['max_difficulty' => $max_difficulty]);
			
        return true;
    }
}
if (! function_exists('calculate_min_discrimination')) {
    function calculate_min_discrimination($test_id)
    {
		$result = DB::table('item')
                ->select( DB::raw('MIN(discrimination) as min_discrimination'))
				->where('test_id', $test_id)
                ->first();
		$min_discrimination = $result->min_discrimination;
			
		DB::table('test')
            ->where('id', $test_id)
            ->update(['min_discrimination' => $min_discrimination]);
			
        return true;
    }
}
if (! function_exists('calculate_max_discrimination')) {
    function calculate_max_discrimination($test_id)
    {
		$result = DB::table('item')
                ->select( DB::raw('MAX(discrimination) as max_discrimination'))
				->where('test_id', $test_id)
                ->first();
		$max_discrimination = $result->max_discrimination;
			
		DB::table('test')
            ->where('id', $test_id)
            ->update(['max_discrimination' => $max_discrimination]);
			
        return true;
    }
}
if (! function_exists('calculate_item_discrimination')) {
    function calculate_item_discrimination($test_id)
    {
		$result = DB::table('test')
                ->select( 'students_count')
				->where('id', $test_id)
                ->first();
		$students_count = $result->students_count;
		$limit = 0.27 * $students_count;
		
		
		$bestStudents = DB::table('test_student')
                ->select( 'student_id')
				->where('test_id', $test_id)
				->orderBy('test_score', 'desc')
				->limit($limit)
                ->get();
		$best = [];		
		foreach($bestStudents as $bestStudent){
			$best[] = $bestStudent->student_id;
		}
		$worstStudents = DB::table('test_student')
                ->select( 'student_id')
				->where('test_id', $test_id)
				->orderBy('test_score', 'asc')
				->limit($limit)
                ->get();
		$worst = [];		
		foreach($worstStudents as $worstStudent){
			$worst[] = $worstStudent->student_id;
		}
		
		$items = Item::where('test_id', $test_id)->get();
		foreach($items as $item){
			$cntBest = 0;
			$cntWorst = 0;
			$item_id = $item->id;
			$student_items = StudentItem::where('item_id', $item_id)->get();
			foreach($student_items as $stud_item){
				if($stud_item->item_score == 1){
					if(in_array($stud_item->student_id, $best)){
						$cntBest++;
					}
					if(in_array($stud_item->student_id, $worst)){
						$cntWorst++;
					}	
				}	
			}
			$discrimination = ($cntBest - $cntWorst)/$limit;
			DB::table('item')
            ->where('id', $item_id)
            ->update(['discrimination' => $discrimination]);
			
		}	
		
        return true;
    }
}
if (! function_exists('calculate_distractor_discrimination')) {
    function calculate_distractor_discrimination($test_id)
    {
		$result = DB::table('test')
                ->select( 'students_count')
				->where('id', $test_id)
                ->first();
		$students_count = $result->students_count;
		$limit = 0.27 * $students_count;
		
		
		$bestStudents = DB::table('test_student')
                ->select( 'student_id')
				->where('test_id', $test_id)
				->orderBy('test_score', 'desc')
				->limit($limit)
                ->get();
		$best = [];		
		foreach($bestStudents as $bestStudent){
			$best[] = $bestStudent->student_id;
		}
		$worstStudents = DB::table('test_student')
                ->select( 'student_id')
				->where('test_id', $test_id)
				->orderBy('test_score', 'asc')
				->limit($limit)
                ->get();
		$worst = [];		
		foreach($worstStudents as $worstStudent){
			$worst[] = $worstStudent->student_id;
		}
		$items = Item::where('test_id', $test_id)->get();
		foreach($items as $item){
			
			$item_id = $item->id;
			$distractors = Distractor::where('item_id', $item_id)->get();
			$student_items = StudentItem::where('item_id', $item_id)->get();
			foreach($distractors as $distractor){
				$cntBest = 0;
				$cntWorst = 0;
				$distractor_id = $distractor->id;
				foreach($student_items as $stud_item){
					if($stud_item->answer == $distractor->letter){
						if(in_array($stud_item->student_id, $best)){
							$cntBest++;
						}
						if(in_array($stud_item->student_id, $worst)){
							$cntWorst++;
						}	
					}	
				}	
				
				$discrimination = ($cntBest - $cntWorst)/$limit;
				DB::table('distractor')
				->where('id', $distractor_id)
				->update(['discrimination' => $discrimination]);
			}
		}	
        return true;
    }
}
if (! function_exists('calculate_answers_to_distractors')) {
    function calculate_answers_to_distractors($test_id)
    {
		$items = Item::where('test_id', $test_id)->get();
		foreach($items as $item){
			$item_id = $item->id;
			$distractors = Distractor::where('item_id', $item_id)->get();
			$student_items = StudentItem::where('item_id', $item_id)->get();
			foreach($distractors as $distractor){
				$distractor_id = $distractor->id;
				foreach($student_items as $stud_item){
					if($stud_item->answer == $distractor->letter){
						$cnt = DB::table('distractor')
											->select( 'count_answers')
											->where('id', $distractor_id)
											->first();
						$count_answers = $cnt->count_answers + 1;
						DB::table('distractor')
						->where('id', $distractor_id)
						->update(['count_answers' => $count_answers]);
					}
				}
			}		
		}	
        return true;
    }
}
if (! function_exists('calculate_min_rpbis')) {
    function calculate_min_rpbis($test_id)
    {
		$result = DB::table('item')
                ->select( DB::raw('MIN(rpbis) as min_rpbis'))
				->where('test_id', $test_id)
                ->first();
		$min_rpbis = $result->min_rpbis;
			
		DB::table('test')
            ->where('id', $test_id)
            ->update(['min_rpbis' => $min_rpbis]);
			
        return true;
    }
}
if (! function_exists('calculate_max_rpbis')) {
    function calculate_max_rpbis($test_id)
    {
		$result = DB::table('item')
                ->select( DB::raw('MAX(rpbis) as max_rpbis'))
				->where('test_id', $test_id)
                ->first();
		$max_rpbis = $result->max_rpbis;
			
		DB::table('test')
            ->where('id', $test_id)
            ->update(['max_rpbis' => $max_rpbis]);
			
        return true;
    }
}
if (! function_exists('calculate_rpbis')) {
    function calculate_rpbis($test_id)
    {
		$items = Item::where('test_id', $test_id)->get();
		foreach($items as $item){
			$item_id = $item->id;
			$result = DB::table('item')
                ->select(DB::raw('difficulty, mean_correct'))
				->where('id', $item_id)
                ->first();
			$p = $result->difficulty;
		    $mean_correct = $result->mean_correct;
			$result2 = DB::table('test')
                ->select('sd', 'mean')
				->where('id', $test_id)
                ->first();
			$sd = $result2->sd;
			$mean = $result2->mean;
			$q = 0;
			if($p < 1)  $q = 1 - $p;
			
			$tmp1 = $p/$q;
			$tmp2 = sqrt($tmp1);
			$tmp3 = $mean_correct - $mean;
			$tmp4 = $tmp3/$sd;
			
			$rpbis = $tmp2*$tmp4;
			
			DB::table('item')
            ->where('id', $item_id)
            ->update(['rpbis' => $rpbis]);
		}
        return true;
    }
}
if (! function_exists('calculate_kr20')) {
    function calculate_kr20($test_id)
    {
		$items = Item::where('test_id', $test_id)->get();
		$sum = 0;
		$result2 = DB::table('test')
                ->select('disperse')
				->where('id', $test_id)
                ->first();
			
		$disperse = $result2->disperse;
		foreach($items as $item){
			$item_id = $item->id;
			$result = DB::table('item')
                ->select('difficulty')
				->where('id', $item_id)
                ->first();
			$p = $result->difficulty;
	        $q = 1 - $p;
			
			$sum += $p*$q;
		}
		$tmp2 = $sum/$disperse;
		$tmp3 = 1 - $tmp2;
		
		$result = DB::table('item')
			->select( DB::raw('COUNT(*) as items_count'))
			->where('test_id', $test_id)
			->first();
			
		$items_count = $result->items_count;
			
		$tmp4 = $items_count - 1;
		$tmp5 = $items_count/$tmp4;
		
		$kr20 = $tmp5*$tmp3;
		
		DB::table('test')
            ->where('id', $test_id)
            ->update(['kr20' => $kr20]);
			
        return true;
    }
}
if (! function_exists('calculate_sem')) {
    function calculate_sem($test_id)
    {
		$test = Test::where('id', $test_id)->first();
			
		$sd = $test->sd;
		$kr20 = $test->kr20;
		
		$tmp = sqrt(1 - $kr20);
		$sem = $sd * $tmp;
		
		DB::table('test')
            ->where('id', $test_id)
            ->update(['sem' => $sem]);
			
        return true;
    }
}
if (! function_exists('set_marks')) {
    function set_marks($test_id)
    {
		$students = Student::where('test_id', $test_id)->get();
		$result = DB::table('test')
                ->select( DB::raw('count_distractors, sd, items_count'))
				->where('id', $test_id)
                ->first();
			
		$sd = $result->sd;
		$count_distractors = $result->count_distractors;
		$count_items = $result->items_count;
		
		$N = 0.9 * $count_items;
		$M = $count_items/$count_distractors;
		$M += $sd;
		
		$a = 3/($N - $M);
		$b = (3*$N - 6*$M)/($N - $M);
		
		foreach($students as $student){
			$student_id = $student->id;
			$result2 = DB::table('test_student')
                ->select('test_score')
				->where('student_id', $student_id)
                ->first();
			$test_score = $result2->test_score;
	        $mark = $a * $test_score + $b;
			if($mark < 2) $mark = 2;
			if($mark > 6) $mark = 6;
			
			DB::table('test_student')
            ->where('student_id', $student_id)
            ->update(['mark' => $mark]);
		}	
        return true;
    }
}
if (! function_exists('calculate_mean_correct_incorrect')) {
    function calculate_mean_correct_incorrect($test_id)
    {
		$items = DB::table('item')
                ->select('id')
				->where('test_id', $test_id)
                ->get();
		
		
		foreach($items as $item){
			$sumWrong = 0;
			$cntWrong = 0;
			$sumRight = 0;
			$cntRight = 0;
			
			$id = $item->id;
			$studentItems = StudentItem::where('item_id', $id)->get();
			foreach($studentItems as $studentItem){
				$student_id = $studentItem->student_id;
				$testStudent = TestStudent::where('student_id', $student_id)->first();
				$test_score = $testStudent->test_score;
				if($studentItem->item_score == 1){
					$sumRight += $test_score;
					$cntRight++;
				}
				if($studentItem->item_score == 0){
					$sumWrong += $test_score;
					$cntWrong++;
				}	
			}
			
			$mean_correct = ($cntRight)?$sumRight / $cntRight: 0;
			$mean_incorrect = ($cntWrong)?$sumWrong / $cntWrong: 0;
			
			DB::table('item')
				->where('id', $id)
				->update([
						'mean_correct' => $mean_correct, 
						'mean_incorrect' => $mean_incorrect,
						'number_correct' => $cntRight,
						'number_incorrect' => $cntWrong
				]);
		}
			
        return true;
    }
}