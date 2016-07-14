@extends('layouts.frontend_master')

@section('title', 'Account Registration')

@includeJs(/assets/plugins/jquery-1.8.3.min.js)
@includeJs(elixir('js/forms.js'))

@section('content')
    @include('partials.logo-header')
    <div class="p-t-40">
        <div class="grid simple">
            <div class="col-md-8 col-md-offset-2 grid-body no-border">
                <br/>
                <div class="row">
                    <div class="col-md-8">
                        <div class="page-title">
                            <h3>Account <span class="semi-bold">Information</span></h3>
                            <p>Keep it up, you're almost there!</p>
                        </div>
                    </div>
                    <div class="col-md-4"></div>
                </div>
                <div class="row">
                    <div class="col-md-12"> <br>
                        @include('partials.messages')
                        {!! Form::open(['method' => 'post', 'class' => 'form-horizontal', 'role' => 'form']) !!}
                        <div class="row form-group">
                            <div class="col-md-12">
                                <label class="form-label">Name <span class="required">*</span></label>
                                <span class="help"></span>
                                <div class="row">
                                    <div class="col-md-6">
                                        {!! Form::text('first_name', null, ['class' => 'form-control', 'placeholder' => 'First', 'maxlength' => 64, 'autofocus']) !!}
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => 'Last', 'maxlength' => 64]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label class="form-label">Phone <span class="required">*</span></label>
                                <span class="help"></span>
                                <div class="controls">
                                    {!! Form::text('phone', null, ['class' => 'form-control', 'id' => 'phone', 'maxlength' => 10]) !!}<br/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Gender <span class="required">*</span></label>
                                <span class="help"></span>
                                <div class="controls">
                                    @include('partials.forms.gender')
                                </div>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label class="form-label">Timezone <span class="required">*</span></label>
                                <span class="help"></span>
                                <div class="controls p-b-10">
                                    {!! Form::selectTimezone('timezone', null, ['class' => 'form-control', 'maxlength' => 255]) !!}<br/>
                                </div>
                            </div>
                        </div>
                        <h4>Home <span class="semi-bold">Address</span></h4>
                        @include('account.address.form', [
                            'required' => true
                        ])
                        <div class="p-b-20"></div>
                        <h4>Brief <span class="semi-bold">Survey</span></h4>
                        <div class="row form-group">
                            @foreach($questions as $question)
                            <div class="col-md-6">
                                <label class="form-label">{{ $question->question }}</label>
                                <div class="help">Check all that apply</div>
                                <div class="controls p-b-20">
                                    @foreach($question->answers as $answer)
                                        @if($answer->answer == 'Other')
                                            <div class="checkbox check-primary">
                                                {!! Form::checkbox('answer['.$question->id.']['.$answer->id.']', '1', old('answer['.$question->id.']['.$answer->id.']'), ['id' => 'question-'.$question->id.'-other']) !!}
                                                <label for="question-{{ $question->id }}-other">Other</label>
                                            </div>
                                            <div class="m-l-10 m-t-5">
                                                {!! Form::text('other['.$question->id.']', old('other['.$question->id.']'), ['class' => 'form-control', 'maxlength' => 255, 'placeholder' => 'Please elaborate']) !!}
                                            </div>
                                        @else
                                            <div class="checkbox check-primary">
                                                {!! Form::checkbox('answer['.$question->id.']['.$answer->id.']', '1', old('answer['.$question->id.']['.$answer->id.']'), ['id' => 'answer-'.$answer->id]) !!}
                                                <label for="answer-{{ $answer->id }}">{{ $answer->answer }}</label>
                                            </div><br/>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center p-t-20">
                                <button class="btn btn-primary btn-cons" type="submit">Save</button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection