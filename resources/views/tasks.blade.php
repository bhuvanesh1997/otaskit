@extends('layouts.logged_in_layout')
@section('content')
<div class="pd-ltr-20 xs-pd-20-10">
  <div class="min-height-200px">
    <!-- form starts here -->
    <div class="pd-20 card-box mb-30">
      <div class="clearfix">
          <div class="pull-left">
            <h4 class="text-blue h4">Add / Update</h4>
          </div>
      </div>
      <div class="clearfix">
        @if ($errors->any())
          <div class="pull-left">
            <h4 class="text-blue h4" style="color: red!important;">Error</h4>
            <ul>
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif
      </div>
      <div class="flash-message">
        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
          @if(Session::has('alert-' . $msg))
            <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
            <script type="text/javascript">
              setTimeout(function() {
                $('.alert').fadeOut('fast');
              }, 5000);
            </script>
          @endif
        @endforeach
      </div>
      <form method="post" enctype="multipart/form-data">
        @csrf
        @php $edit_id = ""; @endphp
        @if ($errors->first('edit_id'))
          @php $edit_id = old('edit_id'); @endphp
        @endif
        <input type="hidden" id="edit_id" name="edit_id" value="{{ $edit_id }}">
        <div class="row">
          <div class="col-md-4 col-sm-12">
            <div class="form-group @if ($errors->first('title')){{'has-warning'}} @endif">
            	<label>Title</label>
            	<input class="form-control @if ($errors->first('title')){{'form-control-warning'}} @endif" type="text" id="title" name="title" placeholder="Enter Title" value="{{ old('title') }}">
            	@if ($errors->first('title'))
              	<div class="form-control-feedback">Sorry, {{ $errors->first('title')  }}</div>
            	@endif
            </div>
          </div>
          <div class="col-md-8 col-sm-12">
            <div class="form-group @if ($errors->first('description')){{'has-warning'}} @endif">
              <label>Description</label>
              <input class="form-control @if ($errors->first('description')){{'form-control-warning'}} @endif" type="text" id="description" name="description" placeholder="Enter Description" value="{{ old('description') }}">
              @if ($errors->first('description'))
                <div class="form-control-feedback">Sorry, {{ $errors->first('description')  }}</div>
              @endif
            </div>
          </div>
          <div class="col-md-2 col-sm-12">
        	  <div class="form-group">
              <label></label>
            	<center>
                @php $value = "Submit"; @endphp
                @if ($errors->first('edit_id'))
                  @php $value = "Update"; @endphp
                @endif
              	<input type="submit" class="btn btn-primary btn-lg" value="{{ $value }}" name="submit" id="submit">
               	<input type="reset" class="btn btn-primary btn-lg" onclick="window.location.reload()">
             	</center>
            </div>
          </div>
        </div>
     </form>
    </div>
    <!-- table starts here -->
    <div class="pd-20 card-box mb-30">
      <div class="clearfix">
          <div class="pull-left">
            <h4 class="text-blue h4">Tasks List</h4>
          </div>
      </div>
      <div class="pb-20 scroll">
        <table class="data-table table stripe hover nowrap mytable table table-bordered table-hover dataTable no-footer">
          <thead>
            <tr class="text-center">
              <th>#</th>
              <th>Created At</th>
              <th>Added By</th>
              <th>Title</th>
              <th>Description</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @php $i = 1; @endphp
            @foreach($tasks as $val)
              <tr>
                <td class="text-center">{{$i}}</td>
                <td>{{date('d-m-Y', strtotime($val->created_at))}}</td>
                <td>{{$val->name}}</td>
                <td>{{$val->title}}</td>
                <td>{{$val->description}}</td>
                <td class="text-center">  
                  <!-- <a class="dropdown-item" onclick="edit_details('{{$val->id}}')"><i class="dw dw-edit2"></i> Edit</a> -->
                  <a class="dropdown-item" onclick="delete_data('{{$val->id}}')"><i class="dw dw-delete-3"></i> Delete</a>
                </td>
              </tr>
              @php $i++; @endphp
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    <!-- Export Datatable End -->
  </div>
</div>
<div class="modal fade show" id="delete_modal" role="dialog">
  <div class="modal-dialog modal-lg view-modal">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Delete Confirmation</h4>
        <button type="button" class="close" data-dismiss="modal">×</button>
      </div>
      <div class="modal-body mbody" id="delete_body">
        <form method="post" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="del_id" id="del_id">
          <h4 style="color: red">Are You Sure To Delete This Cannot Be Reverted.</h4>
          <div class="col-md-4 col-sm-12">
            <div class="form-group">
              <label></label> 
              <center>
                <input type="submit" class="btn btn-secondary btn-lg" value="Delete" name="submit" id="submit">
              </center>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection
@section('js')
<script type="text/javascript">
$('.data-table').DataTable({
  scrollCollapse: true,
  autoWidth: false,
  responsive: true,
  "language": {
    "info": "_START_-_END_ of _TOTAL_ entries",
    searchPlaceholder: "Search",
    paginate: {
      next: '<i class="ion-chevron-right"></i>',
      previous: '<i class="ion-chevron-left"></i>'  
    }
  },
});
function edit_details(id){
  $.ajax({
        type:'get',
        url:'{{route("manage_taskEdit")}}',
        data:{id:id},
        dataType:'json',
        success:function(data) {
          $('#title').val(data.title);
          $('#description').val(data.description);
          $('#edit_id').val(data.id);
          $('#submit').val('Update');
          window.scroll({
            top: 0, 
            left: 0, 
            behavior: 'smooth'
          });
        }
  }); 
}
function delete_data(id){
  $('#delete_modal').modal('show');
  $('#del_id').val();
  $('#del_id').val(id);
}
</script>
@endsection