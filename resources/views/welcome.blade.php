@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Medical Records System</div>
                <div class="card-body text-center">
                    <h1>Welcome to Medical Records System</h1>
                    <p class="lead">Manage your medical records efficiently</p>
                    
                    @guest
                        <div class="mt-4">
                            <a href="{{ route('login') }}" class="btn btn-primary me-3">Login</a>
                            <a href="{{ route('register') }}" class="btn btn-outline-primary">Register</a>
                        </div>
                    @else
                        <div class="mt-4">
                            <a href="{{ route('home') }}" class="btn btn-primary">Go to Dashboard</a>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
