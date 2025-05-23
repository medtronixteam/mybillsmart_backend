<?php namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PaymentIntent;

class PaymentIntentsTable extends Component
{
    use WithPagination;

    public $planName = '';
    public $status = '';
    public $user = '';
    public $date = '';

    protected $queryString = ['planName', 'status', 'user', 'date'];


    public function updating($field)
    {
        $this->resetPage();
    }

    public function onChangeIn()
    {

    }
    public function render()
    {
        $query = PaymentIntent::with('user');

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

        if ($this->date) {
            $query->whereDate('created_at', $this->date);
        }

        $intents = $query->orderBy('created_at', 'desc')->paginate(5);

        return view('livewire.payment-intents-table', compact('intents'))->layout('layout.app');
    }
}
