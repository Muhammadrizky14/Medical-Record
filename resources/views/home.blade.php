@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="text-center">
                        <h4>Welcome to Medical Records System</h4>
                        <p class="text-muted">You are logged in as: <strong>{{ Auth::user()->name }}</strong></p>
                        
                        @if(Auth::user()->isAdmin())
                            <div class="mt-4">
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-lg">
                                    <i class="fas fa-tachometer-alt"></i> Go to Admin Dashboard
                                </a>
                            </div>
                        @elseif(Auth::user()->isDoctor())
                            <div class="mt-4">
                                <a href="{{ route('doctor.dashboard') }}" class="btn btn-primary btn-lg">
                                    <i class="fas fa-stethoscope"></i> Go to Doctor Dashboard
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
