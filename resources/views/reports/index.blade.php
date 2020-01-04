@extends('layouts.main')

@section('content')
<div class="title-bar">
	<h1 class="title-bar-title">
		<span class="d-ib">Reports</span>
	</h1>
</div>

<!-- <div class="card">
	<div class="card-body">
		<div class="panel m-b-lg">
			<ul class="nav nav-tabs">
				<li class="active">
					<a href="#inv" data-toggle="tab" aria-expanded="true">
						<span class="icon icon-cubes"></span> Inventory
					</a>
				</li>
				<li class="">
					<a href="#bp" data-toggle="tab" aria-expanded="false">
						<span class="icon icon-users"></span> Business Partners
					</a>
				</li>
				<li class="">
					<a href="#bp2" data-toggle="tab" aria-expanded="false">
						<span class="icon icon-users"></span> Business Partners 2
					</a>
				</li>
				
				<li class="">
					<a href="#goods" data-toggle="tab" aria-expanded="false">
						<span class="icon icon-truck"></span> Goods Entry
					</a>
				</li>
				
			</ul>
			<div class="tab-content">
				
				<div class="tab-pane fade" id="bp">
				</div>
			
			</div>
		</div>
	</div>
</div> -->


<div class="panel m-b-lg">
	<ul class="nav nav-tabs nav-justified">
		<li><a href="#home-11" data-toggle="tab"><span class="icon icon-cubes"></span> Inventory</a></li>
		<!-- <li><a href="#profile-11" data-toggle="tab"><span class="icon icon-users"></span> Business Partners</a></li> -->
		<li><a href="#profile-12" data-toggle="tab"><span class="icon icon-truck"></span> Goods Entry</a></li>
		<li><a href="#profile-13" data-toggle="tab"><span class="icon icon-list-alt"></span> GE Details</a></li>
		<li><a href="#profile-14" data-toggle="tab"><span class="icon icon-shopping-cart"></span> Sales</a></li>
		<li><a href="#profile-15" data-toggle="tab"><span class="icon icon-list-ol"></span> Sales Details</a></li>

	</ul>
	<div class="tab-content">
		@include('reports.tabs.inv')
		@include('reports.tabs.reports')
		@include('reports.tabs.goods')
		@include('reports.tabs.goodsDetails')
		@include('reports.tabs.sales')
		@include('reports.tabs.salesDetails')

@include('reports.script.reportScript')
	</div>
</div>

@endsection