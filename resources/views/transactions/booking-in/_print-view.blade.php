<div class="row">
    <div class="col">
        <div class="text-center">
            <h3 >PT. DUANTA ADJI SEJAHTERA</h3>
            <h5 class="lead">LOADING SLIP</h5>
            <p class="mb-0">{{ $bookingIn->booking_code }}</p>
            <p>{{ $bookingIn->customer->name }}</p>
            {!! QrCode::size(250)->generate($bookingIn->booking_code); !!}
        </div>
    </div>
</div>