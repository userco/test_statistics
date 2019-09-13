<?php
use App\Test;
use App\Item;
use App\StudentItem;
use App\Student;

if (! function_exists('student_item_score')) {
    function student_item_score($test_id)
    {
        $items = Item::where('test_id', $test_id)->get();
		foreach($items as $item){
			
			$right_answer = $item->right_answer;
			$item_id = $item->id;
			$student_items = StudentItem::where('item_id', $item_id)->get();
			foreach($student_items as $student_item){
				$answer = $student_item->answer;
				if($answer == $right_answer)
					$student_item->item_score = 1;
				else 
					$student_item->item_score = 0;
				$student_item->save();
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
            ->insert(['mode' => $test_score]);
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
			
			$difficulty = $count_right_answers/$count_answers;
			
			DB::table('item')
            ->where('id', $item_id)
            ->update(['difficulty' => $difficulty]);
		}
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
                ->select('difficulty, mean_correct')
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
		foreach($items as $item){
			$item_id = $item->id;
			$result = DB::table('item')
                ->select('difficulty')
				->where('id', $item_id)
                ->first();
			$p = $result->difficulty;
			$result2 = DB::table('test')
                ->select('disperse')
				->where('id', $test_id)
                ->first();
			$sd = $result2->disperse;
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