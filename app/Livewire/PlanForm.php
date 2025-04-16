<?php

namespace App\Livewire;

use App\Models\Plan;
use Livewire\Component;

class PlanForm extends Component
{
    public $name;
    public $price;
    public $duration;
    public $planId; // To track the plan being edited
    public $isEditing = false; // To toggle between create and edit modes


    public $plans = [];
    public function mount()
    {
        // Fetch all plans from the database
        $this->plans = Plan::all()->toArray();
    }
    public function updatePrice($index, $newPrice)
    {

        Plan::updateOrCreate(
            ['name' => $this->plans[$index]['name']],
            ['price' => $newPrice]
        );
        $this->plans[$index]['price'] = $newPrice;
    }

    public function render()
    {
        return view('livewire.plan-form')->layout('layout.app');
    }
}
