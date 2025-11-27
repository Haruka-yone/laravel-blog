@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
    <form action="{{ route('profile.update') }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <div class="row mt-2 mb-3">
            <div class="col">
                {{-- display avatar --}}
                <div class="text-center">
                    @if ($user->avatar)
                        <img src="{{ asset('storage/avatars/' . $user->avatar) }}" alt="{{ $user->avatar }}" class="w-25 img-thumbnail rounded-circle">
                    @else
                        <i class="fa-solid fa-image fa-10x d-block text-center"></i>
                    @endif
                </div>
                
                <div class="mt-3">
                    <label for="name" class="form-label text-secondary">Update New Avater</label>
                    <input type="file" name="avatar" id="avatar" class="form-control mt-1" aria-describedby="image-info">
                </div>
                
                <div class="form-text" id="image-info">
                    <i class="fa-solid fa-circle-exclamation text-primary"></i> Acceptable formats: <span class="fw-bold">jpeg, jpg, png, gif</span><br>
                    <i class="fa-solid fa-circle-exclamation text-danger"></i> Max file size: <span class="fw-bold">104kB.</span>
                </div>
                @error('avatar')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>      
        </div>
        <div class="mb-3">
            <label for="name" class="form-label text-secondary">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}">
            @error('name')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="email" class="form-label text-secondary">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}">
            @error('email')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="password" class="form-label text-secondary">New Password</label>
            <input type="password" name="password" id="password" class="form-control">
            @error('password')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="text-end">
            <a href="{{ route('profile.show') }}" class="btn btn-secondary px-5">Cancel</a>
            <button type="submit" class="btn btn-warning px-5">Save</button>
        </div>
        
    </form>
@endsection