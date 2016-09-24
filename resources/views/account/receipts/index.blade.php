@extends('layouts.master')

@section('title', 'My Receipts')

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="grid simple">
                    <div class="grid-title no-border p-l-20 p-t-15">
                        <h4>My <span class="semi-bold">Receipts</span></h4>
                    </div>
                    <div class="grid-body no-border">
                        <p>Here you can view all of your past purchases</p>
                        @include('partials.messages')
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Invoice #</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Date</th>
                                <th class="text-center">Options</th>
                            </tr>
                            </thead>
                            <tbody>
                        @if(count($receipts) > 0)
                            @foreach($receipts as $receipt)
                            <tr>
                                <td class="v-align-middle">
                                    <a href="/account/receipts/{{ $receipt->id }}">#{{ $receipt->id }}</a>
                                </td>
                                <td class="v-align-middle text-center">${{ number_format($receipt->total, 2) }}</td>
                                <td class="v-align-middle text-center">{{ $receipt->created_at->timezone($user->settings->timeszone())->diffForHumans() }}</td>
                                <td class="v-align-middle text-center"><a href="/account/receipts/{{ $receipt->id }}" class="btn btn-primary btn-xs btn-mini"><i class="fa fa-download"></i> PDF</a></td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="text-center" colspan="7"><div class="muted m-t-40" style="font-style: italic">You have not yet made any payments</div></td>
                            </tr>
                        @endif
                            </tbody>
                        </table>
                        {!! $receipts->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection