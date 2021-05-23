@extends('layout')

@section('content')	
	<div class="container" style="margin-top:20px;">
		<div class="row">
			<div class="col-lg-4">
			</div>
			<div class="col-sm">
				<div class="card" style="width:25rem;">
				<div class="card-body">
					
					<h5 class="card-title">Welcome {{ auth()->user()->name }}</h5>
						<div class="card-body">
							You currently have {{ $embeddingsCount }} voice samples. <br><br>
							<a class="btn btn-primary" style="width:100%;" href="{{ url('dashboard/voice/add') }}"><i class="fas fa-plus"></i> Add</a>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm">
			</div>
		</div>
	</div>
@endsection