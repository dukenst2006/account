<div class="row" id='price-field'>
    <div class="col-md-12 form-group">
        <label class="form-label">Price</label>
        <span class="help">Leave empty if the event is free or included</span>
        <div class="input-group transparent">
          <span class="input-group-addon">
            <i class="fa fa-usd"></i>
          </span>
            {!! Form::text('price_per_participant', null, ['class' => 'form-control']) !!}
        </div>
    </div>
</div>
<div class="row" id='required-participation'>
    <div class="col-md-12 form-group">
        <div class="checkbox check-primary">
            {!! Form::checkbox("required", 1, old("required"), [ "id" => 'requiresParticipation' ]) !!}
            <label for="requiresParticipation"><strong>Participation Required</strong> - All players will participate in this event</label>
        </div>
    </div>
</div>