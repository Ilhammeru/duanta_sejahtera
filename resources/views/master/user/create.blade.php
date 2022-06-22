@extends('layouts.master')

@push('styles')
    <link rel="stylesheet" href="{{ asset('plugins/custom/filepond/filepond.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/custom/filepond/plugins-preview.css') }}">
@endpush

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
                    <input type="file" class="form-control" id="userImage" name="avatar">
                </div>
                <div class="col-md-8 col-xl-8 col-sm-12">
                    <div class="form-group row mb-5">
                        <div class="col-md-12 col-xl-12 mb-3">
                            <label for="userName" class="col-form-label required">Nama</label>
                            <input type="text" class="form-control" id="userName" placeholder="Richard Joe" name="name" value="{{ isset($user) ? $user->name : "" }}">
                        </div>
                        <div class="col-md-12 col-xl-12 mb-3">
                            <label for="userEmail" class="col-form-label required">Email</label>
                            <input type="email" class="form-control" id="userEmail" placeholder="Richard@gmail.com" name="email" value="{{ isset($user) ? $user->email : "" }}">
                        </div>
                        <div class="col-md-12 col-xl-12 mb-3">
                            <label for="userNik" class="col-form-label required">NIK</label>
                            <input type="number" class="form-control" id="userNik" placeholder="3573042323843999" name="nik" value="{{ isset($user) ? $user->identity_number : "" }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row mb-5">
                <div class="col-md-4 col-xl-4">
                    <label for="userBirth" class="col-form-label required">Tanggal Lahir</label>
                    <input type="date" class="form-control" id="userBirth" name="birth_of_date" value="{{ isset($user) ? $user->birth_date : "" }}">
                </div>
                <div class="col-md-4 col-xl-4">
                    <label for="userPhone" class="col-form-label required">No Telfon</label>
                    <input type="number" class="form-control" id="userPhone" placeholder="085795327357" name="phone" value="{{ isset($user) ? $user->phone : "" }}">
                </div>
                <div class="col-md-4 col-xl-4">
                    <label for="userPassword" class="col-form-label required">Password</label>
                    <input type="password" class="form-control" id="userPassword" placeholder="Kosongkan Bila Tidak Diganti" name="password">
                </div>
            </div>

            <div class="form-group text-center" style="margin-top: 32px; margin-bottom: 20px;">
                <h3 class="border-bottom">Data Pekerjaan</h3>
            </div>

            <div class="form-group mb-5 row">
                <div class="col-md-4 col-xl-4">
                    <label for="userDivision" class="col-form-label required">Divisi</label>
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
    <script src="{{ asset('plugins/custom/filepond/filepond.js') }}"></script>
    <script src="{{ asset('plugins/custom/filepond/plugins-preview.js') }}"></script>
    <script>
        let issetUser = "{{ isset($user) }}";
        let userAvatar = "{{ isset($user) ? $user->photo : '' }}";
        let userAvatarOnImg = "{{ isset($user) ? asset($user->photo) : '' }}";
        let elemAvatar = document.querySelector('#userImage');

        FilePond.registerPlugin(FilePondPluginImagePreview);
        const userPond = FilePond.create(elemAvatar);
        if (userAvatar == "") {
            userPond.addFile("{{ asset('images/blank.png') }}");
        } else {
            userPond.addFile(userAvatarOnImg);
        }

        if (userAvatar != "") {
            document.querySelector('#userImage').addEventListener('FilePond:removefile', (e) => {
                Swal.fire({
                    title: 'Apakah anda yakin ingin menghapus foto ini?',
                    showDenyButton: true,
                    showCancelButton: false,
                    confirmButtonText: 'Ya!',
                    denyButtonText: `Batalkan`,
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "DELETE",
                            url: "{{ url('/users/photo') }}" + '/' + "{{ isset($user) ? $user->id : '' }}",
                            dataType: 'json',
                            error: function(err) {
                                console.log(err);
                                handleError(err);
                            },
                            success: function(res) {
                                console.log(res);
                                iziToast['success']({
                                    message: 'Foto berhasil di hapus',
                                    position: "topRight"
                                });
                                window.location.href = "{{ url('/users/') }}" + "/" + "{{ isset($user) ? $user->id : '' }}"
                            }
                        })
                    } else {
                        userPond.addFile(userAvatarOnImg);
                    }
                })
            });
        }


        function save() {
            let data = new FormData($('#formUser')[0]);
            let avatarFile = userPond.getFile();
            data.append('avatar', avatarFile.file);
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
                    console.log(res);
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
