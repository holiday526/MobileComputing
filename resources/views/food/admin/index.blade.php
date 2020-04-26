@extends('layouts.auth')

@section('content')
    <div class="container">
        @include('inc.messages')
        <table class="table">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Name</th>
                    <th>Origin</th>
                    <th>Promotion discount?</th>
                    <th>Edit?</th>
                </tr>
            </thead>
            <tbody>
            @foreach($foods as $food)
                <tr>
                {!! Form::open(['url' => '/food/update/'.$food->id, 'method' => 'post', 'id'=>'foodEdit'.$food->id]) !!}
                        <td>
                            <select name="category_id" form="{{'foodEdit'.$food->id}}">
                                <option value="{{ $food->category_id }}" selected disabled>Selected: {{ \App\Category::find($food->category_id)->name }}</option>
                                @foreach(\App\Category::all() as $cate)
                                <option value="{{ $cate->id }}">{{$cate->name}}</option>
                                @endforeach
                            </select>
                        </td>
{{--                        <td>{!! Form::text('name', $food->name, ['class' => 'form-control']) !!}</td>--}}
                        <td><input type="text" value="{{ $food->name }}" name="name" form="{{'foodEdit'.$food->id}}"></td>
                        <td>
                            <select name="origin_id" form="{{'foodEdit'.$food->id}}">
                                <option value="{{ $food->origin_id }}">Selected: {{ \App\Origin::find($food->origin_id)->name }}</option>
                                @foreach(\App\Origin::all() as $origin)
                                <option value="{{ $origin->id }}">{{ $origin->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="number" name="promotion" value="{{ $food->promotion }}" min="0" max="100" oninput="validity.valid||(value='0');" form="{{'foodEdit'.$food->id}}"></td>
                        <td>
{{--                            {!! Form::submit('Edit', ['class' => 'form-control']) !!}--}}
                            <button type="submit" form="{{'foodEdit'.$food->id}}">Edit</button>
                        </td>
                {!! Form::close() !!}
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
@endsection
