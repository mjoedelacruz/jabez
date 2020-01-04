@extends('layouts.main')

@section('content')
<div class="title-bar">
	<h1 class="title-bar-title">
		<span class="d-ib">Food Packages</span>
	</h1>
</div>
<div class="row gutter-xs">
  <div class="col-md-6 col-lg-3 col-lg-push-0">
    <div class="card">
      <div class="card-body">
        <div class="media">
          <div class="media-middle media-left">
            <span class="bg-primary circle sq-48">
              <span class="icon icon-cutlery"></span>
            </span>
          </div>
          <div class="media-middle media-body">
            <h6 class="media-heading">Food</h6>
            <h3 class="media-heading">
              <span class="fw-l">Main Course</span>
            </h3>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-6 col-lg-3 col-lg-push-3">
    <div class="card">
      <div class="card-body">
        <div class="media">
          <div class="media-middle media-left">
            <span class="bg-primary circle sq-48">
              <span class="icon icon-cutlery"></span>
            </span>
          </div>
          <div class="media-middle media-body">
            <h6 class="media-heading">Food</h6>
            <h3 class="media-heading">
              <span class="fw-l">Beverages</span>
            </h3>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-6 col-lg-3 col-lg-pull-3">
    <div class="card">
      <div class="card-body">
        <div class="media">
          <div class="media-middle media-left">
            <span class="bg-primary circle sq-48">
              <span class="icon icon-cutlery"></span>
            </span>
          </div>
          <div class="media-middle media-body">
            <h6 class="media-heading">Food</h6>
            <h3 class="media-heading">
              <span class="fw-l">Deserts</span>
            </h3>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-6 col-lg-3 col-lg-pull-0">
    <div class="card">
      <div class="card-body">
        <div class="media">
          <div class="media-middle media-left">
            <span class="bg-primary circle sq-48">
              <span class="icon icon-usd"></span>
            </span>
          </div>
          <div class="media-middle media-body">
            <h6 class="media-heading">Total Sales</h6>
            <h3 class="media-heading">
              <span class="fw-l">$155,352.47</span>
            </h3>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection