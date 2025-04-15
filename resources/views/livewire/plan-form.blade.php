<!-- filepath: e:\laravelProjects\mySmartBiling_backend\resources\views\livewire\plan-form.blade.php -->
<div>
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="{{ $isEditing ? 'update' : 'submit' }}">
        <div>
            <label for="name">Plan Name</label>
            <input type="text" id="name" wire:model="name">
            @error('name') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="price">Price</label>
            <input type="number" id="price" wire:model="price" step="0.01">
            @error('price') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="duration">Duration</label>
            <select id="duration" wire:model="duration">
                <option value="">Select Duration</option>
                <option value="monthly">Monthly</option>
            </select>
            @error('duration') <span class="error">{{ $message }}</span> @enderror
        </div>

        <button type="submit">{{ $isEditing ? 'Update Plan' : 'Create Plan' }}</button>
        @if ($isEditing)
            <button type="button" wire:click="resetForm">Cancel</button>
        @endif
    </form>

    <hr>

    <div class="plans">
        @foreach ($plans as $plan)
            <div class="card">
                <h3>{{ $plan->name }}</h3>
                <p>Price: ${{ $plan->price }}</p>
                <p>Duration: {{ ucfirst($plan->duration) }}</p>
                <button wire:click="edit({{ $plan->id }})">Edit</button>
            </div>
        @endforeach
    </div>
</div>
