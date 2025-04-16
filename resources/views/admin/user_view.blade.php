@extends('layout.admin')

@section('content')
    <div class="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header bg-dark text-white">
                            <h2 class="mb-0">Personal Information</h2>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <p><strong>Name:</strong> {{ $user->name }}</p>
                                        <p><strong>Email:</strong> {{ $user->email }}</p>
                                        <p><strong>Created At:</strong> {{ $user->created_at->format('d M, Y') }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Status:</strong>
                                            @if ($user->status == 1)
                                                <span class="badge badge-success">Enable</span>
                                            @else
                                                <span class="badge badge-warning">Disable</span>
                                            @endif
                                        </p>
                                        <p><strong>Role:</strong> {{str_replace('_',' ' , ucfirst($user->role)) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row my-3">
                        <div class="col-sm-2">
                            <div class="card">
                                <div class="card-header bg-dark text-white">
                                    <h4>Total Invoices</h4>
                                </div>
                                <div class="card-body">
                                    {{ $groupInvoices->count() }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="card">
                                <div class="card-header bg-dark text-white">
                                    <h4>Total Users</h4>
                                </div>
                                <div class="card-body">
                                    {{ $groupUsers->count() }}
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- end of card --}}
                @if ($user->role=="group_admin")

                    <div class="card">
                        <div class="card-header bg-dark text-white">
                            <h2 class="mb-0">User Lists</h2>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <table class="table">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                    </tr>
                                    @foreach ($groupUsers as $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ ucfirst($user->role) }}</td>
                                            <td>
                                                @if ($user->status == 1)
                                                    <span class="badge badge-success">Enable</span>
                                                @else
                                                    <span class="badge badge-warning">Disable</span>
                                                @endif
                                            </td>
                                            <td>{{ $user->created_at->format('d M, Y') }}</td>

                                        </tr>
                                        @endforeach
                                </table>
                            </div>
                        </div>
                    </div>

                @endif
                    {{-- end of card --}}
                    <div class="card">
                        <div class="card-header bg-dark text-white">
                            <h2 class="mb-0">Recent Invoices</h2>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <table class="table">
                                    <tr>
                                        <th>ID</th>
                                        <th>Bill Type</th>
                                        <th>Billing Period</th>
                                        <th>CUPS</th>

                                        <th>Created At</th>
                                    </tr>
                                    @foreach ($groupInvoices as $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ $item->bill_type }}</td>
                                            <td>{{ $item->billing_period }}</td>
                                            <td>{{ ucfirst($item->CUPS) }}</td>

                                            <td>{{ $item->created_at->format('d M, Y') }}</td>

                                        </tr>
                                        @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                    {{-- end of card --}}

                </div>
            </div>
        </div>
@endsection
