@extends('layouts.auth')

@section('content')
    <div class="container">
        @include('inc.messages')
        <h2>Food create</h2>
        {!! Form::open(['route' => 'food.store', 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
            <div class="form-group">
                {!! Form::label('category_id', 'Category_id', ['class' => 'control-label']) !!}
                <select name="category_id">
                    <option value="{{ null }}" selected disabled>{{ "Category" }}</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                {!! Form::label('name', 'Name', ['class' => 'control-label']) !!}
                <input type="text" name="name" required>
            </div>
            <div class="form-group">
                {!! Form::label('price', 'Price', ['class' => 'control-label']) !!}
                <input type="number" name="price" required>
            </div>
            <div class="form-group">
                {!! Form::label('weight', 'Weight', ['class' => 'control-label']) !!}
                <input type="number" name="weight" required>
            </div>
            <div class="form-group">
                {!! Form::label('promotion', 'Promotion discount: ', ['class' => 'control-label']) !!}
                <input type="number" name="promotion" required>
            </div>
            <div>
                {!! Form::label('origin', 'Origin', ['class' => 'control-label']) !!}
                <input type="text" name="origin_name" placeholder="create new origin">
                <select name="origin_id">
                    <option value="{{null}}" disabled selected>{{ "origin_id" }}</option>
                    @foreach($origins as $origin)
                        <option value="{{ $origin->id }}">{{ $origin->name }}</option>
                    @endforeach
                    {{-- need to add options --}}
                </select>
            </div>
            <button type="submit">submit</button>
        {!! Form::close() !!}
    </div>
@endsection
