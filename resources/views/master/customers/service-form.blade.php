<div class="form-gorup row mb-5 serviceSection" id="serviceSection{{$count + 1}}">
    <div class="col-md-5 col-xl-5">
        <label for="customerContractService{{$count + 1}}" class="col-form-label">Pelayanan</label>
        <select name="customer_service_id[]" id="customerContractService{{$count + 1}}" class="form-control customerContractService">
            <option value="">- Pilih Pelayanan -</option>
            @foreach ($services as $service)
                <option value="{{ $service->id }}">{{ $service->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-5 col-xl-5">
        <label for="customerContractBillingType{{$count + 1}}" class="col-form-label">Jenis Pembayaran</label>
        <select name="customer_billing_type_id[]" id="customerContractBillingType{{$count + 1}}" class="form-control customerContractBillingType">
            <option value="">- Pilih Jenis Pembayaran -</option>
            <option value="1">Cash</option>
            <option value="2">Tempo</option>
        </select>
    </div>
    <div class="col-md-2 col-xl-2">
        <label for="" class="col-form-label label-action" style="color: transparent;">Data</label>
        <span class="text-info form-control" style="border: none !important;">
            <i class="fa fa-times" style="cursor:pointer;" onclick="deleteSectionService({{ $count + 1 }})"></i>
        </span>
    </div>
</div>