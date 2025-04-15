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
    public $plans=[];

    public function mount()
    {

        $this->plans = Plan::all();
    }

    public function submit()
    {

        $this->validate([
            'name' => 'required|unique:plans,name',
            'price' => 'required|numeric|min:0',
        ]);

        Plan::create([
            'name' => $this->name,
            'price' => $this->price,
            'duration' => 'monthly',
        ]);

        session()->flash('message', 'Plan created successfully!');
      //  $this->resetForm();
    }

    public function edit($id)
    {

        $plan = Plan::findOrFail($id);

        $this->planId = $plan->id;
        $this->name = $plan->name;
        $this->price = $plan->price;
        $this->isEditing = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|unique:plans,name,' . $this->planId,
            'price' => 'required|numeric|min:0',
        ]);

        $plan = Plan::findOrFail($this->planId);
        $plan->update([
            'name' => $this->name,
            'price' => $this->price,
        ]);

        session()->flash('message', 'Plan updated successfully!');
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->name = '';
        $this->price = '';
        $this->duration = '';
        $this->planId = null;
        $this->isEditing = false;
    }

    public function render()
    {
        return view('livewire.plan-form')->layout('layout.admin');
    }
}
