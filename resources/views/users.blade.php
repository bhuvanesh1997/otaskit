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
          <div class="col-md-3 col-sm-6">
            <div class="form-group @if ($errors->first('privilage')){{'has-warning'}} @endif">
              <label>Department</label>
              <select class="form-control @if ($errors->first('privilage')){{'form-control-warning'}} @endif" id="privilage" name="privilage" value="{{ old('privilage') }}">
                <option value="">-- Select Department --</option>
                <option value="0" @if(old('privilage') == '0') selected @endif>Admin</option>
                <option value="1" @if(old('privilage') == '1') selected @endif>Sales</option>
                <option value="2" @if(old('privilage') == '2') selected @endif>Marketing</option>
                <option value="3" @if(old('privilage') == '3') selected @endif>IT</option>
              </select>
              @if ($errors->first('privilage'))
                <div class="form-control-feedback">Sorry, {{ $errors->first('privilage')  }}</div>
              @endif
            </div>
          </div>
          <div class="col-md-3 col-sm-6">
            <div class="form-group @if ($errors->first('name')){{'has-warning'}} @endif">
            	<label>Name</label>
            	<input class="form-control @if ($errors->first('name')){{'form-control-warning'}} @endif" type="text" id="name" name="name" placeholder="Enter Name" value="{{ old('name') }}">
            	@if ($errors->first('name'))
              	<div class="form-control-feedback">Sorry, {{ $errors->first('name')  }}</div>
            	@endif
            </div>
          </div>
          <div class="col-md-3 col-sm-6">
            <div class="form-group @if ($errors->first('email')){{'has-warning'}} @endif">
              <label>Email</label>
              <input class="form-control @if ($errors->first('email')){{'form-control-warning'}} @endif" type="email" id="email" name="email" placeholder="Enter Email" value="{{ old('email') }}">
              @if ($errors->first('email'))
                <div class="form-control-feedback">Sorry, {{ $errors->first('email')  }}</div>
              @endif
            </div>
          </div>
          <div class="col-md-3 col-sm-6">
            <div class="form-group @if ($errors->first('mobile')){{'has-warning'}} @endif">
              <label>Mobile No.</label>
              <input class="form-control @if ($errors->first('mobile')){{'form-control-warning'}} @endif" type="text" id="mobile" name="mobile" placeholder="Enter Mobile No" value="{{ old('mobile') }}">
              @if ($errors->first('mobile'))
                <div class="form-control-feedback">Sorry, {{ $errors->first('mobile')  }}</div>
              @endif
            </div>
          </div>
          <div class="col-md-3 col-sm-6">
            <div class="form-group @if ($errors->first('block_status')){{'has-warning'}} @endif">
              <label>Satus</label>
              <select class="form-control @if ($errors->first('block_status')){{'form-control-warning'}} @endif" id="block_status" name="block_status" value="{{ old('block_status') }}">
                <option value="">-- Select Satus --</option>
                <option value="0" @if(old('block_status') == '0') selected @endif>Un-Blocked</option>
                <option value="1" @if(old('block_status') == '1') selected @endif>Blocked</option>
              </select>
              @if ($errors->first('block_status'))
                <div class="form-control-feedback">Sorry, {{ $errors->first('block_status')  }}</div>
              @endif
            </div>
          </div>
          <div class="col-md-3 col-sm-6">
            <div class="form-group @if ($errors->first('password')){{'has-warning'}} @endif">
              <label>password</label>
              <input class="form-control @if ($errors->first('password')){{'form-control-warning'}} @endif" type="text" id="password" name="password" placeholder="Enter password" value="123456">
              @if ($errors->first('password'))
                <div class="form-control-feedback">Sorry, {{ $errors->first('password')  }}</div>
              @endif
            </div>
          </div>
          <div class="col-md-3 col-sm-6">
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
            <h4 class="text-blue h4">Users List</h4>
          </div>
      </div>
      <div class="pb-20 scroll">
        <table class="data-table table stripe hover nowrap mytable table table-bordered table-hover dataTable no-footer">
          <thead>
            <tr class="text-center">
              <th>#</th>
              <th>Created At</th>
              <th>Privilage</th>
              <th>Name</th>
              <th>Email</th>
              <th>Mobile</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @php $i = 1; @endphp
            @foreach($users as $val)
              <tr>
                <td class="text-center">{{$i}}</td>
                <td>{{date('d-m-Y', strtotime($val->created_at))}}</td>
                <td>{{$val->privilage}}</td>
                <td>{{$val->name}}</td>
                <td>{{$val->email}}</td>
                <td>{{$val->mobile}}</td>
                <td class="text-center">  
                  @if($val->block_status == 0)
                    <a class="dropdown-item" onclick="block_unblock('{{$val->id}}',0)"><i class="dw dw-lock"></i> Block</a>
                  @elseif($val->block_status == 1)
                    <a class="dropdown-item" onclick="block_unblock('{{$val->id}}',1)"><i class="dw dw-unlock"></i> Un Block</a>
                  @endif
                  <a class="dropdown-item" onclick="edit_details('{{$val->id}}')"><i class="dw dw-edit2"></i> Edit</a>
                  <a class="dropdown-item" onclick="delete_data('{{$val->id}}')"><i class="dw dw-delete-3"></i> Delete</a>
                  <a class="dropdown-item" onclick="change_password('{{$val->id}}')"><i class="dw dw-key"></i> Change Password</a>
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
<div class="modal fade show" id="blockunblock_modal" role="dialog">
  <div class="modal-dialog modal-lg view-modal">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Block / Unblock</h4>
        <button type="button" class="close" data-dismiss="modal">×</button>
      </div>
      <div class="modal-body mbody" id="blockunblock_body">
        <form method="post" enctype="multipart/form-data">
          @csrf
          <input type="hidden" id="blkunblk_id" name="blkunblk_id">
          <h4 style="color: red">Are You Sure To Block?.</h4>
          <div class="col-md-4 col-sm-12">
            <div class="form-group">
              <label></label> 
              <center>
                <input type="submit" class="btn btn-secondary btn-lg" value="Block" name="submit" id="blkunblk">
              </center>
            </div>
          </div>
        </form>
      </div>
    </div>
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

