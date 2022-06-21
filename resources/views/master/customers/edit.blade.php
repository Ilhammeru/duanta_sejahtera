@extends('layouts.master')

{{-- begin::styles --}}
@push('styles')
<link href="{{ asset('plugins/custom/filepond/filepond.css') }}" rel="stylesheet" />
<link
    href="{{ asset('plugins/custom/filepond/plugins-preview.css') }}"
    rel="stylesheet"
/>
@endpush
{{-- end::styles --}}

{{-- begin::content --}}
@section('content')
    {{-- begin::card --}}
    <div class="card card-flush mb-5">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between">
                <div>
                    <a href="{{ route('customers.index') }}" class="btn btn-light-primary">
                        <i class="fa fa-chevron-left"></i>
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
    {{-- end::card --}}

    {{-- begin::card --}}
    <div class="card card-flush">
        <div class="card-body">
            {{-- begin::form --}}
            <form action="{{ route('customers.update', $customer->id) }}" id="formCustomer" enctype="multipart/form-data">
                {{-- begin::section-title --}}
                <h3 class="border-bottom text-center p-3 mb-5">Data Personal</h3>
                {{-- end::section-title --}}
                <div class="form-group mb-5 row">
                    <div class="col-md-6 col-xl-6">
                        <label for="customerName" class="col-form-label">Nama</label>
                        <input type="text" placeholder="Nama Customer" value="{{ $customer->name }}" name="name" class="form-control" id="customerName">
                    </div>
                    <div class="col-md-6 col-xl-6">
                        <label for="customerEmail" class="col-form-label">Email</label>
                        <input type="email" name="email" value="{{ $customer->email }}" placeholder="Email Customer" class="form-control" id="customerEmail">
                    </div>
                </div>
                <div class="form group row mb-5">
                    <div class="col-md-6 col-xl-6">
                        <label for="customerPhone" class="col-form-label">Telepon</label>
                        <input type="number" value="{{ $customer->phone }}" placeholder="No. Telpon Customer" name="phone" class="form-control" id="customerPhone">
                    </div>
                    <div class="col-md-6 col-xl-6">
                        <label for="customerNpwp" class="col-form-label">NPWP</label>
                        <input type="text" placeholder="NPWP Customer" value="{{ $customer->npwp }}" name="npwp" class="form-control" id="customerNpwp">
                    </div>
                </div>
                <div class="form-group row mb-5">
                    <div class="col-md-4 col-xl-4">
                        <label for="customerProvince" class="col-form-label">Provinsi</label>
                        <select name="province" value="{{ $customer->province }}" id="customerProvince" data-placeholder="- Pilih Provinsi -" class="form-select form-control">
                            <option value="">- Pilih Provinsi -</option>
                            @foreach ($provinces as $province)
                                <option {{ $customer->province == $province->id ? 'selected' : '' }} value="{{ $province->id }}">{{ $province->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 col-xl-4">
                        <label for="customerCity" class="col-form-label">Kota</label>
                        <select name="city" id="customerCity" data-placeholder="- Pilih Kota -" class="form-select form-control">
                            <option value="">- Pilih Kota -</option>
                            @foreach ($regencies as $regency)
                                <option {{ $customer->city == $regency->id ? 'selected' : '' }} value="{{ $regency->id }}">{{ $regency->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 col-xl-4">
                        <label for="customerDistrict" class="col-form-label">Kecamatan/Kelurahan</label>
                        <select name="district" id="customerDistrict" data-placeholder="- Pilih Kecamatan / Kelurahan -" class="form-select form-control">
                            <option value="">- Pilih Kecamatan / Kelurahan -</option>
                            @foreach ($format as $item)
                                <option {{ $customer->district == $item['id'] ? 'selected' : '' }} value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row mb-5">
                    <div class="col">
                        <label for="customerAddress" class="col-form-label">Alamat Lengkap</label>
                        <textarea name="address" id="customerAddress" cols="4" rows="4" class="form-control">
                            {{ $customer->address }}
                        </textarea>
                    </div>
                </div>
                <div class="form-group row mb-5">
                    <div class="col-md-6 col-xl-6">
                        <label for="customerPicName" class="col-form-label">Nama PIC</label>
                        <input type="text" placeholder="Nama PIC" value="{{ $customer->pic_name }}" name="pic_name" class="form-control" id="customerPicName">
                    </div>
                    <div class="col-md-6 col-xl-6">
                        <label for="customerPicPhone" class="col-form-label">Telepon PIC</label>
                        <input type="number" placeholder="No. Telepon PIC" value="{{ $customer->pic_phone }}" name="pic_phone" class="form-control" id="customerPicPhone">
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col">
                        {{-- begin::section-title --}}
                        <h3 class="border-bottom border-top text-center p-3 mb-5 mt-5">Data Kontrak</h3>
                        {{-- end::section-title --}}
                    </div>
                </div>
                {{-- begin::service-form --}}
                @for ($i = 0; $i < count($customer->services); $i++)
                    <div class="form-gorup row mb-5 serviceSection" id="serviceSection{{ $i + 1 }}">
                        <div class="col-md-5 col-xl-5">
                            <label for="customerContractService1" class="col-form-label">Pelayanan</label>
                            <select name="customer_service_id[]" id="customerContractService{{ $i + 1 }}" class="form-control customerContractService">
                                <option value="">- Pilih Pelayanan -</option>
                                @foreach ($services as $service)
                                    <option {{ $customer->services[$i]->service_id == $service->id ? 'selected' : '' }} value="{{ $service->id }}">{{ $service->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5 col-xl-5">
                            <label for="customerContractBillingType1" class="col-form-label">Jenis Pembayaran</label>
                            <select name="customer_billing_type_id[]" id="customerContractBillingType{{ $i + 1 }}" class="form-control customerContractBillingType">
                                <option value="">- Pilih Jenis Pembayaran -</option>
                                <option {{ $customer->services[$i]->billing_type_id == 1 ? 'selected' : '' }} value="1">Cash</option>
                                <option {{ $customer->services[$i]->billing_type_id == 2 ? 'selected' : '' }} value="2">Tempo</option>
                            </select>
                        </div>
                        <div class="col-md-2 col-xl-2">
                            <label for="" class="col-form-label label-action" style="color: transparent;">Data</label>
                            <span class="text-info form-control" style="border: none !important;">
                                <i class="fa fa-times" style="cursor:pointer;" onclick="deleteSectionService({{ $i + 1 }})"></i>
                            </span>
                        </div>
                    </div>
                @endfor
                <div id="targetServiceSection"></div>
                <div class="form-group" style="margin-bottom: 30px;">
                    <button class="btn btn-primary p-2" style="font-size: 10px;" onclick="addService()" type="button">
                        Tambah Layanan
                        <i class="fa fa-plus ms-3" style="font-size: 8px;"></i>
                    </button>
                </div>
                {{-- end::service-form --}}
                <div class="row mb-5">
                    <div class="col-md-4">
                        <label for="customerContractDate" class="col-form-label mb-0 p-0">Tanggal Mulai Kontrak</label>
                        <p class="text-secondary" style="font-size: 12px;">Tanggal dimulai nya kontrak dengan customer</p>
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-5">
                        <input type="date" value="{{ date('Y-m-d', strtotime($customer->contract->start_date)) }}" id="customerContractDate" class="form-control" name="contract_date">
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-md-4">
                        <label for="customerContractPeriod" class="col-form-label mb-0 p-0">Lama Kontrak (hari)</label>
                        <p class="text-secondary" style="font-size: 12px;">Lama Masa Kontrak yang Disepakati dalam Hari</p>
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-5">
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
                    <div class="col-md-5">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" {{ $customer->contract->is_auto_renewal == 1 ? 'checked' : '' }} type="radio" name="customer_renewal" value="1" id="customerContractRenewal">
                                    <label class="form-check-label" for="customerContractRenewal">
                                      Ya
                                    </label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" {{ $customer->contract->is_auto_renewal == 0 ? 'checked' : '' }} type="radio" name="customer_renewal" value="0" id="customerContractRenewal1">
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
                    <div class="col-md-5">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" {{ $customer->type == 'casual' ? 'checked' : '' }} name="customer_type" value="casual" id="customerType">
                                    <label class="form-check-label" for="customerType">
                                        Casual
                                    </label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" {{ $customer->type == 'regular' ? 'checked' : '' }} type="radio" name="customer_type" value="regular" id="customerType1">
                                    <label class="form-check-label" for="customerType1">
                                        Regular
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-md-4">
                        <label for="customerContractDoc" class="col-form-label mb-0 p-0">Upload Dokumen Perjanjian</label>
                        <p class="text-secondary" style="font-size: 12px;">Foto Perjanjian Kontrak dengan format pdf, jpg, jpeg atau png dengan maksimal ukuran adalah 2 MB</p>
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-5">
                        <input type="file" class="form-control" name="aggreement_letter" id="aggreementLetterPhoto" accept="image/png, image/jpg, image/jpeg, image/pdf">
                    </div>
                </div>

                <div class="form-group">
                    <div class="text-end">
                        <button class="btn btn-primary" type="button" id="btnSave" onclick="save()">Simpan</button>
                    </div>
                </div>
            </form>
            {{-- end::form --}}
        </div>
    </div>
    {{-- end::card --}}
@endsection
{{-- end::content --}}

{{-- begin::script --}}
@push('scripts')
    <script src="{{ asset('plugins/custom/filepond/filepond.js') }}"></script>
    <script src="{{ asset('plugins/custom/filepond/plugins-preview.js') }}"></script>
    <script>
        $('#customerProvince').select2();
        $('#customerCity').select2();
        $('#customerDistrict').select2();

        // begin::variable
        let buttonSave = $('#btnSave');
        let form = $('#formCustomer');
        let sectionService = $('.serviceSection');
        // end::variable

        for (let aa = 0; aa < sectionService.length; aa++) {
            $('#customerContractService' + (aa + 1)).select2();
            $('#customerContractBillingType' + (aa + 1)).select2();
        }

        // begin::filePond
        // Create a FilePond instance
        FilePond.registerPlugin(FilePondPluginImagePreview);
        const pond = FilePond.create(
            document.getElementById('aggreementLetterPhoto')
        );
        let letterFile = "{{ $customer->contract->aggreement_letter_img }}";
        if (letterFile != "") {
            pond.addFile("{{ asset($customer->contract->aggreement_letter_img) }}");
        }

        document.getElementById('aggreementLetterPhoto').addEventListener(
            'FilePond:removefile', (e) => {
                console.log('e', e);
                let id = "{{ $customer->id }}";
                Swal.fire({
                    title: 'Apakah anda yakin ingin menghapus Foto ini?',
                    showDenyButton: true,
                    showCancelButton: false,
                    confirmButtonText: 'Ya!',
                    denyButtonText: `Batalkan`,
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "DELETE",
                            url: "{{ url('/customers/delete') }}" + '/' + id,
                            dataType: 'json',
                            error: function(err) {
                                handleError(err);
                            },
                            success: function(res) {
                                iziToast['success']({
                                    message: 'Foto berhasil di hapus',
                                    position: "topRight"
                                });
                                window.location.href = "{{ url('/customers') }}" + "/" + id + "/edit";
                            }
                        });
                    }
                });
            }
        )
        // end::filePond

        $('#customerProvince').on('change', function(e) {
            e.preventDefault();
            let province = $(this).val();
            $.ajax({
                type: "GET",
                url: "{{ url('/region/getCity/') }}" + "/" + province,
                dataType: 'json',
                error: function(err) {
                    handleError(err);
                },
                success: function(res) {
                    let option = '<option value="">- Pilih Kota -</option>';

                    for (let a = 0; a < res.data.length; a++) {
                        option += `<option value="${res.data[a].id}">
                            ${res.data[a].name}</option>`;
                    }
                    $('#customerCity').html(option);
                    $('#customerCity').select2();
                    $('#customerDistrict').html('');
                }
            })
        });

        $('#customerCity').on('change', function(e) {
            e.preventDefault();
            let district = $(this).val();
            $.ajax({
                type: "GET",
                url: "{{ url('/region/getDistrict/') }}" + "/" + district,
                dataType: 'json',
                error: function(err) {
                    handleError(err);
                },
                success: function(res) {
                    let option = '<option value="">- Pilih Kota -</option>';

                    for (let a = 0; a < res.data.length; a++) {
                        option += `<option value="${res.data[a].id}">
                            ${res.data[a].name}</option>`;
                    }
                    $('#customerDistrict').html(option);
                    $('#customerDistrict').select2();
                }
            })
        });

        function addService() {
            let serviceSection = $('.serviceSection');
            $.ajax({
                type: "GET",
                url: "{{ url('/customers/getFormService/') }}" + "/" + serviceSection.length,
                dataType: 'json',
                success: function(res) {
                    $('#targetServiceSection').append(res.data.html);
                    $(`#customerContractService${serviceSection.length + 1}`).select2();
                    $(`#customerContractBillingType${serviceSection.length + 1}`).select2();
                }
            })
        }

        function deleteSectionService(idSection) {
            let currentLength = $('.serviceSection').length;
            if (currentLength == 1) {
                iziToast['error']({
                    message: 'Setidaknya Pilih 1 Layanan dan Tipe Pembayaran',
                    position: "topRight"
                });
            } else {
                $('#serviceSection' + idSection).remove();
            }
        }

        function save() {
            let data = new FormData($('#formCustomer')[0]);
            let pondFile = pond.getFile();
            if (pondFile) {
                pondFile = pondFile.file;
                data.append('aggreement_letter', pondFile);
            }
            let url = $('#formCustomer').attr('action');
            console.log(url);
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "json",
                beforeSend: function() {
                    buttonSave.attr('disabled', true);
                    buttonSave.text('Menyimpan data ...');
                },
                success: function(res) {
                    console.log(res);
                    buttonSave.attr('disabled', false);
                    buttonSave.text('Simpan');
                    iziToast['success']({
                        message: 'Customer Berhasil Disimpan',
                        position: "topRight"
                    });
                    document.getElementById('formCustomer').reset();
                    window.location.href = "{{ route('customers.index') }}";
                },
                error: function(err) {
                    console.error(err);
                    buttonSave.attr('disabled', false);
                    buttonSave.text('Simpan');
                    handleError(err);
                }
            })
        }
    </script>
@endpush
{{-- end::script --}}