<?php

namespace App\Http\Service;

class DataTreatmentService {

    public function verifyDataExistence($data){
       
        if($data->count() > 0) {
            $node = $data->text();
        } else {
            $node = 'No Data';
        }

        return $node;
    }

    public function sanitizeData($data){

        $data = trim($data);
        $data = preg_replace('/\\s\\s+/', " ", $data);

        return $data;
    }

    public function sanitizePrice($price) {
        
        $price = explode(" ", $price);
        $price = (float) $price[1];

        return $price;
    }
}