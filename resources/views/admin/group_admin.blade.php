@extends('layout.admin')

@section('content')

    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-12 align-self-center">
                    <div class="d-flex justify-content-between">
                        <div> <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Group Admin List</h3></div>
                        <div> <a href="{{ route('user.list') }}" class="btn btn-info">Admin</a>
                            <a href="{{ route('group.admin') }}" class="btn btn-success">Group Admin</a>
                            <a href="{{ route('all.users') }}" class="btn btn-warning">All Users</a></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header">
                            <h2 class="">Group Admins</h2>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered dataTable ">
                                        <thead class="bg-dark text-white">
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Status</th>
                                                <th>Plan</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($users as $item)
                                                <tr>
                                                    <td>{{  $loop->iteration  }}</td>
                                                    <td>{{ $item->name }}</td>
                                                    <td>{{ $item->email }}</td>
                                                    <td>
                                                        @if ($item->status == 1)
                                                            <span class="badge badge-success">Enable</span>
                                                        @else
                                                            <span class="badge badge-warning">Disable</span>
                                                        @endif
                                                    </td>
                                                    <td>

                                                        @if ($item->activeSubscriptions()->count()>0)
                                                        <span class="badge badge-info">{{ $item->activeSubscriptions()->value('plan_name')}}</span>
                                                        @else

                                                        <span class="badge badge-danger">No Plan</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="">
                                                            <button class="btn p-0 border-0 bg-transparent" type="button"
                                                                id="dropdownMenuButton" data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                                <i class="fa-solid fa-ellipsis text-dark"
                                                                    style="font-size: 1.5rem;"></i>
                                                            </button>
                                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                @if ($item->status == 1)
                                                                    <li><a class="dropdown-item" href="{{ route('user.view', $item->id) }}">View</a>
                                                                    </li>
                                                                    <li><a class="dropdown-item" href="{{ route('user.password', $item->id) }}">Reset Password</a>
                                                                    </li>
                                                                    <li><a class="dropdown-item" href="javascript:void(0)"
                                                                            onclick="confirmDelete({{ $item->id }})">Delete</a>
                                                                    </li>
                                                                    <li><a class="dropdown-item" href="javascript:void(0)"
                                                                            onclick="confirmDisable({{ $item->id }})">Disable</a>
                                                                    </li>
                                                                @else
                                                                    <li><a class="dropdown-item" href="javascript:void(0)"
                                                                            onclick="confirmEnable({{ $item->id }})">Enable</a>
                                                                @endif
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    <script>
        function confirmDisable(userId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to disable this user?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, disable it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/users/disable/' + userId,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function() {
                            Swal.fire(
                                'Disabled!',
                                'User has been disabled.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        }
                    });
                }
            })
        }

        function confirmEnable(userId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to enable this user?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, enable it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/users/enable/' + userId,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function() {
                            Swal.fire(
                                'Enabled!',
                                'User has been enabled.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        }
                    });
                }
            })
        }

        function confirmDelete(deleteId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to delete this user?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/users/delete/' + deleteId,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function() {
                            Swal.fire(
                                'Deleted!',
                                'User has been deleted.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        }
                    });
                }
            })
        }

    </script>
