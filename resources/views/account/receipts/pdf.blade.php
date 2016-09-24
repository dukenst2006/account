<html>
    <head>
        <style>
            @page {
                margin-top: 40px;
                margin-bottom: 50px;
            }
            body {
                font-size: 10pt;
            }
            .text-center {
                text-align: center;
            }
            .logo {
                width: 300px;
            }
            .receipt {
                font-size: 30px;
                color: #999;
            }
            .full-width {
                width: 100%;
            }
            .green {
                color: #0aa699;
            }
            table.items {
                border-collapse: collapse;
            }
            table.items th,
            table.items td.item-list {
                border: 1px solid #000;
            }
            table.items th {
                background-color: #e2e2e2;
            }
            table.items th,
            table.items td {
                padding: 6px;
            }
            th.label,
            td.label {
                padding-right: 15px;
            }
        </style>
    </head>
    <body>
        <table class="full-width">
            <tr>
                <td style="width: 60%" class="text-center">
                    <img src="{!! base_path('public/img/logo-blue.png') !!}" class="logo"/>
                </td>
                <td style="width: 40%">
                    <h3 class="receipt text-center">INVOICE</h3>
                    <p style="line-height: 140%">
                        <table>
                            <tr>
                                <td class="label"><strong>DATE:</strong></td>
                                <td>{{ $receipt->created_at->timezone(Auth::user()->settings->timeszone())->format('M j, Y, g:i a') }}</td>
                            </tr>
                            <tr>
                                <td class="label"><strong>INVOICE:</strong></td>
                                <td>#{{ $receipt->id }}</td>
                            </tr>
                            <tr>
                                <td class="label"><strong>STATUS:</strong></td>
                                <td><strong class="green">PAID</strong></td>
                            </tr>
                        </table>
                    </p>
                </td>
            </tr>
        </table>
        <table style="margin-top: 10px">
            <tr>
                <td class="label" style="vertical-align: top"><strong>PAID BY:</strong></td>
                <td>
                    {{ $receipt->user->full_name }}
                    @include('partials.address', [
                        'address' => $receipt->address
                    ])
                </td>
            </tr>
        </table>
        <table class="full-width items" style="margin-top: 30px">
            <tr>
                <th>ITEM</th>
                <th>QUANTITY</th>
                <th>PRICE</th>
            </tr>
            <?php $total = 0; ?>
            @foreach($receipt->items as $item)
                <tr>
                    <td class="item-list">{{ $item->description }}</td>
                    <td class="item-list text-center">{{ number_format($item->quantity) }}</td>
                    <td class="item-list text-center">${{ $item->price }}</td>
                </tr>
                <?php $total += ($item->price * $item->quantity); ?>
            @endforeach
            <tr>
                <td></td>
                <td style="text-align: right">TOTAL</td>
                <td class="item-list text-center">${{ number_format($total, 2) }}</td>
            </tr>
        </table>
    </body>
</html>