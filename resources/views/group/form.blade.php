
<div class="row">
    <div class="col-md-12 form-group">
        <label class="form-label">Name</label>
        <span class="help"></span>
        <div class="controls p-b-20">
            {!! Form::text('name', old('name'), ['class' => 'form-control', 'maxlength' => 128]) !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-11 form-group">
        <label class="form-label">Type</label>
        <div class="controls p-b-10">
            <label>{!! Form::selectGroupType('group_type_id', old('group_type_id', (isset($group) ? $group->group_type_id : null)), ['class' => 'form-control']) !!}</label>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-11 form-group">
        <label class="form-label">Mailing Address</label>
        <span class="help">The address where your group can be reached.</span>
        <div class="controls p-b-10 row">
            <div class="col-md-10">
                <div class="controls">
                    <label>{!! Form::selectAddress('address_id', old('address_id', (isset($group) ? $group->address_id : null)), ['class' => 'form-control']) !!}</label>
                </div>
            </div>
            <div class="col-md-2 p-t-10">
                <a href="/account/address/create" class="btn btn-white btn-small">New Address</a>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-11 form-group">
        <label class="form-label">Meeting Location</label>
        <span class="help">The address where your group usually meets.</span>
        <div class="controls p-b-20 row">
            <div class="col-md-10">
                <div class="controls">
                    <label>{!! Form::selectAddress('meeting_address_id', old('meeting_address_id', (isset($group) ? $group->meeting_address_id : null)), ['class' => 'form-control']) !!}</label>
                </div>
            </div>
            <div class="col-md-2 p-t-10">
                <a href="/account/address/create" class="btn btn-white btn-small">New Address</a>
            </div>
        </div>
    </div>
</div>