<div class="radio">
    {!! Form::radio('gender', 'M', old('gender', 'M') == 'M', ['id' => 'male']) !!}
    {!! Form::label('male', 'Male') !!}
    {!! Form::radio('gender', 'F', old('gender') == 'F', ['id' => 'female']) !!}
    {!! Form::label('female', 'Female') !!}
</div>