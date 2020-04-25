@extends('layouts.auth')

@section('content')
    <div class="container">
        @include('inc.messages')
        <img src="{{ asset('storage/'.$image->food_image_location) }}" alt="">
    </div>
@endsection
