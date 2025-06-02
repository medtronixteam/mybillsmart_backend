<div>
    @if(session()->has('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="mb-3">
        <label for="tableSelect" class="form-label">Select Table</label>
        <select id="tableSelect" class="form-select" wire:change='ToggleSelectedTable' wire:model="selectedTable">
            @foreach($tables as $table)
                @php $tableName = array_values($table)[0]; @endphp
                <option value="{{ $tableName }}">{{ $tableName }}</option>
            @endforeach
        </select>
    </div>



    @if(!empty($data))
        <h4>Data Preview (First 10 Rows)</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        @foreach($columns as $column)
                            <th>{{ $column }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $row)
                        <tr>
                            @foreach($row as $cell)
                                <td>{{ $cell }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
