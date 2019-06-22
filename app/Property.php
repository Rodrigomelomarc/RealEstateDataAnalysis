<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Property extends Model
{
    protected $fillable = ['title', 'price', 'description', 'region', 'category'];
    public $timestamps = false;

    public function store($data) {
        DB::transaction(function () use($data) {
            $this->create([
                'title'         => $data['title'],
                'price'         => $data['price'],
                'description'   => $data['description'],
                'region'        => $data['region'],
                'category'      => $data['category'],
            ]);
        });
    }

    public function retrieveAll(){
        return $this
                ->query()
                ->orderBy('id')
                ->get();

    }
}
