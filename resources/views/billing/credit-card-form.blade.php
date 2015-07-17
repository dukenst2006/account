<div class="row">
    <div class="col-md-12">
        <label class="form-label">Card Holder's Name</label>
        <span class="help"></span>
        <div class="controls p-b-20">
            {!! Form::text('cardHolder', null, ['class' => 'form-control', 'maxlength' => 64]) !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-8">
        <label class="form-label">Credit Card Number</label>
        <span class="help"></span>
        <div class="controls p-b-20">
            {!! Form::text('cardNumber', null, ['class' => 'form-control', 'maxlength' => 64]) !!}
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-label">CVV</label>
        <span class="help"></span>
        <div class="controls p-b-20">
            {!! Form::text('cardCVV', null, ['class' => 'form-control', 'maxlength' => 4]) !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <label class="form-label">Expiration</label>
        <span class="help"></span>
        <div class="controls p-b-20">
            {!! Form::selectMonthNumeric('cardExpireMonth', null, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-6">
        <label class="form-label">Year</label>
        <span class="help"></span>
        <div class="controls p-b-20">
            {!! Form::selectFutureYear('cardExpireYear', null, ['class' => 'form-control']) !!}
        </div>
    </div>
</div>