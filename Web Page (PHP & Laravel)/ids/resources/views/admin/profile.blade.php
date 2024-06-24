<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="description" content="Intrusion Detection System">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="author" content="Security team for benha university">
  <meta name="keywords" content=" IDS security protection data attacks virus">
  <title>Profile | IDS</title>
  <link rel="shortcut icon" href="/assets/image/logo2.png" type="image/x-icon">
  <link rel="stylesheet" href="/assets/css/all.css">
  <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="/assets/css/style.css">
  <script src="/assets/js/bootstrap.bundle.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Alkatra&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Delicious+Handrawn&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Play&family=Rajdhani:wght@500&display=swap" rel="stylesheet">
</head>

<body>

  <section class="profile" id="profile">
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
      @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<h3>Profile</h3>
<div class="profile_info mb-4">
  <form action="/profile" method="POST">
    @csrf
    <input type="hidden" name="_method" value="PATCH">
    <h4 style="color: white;">Profile Information</h4>
    <p class="text-muted">Update your account's profile information and email address</p>
    <div class="row">
      <div class="col-md-7 mb-4">
        <label for="exampleInputName" class="form-label" style="color: white;">Name</label>
        <input type="text" class="form-control" id="exampleInputName" name="name" value="{{ $user->name }}" aria-describedby="name">
      </div>
      <div class="col-md-7 mb-4">
        <label for="exampleInputEmail1" class="form-label" style="color: white;">Email</label>
        <input type="email" name="email" class="form-control" id="exampleInputEmail1" value="{{ $user->email }}" aria-describedby="emailHelp">
      </div>
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
  </form>
</div>
      <div class="profile_info mb-4">
        <form action="/password" method="POST">
          @csrf
          <input type="hidden" name="_method" value="PUT">
        <h4 style="color: white;">Update Password</h4>
        <p class="text-muted">Ensure your account is using a long. random password to stay secure</p>
        <div class="row">
          <div class="col-md-7 mb-4">
            <label for="exampleInputPassword1" class="form-label" style="color: white;" >Current Password</label>
            <input name="current_password" type="password" class="form-control" id="exampleInputPassword1">
          </div>
          <div class="col-md-7 mb-4">
            <label for="exampleInputPassword1" class="form-label" style="color: white;">New Password</label>
            <input type="password" name="password" class="form-control" id="exampleInputPassword1">
          </div>
          <div class="col-md-7 mb-4">
            <label for="exampleInputPassword1" class="form-label" style="color: white;">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" id="exampleInputPassword1">
          </div>
        </div>
        <button class="prof_btn">SAVE</button>
        </form>
      </div>
      <div class="profile_info">
        
        <h4 style="color: white;">Delete Account</h4>
        
        <p class="text-muted">Once your account is deleted, all of its resources and data will be permanently deleted.
          Before deleting your account, please download any data or information that you wish to retain.</p>
        <button class="del_btn" onclick="showPopup()"><a href="#">DELETE ACCOUNT</a></button>
      </div>
      <div id="popup" class="popup">
        <div class="popup-content">
          <h4>Are you sure you want to delete your account?</h4>
          <p class="text-muted">Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.</p>
          <form action="/profile" method="POST">
            @csrf
            <input type="hidden" name="_method" value="DELETE">
          <input type="password" name="password" class="form-control mb-3" id="exampleInputPassword1" placeholder="Password">
          <button class="BTN1" class="close" onclick="hidePopup()"><a href="#">CANCEL</a></button>
          <button class="BTN2">DELETE ACCOUNT</button>
          </form>
        </div>
      </div>
    </div>
  </section>
  <script src="/assets/js/main.js"></script>
</body>

</html>