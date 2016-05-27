@extends('emails.simple')

@section('title', 'BBowl Exception: '.$exception->getMessage())

@section('body')

    <?php
        $sessionInfo = [];
        if (Auth::user() != null) {
            $sessionInfo[] = 'User: '.Auth::user()->full_name.' ('.Auth::user()->id.')';
            if (Session::group() != null) {
                $sessionInfo[] = 'Group: '.Session::group()->name.' ('.Session::group()->id.')';
            }
        }
    ?>
    @include('emails.theme.text-block', [
        'body' => implode('<br/>', $sessionInfo)
    ])

    @include('emails.theme.text-block', [
        'body' => $exception->getTraceAsString()
    ])

@endsection