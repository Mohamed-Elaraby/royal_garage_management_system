@extends('admin.layouts.auth.authApp')

@section('title', config('app.name').' Email Verification')

@section('content')


    <div class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <p>{{ __('trans.verify Your Email Address') }}</p>
            </div>
            <!-- /.login-logo -->
            <div class="login-box-body">
                @if (session('resent'))
                    <div class="alert alert-success" role="alert">
                        {{ __('trans.a fresh verification link has been sent to your email address.') }}
                    </div>
                @endif

                {{ __('trans.before proceeding, please check your email for a verification link.') }}
                {{ __('trans.if you did not receive the email') }}, <a href="{{ route('verification.resend') }}">{{ __('trans.click here to request another') }}</a>.
                <br>
                <select id="languagesMenu">
                    <option value="">Choose a language</option>
                    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                        <option href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">{{ $properties['native'] }}</option>
                    @endforeach
                </select>

            </div>
            <!-- /.login-box-body -->
        </div>
    </div>


{{--<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Verify Your Email Address') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif

                    {{ __('Before proceeding, please check your email for a verification link.') }}
                    {{ __('If you did not receive the email') }}, <a href="{{ route('verification.resend') }}">{{ __('click here to request another') }}</a>.
                </div>
            </div>
        </div>
    </div>
</div>--}}
@endsection
@push('scripts')
    <!-- Generate languages Url Script -->
    <script>
        $(document).on('change', '#languagesMenu', function () {
            location.href = $(this).children('option:selected').attr('href');
        })
    </script>
@endpush
