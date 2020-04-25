@extends('layouts.auth')

@section('content')
    <div class="container">
    @include('inc.messages')
    <h1>Food image</h1>
    {!! Form::open(['route' => 'image.store', 'method' => 'post', 'enctype'=>'multipart/form-data']) !!}
        <div class="form-group">
            {!! Form::label('food', 'Food name:', ['class' => 'control-label']) !!}
            <select name="food_id">
                <option value="{{ null }}" selected disabled>{{ "food name" }}</option>
                @foreach ($foods as $food)
                <option value="{{ $food->id }}">{{ $food->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            {!! Form::label('food_image', 'Upload product image:', ['class' => 'control-label']) !!}
            {!! Form::file('food_image', ['class'=>'form-control', 'required']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('index_photo', 'Index photo:', ['class' => 'control-label']) !!}
            <input type="checkbox" name="index_photo">
        </div>
        <button type="submit">submit</button>
    {!! Form::close() !!}
    </div>
@endsection

<script>

</script>


