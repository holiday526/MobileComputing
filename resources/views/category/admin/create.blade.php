@extends('layouts.auth')

@section('content')
    <div class="container">
        @include('inc.messages')
        {!! Form::open(['route' => 'category.store', 'method' => 'post']) !!}
            <div class="form-group">
                {!! Form::label('name', 'Name', ['class' => 'control-label']) !!}
                <input type="text" name="name">
            </div>
            <button type="submit">submit</button>
        {!! Form::close() !!}
    </div>
@endsection
