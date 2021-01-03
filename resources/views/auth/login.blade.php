@extends('layouts.auth')
@section('bodyclass') authentication-bg authentication-bg-pattern @endsection
@section('title')
    Login
@endsection
@section('content')
<div class="account-pages mt-5 mb-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card bg-pattern">

                    <div class="card-body p-4">

                        <form action="{{route('login')}}" method="POST" novalidate>
                            @csrf

                            <div class="form-group mb-3">
                                <label for="username">{{ __('Email / Username') }}</label>
                                <input class="form-control @error('username') is-invalid @enderror" name="username" type="text" 
                                    id="username" required=""
                                    value="{{ old('username')}}"
                                    placeholder="Masukkan email / username" />

                                    @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                            </div>

                            <div class="form-group mb-3">
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-muted float-right">
                                        <small>{{ __('Lupa Password Anda?') }}</small>
                                    </a>
                                @endif
                                <label for="password">{{ __('Password') }}</label>
                                <div class="input-group input-group-merge @error('password') is-invalid @enderror">
                                    <input class="form-control @error('password') is-invalid @enderror" name="password" type="password" required=""
                                        id="password" placeholder="Masukkan password" />
                                        <div class="input-group-append" data-password="false">
                                        <div class="input-group-text">
                                            <span class="password-eye"></span>
                                        </div>
                                    </div>
                                </div>
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="remember" id="checkbox-signin"  {{ old('remember') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="checkbox-signin">{{ __('Remember Me') }}</label>
                                </div>
                            </div>

                            <div class="form-group mb-0 text-center">
                                <button class="btn btn-primary btn-block" type="submit"> Log In </button>
                            </div>

                        </form>

                    </div> <!-- end card-body -->
                </div>
                <!-- end card -->

                <div class="row mt-3">
                    <div class="col-12 text-center">
                        @if (Route::has('password.request'))
                            <p>
                                <a href="{{ route('password.request') }}" class="text-white-50 ml-1">
                                    {{ __('Lupa Password Anda?') }}
                                </a>
                            </p>
                        @endif
                        @if (Route::has('register'))
                            <p class="text-white-50">
                                Don't have an account?
                                <a href="{{route('register')}}" class="text-white ml-1"><b>Sign Up</b></a>
                            </p>
                        @endif
                    </div> <!-- end col -->
                </div>
                <!-- end row -->

            </div> <!-- end col -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</div>
<!-- end page -->
@endsection
