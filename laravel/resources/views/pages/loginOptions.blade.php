
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
						<form action="{{ url('login/password') }}" method="POST">
							{{ csrf_field() }}
							<div class="form-group">
								<label for="password">Login Using Password</label>
								<input type="hidden" name="email" value="{{ $email }}" />
								<input type="password" class="form-control" name="password" id="password" placeholder="Password">
							</div>
							<button type="submit" class="btn btn-primary float-right" style="width:100%">Login</button>
						</form>
						<form action="{{ url('login/voice') }}" method="POST">
							{{ csrf_field() }}
							<br>
							<br>							
							<div class="form-group">
								<label for="password"><b>Or</b> Login Using Voice</label>
								<input type="hidden" name="email" value="{{ $email }}" />
								<button type="submit" class="btn btn-success float-right" style="width:100%"><i class="fas fa-microphone"></i> </button>
							</div>
							<a href="{{ url('login/form') }}">Go back</a>
						</form>
					</div>
				</div>
			</div>
			<div class="col-sm">
			</div>
		</div>
	</div>


@endsection