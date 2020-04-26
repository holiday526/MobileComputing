@extends('layouts.auth')

@section('content')
    <div class="container">
        @include('inc.messages')
        <?php $i = 0 ?>
        {!! Form::open(['route' => 'recipe.store', 'method' => 'post', 'id'=>'recipe_form']) !!}
        <table class="table">
            <thead>
                <tr>
                    <th>Item number</th>
                    <th>Items</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>https://www.youtube.com/watch?v=rVmZXJj5lH0</td>
                </tr>
            </tbody>
        </table>
        {!! Form::close() !!}
    </div>
@endsection

<script>

</script>
