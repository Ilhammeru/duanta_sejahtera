<div class="form-gorup row mb-5 serviceSection">
    <div class="col-md-6 col-xl-6">
        <label for="customerContractService{{$count}}" class="col-form-label">Pelayanan</label>
        <select name="customer_service_id[]" id="customerContractService{{$count}}" class="form-control customerContractService">
            <option value="">- Pilih Pelayanan -</option>
        </select>
    </div>
    <div class="col-md-6 col-xl-6">
        <label for="customerContractBillingType{{$count}}" class="col-form-label">Jenis Pembayaran</label>
        <select name="customer_billing_type_id[]" id="customerContractBillingType{{$count}}" class="form-control customerContractBillingType">
            <option value="">- Pilih Jenis Pembayaran -</option>
            <option value="1">Cash</option>
            <option value="2">Tempo</option>
        </select>
    </div>
</div>