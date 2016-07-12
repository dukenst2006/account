<div class="grid simple horizonal orange">
    <div class="grid-title no-border">
        <h4 class="bold">{{ Session::season()->abbreviation }} Player Registration Fees</h4>
    </div>
    <div class="grid-body no-border" style="padding-bottom:0;">
        <div class="p-b-10 p-r-10 p-l-10 p-t-10 text-center">
            <p class="text-gray p-b-10"><strong>{{ $playersPendingPayment->count() }}</strong> player's fees are due for a total of <strong>${{ number_format(Session::group()->program->registration_fee * $playersPendingPayment->count()) }}</strong> at ${{ number_format(Session::group()->program->registration_fee) }}/player</p>
            @if ($playersPendingPayment->count() > 0)
                <a class="btn btn-primary btn-cons" href="/players/pay">Pay Now</a>
            @endif
        </div>
    </div>
</div>