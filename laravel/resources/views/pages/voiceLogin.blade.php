@extends('layout')

@section('content')	
<?php
	$url = url('login/voice/submit?email=' . $email);
	//$url = str_replace("http", "https", $url);
?>
	<div class="container" style="margin-top:20px;">
		<div class="row">
			<div class="col-lg-4">
			</div>
			<div class="col-sm">
				<div class="card" style="width:25rem;">
				<div class="card-body">
					<script>
						$(document).ready(function(){
							$("#recordButton").click(function(){
								$("#recordButton").hide();
								$("#stopButton").removeAttr("hidden");
							});
							
							$("#stopButton").click(function(){
								$("#stopButton").hide();
								//$("#uploadButton").removeAttr("hidden");
							});
						});
						
						submit_path = "{{ $url }}";
					</script>
					<h5 class="card-title">Login</h5>
						<div class="card-body">
							<div id="buttonHolder">
								<button class="btn btn-primary btn-lg" style="width:100%;" id="recordButton">
									<i class="fas fa-microphone"></i>
								</button>
								<button class="btn btn-danger btn-lg" style="width:100%;" id="stopButton" hidden>
									<i class="fas fa-stop"></i>
								</button>
								
								<button class="btn btn-success btn-lg" style="width:100%;" id="uploadButton" hidden>
									<i class="fas fa-upload"></i> Submit
								</button>
								
								<button class="btn btn-warning btn-lg" style="width:100%;" id="pauseButton" hidden>
									<i class="fas fa-pause"></i> 
								</button>
							
								<div id="recordingsList"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm">
			</div>
		</div>
	</div>
@endsection