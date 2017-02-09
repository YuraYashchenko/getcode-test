<?php

namespace App\Http\Controllers;

use App\Todo;
use Illuminate\Http\Request;

class TodosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('todos');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $todo = new Todo;

        $last = $todo->orderBy('order')->get()->last();

        if ($last) {
           $order = $last->order + 1;
        } else {
            $order = 0;
        }

        $this->validate($request, [
                'name' => 'required|max:256|min:5',
            ]);

        $todo->create([
                'name' => $request->input('name'),
                'order' => $order
            ]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Todo $todo)
    {
        $this->validate($request, [
                'name' => 'required|max:256|min:5'
            ]);
        
        $todo->update([
                'name' => $request->input('name')
            ]);
        $todo->save();
    }
     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Todo $todo)
    {
        $order = $todo->order;

        $todos = Todo::where('order', '>', $order)->get();

        $todos->each->decrementOrder();

        $todo->delete(); 
    }
}