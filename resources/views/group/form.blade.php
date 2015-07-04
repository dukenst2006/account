
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
    <div class="col-md-10">
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