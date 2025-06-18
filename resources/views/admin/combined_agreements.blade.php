@extends('layout.admin')

@section('content')

    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-12 align-self-center">
                    <div class="d-flex justify-content-between">
                        <div> <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Combined Agreements</h3></div>
                        <div> <a href="{{ route('agreements') }}" class="btn btn-info">Electricity</a>
                            <a href="{{ route('gas.list') }}" class="btn btn-success">Gas</a>
                            <a href="{{ route('combined.list') }}" class="btn btn-warning">Combined</a></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header d-flex justify-content-between">
                            <h2 class="">Agreements</h2>
                            <a class="btn btn-primary" href="{{ route('combined.create') }}" class="">Add Combined Agreements</a>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="bg-dark text-white">
                                            <tr>
                                                <th>#</th>
                                                <th>Product Name</th>
                                                <th>Provider</th>
                                                <th>Agent Commision</th>
                                                <th>Agreement Type</th>
                                                <th>Customer Type</th>
                                                <th>Agreement Type</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($agreements as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->product_name }}</td>
                                                    <td>{{ $item->provider_name }}</td>
                                                    <td>
                                                     {{ $item->sales_commission }}
                                                    </td>
                                                    <td>
                                                   {{ $item->agreement_type }}
                                                    </td>
                                                    <td>
                                                   {{ $item->customer_type }}
                                                    </td>
                                                    <td>
                                                   {{ $item->product_type }}
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
                                                                <li><a class="dropdown-item" href="{{ route('combined.view', $item->id) }}">View</a>
                                                                </li>
                                                                <li> <a href="{{ route('combined.edit', $item->id) }}"
                                                                        class="dropdown-item">Edit</a></li>
                                                                </li>
                                                                <li> <a href="javascript:void(0);"
                                                                        onclick="confirmDelete({{ $item->id }})"
                                                                        class="dropdown-item">Delete</a></li>

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
        function confirmDelete(deleteId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to delete this agreement?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/both/agreement/delete/' + deleteId,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function() {
                            Swal.fire(
                                'Deleted!',
                                'Agreement has been deleted.',
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
