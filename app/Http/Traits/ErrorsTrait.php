<?php
namespace App\Http\Traits;

trait ErrorsTrait{

    public function HandleErrors($errors){

        $handleErrors = [];

        foreach ($errors as $error){
            $handleErrors[] = $error;
        }

        return $handleErrors;
    }
}
