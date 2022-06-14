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
@endpush
{{-- end::styles --}}

{{-- begin::content --}}
@section('content')

@php
    $userImage = true;
@endphp

<div class="row">
    <div class="col-md-4">
        <!--begin::Card-->
        <div class="card mb-5">
            <div class="card-body">
                <!--begin::Input group-->
                <div class="d-flex justify-content-center">
                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-lg-12">
                            <!--begin::Image input-->
                            <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url({{ asset('images/blank.png') }})">
                                <!--begin::Preview existing avatar-->
                                <div class="image-input-wrapper w-250px h-250px" style="background-image: url( {{ asset('images/blank.png') }})"></div>
                                <!--end::Preview existing avatar-->
                                <!--begin::Label-->
                                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Ganti Foto">
                                    <i class="bi bi-pencil-fill fs-7"></i>
                                    <!--begin::Inputs-->
                                    <input type="file" id="inputUserImage" name="avatar" accept="image/jpeg, image/x-png" />
                                    <input type="hidden" name="avatar_remove" />
                                    <!--end::Inputs-->
                                </label>
                                <!--end::Label-->
                                <!--begin::Cancel-->
                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Batal">
                                    <i class="bi bi-x fs-2"></i>
                                </span>
                                <!--end::Cancel-->
                                @if($userImage)
                                <!--begin::Remove-->
                                <a href="#" data-toggle="delete-profile-image">
                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Hapus Foto">
                                        <i class="bi bi-x fs-2"></i>
                                    </span>
                                </a>
                                <!--end::Remove-->
                                @endif
                            </div>
                            <!--end::Image input-->
                            <!--begin::Hint-->
                            <div class="form-text">Tipe file yang diperbolehkan: png, jpg, jpeg.</div>
                            <!--end::Hint-->
                        </div>
                        <!--end::Col-->
                    </div>
                </div>
                <!--end::Input group-->
            </div>
        </div>
        <!--end::Card-->
    </div>
    <div class="col-md-8">
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
                    <tbody>
                        <tr>
                            <td>Nama</td>
                            <td><b id="templateName">{{ $customer->name }}</b></td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td><b id="templateEmail">{{ $customer->email }}</b></td>
                        </tr>
                        <tr>
                            <td>HP</td>
                            <td><b id="templatePhone">{{ $customer->phone }}</b></td>
                        </tr>
                        <tr>
                            <td>PIC</td>
                            <td><b id="templatePic">{{ $customer->pic_name . ' - ' . $customer->pic_phone }}</b></td>
                        </tr>
                        <tr>
                            <td>NPWP</td>
                            <td><b id="templateNpwp">{{ $customer->npwp ?? '-' }}</b></td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td> <b id="templateAddress">{{ $completeAddress }}</b> </td>
                        </tr>
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
                        <a href="#" class="btn btn-light-primary btn-sm"><i class="fas fa-user-edit"></i>Ubah Layanan</a>
                    </div>
                </div>
                <table class="table table-user">
                    <tbody>
                        @for ($i = 0; $i < count($customer->services); $i++)
                            <tr>
                                <td>Layanan / Service</td>
                                <td><b>{{  $customer->services[$i]->billing_type_id == 1 ? $customer->services[$i]->service->name . ' - CASH' : $customer->services[$i]->service->name . ' - TEMPO' }}</b></td>
                            </tr>
                        @endfor
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
                        <a href="#" class="btn btn-light-primary btn-sm"><i class="fas fa-user-edit"></i>Ubah</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="aggreement-img">
                            @if ($customer->contract->agreement_letter_img != NULL)
                                <img src="{{ $customer->contract->agreement_letter_img }}" class="letter-img" alt="">
                                @else
                                <img src="{{ asset('/images/document.png') }}" class="letter-img" alt="">
                            @endif
                        </div>
                    </div>
                    <div class="col-md-8">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>Pembaruhan Otomatis</td>
                                    <td> <b>{{ $customer->contract->id_auto_renewal == 1 ? 'Ya' : 'Tidak' }}</b> </td>
                                </tr>
                                <tr>
                                    <td>Tanggal Mulai Kontrak</td>
                                    <td> <b>{{ date('d F Y', strtotime($customer->contract->start_date)) }}</b> </td>
                                </tr>
                                <tr>
                                    <td>Lama Kontrak</td>
                                    <td> <b>{{ $customer->contract->contract_period_in_day . ' Hari' }}</b> </td>
                                </tr>
                                <tr>
                                    <td>Tanggal Berakhir Kontrak</td>
                                    <td> <b>{{ date('d F Y', strtotime($customer->contract->end_date)) }}</b> </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
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

{{-- begin::modal-photo --}}
<div class="modal fade" id="userImageModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body pb-5">
                <div class="image-cropper">
                    <div id="userImageCropper" style="width: 320px; height: 320px;"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" data-toggle="upload-image" data-username="userusername">Terapkan</button>
            </div>
        </div>
    </div>
