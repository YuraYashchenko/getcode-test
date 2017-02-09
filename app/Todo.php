<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    protected $fillable = ['name', 'order'];

    public function decrementOrder()
    {
    	$this->update([
    			'order' => $this->order - 1
    		]);
    }
}
