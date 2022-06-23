@extends('layouts.master')
{{-- begin::styles --}}
@push('styles')
    <style>
        .main-list-container {
            height: 600px;
            width: 100%;
            overflow-y: scroll;
            overflow-x: hidden;
        }

        @media screen and (max-width: 500px) {
            .main-list-container {
                height: 900px;
                width: 100%;
                overflow-y: scroll;
                overflow-x: hidden;
            }
        }
    </style>
@endpush
{{-- end::styles --}}
{{-- begin::content --}}
@section('content')
    {{-- begin::card-action --}}
    <div class="card card-flush mb-5">
        <div class="card-body p-4">
            <div class="text-start">
                <a href="{{ route('booking-in.index') }}" class="btn btn-light-primary" type="button">
                    <i class="fas fa-chevron-left me-3"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>
    {{-- end::card-action --}}
    {{-- begin::card-detail --}}
    <div class="card card-flush mb-5">
        <div class="card-body">
            <h3 class="mb-5">Data Booking</h3>
            <div class="row">
                <div class="col-md-6">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>Kode Booking</td>
                                <td>:</td>
                                <td>
                                    <b>{{ $bookingIn->booking_code }}</b>
                                </td>
                            </tr>
                            <tr>
                                <td>DO Reference</td>
                                <td>:</td>
                                <td>
                                    <b>{{ $bookingIn->do_reference ?? '-' }}</b>
                                </td>
                            </tr>
                            <tr>
                                <td>Waktu Checkin</td>
                                <td>:</td>
                                <td>
                                    <b>{{ date($bookingIn->booking_time) ?? '-' }}</b>
                                </td>
                            </tr>
                            <tr>
                                <td>Pelanggan</td>
                                <td>:</td>
                                <td>
                                    <b>{{ strtoupper($bookingIn->customer->name) }}</b>
                                </td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>:</td>
                                <td>
                                    <b>{{ $bookingIn->customer->email }}</b>
                                </td>
                            </tr>
                            <tr>
                                <td>Telefon</td>
                                <td>:</td>
                                <td>
                                    <b>{{ $bookingIn->customer->phone }}</b>
                                </td>
                            </tr>
                            <tr>
                                <td>PIC</td>
                                <td>:</td>
                                <td>
                                    <b>{{ $bookingIn->customer->pic_name . ' (' . $bookingIn->customer->pic_phone . ')' }}</b>
                                </td>
                            </tr>
                            <tr>
                                <td>Tipe Customer</td>
                                <td>:</td>
                                <td>
                                    <b>{{ strtoupper($bookingIn->customer->type) }}</b>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{-- end::card-detail --}}
    {{-- begin::card-container --}}
    <div class="card card-flush">
        <div class="card-body">
            <h3 class="mb-5">List Container</h3>
            <div class="main-list-container">
                <div class="row">
                    @php
                        $containers = $bookingIn->containers;
                    @endphp
                    @for($x = 0; $x < count($containers); $x++)
                        <div class="col-md-4">
                            <div class="card card-flush mb-5 bg-secondary">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>Nomor</td>
                                                <td>:</td>
                                                <td>
                                                    <b>{{ $containers[$x]->container_number }}</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Seal</td>
                                                <td>:</td>
                                                <td>
                                                    <b>{{ $containers[$x]->container_seal }}</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Cargo/Goods</td>
                                                <td>:</td>
                                                <td>
                                                    <b>{{ $containers[$x]->cargo_goods }}</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Size/Type</td>
                                                <td>:</td>
                                                <td>
                                                    @if ($containers[$x]->is_customer_container_size)
                                                        <b>{{ $containers[$x]->custom_container_size }}</b>
                                                    @else
                                                        <b>{{ $containers[$x]->sizeType->size . '/' . $containers[$x]->sizeType->type }}</b>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Volume</td>
                                                <td>:</td>
                                                <td>
                                                    <b>{{ $containers[$x]->volume }}</b>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
    {{-- end::card-container --}}
@endsection
{{-- end::content --}}