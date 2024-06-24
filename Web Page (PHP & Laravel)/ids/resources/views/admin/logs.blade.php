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
  <style>
    .table {
      max-width: 85%; /* Adjust the percentage as needed */
      margin-left: auto; /* Push the table to the right */
      margin-right: 0; /* Reset any default margin */
    }
  </style>
  
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
      <div id="leftMenu1" class="sidenav1 p-2">
        <p style="color:gold; font-size:30px; font-weight:bolder;padding-left:10px" >Filter</p>
        <div class="row">
          <form action="{{ route('search') }}" method="GET">
          <div class="col-md-12 mb-4">
            <label for="exampleInputIP" class="form-label" style="color: white;">IP</label>
            <input type="text" class="form-control" name="ip" id="exampleInputIP" aria-describedby="IP" value="{{ old('ip') }}">
          </div>
          <div class="col-md-12 mb-4">
            <label for="exampleInputPort" class="form-label" style="color: white;">Port</label>
            <input type="text" class="form-control" id="exampleInputContent" aria-describedby="Port" name="port" value="{{ old('port') }}">
          </div>
          <div class="col-md-12 mb-4">
            <label for="exampleInputContent" class="form-label" style="color: white;">protocol</label>
            <input type="text" class="form-control" id="exampleInputContent" aria-describedby="Protocol" name="protocol" value="{{ old('protocol') }}">
          </div>
          <div class="col-md-12 mb-4">
            <label for="exampleInputContent" class="form-label" style="color: white;">Content</label>
            <input type="text" class="form-control" id="exampleInputContent" aria-describedby="Content" name="content" value="{{ old('content') }}">
          </div>
        </div>
        <button type="submit" class="logs_btn">Search</button>
      </form>
      <br>

      <br>
      <br>
      <br>
      <br>
      <br>
      <br>
      <button type="button" class="btn btn-danger" onclick="confirmDeletion()">Clear Network Traffic Logs</button>

      <script>
      function confirmDeletion() {
          if (confirm('Are you sure you want to delete all network traffic logs? This action cannot be undone.')) {
              location.href = '{{ route("clear_packet_logs") }}';
          }
      }
      </script>
            {{-- <button class="nav-link py-2 pl-3 bg-danger text-white" onclick="location.href='{{ route(clear_packet_logs) }}'">Clear Packet Logs</button>

      <a class="nav-link py-2 pl-3 bg-danger text-white" href="{{ route("clear_packet_logs") }}">Clear Packet Logs</a> --}}

      </div>
      <table class="table table-hover">
        <thead>
          <tr style="color: gold;">
            <th scope="col">Packet ID</th>
            <th scope="col">Time</th>
            <th scope="col">Protocol</th>
            <th scope="col">Source IP</th>
            <th scope="col">Source port</th>
            <th scope="col">Destination IP</th>
            <th scope="col">Destination Port</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($logs as $log)
          <tr data-href="{{ route('show_log', ['packet_id' => $log->packet_id]) }}"> <!-- Assuming 'id' is the parameter -->
            <td>{{$log->packet_id}}</td>
            <td>{{$log->timestamp}}</td>
            <td>{{$log->protocol}}</td>
            <td>{{$log->source_ip}}</td>
            <td>{{$log->source_port}}</td>
            <td>{{$log->destination_ip}}</td>
            <td>{{$log->destination_port}}</td>
          </tr>
          @endforeach
          
        </tbody>
      </table>
      <div class="container" style="display: flex; justify-content: space-between; padding: 20px;">
        @if (!$logs->onFirstPage())
            <a class="btn btn-primary" href="{{ $logs->previousPageUrl() }}" role="button" style="margin-right: auto; margin-left: 20%;">Previous</a>
        @endif
    
        @if ($logs->hasMorePages())
            <a class="btn btn-primary" href="{{ $logs->nextPageUrl() }}" role="button" style="margin-left: auto;">Next</a>
        @endif
    </div>
    
    
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