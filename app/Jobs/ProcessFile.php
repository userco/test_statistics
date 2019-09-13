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
			calculate_avg_score($test_id);
			calculate_min_score($test_id);
			calculate_max_score($test_id);
			calculate_disperse($test_id);
			calculate_std_deviation($test_id);
			calculate_mode($test_id);
			
			
			$test = Test::find($test_id);
			$spreadsheet = new Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();
			$sheet->setCellValue('A1', 'Номер');
			$sheet->setCellValue('B1', 'Име');
			$sheet->setCellValue('C1', 'Брой точки');
			$sheet->setCellValue('D1', 'Оценка');
			
			$writer = new Xlsx($spreadsheet);
			$t = time();
			$filename = "test_analysis".$t.".xlsx";
			$writer->save('results/'.$filename);
				
				
		}
		
    }
}
