<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Property extends Model
{
    protected $fillable = ['title', 'price', 'description', 'region', 'category'];
    public $timestamps = false;

}
