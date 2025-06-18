<div>
    @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <!-- Card -->
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Subscriptions</h5>
            <div>
                  <button class="btn btn-primary" wire:click="startFreeTrial">Extend Free Trial</button>
                  <a class="btn btn-dark m-1" href="{{ route('group.admin') }}">Go Back</a>
            </div>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped dataTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Plan Name</th>
                        <th>Status</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subscriptions as $subscription)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            {{-- <td>{{ optional($subscription->user)->name ?? 'N/A' }}</td> --}}
                            <td>{{ $subscription->plan_name }}</td>
                            <td>{{ $subscription->status }}</td>
                            <td>{{ $subscription->start_date }}</td>
                            <td>{{ $subscription->end_date }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No subscriptions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    @if($showModal)
        <div class="modal show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Start Free Trial</h5>
                        <button type="button" class="btn-close btn-dark" wire:click="$set('showModal', false)" aria-label="Close">X</button>
                    </div>
                    <form wire:submit.prevent="confirmStartTrial">
                        <div class="modal-body">

                            <div class="mb-3">
                                <label class="form-label">Start Date</label>
                                <input min="{{ date('Y-m-d') }}" type="date" class="form-control" wire:model="start_date">
                                @error('start_date') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">End Date (Optional)</label>
                                <input  type="date" class="form-control" wire:model="end_date">
                                <small class="text-muted">If left empty, default is 7 days after start date.</small>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <label class="form-label">Invoices</label>
                                    <input type="number" min="0" class="form-control" wire:model="invoices">
                                      @error('invoices') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">Agents</label>
                                    <input type="number" min="0" class="form-control" wire:model="agents">
                                      @error('agents') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="$set('showModal', false)">Cancel</button>
                            <button type="submit" class="btn btn-primary">Confirm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
