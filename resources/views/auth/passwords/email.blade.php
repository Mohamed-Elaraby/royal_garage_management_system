@extends('admin.layouts.auth.authApp')

@section('title', config('app.name').' Reset Password')

@section('content')
    <div class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <a href="{{ route('dashboard') }}"><b>{{ config('app.name'). " Reset Password" }}</b></a>
            </div>
            <!-- /.login-logo -->
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="login-box-body">
                    <p class="login-box-msg">{{ __('trans.You forgot your password? Here you can easily retrieve a new password.') }}</p>
                    {!! Form::open(['route' => 'password.email', 'method' => 'post']) !!}
                        <div class="form-group has-feedback">
                            {!! Form::email('email', old('email'), ['class' => ['form-control', $errors->has('email') ? 'is-invalid' : ''] , 'placeholder' => __('trans.email')]) !!}
                            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                <strong class="text-danger">{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
                        </div>
                    {!! Form::submit(__('trans.send Password Reset Link'), ['class' => 'form-control btn btn-primary btn-block btn-flat']) !!}
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
