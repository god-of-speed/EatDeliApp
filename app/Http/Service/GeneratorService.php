<?php
namespace App\Service;

class GeneratorService {
    public function makeName($type,$length) {
        //get all valid characters
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        
        if($type == 'image') {
            $name = '';
            for($i=0; $i<$length; $i++) {
                $name .= $characters[(int) floor(rand(0,61))];
            }
            return $name;
        }
    }
}