@extends('admin.layouts.auth.authApp')

@section('title', config('app.name').' Register')

@section('content')
    <div class="hold-transition register-page">
        <div class="register-box">
            <div class="register-logo">
                <a href="{{ route('dashboard') }}"><b>{{ config('app.name'). " Register" }}</b></a>
            </div>

            <div class="register-box-body">
                <p class="login-box-msg">{{ __('trans.register a new member') }}</p>

                {!! Form::open(['route' => 'register', 'method' => 'post']) !!}
                <div class="form-group has-feedback">
                    {!! Form::text('name', old('name'), ['class' => ['form-control', $errors->has('name') ? 'is-invalid' : ''] , 'placeholder' => __('trans.username')]) !!}
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    @if ($errors->has('name'))
                        <span class="invalid-feedback" role="alert">
                    <strong class="text-danger">{{ $errors->first('name') }}</strong>
                </span>
                    @endif
                </div>
                <div class="form-group has-feedback">
                    {!! Form::email('email', old('email'), ['class' => ['form-control', $errors->has('email') ? 'is-invalid' : ''] , 'placeholder' => __('trans.email')]) !!}
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    @if ($errors->has('email'))
                        <span class="invalid-feedback" role="alert">
                            <strong class="text-danger">{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group has-feedback">
                    {!! Form::text('phone', old('phone'), ['class' => ['form-control', $errors->has('phone') ? 'is-invalid' : ''] , 'placeholder' => __('trans.phone')]) !!}
                    <span class="glyphicon glyphicon-phone form-control-feedback"></span>
                    @if ($errors->has('phone'))
                        <span class="invalid-feedback" role="alert">
                            <strong class="text-danger">{{ $errors->first('phone') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group has-feedback">
                    {!! Form::password('password', ['class' => ['form-control', $errors->has('password') ? 'is-invalid' : ''], 'placeholder' => __('trans.password'), 'autocomplete' => 'on']) !!}
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    @if ($errors->has('password'))
                        <span class="invalid-feedback" role="alert">
                    <strong class="text-danger">{{ $errors->first('password') }}</strong>
                </span>
                    @endif
                </div>
                <div class="form-group has-feedback">
                    {!! Form::password('password',['class' => ['form-control', $errors->has('password_confirmation') ? 'is-invalid' : ''], 'placeholder' => __('trans.password confirmation'), 'autocomplete' => 'on', 'name' => 'password_confirmation']) !!}
                    <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                    @if ($errors->has('password_confirmation'))
                        <span class="invalid-feedback" role="alert">
                    <strong class="text-danger">{{ $errors->first('password_confirmation') }}</strong>
                </span>
                    @endif
                </div>
                <div class="row">
                    <div class="col-xs-8">
                        `<label>
                            {!! Form::checkbox('terms', 1, null,  ['id' => 'terms', old('terms') ? 'checked' : '']) !!}
                            {{ __('trans.i agree to the') }} <a href="#">{{ __('trans.terms') }}</a>
                        </label>`
                    </div>
                    <div class="col-xs-4">
                        {!! Form::submit(__('trans.register'), ['class' => 'form-control btn btn-primary btn-block btn-flat']) !!}
                    </div>
                </div>
                <div class="social-auth-links text-center">
                    <p>- {{ __('trans.or') }} -</p>
                    <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> {{ __('trans.sign in using Facebook') }}</a>
                    <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> {{ __('trans.sign in using Google') }}</a>
                </div>
                <a href="{{ route('login') }}" class="text-center">{{ __('trans.i already have a membership') }}</a>
                {!! Form::close() !!}
                <select id="languagesMenu">
                    <option value="">Choose a language</option>
                    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                        <option href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">{{ $properties['native'] }}</option>
                    @endforeach
                </select>
            </div>
            <!-- /.form-box -->
        </div>
    </div>
@stop

@push('scripts')
    <!-- Generate languages Url Script -->
    <script>
        $(document).on('change', '#languagesMenu', function () {
            location.href = $(this).children('option:selected').attr('href');
        })
    </script>
@endpush
