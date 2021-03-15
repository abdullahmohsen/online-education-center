<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
//        return parent::toArray($request);
        return [
            'id' => $this->id,
            'count' => $this->count,
            'price' => $this->price,
            'student' => $this->student,
            'group' => $this->group
        ];
    }

//    public function with($request) {
//        return [
//            'version' => '1.0.0'
//        ];
//    }
}
