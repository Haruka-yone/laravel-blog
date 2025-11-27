@extends('layouts.app')

@section('title', 'Show Post')

@section('content')
    <div class="mt-2 border border-2 rounded p-4 shadow-sm">
        <h2 class="h4">{{ $post->title }}</h2>
        <a href="{{ route('profile.specificshow', $post->user->id) }}" class="text-decoration-none">
            {{--                             model->user() method inside Post.php --}}
            <h3 class="h6 text-secondary">{{ $post->user->name }}</h3>
        </a>
        <p class="fw-lightmb-0">{{ $post->body }}</p>

        <img src="{{ asset('storage/images/' .$post->image) }}" alt="{{ $post->image }}" class="w-100 shadow rounded">
        {{-- asset() helper is used to access the public directory --}}
    </div>  

    <form action="{{ route('comment.store', $post->id) }}" method="post">
        @csrf
        <div class="input-group mt-5">
            <input type="text" name="comment" id="comment" class="form-control" placeholder="Add a comment...">
            <button type="submit" class="btn btn-outline-secondary btn-sm">Post</button>
        </div>
        @error('comment')
            <div class="text-danger small">{{ $massage }}</div>
        @enderror
    </form>

    {{-- If the post has comments, show the comments --}}
    @if ($post->comments)
        <div class="mt-2 mb-5">
            @foreach ($post->comments as $comment)
                <div class="row p-2">
                    <div class="col-10">
                        <a href="{{ route('profile.specificshow', $comment->user->id) }}" class="text-decoration-none text-secondary">
                            <span class="fw-bold">{{ $comment->user->name }}</span>
                        </a>
                        &nbsp;
                        {{-- non-breaking space --}}
                        <span class="small text-muted">{{ $comment->created_at }}</span>
                        <p class="mb-0">{{ $comment->body }}</p>
                    </div>
                    <div class="col-2 text-end">
                        {{-- Show a Delete button if the Auth user is the owner of the comment --}}
                        @if ($comment->user_id === Auth::user()->id)
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editCommentModal-{{ $comment->id }}">
                                <i class="fa-solid fa-pen"></i>
                            </button>

                            <form action="{{ route('comment.destroy', $comment->id) }}" method="post" class="d-inline">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>    
                        @endif
                    </div>
                </div>

                <div class="modal fade" id="editCommentModal-{{ $comment->id }}" tabindex="-1" aria-labelledby="editCommentModalLabel-{{ $comment->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('comment.update', $comment->id) }}" method="post">
                                @csrf
                                @method('PATCH')
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editCommentModalLabel-{{ $comment->id }}">Edit Comment</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <label for="comment-{{ $comment->id }}" class="form-label">Write here</label>
                                    <textarea name="comment" id="comment-{{ $comment->id }}" class="form-control" rows="3">{{ $comment->body }}</textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    
@endsection