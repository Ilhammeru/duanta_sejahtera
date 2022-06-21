@php
    $selectedServices = $customer->services;
@endphp
<form id="formEditCustomer">
    @for ($i = 0; $i < count($selectedServices); $i++)
        <div class="form-group row mb-5">
            <div class="col-md-6">
                <label for="serviceName" class="col-form-label">Layanan</label>
                <select name="service_id[]" class="form-select form-control" id="serviceName">
                    <option value="">- Pilih Layanan -</option>
                    @foreach ($service as $item)
                        <option {{ $selectedServices[$i]->service_id == $item->id ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label for="billingType" class="col-form-label">Jenis Pembayaran</label>
                <select name="billing_type_id[]" class="form-select form-control" id="billingType">
                    <option value="">- Pilih Jenis Pembayaran -</option>
                    <option {{ $selectedServices[$i]->billing_type_id == 1 ? 'selected' : '' }} value="1">Cash</option>
                    <option {{ $selectedServices[$i]->billing_type_id == 2 ? 'selected' : '' }} value="2">Tempo</option>
                </select>
            </div>
        </div>
    @endfor
</form>