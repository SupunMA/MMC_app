@extends('layouts.adminLayout')

@section('content')
<div class="container-fluid ">

    {{-- button to go to all loans --}}
    <a class="btn btn-danger mb-1" href="{{route('admin.allTransaction')}}">
        <i class="fas fa-list-ul mr-1"></i>
        <b>View All Transactions</b>
    </a>
<br>



            {{-- <h5 class="form-title">Are you Going to?</h5> --}}
            <div class="row" style="margin: 20px 0px">
            <div class="custom-control custom-radio" style="margin-right: 20px">
              <input class="custom-control-input custom-control-input-success" value="card1" onclick="showCard('card1')" id="show1" type="radio"  name="showcard" checked>
              <label for="show1" class="custom-control-label">Pay the loan</label>
            </div>

            <div class="custom-control custom-radio">
              <input class="custom-control-input custom-control-input-danger" value="card2" onclick="showCard('card2')" id="show2" type="radio" name="showcard">
              <label for="show2" class="custom-control-label">Cut off Debt </label>
            </div>
 </div>
            {{-- Client Details form --}}


            <div id="card1" style="display: block;">

                @include('Users.Admin.Transactions.components.newTransaction')

            </div>

            <div id="card2" style="display: none;">

                @include('Users.Admin.Transactions.components.newDeductionTransaction')

            </div>
            {{-- Client Password form --}}



            {{-- End of Row --}}


            {{-- End of Form --}}


</div>
@endsection

@push('specificJs')
<script>
    function showCard(cardId) {
        var card1 = document.getElementById('card1');
        var card2 = document.getElementById('card2');

        if (cardId === 'card1') {
            card1.style.display = 'block';
            card2.style.display = 'none';
        } else if (cardId === 'card2') {
            card1.style.display = 'none';
            card2.style.display = 'block';
        }
    }
</script>
@endpush

@section('header')
Add New Transaction
@endsection