<div class="modal fade show" id="password_reset_modal" role="dialog">
  <div class="modal-dialog modal-lg view-modal">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Password Reset</h4>
        <button type="button" class="close" data-dismiss="modal">×</button>
      </div>
      <div class="modal-body mbody" id="password_reset_body">
        <form method="post" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="reset_id" id="reset_id">
          <div class="col-md-12 col-sm-12">
            <div class="form-group">
              <label>Password</label>
              <input class="form-control" type="password" id="password" name="password" placeholder="Enter password">
            </div>
          </div>
          <div class="col-md-12 col-sm-12">
            <div class="form-group">
              <label></label> 
              <center>
                <input type="submit" class="btn btn-secondary btn-lg" value="Update Password" name="submit" id="submit">
              </center>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="modal fade show" id="blockunblock_modal" role="dialog">
  <div class="modal-dialog modal-lg view-modal">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Block / Unblock</h4>
        <button type="button" class="close" data-dismiss="modal">×</button>
      </div>
      <div class="modal-body mbody" id="blockunblock_body">
        <form method="post" enctype="multipart/form-data">
          @csrf
          <input type="hidden" id="blkunblk_id" name="blkunblk_id">
          <h4 style="color: red">Are You Sure To Block?.</h4>
          <div class="col-md-4 col-sm-12">
            <div class="form-group">
              <label></label> 
              <center>
                <input type="submit" class="btn btn-secondary btn-lg" value="Block" name="submit" id="blkunblk">
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
function block_unblock(id,i){
  $('#blockunblock_modal').modal('show');
  $('#blkunblk_id').val(id);
  if(i == 0)
    $('#blkunblk').val("Block");
  else if(i == 1)
    $('#blkunblk').val("Un-Block");
}
function edit_details(id){
  $.ajax({
        type:'get',
        url:'{{route("userEdit")}}',
        data:{id:id},
        dataType:'json',
        success:function(data) {
          $('#privilage').val(data.privilage);
          $('#name').val(data.name);
          $('#email').val(data.email);
          $('#mobile').val(data.mobile);
          $('#email').attr('readonly',true);
          $('#password').attr('readonly',true);
          $('#edit_id').val(data.id);
          $('#block_status').val(data.block_status);
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
function change_password(id){
  $('#password_reset_modal').modal('show');
  $('#reset_id').val();
  $('#reset_id').val(id);
}

$('#mobile').on('blur', function() {
    // Get the value of the mobile number input field
    const mobileNumber = $(this).val();

    // Regular expression pattern to match a mobile number
    const mobileNumberPattern = /^(\+?\d{1,3}[- ]?)?\d{10}$/;

    // Test if the mobile number matches the pattern
    if (mobileNumberPattern.test(mobileNumber)) {
      // Mobile number is valid
      
    } else {
      // Mobile number is invalid
      $(this).val('');      
      alert('Mobile number is invalid');
    }
  });
</script>
@endsection