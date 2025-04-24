@extends('layout.admin')

@section('content')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-12 d-flex justify-content-between">
                    <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Agreement List</h3>
                    <a href="{{ route('agreements.create') }}" class="btn btn-info">Add Agreements</a>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header">
                            <h2 class="">Agreements</h2>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="bg-dark text-white">
                                            <tr>
                                                <th>#</th>
                                                <th>Title</th>
                                                <th>Description</th>
                                                <th>status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($agreements as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->title }}</td>
                                                    <td>{{ \Illuminate\Support\Str::limit($item->description, 20) }}</td>
                                                    <td>{{ $item->status }}</td>
                                                    <td>
                                                        <div class="">
                                                            <button class="btn p-0 border-0 bg-transparent" type="button"
                                                                id="dropdownMenuButton" data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                                <i class="fa-solid fa-ellipsis text-dark"
                                                                    style="font-size: 1.5rem;"></i>
                                                            </button>
                                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                    <li><a class="dropdown-item" href="{{ route('agreements.edit', $item->id) }}"
                                                                           >Edit</a>
                                                                    </li>
                                                                    <li><a class="dropdown-item" href="{{ route('agreements.view', $item->id) }}"
                                                                           >View</a>
                                                                    </li>
                                                                    <li><a class="dropdown-item" href="javascript:void(0)"
                                                                            onclick="confirmDelete({{ $item->id }})">Delete</a>
                                                                    </li>


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
                        url: '/agreements/delete/' + deleteId,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function() {
                            Swal.fire(
                                'Deleted!',
                                'agreement has been deleted.',
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
