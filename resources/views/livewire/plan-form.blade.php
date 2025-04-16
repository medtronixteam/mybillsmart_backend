<div class="container">
    <div>
        <div class="row">
            @foreach ($plans as $index => $plan)
                <div class="bg-white rounded-lg card p-6 text-center col-md-4 mb-4">
                    <h2 class="text-xl font-bold mb-4">{{str_replace('_',' ',ucfirst( $plan['name'] ))}}</h2>
                    <div class="mb-4">
                        <label for="price-{{ $index }}" class="block text-gray-700">Price</label>
                        <input
                            type="number"
                            id="price-{{ $index }}"
                            class="form-control"
                            wire:model="plans.{{ $index }}.price"
                            wire:change="updatePrice({{ $index }}, $event.target.value)"
                        />
                    </div>
                    <p class="text-gray-600">Current Price: ${{ $plan['price'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</div>
