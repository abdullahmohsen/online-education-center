<?php

namespace App\Http\Repositories;

use App\Http\Resources\SubscriptionResource;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Interfaces\SubscriptionInterface;
use App\Models\StudentGroup;

class SubscriptionRepository implements SubscriptionInterface
{
    use ApiResponseTrait;

    private $studentGroup_model;

    public function __construct(StudentGroup $studentGroup)
    {
        $this->studentGroup_model = $studentGroup;
    }

    public function limitSubscription()
    {
        $data = $this->studentGroup_model::whereIn('count', [1,2])->with('student', 'group')->get();
        if (count($data))
            return $this->ApiResponse(200, 'Done', null, SubscriptionResource::collection($data));
        return $this->ApiResponse(200, 'No Limit Subscription Count found');
    }

    public function closedSubscription()
    {
        $data = $this->studentGroup_model::where('count', 0)->with('student', 'group')->get();
        if (count($data))
            return $this->ApiResponse(200, 'Done', null, $data);
        return $this->ApiResponse(200, 'No Closed Subscription Count found');
    }
}
