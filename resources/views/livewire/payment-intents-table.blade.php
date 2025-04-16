<div class="container mt-4">
    <div class="row mb-3">
        <div class="col">
            <input wire:model.debounce.300ms="planName" type="text" class="form-control" placeholder="Filter by Plan Name">
        </div>
        <div class="col">
            <input wire:model.debounce.300ms="user" type="text" class="form-control" placeholder="Filter by User Name">
        </div>

        <div class="col">
            <input wire:model="date" type="date" class="form-control">
        </div>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Stripe ID</th>
                <th>Plan Name</th>
                <th>User</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Currency</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($intents as $intent)
                <tr>
                    <td>{{ $intent->id }}</td>
                    <td>{{ $intent->stripe_payment_intent_id }}</td>
                    <td>{{ $intent->plan_name }}</td>
                    <td>{{ $intent->user?->name }}</td>
                    <td>${{ number_format($intent->amount, 2) }}</td>
                    <td>{{ ucfirst($intent->status) }}</td>
                    <td>{{ strtoupper($intent->currency) }}</td>
                    <td>{{ $intent->created_at->format('Y-m-d') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">No records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div>
        {{ $intents->links('pagination::bootstrap-4') }}
    </div>
</div>
