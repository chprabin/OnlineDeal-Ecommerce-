@extends('layouts.account')
@section('main')
 <div class="row">
  <div class="col col-12">
   <h1 class="no-margin normal-bold">Welcome {{auth()->user()->name}}</h1>
  </div>
 </div>
 @if(auth()->user()->isAdmin())
  @include('partials.admin-account-reports')
 @endif
@endsection