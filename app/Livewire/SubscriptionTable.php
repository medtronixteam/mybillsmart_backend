<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Subscription;

class SubscriptionTable extends Component
{
    use WithPagination;

    public $planName = '';
    public $user = '';
    public $status = '';
    public $start_date = '';

    public function updating($field)
    {
        $this->resetPage();
    }
    public function onChangeIn()
    {

    }

    public function render()
    {
        $query = Subscription::with('user');

        if ($this->planName) {
            $query->where('plan_name', 'like', '%' . $this->planName . '%');
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->user) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->user . '%');
            });
        }

        if ($this->start_date) {
            $query->whereDate('start_date', $this->start_date);
        }

        $subscriptions = $query->orderBy('start_date', 'desc')->paginate(10);

        return view('livewire.subscription-table', compact('subscriptions'))->layout('layout.app');
    }
}
