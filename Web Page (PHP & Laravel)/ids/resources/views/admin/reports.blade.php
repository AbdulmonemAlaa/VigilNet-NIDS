<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="description" content="Intrusion Detection System">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="author" content="Security team for benha university">
  <meta name="keywords" content=" IDS security protection data attacks virus">
  <title>Signatures | IDS</title>
  <link rel="shortcut icon" href="/assets/image/logo2.png" type="image/x-icon">
  <link rel="stylesheet" href="/assets/css/all.css">
  <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="/assets/css/style.css">
  <script src="/assets/js/bootstrap.bundle.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Alkatra&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Delicious+Handrawn&display=swap" rel="stylesheet">
  <style>
    .report_info {
      max-width: 85%; /* Adjust the percentage as needed */
      margin-left: auto; /* Push the table to the right */
      margin-right: 0; /* Reset any default margin */
    }
  </style>
</head>

<body>

  <section class="report" id="report">
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
      <div id="leftMenu2" class="sidenav2 p-2">
        <h5 style="color:gold;text-align: center;padding-top: 20px; padding-bottom: 30px;"><b>Create Report</b></h5>
        <div class="row">
          <form action="{{ route('generate.report') }}" method="POST">
            @csrf
          <div class="col-md-10 mb-4">
            <label for="exampleInputIP" class="form-label" style="color: white;">Choose Report Type</label>
            <select name="report_type" class="form-select form-select-lg" aria-label=".form-select-lg example">
              <option selected>Network Traffic</option>
              <option value="Alert Logs">Alert Logs</option>
              <option value="Signatures">Signatures</option>

            </select>
          </div>
        </div>
        <button class="report_btn">Create</button>
      </form>
      </div>
      <div class="row">
        @foreach(App\Models\Report::all() as $report)
        <div class="col-md-10 report_info mb-3">
          <div class="row">
            <div class="col-md-3">
              <!-- Report image -->
              <img src="{{ asset($report->image_path) }}" class="w-100" alt="Report Image" style="border-radius: 10px;">
            </div>
            <div class="col-md-7">
              <!-- Report title and description -->
              <h3>{{ $report->title }}</h3>
              <pre>{{ $report->description }}         <span style="color: gold;">{{ $report->created_at }}</span></pre>
            </div>
            <div class="col-md-2">
              <!-- View report and delete report links -->
              <a href="{{ route('view.report', ['id' => $report->id]) }}" target="_blank" class="btn btn-primary" style="color: white; margin-right: 10px; padding: 5px 10px;">View Report</a>
              <br>
              <br>
              <form action="{{ route('delete.report', ['id' => $report->id]) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this Report?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" style="color: white; padding: 5px 10px;">Delete</button>
              </form>
            </div>
            
          </div>
        </div>
        @endforeach
      </div>
        
      </div>
    </div>
  </section>
</body>

</html>