@php
    $pageType = __('trans.edit');
    $pageItem = __('trans.user');
    $profile_picture_path = $user -> image_name == 'default.png' ? 'storage' .DIRECTORY_SEPARATOR. 'default.png' : $user -> profile_picture_path
@endphp
@extends('admin.layouts.app')

@section('title', $pageType.' '.$pageItem)

@section('content')
    <div class="row">
        <div class="col-xs-6 center-block" style="float: none">
            <div class="card card-primary mt-5">
                <div class="error_messages">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
                <div class="card-header">
                    <h3 class="text-center"><i class="fa fa-user"></i> {{ $pageType .' '. $pageItem }}</h3>
                </div>
                <div class="card-body">
                    {!! Form::open(['route' => ['admin.users.update', $user -> id], 'method' => 'put', 'files'=> true]) !!}
                    <div class="form-group">
                        {!! Form::label('name', __('trans.username'), ['class' => 'control-label']) !!}
                        {!! Form::text('name', $user -> name, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('email', __('trans.email'), ['class' => 'control-label']) !!}
                        {!! Form::email('email', $user -> email, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
{{--                        {{ dd($user->job_title_id) }}--}}
{{--                        {{ dd($user-> role_id) }}--}}
                        {!! Form::label('job_title_id', __('trans.job title'), ['class' => 'control-label']) !!}
                        {!! Form::select('job_title_id', $jobTitle, array_key_exists($user->job_title_id, $jobTitle)?$user->job_title_id:'', ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('password', __('trans.password'), ['class' => 'control-label']) !!}
                        {!! Form::password('password', ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('password_confirmation', __('trans.password confirmation'), ['class' => 'control-label']) !!}
                        {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('role_id', __('trans.role'), ['class' => 'control-label']) !!}
                        {!! Form::select('role_id', [''=> '-- أختر واحد --']+$roles , array_key_exists($user -> role_id, $roles)?$user -> role_id:'' , ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('branch_id', __('trans.branch'), ['class' => 'control-label']) !!}
                        {!! Form::select('branch_id', [''=> '-- أختر واحد --']+$branches , array_key_exists($user -> branch_id, $branches)?$user -> branch_id:'' , ['class' => 'form-control']) !!}
                        <span id="branch_error" style="color: red; display: none"></span>
                    </div>
                    <div class="form-group">
                        {!! Form::label('profile_picture', __('trans.profile picture'), ['class' => 'control-label']) !!}
                        {!! Form::file('profile_picture', ['class' => 'form-control', 'id' => 'myImg']) !!}
                    </div>
                    <div class="form-group">
                        <img class="img-thumbnail" id="imagePreview" src="{{ asset($profile_picture_path) }}" height="100px" width="100px">
                    </div>
                    <div class="form-group">
                        {!! Form::submit($pageType, ['class' => 'form-control btn btn-primary']) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    @include('admin.includes.imagePreviewJQueryCode')

    <script>
        $(document).ready(function () {
            let branch_id = $('#branch_id');
            let role_not_required_branch = ['super_owner', 'owner', 'general_manager', 'general_observer', 'purchase_order_entry', 'check_entry', 'deputy_manager'];

            function checkBranch() {
                if ($('#branch_id').val() == '')
                {
                    $('#branch_error').css('display', 'inline').text('برجاء اختيار الفرع');
                }else
                {
                    $('#branch_error').css('display', 'none').text();
                }
            }

            $('#branch_id').on('change', function () {
                checkBranch();
            });

            checkRole();
            function checkRole() {
                let role_selected = $('#role_id').find('option:selected').text();

                if (role_not_required_branch.includes(role_selected))
                {
                    branch_id.prop('disabled', true);
                }else
                {
                    branch_id.prop('disabled', false);
                }
            }

            $('#role_id').on('change', function () {

                let role_selected = $(this).find('option:selected').text();
                if (role_not_required_branch.includes(role_selected) || $(this).val() == '') // filed is disabled
                {
                    branch_id.prop('disabled', true);
                    branch_id.prop('readonly', true);
                    $('#branch_error').css('display', 'none').text();
                }else // filed is enabled
                {
                    checkBranch();
                    branch_id.prop('disabled', false);
                    branch_id.prop('readonly', false);
                }
            })

            $('input[type="submit"]').on('click', function (e) {
                if (!branch_id.prop('disabled')) // filed is enabled
                {
                    if ($('#branch_id').val() == '')
                    {
                        e.preventDefault();
                        $('#branch_error').css('display', 'inline').text('برجاء اختيار الفرع');
                    }
                }else // filed is disabled
                {
                    $('#branch_error').css('display', 'none').text();
                }
            })
        })
    </script>
@endpush
