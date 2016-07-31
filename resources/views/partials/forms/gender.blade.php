<?php

if (!isset($fieldName)) {
    $fieldName = 'gender';
}

?>

<div class="radio">
    {!! Form::radio($fieldName, 'M', old($fieldName, 'M') == 'M', ['id' => $fieldName.'-male']) !!}
    {!! Form::label($fieldName.'-male', 'Male') !!}
    {!! Form::radio($fieldName, 'F', old($fieldName) == 'F', ['id' => $fieldName.'-female']) !!}
    {!! Form::label($fieldName.'-female', 'Female') !!}
</div>