<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use View;
use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DownloadController extends Controller
{
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
		return View::make('download/download');
	}
	
    public function create(Request $request){
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->mergeCells('A1:B1');
		$sheet->setCellValue('A1', 'Ключ');
		$sheet->setCellValue('A2', 'Брой дистрактори');
		$sheet->setCellValue('A3', '№');
		$sheet->setCellValue('B3', 'Име');
		$columns = ['A', 'B', 'C', 'D', 'E','F','G','H','I','J','K','L','M','N','O','P','Q',
		'R','S','T','U','V','W','X','Y','Z'];
		$i = 2;
		$spreadsheet->getActiveSheet()->getStyle('A1:Z1')
					->getFont()->getColor()->setARGB(
					\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED
					);
		$spreadsheet->getActiveSheet()->getStyle('A2:C2')
					->getFont()->getColor()->setARGB(
					\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED
					);			
		while(isset($columns[$i])){
			$j = $i - 1;
			$letter = $columns[$i];  
			$sheet->setCellValue($letter."3", $j);
			$spreadsheet->getActiveSheet()->getStyle($letter."3")
					->getFont()->getColor()->setARGB(
					\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLUE
					);
			$i++;	
		}	
		$writer = new Xlsx($spreadsheet);
		$t = time();
		$filename = "template".$t.".xlsx";
		$writer->save('downloads/'.$filename);
		// Redirect output to a client’s web browser (Xlsx)
		/*header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename=$filename');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
		$writer->save('php://output');
		
		exit;
		*/
		
		return View::make('download/download');;
	}
	
}	