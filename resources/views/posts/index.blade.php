@extends('layouts.app')

@section('title', 'Home')

@section('content')
    @forelse ($all_posts as $post)
        <div class="row mt-2 border border-2 rounded p-4">
            <div class="col-3">
                <img src="{{ asset('storage/images/' .$post->image) }}" alt="{{ $post->image }}" class="w-75 shadow rounded">
            </div>
            <div class="col">
                <a href="{{ route('post.show', $post->id) }}">
                <h2 class="h4">{{ $post->title }}</h2>
                </a>
                {{--                             model->user() method inside Post.php --}}
                <h3 class="h6 text-secondary">
                    <a href="{{ route('profile.specificshow', $post->user->id) }}" class="text-decoration-none text-secondary">
                        <i class="fa-solid fa-user"></i> {{ $post->user->name }} |
                    </a>
                    <i class="fa-solid fa-calendar-days"></i> <span class="small text-muted">{{ $post->created_at }}</span>
                </h3>
                <p class="fw-lightmb-0">{{ Str::limit($post->body, 60, '...') }}
                    @if(mb_strlen($post->body) > 60)
                        <a href="{{ route('post.show', $post->id) }}" class="text-decoration-none">See more</a>
                    @endif
                </p>
                
                {{-- Action Button --}}
                {{-- logged in user == user_id column(owner) --}}
                @if(Auth::user()->id == $post->user_id)
                    <div class="mt-2 text-end">
                        <a href="{{ route('post.edit', $post->id) }}" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-pen"></i> Edit
                        </a>

                        <form action="{{ route('post.destroy', $post->id) }}" method="post" class="d-inline">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fa-solid fa-trash-can"></i> Delete
                            </button>
                        </form>
                    </div>
                @endif
            </div>      
        </div>      
    @empty  
        <div class="text-center" style="margin-top: 100px">
            <h2 class="text-secondary">No Posta Yet</h2>
            <a href="{{ route('post.create') }}" class="text-decoration-none">Create a new post</a>
        </div>
    @endforelse
        <div class="mt-3">{{ $all_posts->links() }}</div>
@endsection

