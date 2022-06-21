<div class="col-md-4">
    <div class="aggreement-img">
        @if ($customer->contract->aggreement_letter_img != NULL)
            <img src="{{ asset($customer->contract->aggreement_letter_img) }}" class="letter-img" alt="">
            @else
            <img src="{{ asset('/images/document.png') }}" class="letter-img" alt="">
        @endif
    </div>
</div>
<div class="col-md-8">
    <table class="table">
        <tbody>
            <tr>
                <td>Pembaruhan Otomatis</td>
                <td> <b>{{ $customer->contract->is_auto_renewal == 1 ? 'Ya' : 'Tidak' }}</b> </td>
            </tr>
            <tr>
                <td>Tanggal Mulai Kontrak</td>
                <td> <b>{{ date('d F Y', strtotime($customer->contract->start_date)) }}</b> </td>
            </tr>
            <tr>
                <td>Lama Kontrak</td>
                <td> <b>{{ $customer->contract->contract_period_in_day . ' Hari' }}</b> </td>
            </tr>
            <tr>
                <td>Tanggal Berakhir Kontrak</td>
                <td> <b>{{ date('d F Y', strtotime($customer->contract->end_date)) }}</b> </td>
            </tr>
        </tbody>
    </table>
</div>