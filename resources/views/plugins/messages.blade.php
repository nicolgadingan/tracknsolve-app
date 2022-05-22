@if (count($errors) > 0 && $errors->has('message'))
    <div class="alert alert-danger p-2">
        <div class="d-flex align-items-start">
            <div class="pl-2 pr-2">
                <i class="bi bi-exclamation-diamond-fill fs-xl"></i>
            </div>
            <div class="fs-sm">
                <strong>Oopsy Daisy!</strong><br>
                @if ($errors->has('message'))
                    {!! $errors->first() !!}
                @endif
            </div>
        </div>
        {{-- @foreach ($errors->all() as $error)
            {{ $error }} <br>
        @endforeach --}}
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success fs-sm">
        <strong>Hooray!</strong>
        {!! session('success') !!}
    </div>
@endif

@if (session('warning'))
    <div class="alert alert-warning fs-sm">
        <strong>Holy Guacamole!</strong>
        {!! session('warning') !!}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger fs-sm">
        <strong>Oh Snap!</strong>
        {!! session('error') !!}
    </div>
@endif