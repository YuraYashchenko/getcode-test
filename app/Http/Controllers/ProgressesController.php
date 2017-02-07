<?php

namespace App\Http\Controllers;

use App\Todo;
use Illuminate\Http\Request;

class ProgressesController extends Controller
{
    
    public function setProgress(Request $request)
    {
    	$todo = Todo::find(
    			$request->input('todoId')
    		);

    	$todo->progress = $request->input('progress');
    	$todo->save();
    }
}
