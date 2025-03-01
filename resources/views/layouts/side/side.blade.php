<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        {{-- <meta name="viewport" content="width=device-width, initial-scale=1"> --}}
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <title>{{ config('app.name', 'SISPAM') }}</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        {{-- <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script> --}}
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> --}}

            <!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" >

        
        @include('layouts.side.sidestyle')
    </head>
    <body>

        <div class="d-flex " id="wrapper">
        <!-- Sidebar -->
        @include('layouts.side.sidenav')
        <!-- /#sidebar-wrapper -->



        <!-- Page Content -->
        <div id="page-content-wrapper" class="d-flex flex-column min-vh-100">
        <nav class="navbar navbar-expand-lg navbar-light bg-info border-bottom sticky-top">
            <button class="btn btn-light ms-2" id="menu-toggle">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                    <path fill-rule="evenodd" d="M4 7a1 1 0 100-2 1 1 0 000 2zm4.75-1.5a.75.75 0 000 1.5h11.5a.75.75 0 000-1.5H8.75zm0 6a.75.75 0 000 1.5h11.5a.75.75 0 000-1.5H8.75zm0 6a.75.75 0 000 1.5h11.5a.75.75 0 000-1.5H8.75zM5 12a1 1 0 11-2 0 1 1 0 012 0zm-1 7a1 1 0 100-2 1 1 0 000 2z">
                    </path>
                </svg>
            </button>
            <button class="navbar-toggler me-3" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse ms-2" id="navbarSupportedContent">
            
                        <!-- Authentication Links -->
                        
            <div class="nav-link me-5 pe-5 ml-auto">
            <div class="dropdown float-end">
              <a class="nav-link dropdown-toggle text-white font-weight-bold" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                {{ Auth::user()->name }}
              </a>

              <ul class=" py-1 dropdown-menu" aria-labelledby="dropdownMenuLink">
                <li>
                    <a class="dropdown-item font-weight-bold text-center" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
                {{-- <li><a href="#" class="dropdown-item font-weight-bold ps-2"> </a></li> --}}
              </ul>
            </div>
            </div>
            </div>
        </nav>
        <main class="py-1">
            @yield('content')
        </main>
            
        </div>
    </div>
            <footer class="footer py-1">
                  <center>Copyright &copy; {{Carbon\Carbon::today()->isoFormat('Y');}} www.sispam.id. All Rights Reserved </center>  
            </footer>
</div>
</body>


    <script src=
"https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
            integrity=
"sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
            crossorigin="anonymous">
    </script>


<script>
    function cekPass() {
  var x = document.getElementById("password");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}
</script>

<script>
    function cekMaster() {
  var x = document.getElementById("mas");
  if (x.className === "bi bi-caret-right-fill") {
    x.className = "bi bi-caret-down-fill";
  } else {
    x.className = "bi bi-caret-right-fill";
  }
}
</script>

<script>
    function cekdown() {
  var x = document.getElementById("ubah");
  if (x.className === "bi bi-caret-right-fill") {
    x.className = "bi bi-caret-down-fill";
  } else {
    x.className = "bi bi-caret-right-fill";
  }
}
</script>
<!-- JavaScript -->
