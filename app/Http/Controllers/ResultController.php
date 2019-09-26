<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use View;
use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Jobs\ProcessFile;
use DB;

class ResultController extends Controller
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

    public function create(){
		return View::make('result/result');
	}
	
    public function store(Request $request){
		$user = \Auth::user();
		$userId = $user->id;
		$result= DB::table('test')
						 ->select(DB::raw('id'))
						 ->where('user_id', '=', $userId)
						 ->where('result_processed', '=', null)
						 ->first();
		if(!$result){
			$notice = "Няма импортнати данни за обработване.";
			return View::make('result/result')->with(array('notice'=> $notice));
		}	
		
		ProcessFile::dispatch();
		$notice2 = "Успешно е генериран файл.";
		return View::make('result/result')->with(array('notice2' => $notice2));
	}
	
	
}	