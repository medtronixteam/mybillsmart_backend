<div class="container mt-4">

    {{-- <div class="row mb-3">
        <div class="col">
            <input wire:keyup="onChangeIn" wire:change="onChangeIn" wire:model.debounce.500ms="planName" type="text" class="form-control" placeholder="Plan Name">
        </div>
        <div class="col">
            <input wire:keyup="onChangeIn" wire:change="onChangeIn" wire:model.debounce.500ms="user" type="text" class="form-control" placeholder="User Name">
        </div>
        <div class="col">
            <select  wire:change="onChangeIn" wire:model="status" class="form-control">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="cancelled">Cancelled</option>
                <option value="paused">Paused</option>
                <option value="expired">Expired</option>
            </select>
        </div>
        <div class="col">
            <input wire:model.lazy="start_date" type="date" class="form-control" placeholder="Start Date">
        </div>
    </div> --}}
    <div class="row">   
             <div class="col-12 col-lg-4 col-md-6">
                    <div class="card border-right shadow">
                        <div class="card-body">
                            <div class="d-flex d-lg-flex d-md-block align-items-center">
                                <div>
                                    <div class="d-inline-flex align-items-center">
                                        <h2 class="text-dark mb-1 font-weight-medium">  </h2>
                                    </div>
                                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Total Users</h6>
                                </div>
                                <div class="ml-auto mt-md-3 mt-lg-0">
                                    <span class="opacity-7 text-muted"><i data-feather="user-plus"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    </div>
<div x-data
     x-init="
        setTimeout(() => { $wire.call('callFirstApi') }, 4000);
        setTimeout(() => { $wire.call('callSecondApi') }, 6000);
     "
     class="p-4 bg-white rounded shadow">

    <p class="text-sm text-gray-600">Waiting for API calls...</p>
</div>
  <div class="card">
    <div class="card-header h2">
        WhatssApp Manager
    </div>
    <div class="card-body">
          <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Session</th>
                <th>User</th>


            </tr>
        </thead>
        <tbody>
            @forelse ($WhatsappSessions as $sub)
                <tr>
                    <td>{{ $sub->id }}</td>
                    <td>{{ $sub->session_name }}</td>
                    <td>{{ $sub->user?->name ?? '-' }}</td>
                                @empty
                <tr>
                    <td colspan="6">No Session   found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    <div class="card-footer">
 {{ $WhatsappSessions->links('pagination::bootstrap-4') }}
    </div>
  </div>

    <div>

    </div>
</div>
