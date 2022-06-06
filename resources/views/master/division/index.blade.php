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
        <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0 table_prospect_unique" id="dt_table">
            <!--begin::Table head-->
            <thead>
                <!--begin::Table row-->
                <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                    <th>Nama</th>
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
<div class="modal fade" tabindex="-1" id="modalDivision">
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

            <form action="" id="formDivision">
                <div class="modal-body">
                    <div class="form-group mb-5 row">
                        <label for="divisionName" class="col-form-label">Nama</label>
                        <input type="text" class="form-control" name="name" id="divisionName">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary" type="button" id="actionDivision">Simpan</button>
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
        ajax: "{{ route('division.json') }}",
        columns: _columns,
    });

    function addDivision() {
        $('#modalDivision').modal('show');
        $('.modal-title').text('Tambah Divisi');
        $('#actionDivision').attr('onclick', 'save()');
    }

    function editDivision(id) {
        let url = "{{ url('/division/update') }}" + "/" + id;
        $.ajax({
            type: "PUT",
            url: url,
            dataType: "json",
            success: function(res) {
                console.log(res);
            },
            error: function(err) {
                console.log(err);
            }
        })
    }

    function save() {
        let data = $('#formDivision').serialize();
        let elem = $('#actionDivision');

        $.ajax({
            type: "POST",
            url: "{{ route('division.store') }}",
            data: data,
            beforeSend: function() {
                elem.attr('disabled', true);
                elem.text("Menyimpan data ...");
            },
            success: function(res) {
                elem.attr('disabled', false);
                elem.text('Simpan');
                $('#modalDivision').modal('hide');
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
                iziToast['error']({
                    message: error,
                    position: "topRight"
                });
            }
        })
    }
</script>
@endpush
