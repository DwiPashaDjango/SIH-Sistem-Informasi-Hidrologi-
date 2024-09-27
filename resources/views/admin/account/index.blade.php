@extends('layouts.admin')

@section('title')
    Change Password
@endsection

@push('css')
    
@endpush

@section('content')
    @if (session()->has('success'))
        <div class="alert alert-primary">
            {{session()->get('success')}}
        </div>
    @endif
    @if (session()->has('message'))
        <div class="alert alert-danger">
            {{session()->get('message')}}
        </div>
    @endif
   <div class="card">
        <div class="card-body">
            <form action="{{route('account.changePassword')}}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label for="">Password Lama</label>
                    <input type="password" name="old_password" id="old_password" class="form-control @error('old_password') is-invalid @enderror">
                    @error('old_password')
                        <span class="invalid-feedback">
                            {{$message}}
                        </span>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="">Password Baru</label>
                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
                    @error('password')
                        <span class="invalid-feedback">
                            {{$message}}
                        </span>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror">
                    @error('password_confirmation')
                        <span class="invalid-feedback">
                            {{$message}}
                        </span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100">Change Password</button>
            </form>
        </div>
    </div>
@endsection

@push('js')
    
@endpush