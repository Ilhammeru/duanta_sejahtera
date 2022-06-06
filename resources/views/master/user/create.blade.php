@extends('layouts.master')

@php
    $urlPost = isset($user) ? route('user.update', $user->id) : route('user.store');
@endphp

@section('content')
{{-- begin::card --}}
<div class="card card-flush mb-3">
    <div class="card-body p-4">
        <div class="text-start">
            <a class="btn btn-light-primary" href="{{ route('user.index') }}">
                <i class="fa fa-angle-double-left"></i>
                Kembali
            </a>
        </div>
    </div>
</div>
{{-- end::card --}}

<!--begin::Card-->
<div class="card card-flush">
    <!--begin::Card body-->
    <div class="card-body pt-4">

        <div class="form-group text-center mt-5 mb-5 row">
            <div class="col-md-2 col-xl-2"></div>
            <div class="col-md-8 col-xl-8">
                <h3 class="border-bottom">Data Personal</h3>
            </div>
            <div class="col-md-2 col-xl-2"></div>
        </div>

        {{-- begin::form --}}
        <form action="" id="formUser" class="mt-5" enctype="multipart/form-data">
            <div class="form-group row mb-5">
                <div class="col-md-4 col-xl-4 col-sm-12">
                    <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url({{ asset('images/blank.png') }});">
                        <!--begin::Preview existing avatar-->
                        <div class="image-input-wrapper w-250px h-250px" style="background-image: url( {{ !isset($user) ? asset('images/blank.png') : ($user->photo == NULL ? asset('images/blank.png') : asset("storage/$user->photo")) }}); background-size: 100% auto;"></div>
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
                        @if(isset($user) && $user->photo != NULL)
                        <!--begin::Remove-->
                        <a href="" data-toggle="delete-profile-image">
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Hapus Foto">
                                <i class="bi bi-x fs-2"></i>
                            </span>
                        </a>
                        <!--end::Remove-->
                        @endif
                    </div>
                </div>
                <div class="col-md-8 col-xl-8 col-sm-12">
                    <div class="form-group row mb-5">
                        <div class="col-md-12 col-xl-12 mb-3">
                            <label for="userName" class="col-form-label required">Nama</label>
                            <input type="text" class="form-control" id="userName" name="name" value="{{ isset($user) ? $user->name : "" }}">
                        </div>
                        <div class="col-md-12 col-xl-12 mb-3">
                            <label for="userEmail" class="col-form-label required">Email</label>
                            <input type="email" class="form-control" id="userEmail" name="email" value="{{ isset($user) ? $user->email : "" }}">
                        </div>
                        <div class="col-md-12 col-xl-12 mb-3">
                            <label for="userNik" class="col-form-label required">NIK</label>
                            <input type="number" class="form-control" id="userNik" name="nik" value="{{ isset($user) ? $user->identity_number : "" }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row mb-5">
                <div class="col-md-6 col-xl-6">
                    <label for="userBirth" class="col-form-label required">Tanggal Lahir</label>
                    <input type="date" class="form-control" id="userBirth" name="birth_of_date" value="{{ isset($user) ? $user->birth_date : "" }}">
                </div>
                <div class="col-md-6 col-xl-6">
                    <label for="userPhone" class="col-form-label required">No Telfon</label>
                    <input type="number" class="form-control" id="userPhone" name="phone" value="{{ isset($user) ? $user->phone : "" }}">
                </div>
            </div>

            <div class="form-group text-center" style="margin-top: 32px; margin-bottom: 20px;">
                <h3 class="border-bottom">Data Pekerjaan</h3>
            </div>

            <div class="form-group mb-5 row">
                <div class="col-md-4 col-xl-4">
                    <label for="userDivision" class="col-form-label">Divisi</label>
                    <select name="division" id="userDivision" class="form-control form-select" data-placeholder="- Pilih Divisi -">
                        <option value="">- Pilih Divisi -</option>
                        @foreach ($division as $item)
                            <option value="{{ $item->id }}" {{ !isset($user) ? '' : ($user->division_id == $item->id ? 'selected' : '') }}>{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 col-xl-4">
                    <label for="userDateIn" class="col-form-label required">Tanggal Masuk</label>
                    <input type="date" class="form-control" name="date_in" id="userDateIn" value="{{ isset($user) ? date('Y-m-d', strtotime($user->date_in)) : "" }}">
                </div>
                <div class="col-md-4 col-xl-4">
                    <label for="userRole" class="col-form-label required">Role User</label>
                    <select name="role" id="userRole" class="form-control form-select" data-placeholder="- Pilih Role -">
                        <option value="">- Pilih Role -</option>
                        @foreach ($role as $item)
                            <option value="{{$item->id}}" {{ !isset($user) ? '' : ($user->userRole->role->id == $item->id ? 'selected' : '') }}>{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group text-end mt-5 mb-5">
                <button class="btn btn-primary btn-save" type="button" onclick="save()">Simpan</button>
            </div>
        </form>
        {{-- end::form --}}

    </div>
    <!--end::Card body-->
</div>
<!--end::Card-->
@endsection

@push('scripts')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
@endpush

@push('scripts')
    <script>
        var userImage = null;

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

        function save() {
            let data = new FormData(document.getElementById('formUser'));
            let elem = $('.btn-save');
            $.ajax({
                type: "POST",
                url: "{{ $urlPost }}",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    elem.attr('disabled', true);
                    elem.text('Menyimpan data ...');
                },
                success: function(res) {
                    elem.attr('disabled', false);
                    elem.text('Simpan');
                    iziToast['success']({
                        message: 'Simpan karyawan berhasil',
                        position: "topRight"
                    });
                    window.location.href = "{{ route('user.index') }}";
                },
                error: function(err) {
                    console.log(err);
                    elem.attr('disabled', false);
                    elem.text('Simpan');
                    let message = err.responseJSON.message;
                    if (message == 'VALIDATION_FAILED') {
                        let error = err.responseJSON.data.error;
                        for (let a = 0; a < error.length; a++) {
                            iziToast['error']({
                                message: error[a],
                                position: "topRight"
                            });
                        }
                    } else {
                        iziToast['error']({
                            message: err.responseJSON.message,
                            position: "topRight"
                        });
                    }
                }
            })
        }

        $(document).ready(function() {
            $('#userDivision').select2({
                placeholder: '- Plih Divisi -'
            });
            $('#userRole').select2();
        })
    </script>
@endpush
