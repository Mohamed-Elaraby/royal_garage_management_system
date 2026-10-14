@extends('admin.layouts.auth.authApp')

@section('title', config('app.name').' Recover Password')

@section('content')
    <div class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <a href="{{ route('adashboard') }}"><b>{{ config('app.name'). " Recover Password" }}</b></a>
            </div>
            <div class="login-box-body">
                {!! Form::open(['route' => 'password.update', 'method' => 'post']) !!}
                <!-- Send Reset Password Token -->
                {!! Form::hidden('token', $token) !!}
                <div class="form-group has-feedback">
                    {!! Form::email('email', $email ?? old('email') , ['class' => ['form-control', $errors->has('email') ? 'is-invalid' : ''] , 'placeholder' => __('trans.email'), 'id' => 'email']) !!}
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    @if ($errors->has('email'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
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
                    {!! Form::password('password',['class' => 'form-control', 'placeholder' => __('trans.password confirmation'), 'autocomplete' => 'on', 'name' => 'password_confirmation']) !!}
                    <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                </div>
                {!! Form::submit(__('trans.reset password'), ['class' => 'form-control btn btn-primary btn-block btn-flat']) !!}
                <div>
                    <a href="{{ route('login') }}" class="text-center">{{ __('trans.sign in') }}</a>
                </div>
                <div>
                    <a href="{{ route('register') }}" class="text-center">{{ __('trans.register a new member') }}</a>
                </div>
                {!! Form::close() !!}
                <br>
                <select id="languagesMenu">
                    <option value="">Choose a language</option>
                    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                        <option href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">{{ $properties['native'] }}</option>
                    @endforeach
                </select>
            </div>
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


