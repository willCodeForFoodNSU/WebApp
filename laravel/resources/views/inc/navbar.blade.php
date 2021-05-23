<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="#">willCodeForFood</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">

		@if(!Auth::user())
		  <li class="nav-item">
			<a class="nav-link" href="{{ url('login/form') }}">Login</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link" href="{{ url('register/form') }}">Register</a>
		  </li>
		@else
			<li class="nav-item">
				<a class="nav-link" href="{{ url('dashboard') }}">Dashboard</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="{{ url('dashboard/voice/add') }}">Add New Voice Sample</a>
			</li>
			<li class="nav-item">
				<a class="nav-link text-danger" href="{{ url('logout') }}">Logout</a>
			</li>
		@endif
	<!--
	  <li class="nav-item">
        <a class="nav-link" href="#">Identify</a>
      </li>
	-->	  
    </ul>
  </div>
</nav>