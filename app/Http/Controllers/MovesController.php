<?php

namespace App\Http\Controllers;

use App\Todo;


class MovesController extends Controller
{
    
    public function move()
    {
    	$direction = request('direction');
    	$id = request('id');
    	$todo = Todo::findOrFail($id);
    	$order = $todo->order;
 		
 		if ($direction == 'up') {
 			$upTodo = Todo::where('order', $order + 1)->first();
 			$todo->update([
					'order' => $order + 1 
  				]);

 			$upTodo->update([
 					'order' => $upTodo->order - 1 
 				]);

 		} elseif ($direction == 'down') {
 			$downTodo = Todo::where('order', $order - 1)->first();
 			$todo->update([
					'order' => $order - 1 
  				]);

 			$downTodo->update([
 					'order' => $downTodo->order + 1 
 				]);
 		} 
    }
}
