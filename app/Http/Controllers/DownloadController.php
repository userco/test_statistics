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
		$sheet->setCellValue('A1', 'Ключ');
		$sheet->setCellValue('B1', 'Брой дистрактори');
		$writer = new Xlsx($spreadsheet);
		$writer->save('downloads/hello world.xlsx');
		
		return View::make('download/download');;
	}
	
}	