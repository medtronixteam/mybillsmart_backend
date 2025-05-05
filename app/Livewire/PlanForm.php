<?php

namespace App\Livewire;

use App\Models\Plan;
use Livewire\Component;

class PlanForm extends Component
{
    public $name;
    public $monthly_price;
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
    public function updatePrice($index, $monthly_price)
    {

        Plan::updateOrCreate(
            ['name' => strtolower($this->plans[$index]['name'])],
            ['monthly_price' => $monthly_price],

            ['invoices_per_month' => $this->invoices],
            ['agents_per_month' => $this->agents],
        );
        $this->plans[$index]['monthly_price'] = $monthly_price;
    }
    public function updateAnnualPrice($index, $annual_price)
    {

        Plan::updateOrCreate(
            ['name' => strtolower($this->plans[$index]['name'])],

            ['annual_price' => $annual_price],
            ['invoices_per_month' => $this->invoices],
            ['agents_per_month' => $this->agents],
        );
        $this->plans[$index]['annual_price'] = $annual_price;
    }
    public function updateInvoice($index, $invoices)
    {
        Plan::where('name', strtolower($this->plans[$index]['name']))->update([
            'invoices_per_month' => $invoices,
        ]);
    }
    public function updateAgents($index, $agents)
    {
        Plan::where('name', strtolower($this->plans[$index]['name']))->update([
            'agents_per_month' => $agents,
        ]);
    }

    public function render()
    {
        return view('livewire.plan-form')->layout('layout.app');
    }
}
