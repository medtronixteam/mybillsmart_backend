<?php
namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PaymentIntent;
use App\Models\User;

class PaymentIntentsTable extends Component
{
    use WithPagination;

    public $planName = '';
    public $status = '';
    public $user = '';
    public $date = '';
    public $intents= [];

    public function updating($field)
    {
        $this->resetPage();
    }
    public function mount()
    {

        $query = PaymentIntent::query();

        if ($this->planName) {
            $query->where('plan_name', 'like', '%' . $this->planName . '%');
        }



        if ($this->user) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->user . '%');
            });
        }

        if ($this->date) {
            $query->whereDate('created_at', $this->date);
        }

        $this->intents = $query->with('user')->orderBy('created_at', 'desc')->paginate(10);
    }
    public function render()
    {

        return view('livewire.payment-intents-table')->layout('layout.app');
    }
}
