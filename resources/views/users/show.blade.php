@extends('layouts.app')

@section('title', $user->name)

@section('content')
    <div class="row mt-2 mb-5">
        <div class="col-4">
            @if ($user->avatar)
                <img src="{{ asset('storage/avatars/' . $user->avatar) }}" alt="{{ $user->avatar }}" class="w-100 img-thumbnail">
            @else
                <i class="fa-solid fa-image fa-10x d-block text-center"></i>
            @endif
        </div>
        <div class="col-8">
            <h2 class="display-6">{{ $user->name }}</h2>
            @if(Auth::user()->id == $user->id)
                <a href="{{ route('profile.edit') }}" class="text-decoration-none">Edit Profile</a>
            @endif  
        </div>
    </div>

    {{-- Display all posts created by the logged in user --}}
    @if ($user->posts)
        <ul class="list-group">
            @foreach ($user->posts as $post)              
                <li class="list-group-item py-4">
                    <div class="row">
                        <div class="col-3">
                            <img src="{{ asset('storage/images/' .$post->image) }}" alt="{{ $post->image }}" class="w-100 shadow rounded">
                        </div>
                        <div class="col">
                            <a href="{{ route('post.show', $post->id) }}">
                            <h3 class="h4">{{ $post->title }}</h3>
                            </a>
                            <p class="fw-light mb-0">{{ $post->body }}</p>
                        </div>    
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
@endsection