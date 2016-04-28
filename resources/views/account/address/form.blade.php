<div class="row">
    <div class="col-md-12 t-p-20">
        <label class="form-label">Street</label>
        <span class="help"></span>
        <div class="controls p-b-20">
            {!! Form::text('address_one', null, ['class' => 'form-control', 'maxlength' => 255]) !!}<br/>
            {!! Form::text('address_two', null, ['class' => 'form-control', 'maxlength' => 255]) !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <label class="form-label">Zip Code</label>
        <div class="controls">
            {!! Form::text('zip_code', null, ['class' => 'form-control', 'maxlength' => 16]) !!}
        </div>
    </div>
    <div class="col-md-6"></div>
</div>