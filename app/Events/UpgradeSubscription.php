<?php

namespace App\Events;


use App\Models\Plans\Plan;
use App\Models\Plans\PlanFeature;
use App\Models\Plans\PlanSubscription;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpgradeSubscription
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Model $model;

    public PlanSubscription $subscription;

    public bool $startFromNow;

    public ?Plan $oldPlan;

    public ?Plan $newPlan;

    /**
     * @param  Model  $model The model on which the action was done.
     * @param  PlanSubscription  $subscription PlanSubscription that was upgraded.
     * @param  bool  $startFromNow Wether the current subscription is upgraded by extending now or is upgraded at the next cycle.
     * @param  Plan|null  $oldPlan The old plan.
     * @param  Plan|null  $newPlan The new plan.
     * @return void
     */
    public function __construct(Model $model, PlanSubscription $subscription, bool $startFromNow, ?Plan $oldPlan, ?Plan $newPlan)
    {
        $this->model = $model;
        $this->subscription = $subscription;
        $this->startFromNow = $startFromNow;
        $this->oldPlan = $oldPlan;
        $this->newPlan = $newPlan;
    }
}
