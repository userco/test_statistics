<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use View;
use DB;

class SearchController extends Controller
{
    public function create(){
		return View::make('search/search');
	}
	
    public function store(Request $request){
		 $validatedData = $request->validate([
			  'start_date' => 'required',
			  'end_date' => 'required',
		 ]);
		 
		$input = Input::get();
		
		$start_date = $input['start_date'];
		$end_date = $input['end_date'];
		$results =DB::table('result_file')
				->select('file_name')
				->where('result_date', '>=',$start_date)
				->where('result_date', '<=',$end_date)
				->get();
		$links = "";		
		foreach($results as $result){
			$links = "<a href='results/".$result->file_name."' target='_blank'>".$result->file_name."</a>";
		}	
		return View::make('search/search')->with(array('links'=> $links));
	}	
		
}
