@extends('layout')

@section('title', 'Login')

@section('content')

<div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
    <div class="card">
        <div class="card-header">
            <h4>LOGIN<a class="btn btn-outline-primary float-end" href="{{ route('register') }}">or Register</a></h4>
        </div>

        <div class="card-body">
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="email">Email <span class="text-danger">* </span></label>
                    <input type="text" class="form-control mt-2" name="email" id="email" value="{{ old('email') }}" autofocus>
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
                </div>


                <div class="mb-3">
                    <label for="password">Password <span class="text-danger">* </span></label>
                    <input type="password" class="form-control mt-2" name="password" id="password">
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
                </div>

                <!-- Remember Me -->
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                    <label class="form-check-label" for="remember_me">Remember me</label>
                </div>

                <input type="submit" value="Login" class="btn btn-primary form-control my-2">

            </form>
        </div>

        <div class="card-footer">
            <!-- Forgot password -->
            @if (Route::has('password.request'))
                <a class="" href="{{ route('password.request') }}">Forgot your password?</a>
            @endif
        </div>
    </div>
</div>

@endsection
