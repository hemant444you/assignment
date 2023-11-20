@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Ball') }} 
                	<button class="btn btn-sm btn-success float-right" data-toggle="modal" data-target="#addModal">Add New Ball</button>
                </div>

                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                    	<table class="table table-bordered table-striped">
                    		<thead>
                    			<th>S No</th>
                    			<th>Name</th>
                    			<th>Volume</th>
                    			<th>Action</th>
                    		</thead>
                    		<tbody>
                    			<?php $i = 0; ?>
                    			@forelse($balls as $ball)
                    			<?php $i++ ?>
                    			<tr>
                    				<td>{{$i}}</td>
                    				<td>{{$ball->name}}</td>
                    				<td>{{$ball->volume}}</td>
                    				<td>
                    					<a href="{{route('ball.show',$ball->id)}}" target="_blank"  class="btn btn-sm btn-warning"><i class="fa fa-eye"></i></a>
                      					<button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addModal" data-id="{{$ball->id}}" data-name="{{$ball->name}}" data-volume="{{$ball->volume}}"><i class="fa fa-pencil"></i></button>
                      					<button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal" data-id="{{$ball->id}}"><i class="fa fa-trash"></i></button>
                    				</td>
                    			</tr>
                    			@empty

                    			@endforelse
                    		</tbody>
                    	</table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Add Modal -->

<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Ball</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{route('ball.store')}}" method="post" class="add-form" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="error"></div>
          <div class="form-group">
            <label for="name" class="col-form-label">Name:</label>
            <input type="text" name="name" class="form-control" id="name" required>
          </div>

          <div class="form-group">
            <label for="name" class="col-form-label">Volume:</label>
            <input type="text" name="volume" class="form-control" id="volume" required>
          </div>

          <input type="hidden" name="id" id="edit-id">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="save-button">Save Ball</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Are you sure ?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p class="text"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal" id="delete-button">Confirm Delete</button>
      </div>
    </div>
  </div>
</div>


@section('script')

<script>
  $(document).ready(function(){
    var id = '';
    var action = '';
    var token = "{{csrf_token()}}";

    $('#deleteModal').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget);
      id = button.data('id');
      $('#delete-id').val(id);
    });

    $(document).on('click','#delete-button',function(){
      var url = "{{route('ball.destroy','')}}";
      $.ajax({
        url : url + '/' + id,
        type: "DELETE",
        data : {'_token':token,'action':action},
        success: function(data)
        {
          window.location.reload();
        }
      });
    });

    $('#addModal').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget);
      $('#edit-id').val(button.data('id'));
      $('#name').val(button.data('name'));
      $('#volume').val(button.data('volume'));
    });

  });
</script>
@endsection


@endsection
