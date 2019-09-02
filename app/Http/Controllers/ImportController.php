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
use App\Jobs\ImportFIle;

class ImportController extends Controller
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
		return View::make('import/import');
	}
	
    public function store(Request $request){
		
		 $file = $request->file('file');
   
      /*/Display File Name
      echo 'File Name: '.$file->getClientOriginalName();
      echo '<br>';
   
      //Display File Extension
      echo 'File Extension: '.$file->getClientOriginalExtension();
      echo '<br>';
   
      //Display File Real Path
      echo 'File Real Path: '.$file->getRealPath();
      echo '<br>';
   
      //Display File Size
      echo 'File Size: '.$file->getSize();
      echo '<br>';
   
      //Display File Mime Type
      echo 'File Mime Type: '.$file->getMimeType();
   */
		$t = time();
		$originalName = $file->getClientOriginalName();
		$array = explode(".",$originalName);
		$name = $array[0];
		$fileName = $name.$t.".".$file->getClientOriginalExtension();
		  //Move Uploaded File
		$destinationPath = 'uploads';
		$file->move($destinationPath,$fileName);
		$inputFileName = 'uploads/'.$fileName;
		$spreadsheet = IOFactory::load($inputFileName);
		$sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
		//var_dump($sheetData);
		ImportFIle::dispatch($sheetData);
		return View::make('import/import');
	}
	
	
}	