@if (count($errors) > 0 && $errors->has('message'))
    <div class="ts-alert-dark ts-alert-danger">
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
    <div class="ts-alert-dark ts-alert-success">
        <strong>Hooray!</strong>
        {!! session('success') !!}
    </div>
@endif

@if (session('warning'))
    <div class="ts-alert-dark ts-alert-warning">
        <strong>Holy Guacamole!</strong>
        {!! session('warning') !!}
    </div>
@endif

@if (session('error'))
    <div class="ts-alert-dark ts-alert-danger">
        <strong>Oh Snap!</strong>
        {!! session('error') !!}
    </div>
@endif

@if (session('info'))
    <div class="ts-alert-dark ts-alert-info">
        <strong>Hey there!</strong>
        {!! session('info') !!}
    </div>
@endif

@if (session('status'))
    <div class="ts-alert-dark ts-alert-info">
        {!! session('status') !!}
    </div>
@endif