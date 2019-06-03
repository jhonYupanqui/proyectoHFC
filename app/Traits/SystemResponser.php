<?php 

namespace App\Traits;

use Illuminate\Support\Collection;
 
Trait SystemResponser {
 
    private function successResponse($data,$code){
        return response()->json(["error"=>false,"data"=>$data],$code);
    }

    private function errorResponse($message,$code){
        return response()->json(["error"=>true,"mensaje"=>$message,"code"=>$code],$code);
    }

    protected function showContJsonAll(Collection $collection,$code = 200){
        
        return $this->successResponse($collection,$code);
    }

    protected function showModJsonOne(Model $instance, $code = 200){
        return $this->successResponse($instance,$code);
    }
}