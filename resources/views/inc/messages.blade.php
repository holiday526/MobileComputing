@if(count($errors) > 0)
    @foreach($errors as $key => $messages)
        <div class="alert alert-danger">
            Error Message: {{ $key }} ->
            @foreach($messages as $message)
                "{{ $message }}"
            @endforeach
        </div>
    @endforeach
@endif

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
