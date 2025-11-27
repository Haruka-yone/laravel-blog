@extends('layouts.app')

@section('title', 'Edit Post')

@section('content')
<form action="{{ route('post.update', $post->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PATCH')  

        <div class="mb-3">
            <label for="title" class="form-label text-secondary">Title</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $post->title) }}" placeholder="Enter title here" autofocus>
            @error('title')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="body" class="form-label text-secondary">Body</label>
            <textarea name="body" id="body" row="5" class="form-control" placeholder="Start writing...">{{ old('body', $post->body) }}</textarea>
            @error('body')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="row mb-3">
            <div class="col-6">
                <label for="image" class="form-label text-secondary">Image</label>

                {{-- display image --}}
                <img src="{{ asset('storage/images/' .$post->image) }}" alt="{{ $post->image }}" class="w-100 img-thumbnail">
               
                <input type="file" name="image" id="image" class="form-control mt-1" aria-describedby="image-info">
                <div class="form-text" id="image-info">
                    Acceptable formats are jpeg, jpg, png, gif only.<br>
                    Maximum file size is 1048kB.
                </div>
                @error('image')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>      
        </div>

        <button type="submit" class="btn btn-warning px-5">Save</button>
    </form>
@endsection