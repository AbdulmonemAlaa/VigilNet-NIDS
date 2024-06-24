<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="description" content="Intrusion Detection System">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="author" content="Security team for benha university">
  <meta name="keywords" content=" IDS security protection data attacks virus">
  <title>Logs | IDS</title>
  <link rel="shortcut icon" href="assets/image/logo2.png" type="image/x-icon">
  <link rel="stylesheet" href="assets/css/all.css">
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <script src="assets/js/bootstrap.bundle.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Alkatra&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Delicious+Handrawn&display=swap" rel="stylesheet">
</head>

<body>

  <section class="logs" id="logs">
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
      <table class="table table-hover">
        <thead>
          <tr style="color: gold;">
            <th scope="col">Log ID</th>
            <th scope="col">Time</th>
            <th scope="col">Protocol</th>
            <th scope="col">Source IP</th>
            <th scope="col">Source port</th>
            <th scope="col">Destination IP</th>
            <th scope="col">Destination Port</th>

          </tr>
        </thead>
        <tbody>
          @foreach ($alerts as $alert)
          <tr data-href="{{ route('show1_log', ['alert_id' => $alert->id]) }}"> <!-- Assuming 'id' is the parameter -->
            <td>{{$alert->id}}</td>
            <td>{{$alert->timestamp}}</td>
            <td>{{$alert->protocol}}</td>
            <td>{{$alert->source_ip}}</td>
            <td>{{$alert->source_port}}</td>
            <td>{{$alert->destination_ip}}</td>
            <td>{{$alert->destination_port}}</td>
              
          </tr>
          @endforeach
          
        </tbody>
      </table>

    
    </div>
  </section>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const rows = document.querySelectorAll('tr[data-href]');
  
      rows.forEach(row => {
        row.addEventListener('click', () => {
          window.location.href = row.dataset.href;
        });
      });
    });
  </script>
  
</body>

</html>