<div class="container">
    <div>
        <div class="row">
            @foreach ($plans as $index => $plan)
                <div class="m bg-white rounded-lg card p-5 text-center col-md-4 mb-4">
                    <h2 class="text-xl font-bold mb-4">{{ str_replace('_', ' ', ucfirst($plan['name'])) }}</h2>
                    <div class="mb-4">
                        <label for="price-{{ $index }}" class="block text-gray-700">Monthly Price</label>
                        <input type="number" id="price-{{ $index }}" class="form-control"
                            wire:model="plans.{{ $index }}.monthly_price"
                            wire:change="updatePrice({{ $index }}, $event.target.value)" />

                        @if ($plan['name'] == 'starter' || $plan['name'] == 'pro' || $plan['name']== 'enterprise')
                        <label for="annual_price-{{ $index }}" class="block text-gray-700">Annual Price</label>
                        <input type="number" id="annual_price-{{ $index }}" class="form-control"
                        wire:model="plans.{{ $index }}.annual_price"
                        wire:change="updateAnnualPrice({{ $index }}, $event.target.value)" />
                            <label for="">Allowed Invoices</label>
                            <input type="number" id="price-{{ $index }}" class="form-control"
                                wire:model="plans.{{ $index }}.invoices_per_month"
                                wire:change="updateInvoice({{ $index }}, $event.target.value)" />
                            <label for="">Allowed Agents</label>
                            <input type="number" id="price-{{ $index }}" class="form-control"
                                wire:model="plans.{{ $index }}.agents_per_month"
                                wire:change="updateAgents({{ $index }}, $event.target.value)" />
                        @endif
                    </div>

                </div>
            @endforeach
        </div>
    </div>
</div>
