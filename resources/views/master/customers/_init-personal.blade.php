<tr>
    <td>Nama</td>
    <td><b id="templateName">{{ $customer->name }}</b></td>
</tr>
<tr>
    <td>Email</td>
    <td><b id="templateEmail">{{ $customer->email }}</b></td>
</tr>
<tr>
    <td>HP</td>
    <td><b id="templatePhone">{{ $customer->phone }}</b></td>
</tr>
<tr>
    <td>PIC</td>
    <td><b id="templatePic">{{ $customer->pic_name . ' - ' . $customer->pic_phone }}</b></td>
</tr>
<tr>
    <td>NPWP</td>
    <td><b id="templateNpwp">{{ $customer->npwp ?? '-' }}</b></td>
</tr>
<tr>
    <td>Alamat</td>
    <td> <b id="templateAddress">{{ $completeAddress }}</b> </td>
</tr>
<tr>
    <td>Tipe Pelanggan</td>
    <td> <b id="templateAddress">{{ !$customer->type ? '-' : strtoupper($customer->type) }}</b> </td>
</tr>