@extends('layouts.auth')

@section('content')
    <div class="container">
        @include('inc.messages')
        <h1>Category image</h1>
        {!! Form::open(['route' => 'category.image.store', 'method' => 'post', 'enctype'=>'multipart/form-data']) !!}
            <div class="form-group">
                {!! Form::label('category_id', 'Category id: ', ['class' => 'control-label']) !!}
                <select name="category_id">
                    <option value="{{ null }}" disabled selected>Category ID</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                {!! Form::label('category_image', 'Category image upload: ', ['class' => 'control-label']) !!}
                {!! Form::file('category_image', ['class'=>'form-control', 'required']) !!}
            </div>
            <button type="submit">submit</button>
        {!! Form::close() !!}
    </div>
@endsection
