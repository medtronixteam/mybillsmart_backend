<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ShowDatabaseTables extends Component
{
    public $tables = [];
    public $selectedTable;
    public $columns = [];
    public $data = [];

    public function mount()
    {
        // Fetch all table names
        $this->tables = DB::select('SHOW TABLES');
        $this->tables = array_map(function($table) {
            return (array) $table;
        }, $this->tables);

        $firstTableName = array_values($this->tables[0] ?? [])[0] ?? null;

        if ($firstTableName) {
            $this->selectedTable = $firstTableName;
            $this->loadTableData();
        }
    }

    public function ToggleSelectedTable()
    {
        $this->loadTableData();

    }

    private function loadTableData()
    {

        if (!$this->selectedTable) return;

        try {
            // Get column names
            $this->columns = Schema::getColumnListing($this->selectedTable);

            // Get first 10 rows of the table
            $this->data = DB::table($this->selectedTable)->limit(10)->get()->toArray();
        } catch (\Exception $e) {
            $this->columns = [];
            $this->data = [];
            session()->flash('error', 'Unable to fetch data for table: ' . $this->selectedTable);
        }
    }

    public function render()
    {
        return view('livewire.show-database-tables')->layout('layout.app');
    }
}
