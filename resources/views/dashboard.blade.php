@extends('layouts.logged_in_layout')
@section('content')
<div class="pd-ltr-20 xs-pd-20-10">
  <div class="min-height-200px">
    <!-- form starts here -->
    <div class="pd-20 card-box mb-30">
      <div class="clearfix">
          <div class="pull-left">
            <h4 class="text-blue h4">{{Auth::user()->name}} You are logged in!</h4>
          </div>
      </div>
  </div>
</div>
@endsection

