<div class="row p-t-20">
    <div class="col-md-3">
        Adult:
    </div>
    <div class="col-md-9">
        <div class="row form-group">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <label class="form-label">Which group are you with?</label>
                <span class="help">Whether they have teams at this tournament or not</span>
                <div class="controls">
                    {!! Form::selectGroup($tournament->program_id, 'group_id', old('group_id'), ['class' => 'form-control'], true) !!}
                </div>
            </div>
        </div>
        @if(Auth::user() == null)
            <div class="row form-group">
                <div class="col-md-12">
                    <label class="form-label">Name</label>
                    <span class="help"></span>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {!! Form::text('first_name', old('first_name'), ['class' => 'form-control', 'placeholder' => 'First', 'maxlength' => 64, 'autofocus']) !!}
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {!! Form::text('last_name', old('last_name'), ['class' => 'form-control', 'placeholder' => 'Last', 'maxlength' => 64]) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-12">
                    <label class="form-label">Email</label>
                    <span class="help"></span>
                    <div class="controls">
                        {!! Form::email('email', old('email'), ['class' => 'form-control', 'maxlength' => 64]) !!}
                    </div>
                </div>
            </div>
        @endif
        <div class="row form-group">
            <div class="col-md-6 col-sm-6">
                <label class="form-label">T-Shirt Size</label>
                <span class="help"></span>
                <div class="controls">
                    {!! Form::selectShirtSize('shirt_size', old('shirt_size'), ['class' => 'form-control']) !!}<br/>
                </div>
            </div>
            @if(Auth::user() == null)
                <div class="col-md-6 col-sm-6">
                    <label class="form-label">Gender</label>
                    <span class="help"></span>
                    <div class="controls">
                        @include('partials.forms.gender')
                    </div>
                </div>
            @endif
        </div>
        <div class="checkbox check-primary">
            {!! Form::checkbox('register-family', 1, old('register-family'), ['id' => 'register-family']) !!}
            <label for="register-family">Register your spouse and/or minors</label>
        </div>
    </div>
</div>
<div class="row p-t-20">
    <div class="col-md-3">
        Spouse:
    </div>
    <div class="col-md-9">
        <div class="row form-group">
            <div class="col-md-12">
                <label class="form-label">Name</label>
                <span class="help"></span>
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        {!! Form::text('spouse_first_name', old('spouse_first_name'), ['class' => 'form-control', 'placeholder' => 'First', 'maxlength' => 64]) !!}
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12"></div>
                </div>
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-6 col-sm-6">
                <label class="form-label">T-Shirt Size</label>
                <span class="help"></span>
                <div class="controls">
                    {!! Form::selectShirtSize('spouse_shirt_size', old('spouse_shirt_size'), ['class' => 'form-control']) !!}<br/>
                </div>
            </div>
            <div class="col-md-6 col-sm-6">
                <label class="form-label">Gender</label>
                <span class="help"></span>
                <div class="controls">
                    @include('partials.forms.gender', [
                        'fieldName' => 'spouse_gender'
                    ])
                </div>
            </div>
        </div>
    </div>
</div>