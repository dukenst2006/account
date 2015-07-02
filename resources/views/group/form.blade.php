
@section('after-styles-end')
    <style type="text/css">
        #name-suffix > input {
            border-right: none;
        }
        #name-suffix > span {
            background-color: #fff;
        }
    </style>
@endsection

<div class="row">
    <div class="col-md-8">
        <label class="form-label">Name</label>
        <span class="help"></span>
        <div class="controls p-b-20">
            <div class="input-group" id="name-suffix">
                {!! Form::text('name', null, ['class' => 'form-control', 'maxlength' => 128]) !!}
                <span class="input-group-addon"></span>
            </div>
        </div>
    </div>
</div>
<h4>Mailing <span class="semi-bold">Address</span></h4>
<div class="row">
    <div class="col-md-12">
        <label class="form-label"></label>
        <span class="help"></span>
        <div class="controls p-b-20">
            <label>
                {!! Form::checkbox('user_owned_address', 1, ['class' => 'form-control']) !!}
                Use an address from my Address Book
            </label>
        </div>
    </div>
</div>
<div class="row" id="myOwnAddresses">
    <div class="col-md-8">
        <div class="controls p-b-20">
            <label>{!! Form::selectAddress('address_id', null, ['class' => 'form-control']) !!}</label>
        </div>
    </div>
    <div class="col-md-4 p-t-5">
        <a href="/account/address/create" class="btn btn-white btn-small">New Address</a>
    </div>
</div>
<div id="addressForm">
    @include('account.address.form')
</div>