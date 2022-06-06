@extends('layouts.master')

@section('content')
{{-- begin::card --}}
<div class="card card-flush mb-3">
    <div class="card-body p-4">
        <div class="text-end">
            <a class="btn btn-light-primary" href="{{ route('user.create') }}">
                <i class="fa fa-plus"></i>
                Tambah User
            </a>
        </div>
    </div>
</div>
{{-- end::card --}}

<!--begin::Card-->
<div class="card card-flush">
    <!--begin::Card body-->
    <div class="card-body pt-4">

        <!--begin::Table-->
        <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0" id="dt_table">
            <!--begin::Table head-->
            <thead>
                <!--begin::Table row-->
                <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Divisi</th>
                    <th>Lama Bekerja</th>
                    <th></th>
                </tr>
                <!--end::Table row-->
            </thead>
            <!--end::Table head-->
            <!--begin::Table body-->
            <tbody class="fw-bold text-gray-600">

            </tbody>
            <!--end::Table body-->
        </table>
        <!--end::Table-->
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

    var _columns = [{
        data: "name"
    }, {
        data: "email"
    }, {
        data: "division"
    }, {
        data: "work_time"
    }, {
        data: "action"
    }];

    let tables = $("#dt_table").DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        scrollX: true,
        ajax: "{{ route('user.json') }}",
        columns: _columns,
    });

    function addDivision() {
        $('#modalRole').modal('show');
        $('.modal-title').text('Tambah Divisi');
        $('#actionRole').attr('onclick', 'save()');
        $('#roleName').val('');
    }

    function edit(id) {
        $.ajax({
            type: "GET",
            url: "{{ url('/roles/') }}" + "/" + id,
            dataType: 'json',
            error: function(err) {

            },
            success: function(res){
                console.log(res);
                $('#roleName').val(res.data.name);
                $('#modalRole').modal('show');
                $('.modal-title').text('Edit Divisi');
                $('#actionRole').attr('onclick', 'editDivision('+ id +')');
            }
        })
    }

    function editDivision(id) {
        let data = $('#formRole').serialize();
        let elem = $('#actionRole');
        let url = "{{ url('/roles') }}" + "/" + id;
        $.ajax({
            type: "PUT",
            url: url,
            data: data,
            dataType: "json",
            beforeSend: function() {
                elem.text('Menyimpan data ...');
                elem.attr('disabled', true);
            },
            success: function(res) {
                elem.attr('disabled', false);
                elem.text('Simpan');
                $('#modalRole').modal('hide');
                iziToast['success']({
                    message: 'Berhasil menyimpan data',
                    position: "topRight"
                });
                tables.ajax.reload();
            },
            error: function(err) {
                console.log(err);
                let error = err.responseJSON.data.error;
                iziToast['error']({
                    message: error,
                    position: "topRight"
                });
            }
        })
    }

    function save() {
        let data = $('#formRole').serialize();
        let elem = $('#actionRole');

        $.ajax({
            type: "POST",
            url: "{{ route('roles.store') }}",
            data: data,
            beforeSend: function() {
                elem.attr('disabled', true);
                elem.text("Menyimpan data ...");
            },
            success: function(res) {
                elem.attr('disabled', false);
                elem.text('Simpan');
                $('#modalRole').modal('hide');
                iziToast['success']({
                    message: 'Berhasil menyimpan data',
                    position: "topRight"
                });
                tables.ajax.reload();
            },
            error: function(err) {
                elem.attr('disabled', false);
                elem.text('Simpan');
                let error = err.responseJSON.data.error;
                if (error) {
                    iziToast['error']({
                        message: error,
                        position: "topRight"
                    });
                } else {
                    iziToast['error']({
                        message: err,
                        position: "topRight"
                    });
                }
            }
        })
    }

    function deleteUser(id) {
        Swal.fire({
            title: 'Apakah anda yakin ingin menghapus data ini?',
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: 'Ya!',
            denyButtonText: `Batalkan`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                $.ajax({
                    type: "DELETE",
                    url: "{{ url('/user/') }}" + '/' + id,
                    dataType: 'json',
                    error: function(err) {
                        console.log(err);
                        if (err.responseJSON.message == 'FAILED') {
                            iziToast['error']({
                                message: err.responseJSON.data.error,
                                position: "topRight"
                            });
                        } else {
                            iziToast['error']({
                                message: err.responseJSON.message,
                                position: "topRight"
                            });
                        }
                    },
                    success: function(res) {
                        iziToast['success']({
                            message: 'User berhasil di hapus',
                            position: "topRight"
                        });
                        tables.ajax.reload();
                    }
                })
            } else if (result.isDenied) {
                iziToast['success']({
                    message: 'Hapus user di batalkan',
                    position: "topRight"
                });
            }
        })
    }
</script>
@endpush
