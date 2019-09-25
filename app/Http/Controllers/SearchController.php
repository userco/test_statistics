<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use View;
use DB;
use App\ResultFile;

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
		
		$user = \Auth::user();
		$userId = $user->id;
		
		$start_date = $input['start_date'];
		$end_date = $input['end_date'];
		$results = ResultFile::where('user_id', $userId)->get();
		$links = "";		
		foreach($results as $result){
			if($result->result_date >= $start_date && $result->result_date <= $end_date){
				$links .= "<a href='results/".$result->file_name."' target='_blank'>".$result->file_name."</a><br>";
			}
		}	
		if(!$links){
			$links = "<div class='alert alert-warning'>
						Няма намерени резултати.
					</div>";
		}	
		return View::make('search/search')->with(array('links'=> $links));
	}	
		
}
