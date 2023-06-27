@extends('layouts.logged_in_layout')
@section('content')
<div class="pd-ltr-20 xs-pd-20-10">
  <div class="min-height-200px">
    @if(Auth::user()->privilage == 0)
        <!-- form starts here -->
        <div class="pd-20 card-box mb-30">
        <div class="clearfix">
            <div class="pull-left">
                <h4 class="text-blue h4">Assign Tasks</h4>
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
                <div class="col-md-3 col-sm-6">
                    <div class="form-group @if ($errors->first('user')){{'has-warning'}} @endif">
                        <label>User</label>
                        <select class="form-control @if ($errors->first('user')){{'form-control-warning'}} @endif" id="user" name="user" value="{{ old('user') }}">
                            <option value="">-- Select User --</option>
                            @foreach($users as $val)
                                <option value="{{$val->id}}" @if(old('user') == $val->id) selected @endif>{{$val->name}}</option>
                            @endforeach
                        </select>
                        @if ($errors->first('user'))
                            <div class="form-control-feedback">Sorry, {{ $errors->first('user')  }}</div>
                        @endif
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="form-group @if ($errors->first('tasks')){{'has-warning'}} @endif">
                        <label>Tasks</label>
                        <select class="form-control @if ($errors->first('tasks')){{'form-control-warning'}} @endif" id="tasks" name="tasks" value="{{ old('tasks') }}">
                            <option value="">-- Select Tasks --</option>
                            @foreach($tasks as $val)
                                <option value="{{$val->id}}" @if(old('tasks') == $val->id) selected @endif>{{$val->title}}</option>
                            @endforeach
                        </select>
                        @if ($errors->first('tasks'))
                            <div class="form-control-feedback">Sorry, {{ $errors->first('tasks')  }}</div>
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
                    <h4 class="text-blue h4">Filter Tasks</h4>
                </div>
            </div>
            <form enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-3 col-sm-6">
                        <div class="form-group">
                            <label>User</label>
                            <select class="form-control" name="user">
                                <option value="">-- Select User --</option>
                                @foreach($users as $val)
                                    <option value="{{$val->id}}" @if($f_user == $val->id) selected @endif>{{$val->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="form-group">
                            <label>Tasks</label>
                            <select class="form-control" name="tasks">
                                <option value="">-- Select Tasks --</option>
                                @foreach($tasks as $val)
                                    <option value="{{$val->id}}" @if($f_tasks == $val->id) selected @endif>{{$val->title}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-12">
                        <div class="form-group">
                            <label></label>
                            <center>
                                <input type="submit" class="btn btn-primary btn-lg" value="Filter" name="submit" id="submit">
                                <input type="submit" class="btn btn-primary btn-lg" value="Filter Reset" name="submit" id="submit">
                            </center>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endif
    @if(Auth::user()->privilage != 0)
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
    @endif
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
              <th>User</th>
              <th>Status</th>
              <th>Title</th>
              <th>Description</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @php $i = 1; @endphp
            @foreach($assigned as $val)
                <tr>
                    <td class="text-center">{{$i}}</td>
                    <td>{{date('d-m-Y', strtotime($val->created_at))}}</td>
                    <td>{{$val->addedby}}</td>
                    <td>{{$val->name}}</td>
                    <td>
                        @php 
                            $logo = "check";
                            $status = ""; 
                        @endphp
                        @if($val->taskstatus == 0) @php $status = "Assigned"; @endphp 
                        @elseif($val->taskstatus == 1) @php $status = "In Progress"; $logo = "spinner"; @endphp
                        @elseif($val->taskstatus == 2) @php $status = "Done"; @endphp
                        @endif
                        {{$status}}
                    </td>
                    <td>{{$val->title}}</td>
                    <td>{{$val->description}}</td>
                    <td class="text-center">
                        @if($val->taskstatus == 0 || $val->taskstatus == 1)
                            <a class="dropdown-item" onclick="update_status('{{$val->id}}')"><i class="fa fa-{{$logo}}"></i> {{$status}}</a>
                        @endif
                        @if($val->taskstatus == 0 && Auth::user()->privilage == 0)
                            <a class="dropdown-item" onclick="edit_details('{{$val->id}}')"><i class="dw dw-edit2"></i> Edit</a>
                            <a class="dropdown-item" onclick="delete_data('{{$val->id}}')"><i class="dw dw-delete-3"></i> Delete</a>
                        @endif
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

<div class="modal fade show" id="updatestatus_modal" role="dialog">
  <div class="modal-dialog modal-lg view-modal">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Update Status</h4>
        <button type="button" class="close" data-dismiss="modal">×</button>
      </div>
      <div class="modal-body mbody" id="delete_body">
        <form method="post" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="update_id" id="update_id">
          <div class="row">

            <div class="col-md-4 col-sm-6">
                <div class="form-group">
                    <label>Status</label>
                    <select class="form-control" name="status">
                        <option value="">-- Select Status --</option>
                        <option value="1">Pending</option>
                        <option value="2">Done</option>
                    </select>
                </div>
            </div>
          <div class="col-md-2 col-sm-6">
            <div class="form-group">
              <label></label> 
              <center>
                <input type="submit" class="btn btn-secondary btn-lg" value="Update Status" name="submit" id="submit">
              </center>
            </div>
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
        url:'{{route("assign_taskEdit")}}',
        data:{id:id},
        dataType:'json',
        success:function(data) {
          $('#user').val(data.userid);
          $('#tasks').val(data.taskid);
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
function update_status(id){
    $('#updatestatus_modal').modal('show');
    $('#update_id').val();
    $('#update_id').val(id);
}
</script>
@endsection