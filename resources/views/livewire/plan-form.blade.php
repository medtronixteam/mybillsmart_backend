

    <div class="page-wrapper">
        <style>
            .plans {
                display: flex;
                flex-wrap: wrap;
                gap: 1rem;
            }
        </style>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <div class="card shadow">
                        <div class="card-header bg-dark text-white">
                            <h2 class="mb-0">Plans </h2>
                        </div>
                        <div class="card-content">
                            <div class="card-body">

                                @if (session()->has('message'))
                                    <div class="alert alert-success">
                                        {{ session('message') }}
                                    </div>
                                @endif

                                <form wire:submit.prevent="{{ $isEditing ? 'update' : 'submit' }}">
                                    <div class="form-group">
                                        <label for="name">Plan Name</label>
                                        <input  class="form-control" type="text" id="name" wire:model="name">
                                        @error('name')
                                            <span class="error">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="price">Price</label>
                                        <input  class="form-control" type="number" id="price" wire:model="price" step="0.01">
                                        @error('price')
                                            <span class="error">{{ $message }}</span>
                                        @enderror
                                    </div>



                                    <button class="btn btn-dark my-2" type="submit">{{ $isEditing ? 'Update Plan' : 'Create Plan' }}</button>
                                    @if ($isEditing)
                                        <button type="button" wire:click="resetForm">Cancel</button>
                                    @endif
                                </form>


                            </div>
                        </div>
                    </div>
                    <div class="plans">
                        @foreach ($plans as $plan)
                            <div class="card ">
                               <div class="card-header bg-dark">
                                <h3 class="text-white">{{ $plan->name }}</h3>
                               </div>
                               <div class="card-body">
                                <p>Price: {{ $plan->price }}</p>

                                <button type="button" class="btn bt-sm btn-dark" wire:click="edit({{ $plan->id }})">Edit</button>

                               </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    </div>
