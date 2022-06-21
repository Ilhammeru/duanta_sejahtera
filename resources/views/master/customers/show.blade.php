@extends('layouts.master')

{{-- begin::styles --}}
@push('styles')
    <style>
        .letter-img {
            width: 150px;
            height: 150px;
            border-radius: 12px;
            cursor: pointer;
        }

        .card-service-contract {
            max-height: 250px;
            height: 250px;
            overflow: hidden;
        }
    </style>
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
    <link
        href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css"
        rel="stylesheet"
    />
@endpush
{{-- end::styles --}}

{{-- begin::content --}}
@section('content')

@php
    $userImage = true;
@endphp

<div class="row">
    <div class="col-md-12">
        <!--begin::Card-->
        <div class="card mb-5">
            <div class="card-body">
                <div class="row">
                    <div class="d-flex justify-content-between">
                        <h3>Identitas</h3>
                        <button class="btn btn-light-primary btn-sm" type="button" id="buttonEditPersonal" data-id="{{ $customer->id }}"><i class="fas fa-user-edit"></i>Ubah</button>
                    </div>
                </div>
                <table class="table table-user">
                    <tbody id="targetPersonalData">
                    </tbody>
                </table>
            </div>
        </div>
        <!--end::Card-->
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <!--begin::Card-->
        <div class="card mb-5 card-service-contract">
            <div class="card-body">
                <div class="row">
                    <div class="d-flex justify-content-between">
                        <h3>Data Layanan</h3>
                        <button class="btn btn-light-primary btn-sm" type="button" onclick="changeService({{ $customer->id }})"><i class="fas fa-user-edit"></i>Ubah Layanan</button>
                    </div>
                </div>
                <table class="table table-user">
                    <tbody id="targetServiceData">
                    </tbody>
                </table>
            </div>
        </div>
        <!--end::Card-->
    </div>
    <div class="col-md-6">
        <!--begin::Card-->
        <div class="card mb-5 card-service-contract">
            <div class="card-body">
                <div class="row mb-5">
                    <div class="d-flex justify-content-between">
                        <h3>Data Kontrak</h3>
                        <button type="button" class="btn btn-light-primary btn-sm" onclick="changeContract({{ $customer->id }})"><i class="fas fa-user-edit"></i>Ubah Kontrak</button>
                    </div>
                </div>
                <div class="row" id="targetContractData">
                    
                </div>
            </div>
        </div>
        <!--end::Card-->
    </div>
</div>

<!--begin::Card-->
<div class="card mb-5">
    <div class="card-body">
        <div class="row">
            <div class="d-flex justify-content-between">
                <h3>Data Pelayanan</h3>
                <a href="" class="btn btn-light-primary btn-sm"><i class="fas fa-user-plus"></i>Tambah</a>
            </div>
        </div>
    </div>
</div>
<!--end::Card-->

{{-- begin::modal-edit --}}
<div class="modal fade" id="modalEdit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5" id="modalEditBody">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="save()" id="btnSaveEdit">Simpan</button>
            </div>
        </div>
    </div>
