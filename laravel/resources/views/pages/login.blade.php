
@extends('layout')

@section('content')
	<br>
	<div class="container" style="margin-top:200px;">
		<div class="row">
			<div class="col-lg-4">

			</div>
			<div class="col-sm">
			<h2>Login</h2>
				<div class="card" style="width:18rem;">
					<div class="card-body">
						@if(isset($_GET["success"]))
						<span class="text-success">Account registered successfully.</span>
						@endif
						
						@if(isset($_GET["error"]))
						<span class="text-danger">{{ $_GET["error"] }}</span>
						@endif
						
						<form action="{{ url('login/options') }}" method="POST">
							{{ csrf_field() }}
							<div class="form-group">
								<label for="email">Email Address</label>
								<input type="email" class="form-control" name="email" id="email" placeholder="Enter email">
							</div>
							<a href="{{ url('register/form') }}">Register</a>
							<button type="submit" class="btn btn-primary float-right">Next</button>
						</form>
					</div>
				</div>
			</div>
			<div class="col-sm">
			</div>
		</div>
	</div>


@endsection