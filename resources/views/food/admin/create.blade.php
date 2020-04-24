@extends('layouts.auth')

@section('content')
    <div class="container pt-4">
        {!! Form::open(['route' => 'food.store', 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
            <div class="form-group">
                {!! Form::label('category_id', 'Category_id', ['class' => 'control-label']) !!}
                <select name="category_id">
                    <option value="{{ null }}" selected disabled>{{ "Category" }}</option>
                    {{-- need to add options --}}
                </select>
            </div>
            <div class="form-group">
                {!! Form::label('name', 'Name', ['class' => 'control-label']) !!}
                <input type="text" name="name">
            </div>
            <div class="form-group">
                {!! Form::label('price', 'Price', ['class' => 'control-label']) !!}
                <input type="number" name="price">
            </div>
            <div class="form-group">
                {!! Form::label('weight', 'Weight', ['class' => 'control-label']) !!}
                <input type="number" name="weight">
            </div>
            <div>
                {!! Form::label('origin', 'Origin', ['class' => 'control-label']) !!}
                <select name="origin_id">
                    <option value="{{null}}" disabled selected>{{ "origin_id" }}</option>
                    {{-- need to add options --}}
                </select>
                <input type="text" name="origin_name" placeholder="create new origin">
            </div>
            <button type="submit">submit</button>
        {!! Form::close() !!}
    </div>
@endsection
