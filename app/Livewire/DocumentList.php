<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
class DocumentList extends Component
{
    use WithFileUploads;

    public $search = '';
    public $file;
    public $uploading = false;
    public $documents = [];
    public $loading = true;
    public $error = null;
    public $deleteId = null;
    public $endUrl = 'http://34.93.86.68:8000';

    protected $rules = [
        'file' => 'required|file|mimes:pdf|max:10240',
    ];

    public function mount()
    {
        $this->loadDocuments();

    }

    public function loadDocuments()
    {

        $this->loading = true;
        $this->error = null;

        try {
            $response = Http::get($this->endUrl.'/documents');

            if ($response->successful()) {
                $data = $response->json();
                $this->documents = $data['documents'] ?? [];
            } else {
                session()->flash('error', 'Failed to fetch data click on refresh button');
            }
        } catch (\Exception $e) {

            session()->flash('error', 'Failed to fetch data click on refresh button');
        } finally {
            $this->loading = false;
        }
    }
    public function confirmDelete($id)
{



    $this->deleteId = $id;
  //  $this->dispatchBrowserEvent('saved-successfully');
}

    public function deleteConfirmed()
    {
        try {
            $response = Http::delete($this->endUrl.'/delete_pdf/'.$this->deleteId);

            if ($response->successful()) {
                $this->dispatch('hideModal', ['Hello from Livewire!']);
                $this->deleteId = null; // Reset deleteId after deletion

                session()->flash('message', 'Document deleted successfully.');
            } else {
                session()->flash('error', 'Failed to delete document Please try again');
            }
            $this->loadDocuments();

        } catch (\Exception $e) {
            $this->deleteId = null; // Reset deleteId after deletion
            session()->flash('error', 'Error: ' . $e->getMessage());
            $this->dispatch('hideModal', ['Hello from Livewire!']);
        }
    }

    public function uploadPdf()
    {
        $this->validate();

        $this->uploading = true;

        try {
            $client = new Client();

            $response = $client->post('http://34.93.86.68:8000/upload_pdf', [
                'headers' => [
                    'accept' => 'application/json',
                ],
                'multipart' => [
                    [
                        'name' => 'file',
                        'contents' => fopen($this->file->getRealPath(), 'r'),
                        'filename' => $this->file->getClientOriginalName(),
                        'headers' => [
                            'Content-Type' => 'application/pdf'
                        ]
                    ]
                ]
            ]);

           // $this->response = json_decode($response->getBody(), true);
            $this->uploading = false;
            $this->file = false;
            $this->loadDocuments();
            session()->flash('message', 'PDF uploaded successfully!');

        } catch (\Exception $e) {
            $this->uploading = false;
            $this->file = false;
         //   $this->response = ['error' => $e->getMessage()];
            session()->flash('error', 'Error uploading PDF: ' . $e->getMessage());
        }
    }


    public function render()
    {
        return view('livewire.document-list')->layout('layout.app');
    }
}
