<?php

if (!isset($fieldName)) {
    $fieldName = 'gender';
}

?>

<div class="radio">
    {!! Form::radio($fieldName, 'M', old($fieldName, 'M') == 'M', ['id' => 'male']) !!}
    {!! Form::label('male', 'Male') !!}
    {!! Form::radio($fieldName, 'F', old($fieldName) == 'F', ['id' => 'female']) !!}
    {!! Form::label('female', 'Female') !!}
</div>