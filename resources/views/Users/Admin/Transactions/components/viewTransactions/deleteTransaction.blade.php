<!-- delete land -->
<div class="modal fade" id="loanDeleteModal-{{$data->transID}}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger" id="exampleModalCenterTitle"><b>{{$data->name}}</b>'s Transaction {{$data->transID}} Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form action="{{route('admin.deleteTransaction',$data->transID)}}" method="post">
                
                @csrf
                
                <div class="modal-body">
                  
                    <h6 class="text-danger">Are you sure you want to Delete ?.. </h6>
                    <h4 class="text-secondary"><b>{{$data->name}}</b>'s Transaction {{$data->transID}} Delete</h4>
                <i>Please confirm it again. you will not be able to recover this Transaction data and there will be calculation problems.</i>  

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger" >Delete Permanently</button>
                </div>
            </form>

        </div>
    </div>
</div>


