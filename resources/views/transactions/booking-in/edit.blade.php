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
                                    <option {{ $bookingIn->booked_by == $item->user->id ? 'selected' : '' }} value="{{ $item->user->id }}">{{ $item->user->name }}</option>
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
                                <option {{ $bookingIn->customer_id == $customer->id ? 'selected' : '' }} value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
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
                        @for($i = 0; $i < count($bookingIn->containers); $i++)
                        <div style="position: relative; z-index: 100;" id="rowContainerNumbers{{ $i+1 }}">
                            <span style="position: absolute; z-index: 101; top: -10px; right: -5px; cursor: pointer;" onclick="deleteContainerNumbers({{ $i+1 }})"><i class="fas fa-times text-danger fa-2x"></i></span>
                            <div class="card card-flush bg-secondary mb-5">
                                <div class="card-body">
                                    <div class="row mb-4 rowContainerNumbers">
                                        <div class="col-md-5">
                                            <input type="text" placeholder="Container Number" value="{{ $bookingIn->containers[$i]->container_number }}" name="containers[0][container_number]" class="form-control" id="containerNumbers1">
                                        </div>
                                        <div class="col-md-5">
                                            <input type="text" placeholder="Container Seal" value="{{ $bookingIn->containers[$i]->container_seal }}" name="containers[0][container_seal]" class="form-control" id="containerNumbers1">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" name="containers[0][volume]" value="{{ $bookingIn->containers[$i]->volume }}" placeholder="Volume" class="form-control" id="volume">
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <h5 class="mb-3 mt-3">Contaier Size / Type</h5>
                                        @for ($a = 0; $a < count($containerSize); $a++)
                                            <div class="col-md-3 mb-5">
                                                <div class="form-check">
                                                    <input class="form-check-input checkboxContainer" name="containers[0][container_size_type_id]" onchange="checkContainer({{ $a+1 }}1,1)" type="checkbox" 
                                                        id="checkboxContainer{{ $a+1 }}1" {{ $bookingIn->containers[$i]->container_size_type_id == $containerSize[$a]->id ? 'checked' : '' }} value="{{ $containerSize[$a]->id }}">
                                                    <label class="form-check-label" for="checkboxContainer{{ $a+1 }}1">{{ $containerSize[$a]->size . ' ' . $containerSize[$a]->type }}</label>
                                                </div>
                                            </div>
                                        @endfor
                                        <div class="col-md-3 mb-5">
                                            <div class="form-check">
                                                <input class="form-check-input checkboxContainer" name="containers[0][container_size_type_id]" onchange="checkContainer({{ count($containerSize) + 1 }}1,1)"
                                                    type="checkbox" id="checkboxContainer{{ count($containerSize) + 1 }}1" {{ $bookingIn->containers[$i]->container_size_type_id == null ? 'checked' : '' }} value="custom">
                                                <label class="form-check-label" for="checkboxContainer{{ count($containerSize) + 1 }}1">Other</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4" id="rowCustomSize1" {{ $bookingIn->containers[$i]->is_customer_container_size ? '' : 'hidden' }}>
                                        <h5 class="mb-3 mt-3">Custom Contaier Size / Type</h5>
                                        <div class="col-md-12">
                                            <input type="text" name="containers[0][custom_container_size]" value="{{ $bookingIn->containers[$i]->custom_container_size }}" placeholder="20 GP" class="form-control" id="customSize">
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <h5 class="mb-3 mt-3">Cargo / Goods</h5>
                                        @for ($a = 0; $a < count($cargoGoods); $a++)
                                            <div class="col-md-4 mb-5">
                                                <div class="form-check">
                                                    <input class="form-check-input checkboxCargoGoods" name="containers[0][cargo_goods]" onchange="checkCargoGoods({{ $a+1 }}1,1)"
                                                        type="checkbox" id="checkboxCargoGoods{{ $a+1 }}1" {{ $bookingIn->containers[$i]->cargo_goods == $cargoGoods[$a]['id'] ? 'checked' : '' }} value="{{ $cargoGoods[$a]['id'] }}">
                                                    <label class="form-check-label" for="checkboxCargoGoods{{ $a+1 }}1">{{ $cargoGoods[$a]['name'] }}</label>
                                                </div>
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endfor
                        <div id="targetContainerNumbers"></div>

                        <div class="row">
                            <div class="col">
                                <div>
                                    <button class="btn btn-sm btn-light-success" type="button" onclick="addContainerNumbers()">
                                        <i class="fas fa-plus me-3" style="cursor: pointer;"></i>
                                        Tambah Containers
                                    </button>
                                </div>
                            </div>
                        </div>
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

        function checkContainer(ids, index) {
            console.log(ids);
            let checkbox = $('.checkboxContainer');

            for (let a = 0; a < checkbox.length; a++) {
                $('#checkboxContainer' + (a+1) + index).prop('checked', false);
            }
            $('#checkboxContainer' + ids).prop('checked', true);

            let val = $('#checkboxContainer' + ids).val();
            if (val == 'custom') {
                $('#rowCustomSize' + index).attr('hidden', false);
            } else {
                $('#customSize').val('');
                $('#rowCustomSize' + index).attr('hidden', true);
            }
        }

        function checkCargoGoods(ids, index) {
            let checkbox = $('.checkboxCargoGoods');

            for (let a = 0; a < checkbox.length; a++) {
                $('#checkboxCargoGoods' + (a+1) + index).prop('checked', false);
            }
            $('#checkboxCargoGoods' + ids).prop('checked', true);
        }

        function addContainerNumbers() {
            let rows = $('.rowContainerNumbers');
            let a = (rows.length + 1);
            let b = rows.length;
            div = `<div style="position: relative; z-index: 100;" id="rowContainerNumbers${a}">
                        <span style="position: absolute; z-index: 101; top: -10px; right: -5px; cursor: pointer;" onclick="deleteContainerNumbers(${a})"><i class="fas fa-times text-danger fa-2x"></i></span>
                        <div class="card card-flush bg-secondary mb-5">
                            <div class="card-body">
                                <div class="row mb-4 rowContainerNumbers">
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Container Number" name="containers[${b}][container_number]" class="form-control" id="containerNumbers1">
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" placeholder="Container Seal" name="containers[${b}][container_seal]" class="form-control" id="containerNumbers1">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" name="containers[${b}][volume]" placeholder="Volume" class="form-control" id="volume">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <h5 class="mb-3 mt-3">Contaier Size / Type</h5>
                                    @for ($a = 0; $a < count($containerSize); $a++)
                                        <div class="col-md-3 mb-5">
                                            <div class="form-check">
                                                <input class="form-check-input checkboxContainer" name="containers[${b}][container_size_type_id]" onchange="checkContainer({{ $a+1 }}${a}, ${a})"
                                                    type="checkbox" id="checkboxContainer{{ $a+1 }}${a}" value="{{ $containerSize[$a]->id }}">
                                                <label class="form-check-label" for="checkboxContainer{{ $a+1 }}${a}">{{ $containerSize[$a]->size . ' ' . $containerSize[$a]->type }}</label>
                                            </div>
                                        </div>
                                    @endfor
                                    <div class="col-md-3 mb-5">
                                        <div class="form-check">
                                            <input class="form-check-input checkboxContainer" name="containers[${b}][container_size_type_id]" onchange="checkContainer({{ count($containerSize) + 1 }}${a}, ${a})"
                                                type="checkbox" id="checkboxContainer{{ count($containerSize) + 1 }}${a}" value="custom">
                                            <label class="form-check-label" for="checkboxContainer{{ count($containerSize) + 1 }}${a}">Other</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-4" id="rowCustomSize${a}" hidden>
                                    <h5 class="mb-3 mt-3">Custom Contaier Size / Type</h5>
                                    <div class="col-md-12">
                                        <input type="text" name="containers[${b}][custom_container_size]" placeholder="20 GP" class="form-control" id="customSize">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <h5 class="mb-3 mt-3">Cargo / Goods</h5>
                                    @for ($a = 0; $a < count($cargoGoods); $a++)
                                        <div class="col-md-4 mb-5">
                                            <div class="form-check">
                                                <input class="form-check-input checkboxCargoGoods" name="containers[${b}][cargo_goods]" onchange="checkCargoGoods({{ $a+1 }}${a},${a})"
                                                    type="checkbox" id="checkboxCargoGoods{{ $a+1 }}${a}" value="{{ $cargoGoods[$a]['id'] }}">
                                                <label class="form-check-label" for="checkboxCargoGoods{{ $a+1 }}${a}">{{ $cargoGoods[$a]['name'] }}</label>
                                            </div>
                                        </div>
                                    @endfor
                                </div>
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
                    message: 'Setidaknya Pilih 1 Container',
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

            $.ajax({
                type: "PUT",
                url: url,
                data: data,
                beforeSend: function() {
                    btnSave.attr('disabled', true);
                    btnSave.text('Menyimpan Data ...');
                },
                success: function(res) {
                    console.log(res);
                    btnSave.attr('disabled', false);
                    btnSave.text('Simpan');
                    iziToast['success']({
                        message: 'Berhasil menyimpan data',
                        position: "topRight"
                    });
                    window.location.href = "{{ route('booking-in.index') }}";
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