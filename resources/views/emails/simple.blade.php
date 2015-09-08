@extends('...layouts.email')

@section('subject', $message->getSubject())

@section('content')
    <table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#f4f4f4" class="moduleSeperatorLine" style="border-top-style: solid;border-top-color: #e5e5e5;border-top-width: 1px;">
        <tr valign="top">
            <td valign="top" align="center" bgcolor="#f4f4f4" style="border-collapse: collapse;">
                @include('emails.theme.empty-spacer')

                @yield('body')

                @include('emails.theme.empty-spacer')
            </td>
        </tr>
    </table>
@endsection