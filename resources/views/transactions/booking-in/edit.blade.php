@extends('layouts.master')
{{-- begin::content --}}
@section('content')
    {{-- begin::card-action --}}
    <div class="card card-flush mb-5">
        <div class="card-body p-4">
            <div class="text-start">
                <a href="{{ route('booking-in.index') }}" class="btn btn-light-primary">
                    <i class="fas fa-chevron-left"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>
    {{-- end::card-action --}}

    {{-- begin::card-form --}}
    <div class="card card-flush">
        <div class="card-body">
            <form action="{{ route('booking-in.update', $bookingIn->id) }}" method="PUT" id="formBookingIn">
                @if ($role == 'admin')
                    <div class="form-group row mb-5">
                        <div class="col-md-4">
                            <label for="bookedBy" class="col-form-label p-0">Marketing</label>
                            <p class="mb-0 helper-label">Pilih Marketing yang Bertanggungjawab</p>
                        </div>
                        <div class="col-md-8">
                            <select name="booked_by" id="bookedBy" class="form-select form-control">
                                <option value="">- Pilih Marketing -</option>
                                @foreach ($marketing as $item)
                                    <option {{ $item->user->id == $bookingIn->booked_by ? 'selected' : '' }} value="{{ $item->user->id }}">{{ $item->user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif
                <div class="form-group row mb-5">
                    <div class="col-md-4">
                        <label for="bookingTime" class="col-form-label p-0">Waktu Booking</label>
                        <p class="mb-0 helper-label">Tanggal kedatangan <i>Container</i></p>
                    </div>
                    <div class="col-md-8">
                        <input type="date" value="{{ date('Y-m-d', strtotime($bookingIn->booking_time)) }}" name="booking_time" class="form-control" id="bookingTime">
                    </div>
                </div>
                <div class="form-group row mb-5">
                    <div class="col-md-4">
                        <label for="customerId" class="col-form-label p-0">Customer</label>
                        <p class="mb-0 helper-label">Nama Customer tidak boleh kosong</p>
                    </div>
                    <div class="col-md-8">
                        <select name="customer_id" id="customerId" class="form-select form-control">
                            <option value="">- Pilih Customer -</option>
                            @foreach ($customers as $customer)
                                <option {{ $customer->id == $bookingIn->customer_id ? 'selected' : '' }} value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row mb-5">
                    <div class="col-md-4">
                        <label for="checkboxContainer" class="col-form-label p-0"><i>Container Size</i></label>
                        <p class="mb-0 helper-label">
                            <i>Container Size</i> tidak boleh kosong. 
                            <br>
                            Pilih Salah Satu
                        </p>
                    </div>
                    <div class="col-md-8">
                        <div class="row" style="margin-top: 10px;">
                            @for ($a = 0; $a < count($containerSize); $a++)
                                <div class="col-md-3 mb-5">
                                    <div class="form-check">
                                        <input {{ $bookingIn->container_size_type_id == $containerSize[$a]->id ? 'checked' : '' }} class="form-check-input checkboxContainer" name="container_size" onchange="checkContainer({{ $a+1 }})" type="checkbox" id="checkboxContainer{{ $a+1 }}" value="{{ $containerSize[$a]->id }}">
                                        <label class="form-check-label" for="checkboxContainer{{ $a+1 }}">{{ $containerSize[$a]->size . ' ' . $containerSize[$a]->type }}</label>
                                    </div>
                                </div>
                            @endfor
                            <div class="col-md-3 mb-5">
                                <div class="form-check">
                                    <input {{ $bookingIn->is_customer_container_size ? 'checked' : '' }} class="form-check-input checkboxContainer" name="container_size" onchange="checkContainer({{ count($containerSize) + 1 }})" type="checkbox" id="checkboxContainer{{ count($containerSize) + 1 }}" value="custom">
                                    <label class="form-check-label" for="checkboxContainer{{ count($containerSize) + 1 }}">Other</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row mb-5" id="rowCustomSize" {{ $bookingIn->is_customer_container_size ? '' : 'hidden' }}>
                    <div class="col-md-4">
                        <label for="customSize" class="col-form-label p-0">Custom Container Size / Type</label>
                        <p class="mb-0 helper-label">
                            Detail Custom Container Size / Type
                        </p>
                    </div>
                    <div class="col-md-8">
                        <input type="text" value="{{ $bookingIn->custom_container_size }}" name="custom_size" placeholder="20 GP" class="form-control" id="customSize">
                    </div>
                </div>
                <div class="form-group row mb-5">
                    <div class="col-md-4">
                        <label for="checkboxCargoGoods" class="col-form-label p-0"><i>Cargo / Goods</i></label>
                        <p class="mb-0 helper-label">
                            <i>Cargo / Goods</i> tidak boleh kosong. 
                            <br>
                            Pilih Salah Satu
                        </p>
                    </div>
                    <div class="col-md-8">
                        <div class="row" style="margin-top: 10px;">
                            @for ($a = 0; $a < count($cargoGoods); $a++)
                                <div class="col-md-4 mb-5">
                                    <div class="form-check">
                                        <input {{ $bookingIn->cargo_goods == $cargoGoods[$a]['id'] ? 'checked' : '' }} class="form-check-input checkboxCargoGoods" name="cargo_goods" onchange="checkCargoGoods({{ $a+1 }})" type="checkbox" id="checkboxCargoGoods{{ $a+1 }}" value="{{ $cargoGoods[$a]['id'] }}">
                                        <label class="form-check-label" for="checkboxCargoGoods{{ $a+1 }}">{{ $cargoGoods[$a]['name'] }}</label>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
                <div class="form-group row mb-5">
                    <div class="col-md-4">
                        <label for="volume" class="col-form-label p-0">Volume</label>
                        <p class="mb-0 helper-label">
                            Volume tidak boleh kosong.
                        </p>
                    </div>
                    <div class="col-md-8">
                        <input type="text" name="volume" value="{{ $bookingIn->volume }}" placeholder="100" class="form-control" id="volume">
                    </div>
                </div>
                <div class="form-group row mb-5">
                    <div class="col-md-4">
                        <label for="services" class="col-form-label p-0">Data Layanan</label>
                        <p class="mb-0 helper-label">
                            Pilih Layanan dan Jenis Pembayaran
                        </p>
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6">
                                <select name="service_id" id="services" class="form-control form-select">
                                    <option value="">- Pilih Layanan -</option>
                                    @foreach ($service as $item)
                                        <option {{ $item->id == $bookingIn->service_id ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <select name="billing_type" id="billingType" class="form-control form-select">
                                    <option value="">- Pilih Jenis Pembayaran -</option>
                                    <option {{ $bookingIn->billing_type_id == 1 ? 'selected' : '' }} value="1">CASH</option>
                                    <option {{ $bookingIn->billing_type_id == 2 ? 'selected' : '' }} value="2">TEMPO</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row mb-5">
                    <div class="col-md-4">
                        <label for="" class="col-form-label p-0">Containers</label>
                        <p class="mb-0 helper-label">
                            Data <i>Containers</i> dan <i>Seal Containers</i>
                        </p>
                    </div>
                    <div class="col-md-8">
                        @for ($a = 0; $a < count($bookingIn->containers); $a++)
                            <div class="row mb-3 rowContainerNumbers" id="rowContainerNumbers{{ $a+1 }}">
                                <div class="col-md-5">
                                    <input type="text" value="{{ $bookingIn->containers[$a]->container_number }}" placeholder="Container Number" name="container_numbers[]" class="form-control" id="containerNumbers1">
                                </div>
                                <div class="col-md-5">
                                    <input type="text" value="{{ $bookingIn->containers[$a]->container_seal }}" placeholder="Container Seal" name="container_seals[]" class="form-control" id="containerNumbers1">
                                </div>
                                <div class="col-md-2 d-flex align-items-center justify-content-center">
                                    <div>
                                        <i class="fas fa-times text-danger" onclick="deleteContainerNumbers({{ $a+1 }})" style="cursor: pointer;"></i>
                                    </div>
                                </div>
                            </div>
                        @endfor
                        <div id="targetContainerNumbers"></div>
                        <button class="btn btn-light-primary btn-sm" type="button" onclick="addContainerNumbers()">
                            <i class="fas fa-plus me-3"></i>
                            Tambah
                        </button>
                    </div>
                </div>
                <div class="form-group row mb-5">
                    <div class="col-md-12">
                        <div class="text-end">
                            <button class="btn btn-primary" type="button" onclick="save()" id="btnSave">Simpan</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{-- end::card-form --}}
@endsection
{{-- end::content --}}

{{-- scripts --}}
@push('scripts')
    <script>
        $('#customerId').select2();
        $('#bookedBy').select2();
        $('#services').select2();
        $('#billingType').select2();

        function checkContainer(ids) {
            let checkbox = $('.checkboxContainer');

            for (let a = 0; a < checkbox.length; a++) {
                $('#checkboxContainer' + (a+1)).prop('checked', false);
            }
            $('#checkboxContainer' + ids).prop('checked', true);

            let val = $('#checkboxContainer' + ids).val();
            if (val == 'custom') {
                $('#rowCustomSize').attr('hidden', false);
            } else {
                $('#customSize').val('');
                $('#rowCustomSize').attr('hidden', true);
            }
        }

        function checkCargoGoods(ids) {
            let checkbox = $('.checkboxCargoGoods');

            for (let a = 0; a < checkbox.length; a++) {
                $('#checkboxCargoGoods' + (a+1)).prop('checked', false);
            }
            $('#checkboxCargoGoods' + ids).prop('checked', true);
        }

        function addContainerNumbers() {
            let rows = $('.rowContainerNumbers');
            let a = (rows.length + 1);
            div = `<div class="row mb-3 rowContainerNumbers" id="rowContainerNumbers${(a + 1)}">
                    <div class="col-md-5">
                        <input type="text" placeholder="Container Number" name="container_numbers[]" class="form-control" id="containerNumbers${a + 1}">
                    </div>
                    <div class="col-md-5">
                        <input type="text" placeholder="Container Seal" name="container_seals[]" class="form-control" id="containerNumbers${a + 1}">
                    </div>
                    <div class="col-md-2 d-flex align-items-center justify-content-center">
                        <div>
                            <i class="fas fa-times text-danger" onclick="deleteContainerNumbers('${(a + 1)}')" style="cursor: pointer;"></i>
                        </div>
                    </div>
                    </div>`;

            $('#targetContainerNumbers').append(div);
        }

        function deleteContainerNumbers(ids) {
            let row = $('.rowContainerNumbers');
            let rowLen = row.length;
            if (rowLen == 1) {
                iziToast['error']({
                    message: 'Container setidaknya mempunya 1 nilai',
                    position: "topRight"
                });
            } else {
                $('#rowContainerNumbers' + ids).remove();
            }
        }

        function save() {
            let data = $('#formBookingIn').serialize();
            let url = $('#formBookingIn').attr('action');
            let btnSave = $('#btnSave');
            let id = "{{ $bookingIn->id }}";

            $.ajax({
                type: "PUT",
                url: url,
                data: data,
                beforeSend: function() {
                    btnSave.attr('disabled', true);
                    btnSave.text('Menyimpan Data ...');
                },
                success: function(res) {
                    btnSave.attr('disabled', false);
                    btnSave.text('Simpan');
                    iziToast['success']({
                        message: 'Berhasil menyimpan data',
                        position: "topRight"
                    });
                    window.location.href = "{{ url('/booking-in') }}" + "/" + id + "/edit";
                },
                error: function(err) {
                    btnSave.attr('disabled', false);
                    btnSave.text('Simpan');
                    handleError(err, btnSave);
                }
            })
        }
    </script>
@endpush
{{-- scripts --}}