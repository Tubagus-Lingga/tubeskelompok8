@extends('layouts.app')

@section('title', 'LOGIN | VIBRANT')

@section('content')
<div class="container py-5 d-flex justify-content-center align-items-center" style="min-height: 60vh;">
    <div style="max-width: 400px; width: 100%;">
        <div class="text-center mb-5">
            <h1 class="fw-bold mb-0" style="letter-spacing: -0.05em;">LOGIN</h1>
            <p class="text-muted small text-uppercase">Welcome back.</p>
        </div>

        @if (session('success') || session('status'))
            <div class="alert alert-dark rounded-0 small mb-4">{{ session('success') ?? session('status') }}</div>
        @endif
        
        @if ($errors->any())
            <div class="alert alert-danger rounded-0 small mb-4">
                Invalid credentials. Please try again.
            </div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}">
            @csrf
            <div class="mb-3">
                <label class="fw-bold small text-uppercase mb-1">Email Address</label>
                <input type="email" name="email" class="form-control rounded-0 p-2" placeholder="name@example.com" value="{{ old('email') }}" required autofocus style="border: 1px solid #ddd;">
            </div>
            
            <div class="mb-4">
                <label class="fw-bold small text-uppercase mb-1">Password</label>
                <input type="password" name="password" class="form-control rounded-0 p-2" placeholder="••••••••" required style="border: 1px solid #ddd;">
            </div>
            
            <div class="mb-4 d-flex justify-content-between align-items-center small">
                <div class="form-check">
                    <input class="form-check-input rounded-0 bg-dark border-dark" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
            </div>
            
            <button type="submit" class="btn btn-dark w-100 rounded-0 py-2 fw-bold text-uppercase mb-3">Sign In</button>
            
            <div class="text-center small">
                <span class="text-muted">Don't have an account?</span> 
                <a href="{{ route('register') }}" class="text-dark fw-bold text-decoration-none">Sign Up</a>
            </div>
        </form>
    </div>
</div>
@endsection
