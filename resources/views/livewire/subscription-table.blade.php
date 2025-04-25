<div class="container mt-4">

    <div class="row mb-3">
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
    </div>

    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Plan</th>
                <th>User</th>
                <th>Status</th>
                <th>Amount</th>
                <th>Start Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($subscriptions as $sub)
                <tr>
                    <td>{{ $sub->id }}</td>
                    <td>{{ $sub->plan_name }}</td>
                    <td>{{ $sub->user?->name ?? '-' }}</td>
                    <td>{{ ucfirst($sub->status) }}</td>
                    <td>${{ $sub->amount }}</td>
                    <td>{{ $sub->start_date ? \Carbon\Carbon::parse($sub->start_date)->format('Y-m-d') : '-' }}</td>                </tr>
            @empty
                <tr>
                    <td colspan="6">No subscriptions found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div>
        {{ $subscriptions->links('pagination::bootstrap-4') }}
    </div>
</div>
