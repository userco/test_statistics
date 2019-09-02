<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use DB;

class ImportFIle implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
	protected $fileContents;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($array)
    {
        $this->fileContents = $array;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
		$i = 0;
		//insert new test
		$test = new Test;
        foreach($this->fileContents as $row){
			$j = 0;
			foreach($row as $cell){
				if($i==0 && $j> 2 && $cell){
					//insert item key
				}
				if($i==1 && $j == 2 && $cell){
					//insert count distractors
				}
				$j++;
			}
			$i++;	
		}	
    }
}
