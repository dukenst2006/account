<div class="row">
    <div class="col-md-12 col-sm-12 t-p-20">
        <label class="form-label">Street
        @if (isset($required) && $required)
            <span class="required">*</span>
        @endif
        </label>
        <span class="help"></span>
        <div class="controls p-b-20">
            {!! Form::text('address_one', old('address_one'), ['class' => 'form-control', 'maxlength' => 255]) !!}<br/>
            {!! Form::text('address_two', old('address_two'), ['class' => 'form-control', 'maxlength' => 255, 'placeholder' => 'Unit number, suite, etc.']) !!}
            <div class="help muted m-t-5">We'll know your city/state based on your zip code</div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-sm-6">
        <label class="form-label">Zip Code
            @if (isset($required) && $required)
                <span class="required">*</span>
            @endif</label>
        <div class="controls">
            {!! Form::text('zip_code', old('zip_code'), ['class' => 'form-control', 'maxlength' => 16]) !!}
        </div>
    </div>
    <div class="col-md-6"></div>
</div>