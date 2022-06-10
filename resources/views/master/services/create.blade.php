@extends('layouts.master')
{{-- begin::content --}}
@section('content')
    {{-- begin::card --}}
    <div class="card card-flush mb-5">
        <div class="card-body p-3">
            <div class="text-start">
                <a href="{{ route('services.index') }}" class="btn btn-light-primary">
                    <i class="fa fa-chevron-left"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>
    {{-- end::card --}}

    {{-- begin::card --}}
    <div class="card card-flush">
        <div class="card-body">
            <div class="container">
                {{-- begin::form --}}
                <form action="{{ isset($service) ? route('services.update', $service->id) : route('services.index') }}"
                        id="formService" method="POST">
                    {{-- begin::form-group --}}
                    <div class="form-group mb-5 row">
                        <div class="col">
                            <label for="serviceName" class="col-form-label">Nama Layanan / Produk</label>
                            <input type="text" class="form-control" name="name" 
                                placeholder="Nama Layanan / Produk" id="serviceName"
                                value="{{ isset($service) ? $service->name : "" }}">
                        </div>    
                    </div>
                    <div class="form-group mb-5 row">
                        <div class="col">
                            <label for="servicePrice" class="col-form-label">Harga Layanan / Produk</label>
                            <input type="number" class="form-control" name="price" 
                                placeholder="Harga Layanan / Produk" id="servicePrice"
                                value="{{ isset($service) ? $service->price : "" }}">
                        </div>    
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary" id="btnSave" type="button" onclick="save()">Simpan</button>
                    </div>
                    {{-- end::form-group --}}
                </form>
                {{-- end::form --}}
            </div>
        </div>
    </div>
    {{-- end::card --}}
@endsection
{{-- end::content --}}

{{-- begin::scripts --}}
@push('scripts')
    <script>
        function save() {
            let data = $('#formService').serialize();
            let url = $('#formService').attr('action');
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                dataType: 'json',
                beforeSend: function() {
                    $('#btnSave').attr('disabled', true);
                    $('#btnSave').text('Memproses data ...');
                },
                success: function(res) {
                    $('#btnSave').attr('disabled', false);
                    $('#btnSave').text('Simpan');
                    document.getElementById('formService').reset();
                    iziToast['success']({
                        message: 'Berhasil menyimpan data',
                        position: "topRight"
                    });

                    setInterval(() => {
                        window.location.href = "{{ route('services.index') }}";
                    }, 1000);
                },
                error: function(err) {
                    console.log(err);
                    $('#btnSave').attr('disabled', false);
                    $('#btnSave').text('Simpan');
                    let message = err.responseJSON.message;
                    if (message == 'FAILED') {
                        iziToast['error']({
                            message: err.responseJSON.data.error,
                            position: "topRight"
                        });
                    } else if (message == 'VALIDATION_FAILED') {
                        let error = err.responseJSON.data.error;
                        for (let a = 0; a < error.length; a++) {
                            iziToast['error']({
                                message: error[a],
                                position: "topRight"
                            });
                        }
                    } else {
                        iziToast['error']({
                            message: message,
                            position: "topRight"
                        });
                    }
                }
            })
        }
    </script>    
@endpush
{{-- end::scripts --}}