<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = "products";
    protected $fillable = [
        'pro_name','category','pro_price','pro_quantity','pro_images','insert_by'
    ];
    public function getCategory(){
        return $this->belongsTo(Category::class,'category')->select('category','id');
    }
    public function getUser(){
        return $this->belongsTo(User::class,'insert_by')->select('id','name');
    }
}
