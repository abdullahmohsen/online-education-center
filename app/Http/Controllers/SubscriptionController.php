<?php
namespace App\Http\Controllers;

use App\Http\Interfaces\SubscriptionInterface;

class SubscriptionController extends Controller
{
    protected $subscription_interface;

    public function __construct(SubscriptionInterface $subscriptionInterface)
    {
        $this->subscription_interface = $subscriptionInterface;
    }

    public function limitCount()
    {
        return $this->subscription_interface->limitSubscription();
    }

    public function closedCount()
    {
        return $this->subscription_interface->closedSubscription();
    }
}
