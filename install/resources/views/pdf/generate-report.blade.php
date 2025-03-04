<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            height: 100%;
            padding: 25px !important;
            margin: 0;
            position: relative;
            font-family: "HelveticaNeue-CondensedBold", "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
            font-size: 12px;
        }

        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }

        @page {
            size: A4;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td,
        th {
            border: 1px solid #cbd5e1;
            padding: 0.5rem;
            text-align: left;
        }

        img {
            max-width: 100%;
            height: auto;
        }

        footer {
            position: absolute;
            top: 20%;
            left: 10px;
            transform: rotate(90deg);
            transform-origin: 3% 0% 0;
            width: 100%;
            height: 40px;
        }

        .footer-content {
            padding: 20px;
            font-size: x-small;
        }

        .clearfix {
            overflow: auto;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        .float-right {
            float: right;
        }

        .float-left {
            float: left;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .w-50 {
            width: 50%;
        }

        .w-25 {
            width: 25%;
        }

        .w-20 {
            width: 20%;
        }

        .w-12 {
            width: 12%;
        }

        .border-bottom {
            border-bottom: 1px solid rgb(90, 90, 90);
            width: 100%;
            font-style: italic
        }

        .p-0 {
            padding: 0
        }

        .m-0 {
            margin: 0
        }

        .ml-2 {
            margin-left: 1em
        }

        .ml-1 {
            margin-left: .5em
        }

        .mr-4 {
            margin-right: 2em
        }

        .mb-4 {
            margin-bottom: 2em
        }

        .mt-1 {
            margin-top: .5em
        }

        .pt-1 {
            padding-top: .5em
        }

        .pt-2 {
            padding-top: 1em
        }

        .pt-3 {
            padding-top: 1.5em
        }

        .pt-4 {
            padding-top: 2em
        }

        .pt-5 {
            padding-top: 2.5em
        }

        .pb-2 {
            padding-bottom: 1em
        }

        .pb-3 {
            padding-bottom: 1.5em
        }

        .pdf-logo {
            width: auto !important;
            height: 60px !important;
        }

        .overflow-hidden {
            overflow: hidden;
        }
    </style>
    <title>{{ $store->name }} income reports</title>
</head>

<body>
    <div class="row mb-4" style="height: 50px">

        @if (app()->environment('local'))
            <img class="float-left mr-4 pdf-logo" src="./images/logo.png" alt=""/>
        @else
            <img class="float-left mr-4 pdf-logo" src="{{ $store->logoPath ?? './images/logo.png'}}" alt=""/>
        @endif
        <p class="m-0 float-left" style="color:#000 !important"><strong>{{ $store->name ?? 'LaundryMart' }}</strong>
            <br>
            Reporte Print Date: {{ now()->format('M d, Y') }}
            <br>
            Reporte printer By: {{ auth()->user()->name }}
            <br>
            <small>Contact: {{ auth()->user()->mobile }}</small>
        </p>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">Delivery Date</th>
                <th scope="col">Order By</th>
                <th scope="col">Quantity</th>
                <th scope="col">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($store->orders as $order)
                <tr>
                    <td>
                        {{ Carbon\Carbon::parse($order->delivery_date)->format('M d, Y') }}
                    </td>
                    <td>{{ $order->customer->user->name }}</td>
                    <td>{{ $order->products->sum('pivot.quantity') }} Pieces</td>
                    <td>{{ currencyPosition($order->payable_amount) }}</td>

                </tr>
            @empty
                <tr>
                    <td colspan="4">Oops! order report not found</td>
                </tr>
            @endforelse
            <tr>
                <td colspan="3" class="text-right">Total order</td>
                <td>{{ currencyPosition($store->orders->sum('payable_amount')) }}</td>
            </tr>
        </tbody>
    </table>

</body>

</html>
