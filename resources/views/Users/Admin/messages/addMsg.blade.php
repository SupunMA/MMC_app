{{-- toast run --}}
{{-- Error message and success msg--}}
@if ($errors->any())

<div class="toastrDefaultError"></div>

    <div class="alert alert-danger" role="alert">
        @foreach ($errors->all() as $err)
        <li>
            {{$err}}
        </li>
        @endforeach
    </div>

@else
    @if (session('message'))
        <div class="toastrDefaultSuccess"></div>
    @endif
@endif