@if (count($errors) > 0)
    <div class="alert alert-danger p-2">
        <div class="d-flex align-items-start">
            <div class="p-2">
                <i class="bi bi-exclamation-diamond-fill fs-xl"></i>
            </div>
            <div>
                <strong>Oopsy Daisy!</strong><br>
                @if ($errors->has('message'))
                    {{ $errors->first() }}
                @else
                    Please check below error{{ count($errors) > 1 ? 's' : '' }} to proceed.
                @endif
            </div>
        </div>
        {{-- @foreach ($errors->all() as $error)
            {{ $error }} <br>
        @endforeach --}}
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success">
        <strong>Hooray!</strong>
        {!! session('success') !!}
    </div>
@endif

@if (session('warning'))
    <div class="alert alert-warning">
        <strong>Holy Guacamole!</strong>
        {!! session('warning') !!}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        <strong>Oh Snap!</strong>
        {!! session('error') !!}
    </div>
@endif