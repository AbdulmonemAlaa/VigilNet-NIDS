<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="description" content="Intrusion Detection System">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="author" content="Security team for benha university">
  <meta name="keywords" content=" IDS security protection data attacks virus">
  <title>Register | IDS</title>
  <link rel="shortcut icon" href="assets/image/logo2.png" type="image/x-icon">
  <link rel="stylesheet" href="assets/css/all.css">
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <script src="assets/js/bootstrap.bundle.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Alkatra&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Delicious+Handrawn&display=swap" rel="stylesheet">
</head>

<body>
  <section class="register" id="register">
    <nav class="navbar navbar-expand-lg navbar-light bg-transparent">
      <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
          aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ">
            <li class="nav-item">
              <a class="nav-link" href="{{route("dashboard")}}">Dashboard</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{route("packets_log")}}">Logs</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{route("alert_logs")}}">Alerts</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{route("signatures")}}">Signatures</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{route("reports")}}">Reports</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                Configurations
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                  <li><a class="dropdown-item" href="{{route("register")}}">Add User</a></li>
                  <li><a class="dropdown-item" href="{{route("profile.edit")}}">Profile</a></li>
                  <li>
                    <form action="{{route('logout')}}" method="POST" style="margin: 0; padding: 0;">
                      @csrf
                      <button class="dropdown-item" type="submit">
                        Log out
                      </button>
                    </form>
                  </li>
                  

              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <div class="container">
      <div class="register_info ">
        <img src="assets/image/logo.jpg" alt="" class="register_logo">
        <form method="POST" action="{{ route('register') }}">
          @csrf
          <h3>Register</h3>
          <div class="row p-2">
            <div class="col-md-12 pb-4">
              <input class="form-control" placeholder="name" aria-label="name" type="name" name="name" value="{{old('name')}}" required autofocus autocomplete="name"
                style="background-color: #e9ecef;">
            </div>
            <div class="col-md-12 pb-4">
              <input type="email" class="form-control" placeholder="email" aria-label="register_email"
                style="background-color: #e9ecef;" name="email" value="{{old('email')}}" required autocomplete="username">
            </div>
            <div class="col-md-12 pb-4">
              <input type="password" class="form-control" placeholder="Password" aria-label="password_email"
                style="background-color: #e9ecef;" name="password"
                required autocomplete="new-password">
            </div>
            <div class="col-md-12 pb-4">
              <input type="password" class="form-control" placeholder="Confirm Password" aria-label="password_email"
                style="background-color: #e9ecef;" name="password_confirmation" required autocomplete="new-password">
            </div>
            
            <div class="col-md-12 text-center">
              <button type="submit" class="btn btn-primary">Sign Up</button>
            </div>
          </div>
        </form>

      </div>

    </div>
  </section>

</body>

</html>