</div>
{{-- end::modal-photo --}}
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/croppie.css') }}">
@endpush
@push('scripts')
    <script src="{{ asset('js/croppie.min.js') }}"></script>
    <script>
        var userImage = null;

        function readUserImageFile(input) {
        if (input.files && input.files[0]) {
            $('#userImageModal').modal('show');
            var reader = new FileReader();

            reader.onload = function (e) {
            setTimeout(function () {
                userImage = new Croppie(document.getElementById('userImageCropper'), {
                viewport: {
                    width: 240,
                    height: 240,
                    type: 'square'
                },
                boundary: {
                    width: 320,
                    height: 320
                },
                url: e.target.result,
                enableExif: true
                });
            }, 500);
            };

            reader.readAsDataURL(input.files[0]);
        }
        }

        $('#inputUserImage').on('change', function () {
            readUserImageFile(this);
        });
        $('#userImageModal').on('hide.bs.modal', function (e) {
            userImage.destroy();
            $('#inputUserImage').val('');
        });
        $('#userImageModal [data-toggle="crop-image"]').on('click', function (e) {
            userImage.result({
                type: 'base64',
                format: 'jpeg',
                size: {
                width: 320,
                height: 320
                }
            }).then(function (resp) {
                $('#userImagePreview img').attr({
                    src: resp,
                    'data-upload': true,
                    'data-filename': $('#inputUserImage')[0].files[0].name
                });
                $('[data-toggle="reset-user-image"]').removeClass('d-none');
                $('#userImageModal').modal('hide');
            });
        });

        function resetUserImage() {
            var $imgTag = $('#userImagePreview img');
            $imgTag.attr({
                src: $imgTag.data('original'),
                'data-upload': false,
                'data-filename': '',
                'data-delete': false
            });
            $('[data-toggle="reset-user-image"]').addClass('d-none');
            $('[data-toggle="delete-user-image"]').removeClass('d-none');
        }

        function deleteUserImage() {
            var $imgTag = $('#userImagePreview img');
            $imgTag.attr({
                src: $imgTag.data('placeholder'),
                'data-upload': false,
                'data-filename': '',
                'data-delete': true
            });
            $('[data-toggle="reset-user-image"]').removeClass('d-none');
            $('[data-toggle="delete-user-image"]').addClass('d-none');
        }

        $('[data-toggle="reset-user-image"]').click(resetUserImage);
        $('[data-toggle="delete-user-image"]').click(deleteUserImage);

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#userImageModal [data-toggle="upload-image"]').on('click', function (e) {
            var $this = $(this);
            userImage.result({
                type: 'blob',
                format: 'jpeg',
                size: {
                width: 320,
                height: 320
                }
            }).then(function (blob) {
                var formData = new FormData();
                formData.append('user_image', blob, $('#inputUserImage')[0].files[0].name);
                $.ajax({
                url:  "",
                data: formData,
                type: 'POST',
                contentType: false,
                processData: false,
                dataType: 'json',
                error: function error(response) {
                    if (response.responseJSON.message) {
                        iziToast['error']({
                            message: response.responseJSON.message,
                            position: "topRight"
                        });
                    }
                },
                success: function success(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Data berhasil disimpan'
                    }).then(function (result) {
                        window.location.reload();
                    });
                },
                });
                $('#userImageModal').modal('hide');
            });
        });

        $('[data-toggle="delete-profile-image"]').click(function (e) {
            e.preventDefault();
            var $this = $(this);
            Swal.fire({
                title: "Hapus gambar ini?",
                text: "Gambar akan dihapus selamanya!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batalkan',
                customClass: {
                confirmButton: 'btn btn-danger mr-2',
                cancelButton: 'btn btn-secondary ml-2'
                },
                buttonsStyling: false
            }).then(function (result) {
                if (result.isConfirmed) {
                $.ajax({
                    url: $this.attr('href'),
                    method: 'POST',
                    dataType: 'json',
                    error: function error(response) {
                        if (response.responseJSON.message) {
                            iziToast['error']({
                                message: response.responseJSON.message,
                                position: "topRight"
                            });
                        }
                    },
                    success: function success(data, status, xhr) {
                    window.location.reload();
                    },
                });
                }
                if(result.isDismissed){
                    window.location.reload();
                }
            });
        });

        // begin::edit-profile
        $('#buttonEditPersonal').on('click', function(e) {
            e.preventDefault();
            let body = $('#modalEditBody');
            let modal = $('#modalEdit');
            let id = $(this).data('id');
            let url = "{{ url('/customer/') }}" + "/" + id;
            $.ajax({
                type: "GET",
                url: "{{ url('/customer/edit/form') }}" + '/personal/' + "{{ $id }}",
                dataType: "json",
                success: function(res) {
                    console.log(res);
                    body.html(res.data.view);
                    $('#modalTitle').text('Edit Personal Data');
                    $('#formEditPersonalData').attr('action', url);
                    $('#formEditPersonalData').attr('method', 'POST');
                    modal.modal('show');
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
                    console.log(err);
                }
            })
        });

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
            let data = new FormData($('#formEditPersonalData')[0]);
            let form = $('#formEditPersonalData');
            let buttonSave = $('#btnSaveEdit');
            let url = form.attr('action');
            let method = form.attr('method');
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
                    console.log(res);
                    buttonSave.attr('disabled', false);
                    buttonSave.text('Simpan');
                    iziToast['success']({
                        message: 'Berhasil menyimpan data',
                        position: "topRight"
                    });
                    $('#templateAddress').html(res.data.address);
                    $('#templateNpwp').html(res.data.customer.npwp);
                    $('#templateName').html(res.data.customer.name);
                    $('#templatePic').html(`${res.data.customer.pic_name} - ${res.data.customer.pic_phone}`);
                    $('#templatePhone').html(res.data.customer.phone);
                    $('#templateEmail').html(res.data.customer.email);
                    $('#modalEdit').modal('hide');
                },
                error: function(err) {
                    console.log(err);
                    buttonSave.attr('disabled', false);
                    buttonSave.text('Simpan');
                    handleError(err);
                }
            })
        }
        // end::edit-profile
    </script>
@endpush

