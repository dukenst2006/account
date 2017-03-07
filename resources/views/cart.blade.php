@extends('layouts.master')

@section('title', 'Payment')

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <h4>Payment</h4>
                <div class="grid simple">
                    <div class="grid-body no-border p-b-10">
                        @include('partials.messages')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row table-header">
                                    <div class="col-md-6 col-sm-6 col-xs-6">Item</div>
                                    <div class="col-md-2 col-sm-2 col-xs-2 text-center">Qty</div>
                                    <div class="col-md-2 col-sm-2 col-xs-2 text-center">Price</div>
                                    <div class="col-md-2 col-sm-2 col-xs-2 text-center">Total</div>
                                </div>
                                @foreach (Cart::items()->get() as $item)
                                <div class="row table-row">
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        {{ $item->name() }}
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-2 text-center">
                                        {{ number_format($item->quantity) }}
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-2 text-center">
                                        ${{ $item->price }}
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-2 text-center">
                                        ${{ number_format($item->price * $item->quantity, 2) }}
                                    </div>
                                </div>
                                @endforeach
                                <div class="row">
                                    <div class="col-md-8 col-sm-8 col-xs-8"></div>
                                    <div class="col-md-2 col-sm-2 col-xs-2 p-t-10 b-t b-grey text-right">
                                        <strong>Total:</strong>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-2 p-t-10 b-t b-grey text-center">
                                        ${{ number_format(Cart::total(), 2) }}
                                    </div>
                                </div>
                                {!! Form::open(['class' => 'form-horizontal', 'role' => 'form', 'id' => 'payment-form']) !!}
                                @if(app()->environment('testing'))
                                    {!! Form::hidden('stripeToken', uniqid()) !!}}
                                @endif
                                <h4 class="m-t-20">Payment Method</h4>
                                <p>We accept Visa, MasterCard, American Express, JCB, Discover, and Diners Club.  Checks and other forms of payment are not accepted.</p>
                                @if(Auth::user() != null)
                                    <p>Receipts are available at any time in the "My Receipts" section of your account.</p>
                                @endif
                                <div class="alert alert-danger payment-errors" style="display: none;"></div>
                                <div class="row m-t-10">
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        {!! Form::text('cardHolder', old('cardHolder', (Auth::user() != null ? Auth::user()->full_name : '')), ['placeholder' => "Cardholder's name", 'class' => 'form-control']) !!}
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        {!! Form::number('number', old('number'), ['placeholder' => 'Credit card number', 'class' => 'form-control', 'pattern' => "[0-9]{13,16}", 'data-stripe' => 'number', 'id' => 'credit-card-number', 'autofocus']) !!}
                                    </div>
                                </div>
                                <div class="row m-t-10">
                                    <div class="col-md-2 col-sm-4 col-xs-4">
                                        {!! Form::text('cvc', old('cvc'), ['placeholder' => 'CVC', 'class' => 'form-control', 'pattern' => "[0-9]*", 'data-stripe' => 'cvc']) !!}
                                    </div>
                                    <div class="col-md-5 col-sm-4 col-xs-4">
                                        {!! Form::selectMonthNumeric('expMonth', old('expMonth'), ['class' => 'form-control', 'data-stripe' => 'exp-month']) !!}
                                    </div>
                                    <div class="col-md-5 col-sm-4 col-xs-4">
                                        {!! Form::selectYear('expYear', date('Y'), date('Y')+5, old('expYear'), ['class' => 'form-control', 'data-stripe' => 'exp-year'], false) !!}
                                    </div>
                                </div>
                                @if(Session::isNotMyself())
                                <div class="row">
                                    <div class="alert alert-info m-t-20">
                                        <p class="semi-bold">Payment Reference Number</p>
                                        <p>As an administrator you have the option of accepting alternate payment methods manually.  To bypass paying by credit card you can provide a payment reference number.  No email receipts will be sent.</p>
                                        <p class="m-t-25">
                                            {!! Form::text('payment_reference_number', old('payment_reference_number'), ['placeholder' => 'Check #, etc.', 'class' => 'form-control']) !!}
                                        </p>
                                    </div>
                                </div>
                                @endif
                                <div class="row m-t-20">
                                    <div class="col-md-12 text-center">
                                        <button class="btn btn-primary btn-cons" type="submit">Submit</button>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@includeStripeJs()
@includeJs(elixir('assets/js/payment.js'))