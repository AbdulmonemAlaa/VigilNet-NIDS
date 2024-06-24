<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="description" content="Intrusion Detection System">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="author" content="Security team for benha university">
  <meta name="keywords" content=" IDS security protection data attacks virus">
  <title>Login | IDS</title>
  <link rel="shortcut icon" href="assets/image/logo2.png" type="image/x-icon">
  <link rel="stylesheet" href="assets/css/all.css">
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <script src="assets/js/bootstrap.bundle.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Alkatra&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Delicious+Handrawn&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Play&family=Rajdhani:wght@500&display=swap" rel="stylesheet">
</head>

<body>
  <section class="login" id="login">
    <div class="container text-center">
      <div class="row">
        <div class="col-md-6 pt-5">
          <img src="assets/image/logo.jpg" class="w-75 rounded-5">
        </div>
        <div class="col-md-6 login_info">
          <h2>Welcome To IDS System</h2>
          <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="row p-3">
              <div class="col-10 pb-4 pt-3">
                <input type="email" class="form-control p-2" placeholder="email" aria-label="email"
                  style="background-color: #e9ecef;" name="email" value="{{old('email')}}" required autofocus autocomplete="username">
              </div>
              <div class="col-10 pb-4">
                <input type="password" class="form-control p-2" placeholder="Password" aria-label="password_email"
                  style="background-color: #e9ecef;" name="password"
                  required autocomplete="current-password" >
              </div>
              <div class="col-10 pb-3">
                <button type="submit" class="btn btn-primary">Login</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>

</body>

</html>