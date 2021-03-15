<?php

namespace App\Http\Traits;

trait UserRoleTrait{

    public function user_role($key, $value, $key1 = null, $value1 = null){

        // return $this->user_model::whereHas('roleName', function($query) use ($key, $value, $key1, $value1) {
        //     return $query->where($key, $value)->where(function($q) use ($key1, $value1) {
        //         return $q->where($key1, $value1);
        //     });
        // });

        return $this->user_model::whereHas('roleName', function($query) use ($key, $value, $key1, $value1) {
            return $query->where($key, $value)->where($key1, $value1);
        });

        // return $this->user_model::whereHas('roleName', function($query) use ($key, $value, $key1, $value1) {
        //     return $query->where([[$key, $value], [$key1, $value1]]);
        // });
    }
}
