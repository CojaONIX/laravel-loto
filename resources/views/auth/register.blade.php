@extends('layout')

@section('title', 'Register')

@section('content')

<div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
    <div class="card">
        <div class="card-header"><h4>REGISTER<a class="btn btn-outline-primary float-end" href="{{ route('login') }}">or Login</a></h4></div>
        <div class="card-body">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-3">
                    <label for="name">Name <span class="text-danger">* </span></label>
                    <input type="text" class="form-control my-2" name="name" id="name" value="{{ old('name') }}" autofocus>
                    <x-input-error :messages="$errors->get('name')" class="mt-2 text-danger" />
                </div>

                <div class="mb-3">
                    <label for="email">Email <span class="text-danger">* </span></label>
                    <input type="text" class="form-control my-2" name="email" id="email" value="{{ old('email') }}">
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
                </div>

                <div class="mb-3">
                    <label for="password">Password <span class="text-danger">* </span></label>
                    <input type="password" class="form-control my-2" name="password" id="password">
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
                </div>

                <div class="mb-3">
                    <label for="password_confirmation">Confirm Password <span class="text-danger">* </span></label>
                    <input type="password" class="form-control my-2" name="password_confirmation" id="password_confirmation">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-danger" />
                </div>

                <input type="submit" value="Register" class="btn btn-primary form-control my-2">
            </form>
        </div>

        <div class="card-footer">

        </div>

    </div>
</div>

@endsection
