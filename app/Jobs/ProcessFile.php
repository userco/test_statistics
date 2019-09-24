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
use App\StudentItem;
use App\Student;
use App\Distractor;
use App\TestStudent;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ProcessFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = \Auth::user();
		$userId = $user->id;
		$result= DB::table('test')
						 ->select(DB::raw('id'))
						 ->where('user_id', '=', $userId)
						 ->where('result_processed', '=', null)
						 ->first();
		if($result){
			$test_id = $result->id;
			
			students_count($test_id);
			items_count($test_id);
			student_item_score($test_id);
			calculate_test_score($test_id);
			set_marks($test_id);
			calculate_avg_score($test_id);
			calculate_min_score($test_id);
			calculate_max_score($test_id);
			calculate_disperse($test_id);
			calculate_std_deviation($test_id);
			calculate_mode($test_id);
			calculate_difficulty($test_id);
			calculate_item_discrimination($test_id);
			calculate_distractor_discrimination($test_id);
			calculate_answers_to_distractors($test_id);
			calculate_mean_correct_incorrect($test_id);
			calculate_rpbis($test_id);
			calculate_kr20($test_id);
			calculate_sem($test_id);
			calculate_min_difficulty($test_id);
			calculate_max_difficulty($test_id);
			calculate_min_discrimination($test_id);
			calculate_max_discrimination($test_id);
			calculate_min_rpbis($test_id);
			calculate_max_rpbis($test_id);
			calculate_mean_j_removed($test_id);
			calculate_disperse_j_rem($test_id);
			calculate_kr20_j_rem($test_id);
			
			$test = Test::find($test_id);
			$students = Student::where('test_id', $test_id)->get();
		
			$spreadsheet = new Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();
			$sheet->setTitle("Резултати от теста");
			$sheet->setCellValue('A1', 'Номер');
			$sheet->setCellValue('B1', 'Име');
			$sheet->setCellValue('C1', 'Брой точки');
			$sheet->setCellValue('D1', 'Оценка');
			$i = 2;
			foreach($students as $student){
				$student_id = $student->id;
				$testStudent = TestStudent::where('student_id', $student_id)->first();
				$student_name = $student->name;
				$student_number = $student->class_number;
				$test_score = $testStudent->test_score;
				$mark = $testStudent->mark;
				
				$sheet->setCellValue('A'.$i, $student_number);
				$sheet->setCellValue('B'.$i, $student_name);
				$sheet->setCellValue('C'.$i, $test_score);
				$sheet->setCellValue('D'.$i, $mark);
				$i++;
			}	
			
			$sheet2 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Статистика за теста');
			$spreadsheet->addSheet($sheet2, 1);
			
			$sheet2->setCellValue('A1', "Брой ученици");
			$sheet2->setCellValue('B1', $test->students_count);
			
			$sheet2->setCellValue('A2', "Брой задачи");
			$sheet2->setCellValue('B2', $test->items_count);
			
			$sheet2->setCellValue('A3', "Среден бал");
			$sheet2->setCellValue('B3', $test->mean);
			
			$sheet2->setCellValue('A4', "Медиана");
			$sheet2->setCellValue('B4', $test->median);
			
			$sheet2->setCellValue('A5', "Минимален бал");
			$sheet2->setCellValue('B5', $test->min_bal);
			
			$sheet2->setCellValue('A6', "Mаксимален бал");
			$sheet2->setCellValue('B6', $test->max_bal);
			
			$sheet2->setCellValue('A7', "Дисперсия");
			$sheet2->setCellValue('B7', $test->disperse);
			
			$sheet2->setCellValue('A8', "Стандартно отклонение");
			$sheet2->setCellValue('B8', $test->sd);
			
			$sheet2->setCellValue('A9', "Мин. трудност на задача");
			$sheet2->setCellValue('B9', $test->min_difficulty);
			
			$sheet2->setCellValue('A10', "Макс. трудност на задача");
			$sheet2->setCellValue('B10', $test->max_difficulty);
			
			$sheet2->setCellValue('A11', "Мин. дискриминация на задача");
			$sheet2->setCellValue('B11', $test->min_discrimination);
			
			$sheet2->setCellValue('A12', "Макс. дискриминация на задача");
			$sheet2->setCellValue('B12', $test->max_discrimination);
			
			$sheet2->setCellValue('A13', "Мин. ТБК");
			$sheet2->setCellValue('B13', $test->min_rpbis);
			
			$sheet2->setCellValue('A14', "Макс. ТБК");
			$sheet2->setCellValue('B14', $test->max_rpbis);
			
			$sheet2->setCellValue('A15', "Надеждност");
			$sheet2->setCellValue('B15', $test->kr20);
			
			$sheet2->setCellValue('A16', "Грешка - измерване");
			$sheet2->setCellValue('B16', $test->sem);
					
			$sheet3 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Статистика за задачите');
			$spreadsheet->addSheet($sheet3, 2);		
			
			$items = Item::where('test_id', $test_id)->get();			
			
			$sheet3->setCellValue('A2', "Задача");
			$sheet3->setCellValue('B2', "Трудност");
			$sheet3->setCellValue('C2', "Дискриминация");
			$sheet3->setCellValue('D2', "ТБК");
			$sheet3->setCellValue('E2', "Ср. бал на отг. правилно");
			$sheet3->setCellValue('F2', "Ср. бал на отг. неправилно");
			$sheet3->setCellValue('G2', "Коеф. надеждност-изтриване");
			$sheet3->setCellValue('H1', "Разпределение на отговорите по дистрактори");
			$k = 3;
			$distractor_letters = [ 'A', 'B', 'C', 'D', 'E'];
			$column_letters = [ 'H', 'I', 'J', 'K', 'L'];
			for($i = 0; $i < $test->count_distractors; $i++){
				$letter = $distractor_letters[$i];
				$column = $column_letters[$i];
				$sheet3->setCellValue($column."2", $letter);
			}	
		
			foreach($items as $item){
				$item_id = $item->id;
				$sheet3->setCellValue('A'.$k, "Задача ". $item->number);
				$sheet3->setCellValue('B'.$k, $item->difficulty);
				$sheet3->setCellValue('C'.$k, $item->discrimination);
				$sheet3->setCellValue('D'.$k, $item->rpbis);
				$sheet3->setCellValue('E'.$k, $item->mean_correct);
				$sheet3->setCellValue('F'.$k, $item->mean_incorrect);
				$sheet3->setCellValue('G'.$k, $item->kr20_rem);
				
				$distractors = Distractor::where('item_id', $item_id)->get();
				foreach($distractors as $distractor){
					
					if($distractor->letter == 'A'){
						if($distractor->letter == $item->right_answer)
							$sheet3->getStyle('H'.$k)
					->getFont()->getColor()->setARGB(
					\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED
					);
						else
							$sheet3->getStyle('H'.$k)
					->getFont()->getColor()->setARGB(
					\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLUE
					);
						$sheet3->setCellValue('H'.$k, $distractor->count_answers);
					}
					else if($distractor->letter == 'B'){
						if($distractor->letter == $item->right_answer)
							$sheet3->getStyle('I'.$k)
					->getFont()->getColor()->setARGB(
					\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED
					);
						else
							$sheet3->getStyle('I'.$k)
					->getFont()->getColor()->setARGB(
					\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLUE
					);
						$sheet3->setCellValue('I'.$k, $distractor->count_answers);
					}
					else if($distractor->letter == 'C'){
						if($distractor->letter == $item->right_answer)
							$sheet3->getStyle('J'.$k)
					->getFont()->getColor()->setARGB(
					\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED
					);
						else
							$sheet3->getStyle('J'.$k)
					->getFont()->getColor()->setARGB(
					\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLUE
					);
						$sheet3->setCellValue('J'.$k, $distractor->count_answers);
					}
					else if($distractor->letter == 'D'){
						if($distractor->letter == $item->right_answer)
							$sheet3->getStyle('K'.$k)
					->getFont()->getColor()->setARGB(
					\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED
					);
						else
							$sheet3->getStyle('K'.$k)
					->getFont()->getColor()->setARGB(
					\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLUE
					);
						$sheet3->setCellValue('K'.$k, $distractor->count_answers);
					}
					else if($distractor->letter == 'E'){
						if($distractor->letter == $item->right_answer)
							$sheet3->getStyle('L'.$k)
					->getFont()->getColor()->setARGB(
					\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED
					);
						else
							$sheet3->getStyle('L'.$k)
					->getFont()->getColor()->setARGB(
					\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLUE
					);
						$sheet3->setCellValue('L'.$k, $distractor->count_answers);
					}
				}	
				$k++;
			}	
			
			
			$sheet4 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Статистика за дистракторите');
			$spreadsheet->addSheet($sheet4, 3);		
			$sheet4->setCellValue('A2', "Задача");
			$sheet4->setCellValue('B1', "Дистрактор");
			$column_letters2 = [ 'B', 'C', 'D', 'E', 'F'];
			for($n = 0; $n < $test->count_distractors; $n++){
				$letter = $distractor_letters[$n];
				$column = $column_letters2[$n];
				$sheet4->setCellValue($column."2", $letter);
			}	
			
			$p = 3;
			foreach($items as $item){
				$item_id = $item->id;
				$sheet4->setCellValue('A'.$p, "Задача ". $item->number);
				$distractors = Distractor::where('item_id', $item_id)->get();
				foreach($distractors as $distractor){
					
					if($distractor->letter == 'A'){
						if($distractor->letter == $item->right_answer)
							$sheet4->getStyle('B'.$p)
					->getFont()->getColor()->setARGB(
					\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED
					);
						else
							$sheet4->getStyle('B'.$p)
					->getFont()->getColor()->setARGB(
					\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLUE
					);
						$sheet4->setCellValue('B'.$p, $distractor->discrimination);
					}
					else if($distractor->letter == 'B'){
						if($distractor->letter == $item->right_answer)
							$sheet4->getStyle('C'.$p)
					->getFont()->getColor()->setARGB(
					\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED
					);
						else
							$sheet4->getStyle('C'.$p)
					->getFont()->getColor()->setARGB(
					\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLUE
					);
						$sheet4->setCellValue('C'.$p, $distractor->discrimination);
					}
					else if($distractor->letter == 'C'){
						if($distractor->letter == $item->right_answer)
							$sheet4->getStyle('D'.$p)
					->getFont()->getColor()->setARGB(
					\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED
					);
						else
							$sheet4->getStyle('D'.$p)
					->getFont()->getColor()->setARGB(
					\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLUE
					);
						$sheet4->setCellValue('D'.$p, $distractor->discrimination);
					}
					else if($distractor->letter == 'D'){
						if($distractor->letter == $item->right_answer)
							$sheet4->getStyle('E'.$p)
					->getFont()->getColor()->setARGB(
					\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED
					);
						else
							$sheet4->getStyle('E'.$p)
					->getFont()->getColor()->setARGB(
					\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLUE
					);
						$sheet4->setCellValue('E'.$p, $distractor->discrimination);
					}
					else if($distractor->letter == 'E'){
						if($distractor->letter == $item->right_answer)
							$sheet4->getStyle('F'.$p)
					->getFont()->getColor()->setARGB(
					\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED
					);
						else
							$sheet4->getStyle('F'.$p)
					->getFont()->getColor()->setARGB(
					\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLUE
					);
						$sheet4->setCellValue('F'.$p, $distractor->discrimination);
					}
				}	
				$p++;
			}	
			$sheet5 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Тълкуване на анализа на теста');
			$spreadsheet->addSheet($sheet5, 4);	
			
			if($test->kr20 >= 0.7)
				$sheet5->setCellValue('A1', 'Тестът е надежден.');
			else
				$sheet5->setCellValue('A1', 'Тестът не е надежден.');
			$items2 = Item::where('test_id', $test_id)->get();
			$it = 2;
			foreach($items2 as $item2){
				$sheet5->setCellValue('A'.$it, 'Задача '.$item2->number);
				if($test->count_distractors == 2){
					if($item2->difficulty >=0 && $item2->difficulty <= 0.5)
						$sheet5->setCellValue('B'.$it, 'Задачата е много трудна(недопустимо).');
					else if($item2->difficulty >=0.51 && $item2->difficulty <= 0.6)
						$sheet5->setCellValue('B'.$it, 'Задачата е трудна.');
					else if($item2->difficulty >=0.61 && $item2->difficulty <= 0.8)
						$sheet5->setCellValue('B'.$it, 'Задачата е оптимална.');
					else if($item2->difficulty >=0.81 && $item2->difficulty <= 0.9)
						$sheet5->setCellValue('B'.$it, 'Задачата е лесна.');
					else if($item2->difficulty >=0.91 && $item2->difficulty <= 1)
						$sheet5->setCellValue('B'.$it, 'Задачата е много лесна.');
				}
				if($test->count_distractors == 3){
					if($item2->difficulty >=0 && $item2->difficulty <= 0.33)
						$sheet5->setCellValue('B'.$it, 'Задачата е много трудна(недопустимо).');
					else if($item2->difficulty >=0.34 && $item2->difficulty <= 0.46)
						$sheet5->setCellValue('B'.$it, 'Задачата е трудна.');
					else if($item2->difficulty >=0.47 && $item2->difficulty <= 0.73)
						$sheet5->setCellValue('B'.$it, 'Задачата е оптимална.');
					else if($item2->difficulty >=0.74 && $item2->difficulty <= 0.87)
						$sheet5->setCellValue('B'.$it, 'Задачата е лесна.');
					else if($item2->difficulty >=0.88 && $item2->difficulty <= 1)
						$sheet5->setCellValue('B'.$it, 'Задачата е много лесна.');
				}
				if($test->count_distractors == 4){
					if($item2->difficulty >=0 && $item2->difficulty <= 0.25)
						$sheet5->setCellValue('B'.$it, 'Задачата е много трудна(недопустимо).');
					else if($item2->difficulty >=0.26 && $item2->difficulty <= 0.4)
						$sheet5->setCellValue('B'.$it, 'Задачата е трудна.');
					else if($item2->difficulty >=0.41 && $item2->difficulty <= 0.7)
						$sheet5->setCellValue('B'.$it, 'Задачата е оптимална.');
					else if($item2->difficulty >=0.71 && $item2->difficulty <= 0.85)
						$sheet5->setCellValue('B'.$it, 'Задачата е лесна.');
					else if($item2->difficulty >=0.86 && $item2->difficulty <= 1)
						$sheet5->setCellValue('B'.$it, 'Задачата е много лесна.');
				}
				if($test->count_distractors == 5){
					if($item2->difficulty >=0 && $item2->difficulty <= 0.20)
						$sheet5->setCellValue('B'.$it, 'Задачата е много трудна(недопустимо).');
					else if($item2->difficulty >=0.21 && $item2->difficulty <= 0.36)
						$sheet5->setCellValue('B'.$it, 'Задачата е трудна.');
					else if($item2->difficulty >=0.37 && $item2->difficulty <= 0.68)
						$sheet5->setCellValue('B'.$it, 'Задачата е оптимална.');
					else if($item2->difficulty >=0.69 && $item2->difficulty <= 0.83)
						$sheet5->setCellValue('B'.$it, 'Задачата е лесна.');
					else if($item2->difficulty >=0.84 && $item2->difficulty <= 1)
						$sheet5->setCellValue('B'.$it, 'Задачата е много лесна.');
				}
				if($item2->discrimination <= 0.1) 
					$sheet5->setCellValue('E'.$it, 'Задачата не бива да се използва в този вид.');
				if($item2->discrimination >= 0.11 && $item2->discrimination <= 0.20) 
					$sheet5->setCellValue('E'.$it, 'Няма добра разделителна способност.');
				if($item2->discrimination >= 0.21 && $item2->discrimination <= 0.30) 
					$sheet5->setCellValue('E'.$it, 'Задачата да се преразгледа и подобри.');
				if($item2->discrimination >= 0.31 && $item2->discrimination <= 0.40) 
					$sheet5->setCellValue('E'.$it, 'Задачата може да се използва.');
				if($item2->discrimination >= 0.41 && $item2->discrimination <= 1) 
					$sheet5->setCellValue('E'.$it, 'Отлична разделителна способност.');
				
				$item2_id = $item2->id;
				$limit = 0.05 * $test->students_count;
				$distractors2 = Distractor::where('item_id', $item2_id)->get();
				$sentences = "";
				foreach($distractors2 as $distractor2){
					if($distractor2->count_answers <= $limit){
						$sentences .= 'Дистрактор '.$distractor2->letter.' e неработещ. ';
					}
				}
				$sheet5->setCellValue('I'.$it, $sentences);	
				$it++;				
			}	
			
			$ldate = date('Y-m-d');
			
			$writer = new Xlsx($spreadsheet);
			$t = time();
			$filename = "test_analysis".$t.".xlsx";
			$writer->save('results/'.$filename);
			DB::table('result_file')
				->insert(['test_id' => $test_id, 'user_id'=>$userId,'file_name' =>$filename,
				'result_date'=>$ldate]);	
			$test->result_processed = 1;
			$test->save();	
		}
    }
}
