<form action="" id="formEditCustomer" enctype="multipart/form-data">
    <div class="row mb-5">
        <div class="col-md-4">
            <label for="customerContractDate" class="col-form-label mb-0 p-0">Tanggal Mulai Kontrak</label>
            <p class="text-secondary" style="font-size: 12px;">Tanggal dimulai nya kontrak dengan customer</p>
        </div>
        <div class="col-md-1"></div>
        <div class="col-md-7">
            <input type="date" value="{{ date('Y-m-d', strtotime($customer->contract->start_date)) }}" id="customerContractDate" class="form-control" name="contract_date">
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-md-4">
            <label for="customerContractPeriod" class="col-form-label mb-0 p-0">Lama Kontrak (hari)</label>
            <p class="text-secondary" style="font-size: 12px;">Lama Masa Kontrak yang Disepakati dalam Hari</p>
        </div>
        <div class="col-md-1"></div>
        <div class="col-md-7">
            <div class="input-group">
                <input type="text" id="customerContractPeriod" value="{{ $customer->contract->contract_period_in_day }}" class="form-control" name="contract_period">
                <span class="input-group-text" id="addon4">Hari</span>
            </div>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-md-4">
            <label for="" class="col-form-label mb-0 p-0">Pembaruhan Kontrak Otomatis</label>
            <p class="text-secondary" style="font-size: 12px;">Otomatis atau Tidak, anda akan menerima pemberitahuan / peringatan akan habisnya kontrak pada customer ini</p>
        </div>
        <div class="col-md-1"></div>
        <div class="col-md-7">
            <div class="row">
                <div class="col-6">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="customer_renewal" {{ $customer->contract->is_auto_renewal == 1 ? 'checked' : '' }} value="1" id="customerContractRenewal">
                        <label class="form-check-label" for="customerContractRenewal">
                          Ya
                        </label>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="customer_renewal" {{ $customer->contract->is_auto_renewal == 0 ? 'checked' : '' }} value="0" id="customerContractRenewal1">
                        <label class="form-check-label" for="customerContractRenewal1">
                          Tidak
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-md-4">
            <label for="" class="col-form-label mb-0 p-0">Jenis Pelanggan</label>
        </div>
        <div class="col-md-1"></div>
        <div class="col-md-7">
            <div class="row">
                <div class="col-6">
                    <div class="form-check"> 
                        <input class="form-check-input" type="radio" name="customer_type" {{ $customer->type == 'casual' ? 'checked' : '' }} value="casual" id="customerType">
                        <label class="form-check-label" for="customerType">
                            Casual
                        </label>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="customer_type" {{ $customer->type == 'regular' ? 'checked' : '' }} value="regular" id="customerType1">
                        <label class="form-check-label" for="customerType1">
                            Regular
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="row mb-5">
        <div class="col-md-4">
            <label for="customerContractDoc" class="col-form-label mb-0 p-0">Upload Dokumen Perjanjian</label>
            <p class="text-secondary" style="font-size: 12px;">Foto Perjanjian Kontrak dengan format pdf, jpg, jpeg atau png dengan maksimal ukuran adalah 2 MB</p>
        </div>
        <div class="col-md-1"></div>
        <div class="col-md-7">
            <input type="file" class="form-control" name="aggreement_letter" id="aggreementLetterPhoto" accept="image/png, image/jpg, image/jpeg, image/pdf">
        </div>
    </div> --}}
</form>