{{-- toast run --}}
{{-- Error message and success msg--}}
@if ($errors->any())

    <div class="toastrDefaultError"></div>
    @push('specificJs')
    <script>
        $('.toastrDefaultError').click(function () {
            toastr.error("Could't Save the Data. Please try again")
        });
    </script>
    @endpush
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
        @push('specificJs')
        <script>
            $('.toastrDefaultSuccess').click(function () {
                toastr.success('&#160; {{ session('message') }}.&#160;')
            });

        </script>
        @endpush

    @endif
@endif
