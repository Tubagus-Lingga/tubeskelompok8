@extends('layouts.app')

@section('title', 'REGISTER | VIBRANT')

@section('content')
<div class="container py-5 d-flex justify-content-center align-items-center" style="min-height: 60vh;">
    <div style="max-width: 400px; width: 100%;">
        <div class="text-center mb-5">
            <h1 class="fw-bold mb-0" style="letter-spacing: -0.05em;">BECOME A MEMBER</h1>
            <p class="text-muted small text-uppercase">Join the community.</p>
        </div>

        <form method="POST" action="{{ route('register.store') }}">
            @csrf
            
            <div class="mb-3">
                <label class="fw-bold small text-uppercase mb-1">Full Name</label>
                <input type="text" name="name" class="form-control rounded-0 p-2" placeholder="YOUR NAME" required style="border: 1px solid #ddd;">
            </div>

            <div class="mb-3">
                <label class="fw-bold small text-uppercase mb-1">Email Address</label>
                <input type="email" name="email" class="form-control rounded-0 p-2" placeholder="name@example.com" required style="border: 1px solid #ddd;">
            </div>
            
            <div class="mb-3">
                <label class="fw-bold small text-uppercase mb-1">Password</label>
                <input type="password" name="password" class="form-control rounded-0 p-2" placeholder="Min 8 characters" required style="border: 1px solid #ddd;">
            </div>
            
            <div class="mb-4">
                <label class="fw-bold small text-uppercase mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control rounded-0 p-2" placeholder="Repeat password" required style="border: 1px solid #ddd;">
            </div>
            
            <button type="submit" class="btn btn-dark w-100 rounded-0 py-2 fw-bold text-uppercase mb-3">Create Account</button>
            
            <div class="text-center small">
                <span class="text-muted">Already a member?</span> 
                <a href="{{ route('login') }}" class="text-dark fw-bold text-decoration-none">Sign In</a>
            </div>
        </form>
    </div>
</div>
@endsection