</div>
{{-- end::modal-edit --}}
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/croppie.css') }}">
@endpush
@push('scripts')
    <script src="{{ asset('js/croppie.min.js') }}"></script>
    <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script>
        // begin::variable
        let body = $('#modalEditBody');
        let modal = $('#modalEdit');
        let form = $('#formEditCustomer');
        let customerId = "{{ $customer->id }}";
        let buttonSave = $('#btnSaveEdit');
        let varPond = "";
        // end::variable
        $(document).ready(function() {
            initAll(customerId);
        });

        // begin::edit-profile
        $('#buttonEditPersonal').on('click', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            let url = "{{ url('/customer/') }}" + "/" + id;
            $.ajax({
                type: "GET",
                url: "{{ url('/customer/edit/form') }}" + '/personal/' + "{{ $id }}",
                dataType: "json",
                success: function(res) {
                    buttonSave.attr('data-type', 'personal');
                    body.html(res.data.view);
                    modal.modal('show');
                    $('#modalTitle').text('Edit Personal Data');
                    $('#formEditCustomer').attr('action', url);
                    $('#formEditCustomer').attr('method', 'POST');
                    // init select2 inside the modal
                    $('#editCustomerProvince').select2({
                        dropdownParent: modal
                    });
                    $('#editCustomerRegency').select2({
                        dropdownParent: modal
                    });
                    $('#editCustomerDistrict').select2({
                        dropdownParent: modal
                    });
                },
                error: function(err) {
                    handleError(err);
                }
            })
        });

        function initAll(id) {
            $.ajax({
                data: "GET",
                url: "{{'/customer/init'}}" + "/" + id + "/all",
                dataType: "json",
                beforeSend: function() {
                    let loading = `<div class="text-center">
                        <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                        </div>
                        </div>`;

                    $('#targetServiceData').html(loading);
                    $('#targetPersonalData').html(loading);
                    $('#targetContractData').html(loading);
                },
                success: function(res) {
                    $('#targetServiceData').html(res.data.service);
                    $('#targetPersonalData').html(res.data.personal);
                    $('#targetContractData').html(res.data.contract);
                },
                error: function(err) {
                    console.error(err);
                }
            })
        }

        function initPerType(id, type) {
            let viewType, idView;
            if (type == 'service') {
                viewType = '_init-service';
                idView = 'targetServiceData';
            } else if (type == 'personal') {
                viewType = '_init-personal';
                idView = 'targetPersonalData';
            } else {
                viewType = '_init-contract';
                idView = 'targetContractData';
            }
            $.ajax({
                data: "GET",
                url: "{{'/customer/init'}}" + "/" + id + "/" + viewType,
                dataType: "json",
                beforeSend: function() {
                    let loading = `<div class="text-center">
                        <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                        </div>
                        </div>`;
                    
                    $('#' + idView).html(loading);
                },
                success: function(res) {
                    console.log('res', res);
                    $('#' + idView).html(res.data.view);
                },
                error: function(err) {
                    console.error('err', err);
                }
            })
        }

        function changeService(id) {
            $.ajax({
                type: "GET",
                url: "{{ url('/customer/change-service') }}" + "/" + id,
                dataType: "json",
                success: function(res) {
                    let url = "{{ url('/customer/change-service') }}" + "/" + id
                    buttonSave.attr('data-type', 'service');
                    body.html(res.data.view);
                    $('#modalTitle').text('Edit Data Layanan');
                    $('#formEditCustomer').attr('action', url);
                    $('#formEditCustomer').attr('method', 'POST');
                    modal.modal('show');
                }
            })
        }

        function changeContract(id) {
            $.ajax({
                type: "GET",
                url: "{{ url('/customer/change-contract') }}" + "/" + id,
                dataType: "json",
                success: function(res) {
                    let url = "{{ url('/customer/change-contract') }}" + "/" + id
                    buttonSave.attr('data-type', 'contract');
                    body.html(res.data.view);
                    $('#modalTitle').text('Edit Data Kontrak');
                    $('#formEditCustomer').attr('action', url);
                    $('#formEditCustomer').attr('method', 'POST');
                    modal.modal('show');

                    let pond = pondFile();
                    let aggrementImg = res.data.aggrementImg;
                    if (aggrementImg != "") {
                        pond.addFile(aggrementImg)
                    }

                }
            })
        }

        function pondFile() {
            FilePond.registerPlugin(FilePondPluginImagePreview);
            const pond = FilePond.create(
                document.getElementById('aggreementLetterPhoto')
            );

            return pond;
        }

        function changeOnProvince() {
            let province = $('#editCustomerProvince').val();
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
                    $('#editCustomerRegency').html(option);
                    $('#editCustomerRegency').select2({
                        dropdownParent: $('#modalEdit')
                    });
                    $('#editCustomerDistrict').val('');
                }
            })
        }

        function changeOnRegency() {
            let district = $('#editCustomerRegency').val();
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
                    $('#editCustomerDistrict').html(option);
                    $('#editCustomerDistrict').select2({
                        dropdownParent: $('#modalEdit')
                    });
                }
            })
        }

        function save() {
            let data = new FormData($('#formEditCustomer')[0]);
            let url = $('#formEditCustomer').attr('action');
            let method = $('#formEditCustomer').attr('method');
            let type = buttonSave.data('type');

            $.ajax({
                type: method,
                url: url,
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    buttonSave.attr('disabled', true);
                    buttonSave.text('Meyimpan data ...');
                },
                success: function(res) {
                    buttonSave.attr('disabled', false);
                    buttonSave.text('Simpan');
                    iziToast['success']({
                        message: 'Berhasil menyimpan data',
                        position: "topRight"
                    });
                    initPerType(customerId, type);
                    if (type == 'contract') {
                        initPerType(customerId, 'personal');
                    }
                    modal.modal('hide');
                },
                error: function(err) {
                    handleError(err, buttonSave);
                }
            })
        }
        // end::edit-profile
    </script>
@endpush

