<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Recent Webhooks</h5>
            <button class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#createForm">
                {{ $editIndex !== null ? 'Edit Hook' : 'Create Hook' }}
            </button>
        </div>

        <div class="collapse p-3 show" id="createForm">
            <form wire:submit.prevent="save(false)" class="row g-3">
                <div class="col-md-3">
                    <input type="text" wire:model="name" class="form-control" placeholder="Hook Name">
                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-4">
                    <input type="url" wire:model="url" class="form-control" placeholder="Webhook URL">
                    @error('url') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-3">
                    <select wire:model="trigger" class="form-control">
                        <option value="">Select Trigger</option>
                        @foreach ($availableTriggers as $event)
                            <option value="{{ $event }}">{{ $event }}</option>
                        @endforeach
                    </select>
                    @error('trigger') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-success btn-sm ">Save</button>
                    <button type="button" class="btn btn-warning btn-sm ml-1" wire:click="save(true)">
                        Test & Save
                    </button>
                </div>
            </form>
        </div>

        <div class="card-body table-responsive">
            @if (session()->has('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session()->has('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Trigger</th>
                        <th>URL</th>
                        <th>Created At</th>
                        <th width="160">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($hooks as $index => $hook)
                        <tr>
                            <td>{{ $hook['name'] }}</td>
                            <td>{{ $hook['trigger'] }}</td>
                            <td class="text-break">{{ $hook['url'] }}</td>
                            <td>{{ $hook['created_at'] }}</td>
                            <td class="">
                                <button wire:click="edit({{ $index }})" class="btn m-1 btn-sm btn-info">Edit</button>
                                <button wire:click="testHook({{ $index }})" class="btn m-1 btn-sm btn-primary">Test</button>
                                <button wire:click="delete({{ $index }})" class="btn m-1 btn-sm btn-danger">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5">No hooks yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
