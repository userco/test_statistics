<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use View;
use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

    public function index(){
		return View::make('import/import');
	}
	
    public function store(Request $request){
		
		//validate user input
        $validatedData = $request->validate([
			  'email' => 'required|email',
			  'name' => 'required',
			  'city' => 'required'
		 ]);

		//get user input
		$input = Input::get();
		$email = $input['email'];
		$name = $input['name'];
		$city = $input['city'];	
		
		//save user data
		$appObject = new App;
		$appObject->email = $email;
		$appObject->name = $name;
		$appObject->city = $city;
		$appObject->save();
		
		//send an email
		Mail::to($email)->send(new AppEmail($appObject));
		
		return View::make('app/thankyou')->with(array('name'=> $name));
	}
	
	public function show(Request $request, $name){		
		return View::make('app/thankyou')->with(array('name'=> $name));
	}	
}	