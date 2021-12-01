@extends('layouts.layout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-9 col-11">
            <div class="">
                <!-- /.login-logo -->
                <div class="card card-outline card-danger">
                    <div class="card-header text-center">
                        <a href="{{ route('login') }}" class="h4"><b>Madhushanka Micro Credit <br></b>(Pvt) Ltd</a>
                    </div>
                    <div class="card-body">
                        <p class="login-box-msg">Sign in to your Account</p>

                        <form action="{{ route('login') }}" method="post">
                            @csrf

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror

                            <div class="input-group mb-3">
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}"
                                    required autocomplete="email" autofocus placeholder="Email">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-envelope"></span>
                                    </div>
                                </div>
                            </div>

                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror

                            <div class="input-group mb-3">
                                <input type="password" class="form-control" placeholder="Password" name="password"
                                    required autocomplete="current-password">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="social-auth-links text-center mt-2 mb-3">
                                <button type="submit" class="btn btn-block btn-danger">
                                    <i class="fas fa-sign-in-alt"></i> Sign in
                                </button>

                            </div>
                            <!-- /.social-auth-links -->
                        </form>

                        <p class="mb-2">
                            <a href="{{ route('forgotPWD') }}">I forgot my password</a>
                        </p>
                        <p class="mb-2">
                            <a href="{{ route('forgotPWD') }}" class="text-center">Register a new membership</a>
                        </p>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.login-box -->

        </div>

    </div>

</div>

@endsection
