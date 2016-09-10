@extends('emails.simple')

@section('body')

    @include('emails.theme.header', [
        'header' => $header
    ])


    @include('emails.theme.text-block', [
        'body' => 'Ownership of <strong>'.$group->name.' ('.$group->program->name.')</strong> has been transferred from <strong>'.$previousOwner->full_name.'</strong> to <strong>'.$newOwner->full_name.'</strong>.'
    ])

@endsection