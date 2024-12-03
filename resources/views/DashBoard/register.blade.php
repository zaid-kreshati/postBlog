@extends('DashBoard.layouts.welcome')
@section('content')
@if(isset($error))
    <div class="alert alert-danger">{{ $error }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card">
    <div class="card-header">
        <h3>{{ __('Register') }}</h3>
    </div>
    <div class="card-body">
        <!-- Registration Form -->
        <form id="registrationForm" action="{{ route('verify.two.factor.register.initiate') }}" method="POST">
            @csrf
            <div class="form-group mb-3">
                <input type="text" placeholder="{{ __('Name') }}" id="name" class="form-control" name="name" required autofocus>
                @if ($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
            </div>

            <div class="form-group mb-3">
                <input type="text" placeholder="{{ __('Email') }}" id="email_address" class="form-control" name="email" required autofocus>
                @if ($errors->has('email'))
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
            </div>

            <div class="form-group mb-3">
                <input type="password" placeholder="{{ __('Password') }}" id="password" class="form-control" name="password" required>
                @if ($errors->has('password'))
                    <span class="text-danger">{{ $errors->first('password') }}</span>
                @endif
            </div>

            <div class="form-group mb-3">
                <input type="password" placeholder="{{ __('Confirm Password') }}" id="password_confirmation" class="form-control" name="password_confirmation" required>
                @if ($errors->has('password_confirmation'))
                    <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                @endif
            </div>

            <input hidden id="role" name="role" value="admin">

            <div  class="d-grid mx-auto" id="register-button-container">
                <button type="submit" class="btn4">{{ __('Sign up') }}</button>
            </div>

        </form>

        <!-- Verification Form -->
        <form id="verificationForm" action="{{ route('admin.verify.two.factor.register.verify') }}" method="POST" style="display: none;">
            @csrf
            <div class="form-group mb-3">
                <input type="text" placeholder="{{ __('Enter Verification Code') }}" id="two_factor_code" name="two_factor_code" class="form-control" required>
            </div>

            <div class="d-grid mx-auto" id="verify-button-container">
                <button type="submit" class="btn btn-primary">{{ __('Verify') }}</button>
            </div>

            <div class="d-grid mx-auto" id="resend-button-container">

                <a href="#" id="resend-button">{{ __('Resend Code') }}</a>
            </div>
        </form>

        @if (session('error'))
            <div class="alert alert-danger mt-3">{{ session('error') }}</div>
        @endif
    </div>
</div>

<!-- Loading Spinner -->
<div id="loading-spinner" class="text-center" style="display: none;" position="fixed">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">{{ __('Loading...') }}</span>
    </div>
</div>


<script>
    $(document).ready(function () {
        const spinner = $('#loading-spinner');


        // Handle Registration Submission
        $('#registrationForm').on('submit', function (e) {
            e.preventDefault();

            const form = $(this);
            spinner.show();

            $.ajax({
                url: form.attr('action'),
                method: form.attr('method'),
                data: form.serialize(),
                success: function (response) {
                    spinner.hide();
                    if (response.success) {
                        Swal.fire({
                            title: '{{ __('Registration successful! Enter verification code.') }}',
                            icon: 'success'
                        });
                        // Hide the registration button
                        $('#register-button-container').html('');

                        // Show the verification form
                        $('#verificationForm').fadeIn();

                    } else {
                        console.log(response);
                        Swal.fire({
                            title: 'Error',
                            text: response || '',
                            icon: 'error'
                        });
                    }
                },
                error: function () {
                    spinner.hide();
                    Swal.fire({
                        title: '{{ __('An error occurred. Please try again.') }}',
                        icon: 'error'
                    });
                }
            });
        });

        $('#verificationForm').on('submit',function(e){
            e.preventDefault();
            const form=$(this);
            $.ajax({
                url: form.attr('action'),
                method: form.attr('method'),
                data: form.serialize(),
                success:function(response){
                    if(response.success){
                       window.location.href = "{{ route('DashBoard.home') }}";
                    }
                },
                error:function(response){
                Swal.fire({
                    title: response.error,
                    icon: 'error'
                });
            }
            })
        });

            // Handle Resend Code Button
            $('#resend-button-container').on('click', function () {
                spinner.show();
            $.ajax({
                url: '{{ route('admin.verify.two.factor.resend') }}',
                method: 'GET',
                success: function (response) {
                    spinner.hide();
                    if (response.success) {
                        Swal.fire({
                            title: '{{ __('Code resent successfully! Check your email.') }}',
                            icon: 'success'
                        });
                    } else {
                        Swal.fire({
                            title: '{{ __('Error resending code.') }}',
                            text: '{{ __('Please try again.') }}',
                            icon: 'error'
                        });
                    }
                },
                error: function () {
                    spinner.hide();
                    Swal.fire({
                        title: '{{ __('An error occurred. Please try again.') }}',
                        icon: 'error'
                    });
                }
            });
        });
    });


</script>

@endsection
