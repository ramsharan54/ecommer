<?php

namespace App;
use App\Category;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function categories(){
    	return $this->hasMany(Category::class,'parent_id');
    }
}
