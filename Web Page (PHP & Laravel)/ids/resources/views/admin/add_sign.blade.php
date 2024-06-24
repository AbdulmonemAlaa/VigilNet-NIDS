<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="description" content="Intrusion Detection System">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="author" content="Security team for benha university">
  <meta name="keywords" content=" IDS security protection data attacks virus">
  <title>Add Sign | IDS</title>
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

  <section class="add_sign" id="add_sign">
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
      <form action="{{route('signatures.store')}}" method="POST">
        @csrf
        <div class="add_info mb-4">
            <div class="row">
                <!-- Action Field -->
                <div class="col-md-7 mb-4">
                    <label for="action" class="form-label" style="color: white;">Action</label>
                    <input type="text" class="form-control" id="action" name="action" value="{{ old('action') }}" aria-describedby="Action">
                </div>
    
                <!-- Protocol Field -->
                <div class="col-md-7 mb-4">
                    <label for="protocol" class="form-label" style="color: white;">Protocol</label>
                    <input type="text" class="form-control" id="protocol" name="protocol" value="{{ old('protocol') }}" aria-describedby="Protocol">
                </div>
    
                <!-- Source IP Field -->
                <div class="col-md-7 mb-4">
                    <label for="source_ip" class="form-label" style="color: white;">Source IP</label>
                    <input type="text" class="form-control" id="source_ip" name="source_ip" value="{{ old('source_ip') }}" aria-describedby="Source IP">
                </div>
    
                <!-- Source Port Field -->
                <div class="col-md-7 mb-4">
                    <label for="source_port" class="form-label" style="color: white;">Source port</label>
                    <input type="text" class="form-control" id="source_port" name="source_port" value="{{ old('source_port') }}" aria-describedby="Source port">
                </div>
    
                <!-- Destination IP Field -->
                <div class="col-md-7 mb-4">
                    <label for="destination_ip" class="form-label" style="color: white;">Destination IP</label>
                    <input type="text" class="form-control" id="destination_ip" name="destination_ip" value="{{ old('destination_ip') }}" aria-describedby="Destination IP">
                </div>
    
                <!-- Destination Port Field -->
                <div class="col-md-7 mb-4">
                    <label for="destination_port" class="form-label" style="color: white;">Destination Port</label>
                    <input type="text" class="form-control" id="destination_port" name="destination_port" value="{{ old('destination_port') }}" aria-describedby="Destination Port">
                </div>
    
                <!-- Options Field -->
                <div class="col-md-7 mb-4">
                    <label for="options" class="form-label" style="color: white;">Options</label>
                    <input type="text" class="form-control" id="options" name="options" value="{{ old('options') }}" aria-describedby="Options">
                </div>
    

            </div>
            <button type="submit">SUBMIT</button>
        </div>
    </form>
    
    
      </div>
  </section>
</body>

</html>