<div class="row">
    <div class="col-md-12">
        <label class="form-label">Name</label>
        <span class="help"></span>
        <div class="controls row p-b-20">
            <div class="col-md-6">
                {!! Form::text('first_name', null, ['class' => 'form-control', 'placeholder' => 'First', 'maxlength' => 32, 'autofocus']) !!}
            </div>
            <div class="col-md-6">
                {!! Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => 'Last', 'maxlength' => 32]) !!}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <label class="form-label">T-Shirt Size</label>
        <span class="help"></span>
        <div class="controls p-b-20">
            {!! Form::selectShirtSize('shirt_size', null, ['class' => 'form-control']) !!}<br/>
        </div>
    </div>
    <div class="col-md-6">
        <label class="form-label">Gender</label>
        <span class="help"></span>
        <div class="controls">
            @include('partials.forms.gender')
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <label class="form-label">Birthday</label>
        <span class="help"></span>
        <div class="controls p-b-20">
            <div class="input-append success date col-md-10 col-lg-6 no-padding" data-date="{{ \Carbon\Carbon::now()->subYears(14)->format('m/d/Y') }}">
                {!! Form::text('birthday', null, ['class' => 'form-control']) !!}
                <span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span>
            </div>
        </div>
    </div>
</div>