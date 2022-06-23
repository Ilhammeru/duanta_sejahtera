@extends('layouts.master')

@section('content')
{{-- begin::card --}}
<div class="card card-flush mb-3">
    <div class="card-body p-4">
        <div class="text-end">
            <a class="btn btn-light-primary" href="{{ route('booking-in.create') }}">
                <i class="fa fa-plus"></i>
                Tambah Data Booking
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
                    <th>Nama Customer</th>
                    <th>Waktu Booking</th>
                    <th>Marketing</th>
                    <th>Jumlah Container</th>
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

{{-- begin::modal --}}
<div class="modal fade" id="modalDetailContainer" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="targetDetailContainer"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPrintContainer" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitlePrint"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="targetPrintContainer"></div>
        </div>
    </div>
</div>
{{-- end::modal --}}
@endsection

@push('scripts')
<script>
    var _columns = [{
        data: "customer_id"
    }, {
        data: "booking_time"
    }, {
        data: "booked_by"
    }, {
        data: "containers"
    }, {
        data: "action"
    }];

    let tables = $("#dt_table").DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        scrollX: true,
        ajax: "{{ route('booking-in.json') }}",
        columns: _columns,
    });

    function deleteBooking(id) {
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
                    url: "{{ url('/booking-in/') }}" + '/' + id,
                    dataType: 'json',
                    error: function(err) {
                        handleError(err);
                    },
                    success: function(res) {
                        iziToast['success']({
                            message: 'Data Booking berhasil di hapus',
                            position: "topRight"
                        });
                        tables.ajax.reload();
                    }
                })
            }
        })
    }

    function detailContainer(id) {
        $.ajax({
            type: "GET",
            url: "{{ url('/booking-in/detail-container') }}" + "/" + id,
            success: function(res) {
                let modal = $('#modalDetailContainer');
                $('#modalTitle').text('Detail Container');
                let view = res.data.view;
                $('#targetDetailContainer').html(view);
                modal.modal('show');
            },
            error: function(err) {
                handleError(err);
            }
        })
    }

    function printBooking(id) {
        $.ajax({
            type: "GET",
            url: "{{ url('/booking-in/print-container') }}" + "/" + id,
            success: function(res) {
                let modal = $('#modalPrintContainer');
                $('#modalTitlePrint').text('Print Tiket Booking In Container');
                let view = res.data.view;
                $('#targetPrintContainer').html(view);
                modal.modal('show');
            }
        })
    }
</script>
@endpush
