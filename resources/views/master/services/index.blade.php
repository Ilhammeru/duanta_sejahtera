@extends('layouts.master')

@section('content')
{{-- begin::card --}}
<div class="card card-flush mb-3">
    <div class="card-body p-4">
        <div class="text-end">
            <a class="btn btn-light-primary" href="{{ route('services.create') }}">
                <i class="fa fa-plus"></i>
                Tambah Layanan
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
                    <th>Harga</th>
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
        data: "price"
    }, {
        data: "action"
    }];

    let tables = $("#dt_table").DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        scrollX: true,
        ajax: "{{ route('services.json') }}",
        columns: _columns,
    });
</script>
@endpush
