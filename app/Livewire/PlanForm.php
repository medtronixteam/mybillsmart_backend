<?php

namespace App\Livewire;

use App\Models\Plan;
use Livewire\Component;

class PlanForm extends Component
{
    public $name;
    public $price;
    public $duration;
    public $invoices;
    public $agents;
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
            ['name' => strtolower($this->plans[$index]['name'])],
            ['price' => $newPrice],
            ['invoices' => $this->invoices],
            ['agents' => $this->agents],
        );
        $this->plans[$index]['price'] = $newPrice;
    }
    public function updateInvoice($index, $invoices)
    {
        Plan::where('name', strtolower($this->plans[$index]['name']))->update([
            'invoices' => $invoices,
        ]);
    }
    public function updateAgents($index, $agents)
    {
        Plan::where('name', strtolower($this->plans[$index]['name']))->update([
            'agents' => $agents,
        ]);
    }

    public function render()
    {
        return view('livewire.plan-form')->layout('layout.app');
    }
}
