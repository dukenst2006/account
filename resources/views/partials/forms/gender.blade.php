<div class="radio">
    {!! Form::radio('gender', 'M', (!isset($value) || $value == 'M'), ['id' => 'male']) !!}
    {!! Form::label('male', 'Male') !!}
    {!! Form::radio('gender', 'F', (isset($value) && $value == 'F'), ['id' => 'female']) !!}
    {!! Form::label('female', 'Female') !!}
</div>