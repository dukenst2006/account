@extends('layouts.master')

@section('title', 'Financials')

@section('content')
    <div class="content">
        <div class="grid white simple">
            <div class="grid-body no-border">
                <h3>Payments</h3>
                <p>A brief overview of payments made within this system.</p>
                <table class="table">
                    <thead>
                    <tr>
                        <th style="width: 40%">Item</th>
                        <th style="width: 20%" class="text-center">{{ \Carbon\Carbon::now()->subMonths(2)->format('M') }}</th>
                        <th style="width: 20%" class="text-center">{{ \Carbon\Carbon::now()->subMonth()->format('M') }}</th>
                        <th style="width: 20%" class="text-center">MTD</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($invoiceItemSummary) > 0)
                        @foreach($invoiceItemSummary as $itemDescription => $summary)
                            <tr>
                                <td class="v-align-middle">{{ $itemDescription }}</td>
                                <td class="v-align-middle text-center">{{ ($summary['monthBeforeLast'] > 0 ? '$'.number_format($summary['monthBeforeLast']) : '-') }}</td>
                                <td class="v-align-middle text-center">{{ ($summary['lastMonth'] > 0 ? '$'.number_format($summary['lastMonth']) : '-') }}</td>
                                <td class="v-align-middle text-center">{{ ($summary['thisMonth'] > 0 ? '$'.number_format($summary['thisMonth']) : '-') }}</td>
                        @endforeach
                    @else
                        <tr>
                            <td class="text-center" colspan="4"><div class="muted m-t-40" style="font-style: italic">There have not been any recent payments</div></td>
                        </tr>
                    @endif
                    </tbody>
                </table>
                <span class="muted" style="font-style: italic">* Items not reflected in this table have not been involved in any transactions during this timeframe</span>
            </div>
        </div>
    </div>
@endsection