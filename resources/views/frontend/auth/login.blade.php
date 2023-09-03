@extends('frontend.layouts.app')

@section('content')
    <section class=" section-10 pt-3">
        <div class="container">
            <div class="login-form">
                @if (Session::has('success'))
                    <div class="alert alert-success">
                        {{ session::get('success') }}
                    </div>
                @endif

                @if (Session::has('errors'))
                    <div class="alert alert-danger">
                        {{ session::get('errors') }}
                    </div>
                @endif
                <form action="{{ route('login.authenticate') }}" method="post">
                    @csrf
                    <h4 class="modal-title">Login to Your Account</h4>
                    <div class="form-group">
                        <input type="text" name="email" id="email"
                            class="form-control @error('email') is-invalid @enderror" placeholder="Email"
                            value="{{ old('email') }}" required="required">
                        @error('email')
                            <p class="invalid-feedback"> {{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" id="password"
                            class="form-control @error('password') is-invalid @enderror" placeholder="Password"
                            value="{{ old('password') }}" required="required">
                        @error('password')
                            <p class="invalid-feedback"> {{ $message }}</p>
                        @enderror
                    </div>
                    <input type="submit" class="btn btn-dark btn-block btn-lg" value="Login">
                </form>
                <div class="text-center small">Don't have an account? <a href="{{ route('register') }}">Sign up</a>
                </div>
            </div>
        </div>
    </section>
@endsection
