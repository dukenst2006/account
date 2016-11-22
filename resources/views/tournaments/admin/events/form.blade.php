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