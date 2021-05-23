
@extends('layout')

@section('content')
	<br>
	<div class="container" style="margin-top:200px;">
		<div class="row">
			<div class="col-lg-4">

			</div>
			<div class="col-sm">
			<h2>Register</h2>
				<div class="card" style="width:18rem;">
					<div class="card-body">
						@if(isset($_GET["success"]))
						<span class="text-success">Account registered successfully.</span>
						@endif
						<form action="{{ url('register/submit') }}" method="POST">
							{{ csrf_field() }}
							<div class="form-group">
								<label for="name">Name</label>
								<input type="text" class="form-control" id="name" name="name" placeholder="Full Name">
							</div>
							<div class="form-group">
								<label for="email">Email Address</label>
								<input type="email" class="form-control" id="email" name="email" placeholder="Enter email">
							</div>
							<div class="form-group">
								<label for="password">Password</label>
								<input type="password" class="form-control" id="password" name="password" placeholder="Password">
							</div>
							<a href="{{ url('login/form') }}">Go to Login</a>
							<button type="submit" class="btn btn-primary float-right">Register</button>
						</form>
					</div>
				</div>
			</div>
			<div class="col-sm">
			</div>
		</div>
	</div>


@endsection