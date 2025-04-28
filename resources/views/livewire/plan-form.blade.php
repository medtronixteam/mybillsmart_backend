<div class="container">
    <div>
        <div class="row">
            @foreach ($plans as $index => $plan)
                <div class="m bg-white rounded-lg card p-5 text-center col-md-4 mb-4">
                    <h2 class="text-xl font-bold mb-4">{{ str_replace('_', ' ', ucfirst($plan['name'])) }}</h2>
                    <div class="mb-4">
                        <label for="price-{{ $index }}" class="block text-gray-700">Price</label>
                        <input type="number" id="price-{{ $index }}" class="form-control"
                            wire:model="plans.{{ $index }}.price"
                            wire:change="updatePrice({{ $index }}, $event.target.value)" />
                        @if ($plan['name'] == 'starter' || $plan['name'] == 'pro' || $plan['name']== 'enterprise')
                            <label for="">Allowed Invoices</label>
                            <input type="number" id="price-{{ $index }}" class="form-control"
                                wire:model="plans.{{ $index }}.invoices"
                                wire:change="updateInvoice({{ $index }}, $event.target.value)" />
                            <label for="">Allowed Agents</label>
                            <input type="number" id="price-{{ $index }}" class="form-control"
                                wire:model="plans.{{ $index }}.agents"
                                wire:change="updateAgents({{ $index }}, $event.target.value)" />
                        @endif
                    </div>

                </div>
            @endforeach
        </div>
    </div>
</div>
