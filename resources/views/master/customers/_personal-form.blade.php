<form action="" id="formEditPersonalData" enctype="multipart/form-data">
    <div class="form-group row mb-5">
        <div class="col-md-6 col-xl-6">
            <label for="editCustomerName" class="col-form-label">Nama</label>
            <input type="text" value="{{ $customer->name }}" name="name" class="form-control" id="editCustomerName">
        </div>
        <div class="col-md-6 col-xl-6">
            <label for="editCustomerEmail" class="col-form-label">Email</label>
            <input type="email" value="{{ $customer->email }}" name="email" class="form-control" id="editCustomerEmail">
        </div>
    </div>
    <div class="form-group row mb-5">
        <div class="col-md-6">
            <label for="editCustomerPhone" class="col-form-label">No. Telfon</label>
            <input type="text" value="{{ $customer->phone }}" name="phone" class="form-control" id="editCustomerPhone">
        </div>
        <div class="col-md-6">
            <label for="editCustomerNpwp" class="col-form-label">NPWP</label>
            <input type="text" value="{{ $customer->npwp }}" name="npwp" class="form-control" id="editCustomerNpwp">
        </div>
    </div>
    <div class="form-group row mb-5">
        <div class="col-md-6 col-xl-6">
            <label for="editCustomerPic" class="col-form-label">Nama PIC</label>
            <input type="text" value="{{ $customer->pic_name }}" name="pic_name" class="form-control" id="editCustomerPic">
        </div>
        <div class="col-md-6 col-xl-6">
            <label for="editCustomerPicPhone" class="col-form-label">No. Telfon PIC</label>
            <input type="email" value="{{ $customer->pic_phone }}" name="pic_phone" class="form-control" id="editCustomerPicPhone">
        </div>
    </div>
    <div class="form-group row mb-5">
        <div class="col-md-4 col-xl-4">
            <label for="editCustomerProvince" class="col-form-label">Provinsi</label>
            <select name="province" class="form-select form-control" id="editCustomerProvince" onchange="changeOnProvince()">
                <option value="">- Pilih Provinsi -</option>
                @foreach ($provinces as $province)
                    <option value="{{ $province->id }}" {{ $province->id == $customer->province ? 'selected' : '' }}>
                        {{ $province->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 col-xl-4">
            <label for="editCustomerRegency" class="col-form-label">Kota</label>
            <select name="city" class="form-select form-control" id="editCustomerRegency" onchange="changeOnRegency()">
                <option value="">- Pilih Kota -</option>
                @foreach ($regencies as $regency)
                    <option value="{{ $regency->id }}" {{ $regency->id == $customer->city ? 'selected' : '' }}>
                        {{ $regency->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 col-xl-4">
            <label for="editCustomerDistrict" class="col-form-label">Kecamatan / Kelurahan</label>
            <select name="district" class="form-select form-control" id="editCustomerDistrict">
                <option value="">- Pilih Kecamatan / Kelurahan -</option>
                @foreach ($formatDistrict as $district)
                    <option value="{{ $district['id'] }}" {{ $district['id'] == $customer->district ? 'selected' : '' }}>
                        {{ $district['name'] }}
                    </option>   
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row mb-5">
        <div class="col">
            <label for="editCustomerAddress" class="col-form-label">Alamat Lengkap</label>
            <textarea name="address" id="editCustomerAddress" cols="4" rows="4" class="form-control">
                {{ $customer->address }}
            </textarea>
        </div>
    </div>
</form>