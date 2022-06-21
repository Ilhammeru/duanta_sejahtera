@extends('layouts.master')

@section('content')
{{-- begin::card --}}
<div class="card card-flush mb-3">
    <div class="card-body p-4">
        <div class="text-end">
            <button class="btn btn-light-primary" onclick="addDivision()">
                <i class="fa fa-plus"></i>
                Tambah Divisi
            </button>
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
                    <th>Nama Ukuran / Tipe</th>
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

{{-- begin::Modal --}}
<div class="modal fade" tabindex="-1" id="modalContainerSizeType">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>

                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <span class="svg-icon svg-icon-2x"></span>
                </div>
                <!--end::Close-->
            </div>

            <form action="" id="formContainerSizeType">
                <div class="modal-body">
                    <div class="form-group mb-5 row">
                        <label for="containerSize" class="col-form-label">Ukuran</label>
                        <input type="text" class="form-control" placeholder="Ukuran Kontainer" name="size" id="containerSize">
                    </div>
                    <div class="form-group mb-5 row">
                        <label for="containerType" class="col-form-label">Tipe</label>
                        <input type="text" class="form-control" placeholder="Tipe Kontainer" name="type" id="containerType">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary" type="button" id="actionContainer">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- end::Modal --}}
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
        data: "action"
    }];

    let tables = $("#dt_table").DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        scrollX: true,
        ajax: "{{ route('container-size-type.json') }}",
        columns: _columns,
    });

    // begin::variable
    let form = $('#formContainerSizeType');
    let button = $('#actionContainer');
    let modal = $('#modalContainerSizeType');
    // end::variable

    function addDivision() {
        modal.modal('show');
        $('.modal-title').text('Tambah Kontainer Ukuran / Tipe');
        button.attr('onclick', 'save()');
        form.attr('action', "{{ route('container-size-type.store') }}");
        form.attr('method', 'POST');
        $('#containerSize').val('');
        $('#containerType').val('');
    }

    function editContainer(id) {
        let route = "{{ url('/container-size-type') }}" + "/" + id;
        $.ajax({
            type: "GET",
            url: route,
            dataType: 'json',
            error: function(err) {
                handleError(err);
            },
            success: function(res){
                $('#containerSize').val(res.data.size);
                $('#containerType').val(res.data.type);
                $('#modalContainerSizeType').modal('show');
                $('.modal-title').text('Edit Kontainer Ukuran / Tipe');
                form.attr('action', route);
                form.attr('method', 'PUT');
            }
        })
    }

    function save() {
        let data = form.serialize();
        let url = form.attr('action');
        let method = form.attr('method');
        console.log(method);

        $.ajax({
            type: method,
            url: url,
            data: data,
            beforeSend: function() {
                button.attr('disabled', true);
                button.text("Menyimpan data ...");
            },
            success: function(res) {
                button.attr('disabled', false);
                button.text('Simpan');
                $('#modalContainerSizeType').modal('hide');
                iziToast['success']({
                    message: 'Berhasil menyimpan data',
                    position: "topRight"
                });
                tables.ajax.reload();
            },
            error: function(err) {
                handleError(err, button);
            }
        })
    }

    function deleteContainer(id) {
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
                    url: "{{ url('/container-size-type') }}" + '/' + id,
                    dataType: 'json',
                    error: function(err) {
                        handleError(err);
                    },
                    success: function(res) {
                        iziToast['success']({
                            message: 'User berhasil di hapus',
                            position: "topRight"
                        });
                        tables.ajax.reload();
                    }
                })
            }
        })
    }
</script>
@endpush
