<div class="row">
    <div class="col-md-12">
        <label class="form-label">Name</label>
        <span class="help"></span>
        <div class="controls">
            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Name', 'maxlength' => 32, 'autofocus']) !!}<br/>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <label class="form-label">Contact Name</label>
        <span class="help"></span>
        <div class="controls row p-b-20">
            <div class="col-md-6">
                {!! Form::text('first_name', null, ['class' => 'form-control', 'placeholder' => 'First', 'maxlength' => 32]) !!}
            </div>
            <div class="col-md-6">
                {!! Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => 'Last', 'maxlength' => 32]) !!}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 t-p-20">
        <label class="form-label">Street</label>
        <span class="help"></span>
        <div class="controls p-b-20">
            {!! Form::text('address_one', null, ['class' => 'form-control', 'placeholder' => 'Street Address', 'maxlength' => 255]) !!}<br/>
            {!! Form::text('address_two', null, ['class' => 'form-control', 'maxlength' => 255]) !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <label class="form-label">City</label>
        <span class="help"></span>
        <div class="controls p-b-20">
            {!! Form::text('city', null, ['class' => 'form-control', 'placeholder' => 'City', 'maxlength' => 64]) !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <label class="form-label">State</label>
        <span class="help"></span>
        <div class="controls p-b-20">
            {!! Form::selectState('state', null, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-6">
        <label class="form-label">Zip Code</label>
        <span class="help"></span>
        <div class="controls">
            {!! Form::text('zip_code', null, ['class' => 'form-control', 'placeholder' => 'Zip Code', 'maxlength' => 16]) !!}
        </div>
    </div>
</div>