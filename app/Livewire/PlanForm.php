<?php

namespace App\Livewire;

use App\Models\Plan;
use Livewire\Component;

class PlanForm extends Component
{
    public $name;
    public $price;
    public $duration;

    protected $rules = [
        'name' => 'required|unique:plans,name',
        'price' => 'required|numeric|min:0',
        'duration' => 'required|in:monthly',
    ];

    public function submit()
    {
        $this->validate();

        Plan::create([
            'name' => $this->name,
            'price' => $this->price,
            'duration' => $this->duration,
        ]);

        session()->flash('message', 'Plan created successfully!');
        $this->reset(); // Reset form fields
    }

    public function render()
    {
        return view('livewire.plan-form');
    }
}
