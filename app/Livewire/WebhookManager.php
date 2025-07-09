<?php namespace App\Livewire;

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class WebhookManager extends Component
{
    public $hooks = [];
    public $name = '';
    public $url = '';
    public $trigger = '';
    public $editIndex = null;
    public $availableTriggers = ['Invoice Uploaded', 'Task Completed', 'Offer Suggested'];

    public function mount()
    {
        $this->hooks = session()->get('webhooks', []);
    }

    public function save($test = false)
    {
        $this->validate([
            'name' => 'required',
            'url' => 'required|url',
            'trigger' => 'required',
        ]);

        $hookData = [
            'name' => $this->name,
            'url' => $this->url,
            'trigger' => $this->trigger,
            'created_at' => now()->toDateTimeString(),
        ];

        // Test if required


        // Update or create
        if (!is_null($this->editIndex)) {
            $this->hooks[$this->editIndex] = $hookData;
        } else {
            $this->hooks[] = $hookData;
        }

        session()->put('webhooks', $this->hooks);
        $this->resetForm();
        session()->flash('success', "Webhook saved successfully.");
    }

    public function testHook($index)
    {

        try {
            Http::post($this->hooks[$index]['url'], [
                'ID' => '10',
                'Amount' => $this->hooks[$index]['trigger'],
                'querystring' => 'This is a test trigger from Livewire',
            ]);
            session()->flash('success', "Test sent successfully to '{$this->hooks[$index]['name']}'");
        } catch (\Exception $e) {
            session()->flash('error', "Test failed: " . $e->getMessage());
        }
    }

    public function edit($index)
    {
        $hook = $this->hooks[$index];
        $this->name = $hook['name'];
        $this->url = $hook['url'];
        $this->trigger = $hook['trigger'];
        $this->editIndex = $index;
    }

    public function delete($index)
    {
        unset($this->hooks[$index]);
        $this->hooks = array_values($this->hooks); // Re-index
        session()->put('webhooks', $this->hooks);
        session()->flash('success', 'Webhook deleted.');
    }

    public function resetForm()
    {
        $this->reset(['name', 'url', 'trigger', 'editIndex']);
    }

    public function render()
    {
           return view('livewire.webhook-manager')->layout('layout.user');
    }
}


