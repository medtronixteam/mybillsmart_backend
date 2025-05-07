<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">Train AI Bot </h1>
        <button wire:click="loadDocuments" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-sync"></i> Refresh
        </button>
    </div>


    @if ($loading)
        <div class="text-center my-5">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <p class="mt-2">Loading documents...</p>
        </div>
    @else
        @if (session('message'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('message') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Upload PDF</h5>
            </div>
            <div class="card-body">
                <form wire:submit.prevent="uploadPdf" accept="multipart/form-data">
                    <div class="form-group">
                        <div class="custom-file">
                            <input type="file" wire:model="file"
                                class="custom-file-input @error('file') is-invalid @enderror" id="customFile">
                            <label class="custom-file-label" for="customFile">
                                @if ($file)
                                    {{ $file->getClientOriginalName() }}
                                @else
                                    Choose PDF file (max 10MB)
                                @endif
                            </label>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="uploadPdf">Upload</span>
                        <span wire:loading wire:target="uploadPdf">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Uploading...
                        </span>
                    </button>
                </form>
            </div>
        </div>

        <div class="mb-4">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search documents..."
                class="form-control">
        </div>

        @if (count($documents) > 0)
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Document Name</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($documents as $filename)
                            @if (empty($search) || str_contains(strtolower($filename), strtolower($search)))
                                <tr>
                                    <td>{{ $filename }}</td>
                                    <td>

                                        {{-- <button wire:click="delete('{{ $filename }}')"
                                            class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> Delete
                                        </button> --}}
                                        <!-- Update the delete button -->
                                        <button wire:click="confirmDelete('{{ $filename }}')"
                                            class="btn btn-sm btn-danger" data-toggle="modal"
                                            data-target="#deleteModal">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info">
                No documents found.
            </div>
        @endif
    @endif
    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this document?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" wire:click="deleteConfirmed">Delete</button>
                </div>
            </div>
        </div>
    </div>



    @push('scripts')
        <script>
            document.addEventListener('livewire:initialized', function() {
                Livewire.on('hideModal', (data) => {
                    $('#deleteModal').modal('hide');
                    $('.modal-backdrop').fadeOut();
                });
                Livewire.on('fileSelected', function() {
                    let input = document.getElementById('customFile');
                    input.addEventListener('change', function() {
                        if (input.files.length > 0) {
                            let fileName = input.files[0].name;
                            let label = input.nextElementSibling;
                            label.innerText = fileName;
                        }
                    });
                });
            });

        </script>
    @endpush
</div>
