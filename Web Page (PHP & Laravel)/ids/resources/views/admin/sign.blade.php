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
</head>

<body>

  <section class="sign" id="sign">
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


            <li class="nav-item dropdown" style="margin-right: 100px">
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
            <li class="nav-item ms-auto">
              <a class="nav-link btn btn-primary" href="{{route("signatures.add")}}">Add Signature</a>
            </li>
            
            
          </ul>
        </div>
      </div>
    </nav>
    <div class="container">
     
      <div class="row">
        <div class="col-md-1">
        </div>
        <div class="col-md-11">
          <table class="table table-hover text-center">
            <thead>
              <tr style="color: gold;">
                <th scope="col">Signature ID</th>
                <th scope="col">Action</th>
                <th scope="col">Protocol</th>
                <th scope="col">Source IP</th>
                <th scope="col">Source port</th>
                <th scope="col">Destination IP</th>
                <th scope="col">Destination Port</th>
                <th scope="col">Options</th>
                <th scope="col">Operations</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($signatures as $signature)
              <tr>
                  <td>{{$signature->id}}</td>
                  <td>{{$signature->action}}</td>
                  <td>{{$signature->protocol}}</td>
                  <td>{{$signature->source_ip}}</td>
                  <td>{{$signature->source_port}}</td>
                  <td>{{$signature->destination_ip}}</td>
                  <td>{{$signature->destination_port}}</td>
                  <td>
                    @php
                    // Retrieve the options field from the signature object
                    $options = $signature->options;
                
                    // Initialize an empty string to accumulate the parsed content
                    $result = '';
                
                    // Flag to track whether we're inside quotes
                    $inQuotes = false;
                
                    // Accumulate characters one by one safely
                    for ($i = 0; $i < strlen($options); $i++) {
                        // Toggle the inQuotes flag when encountering a quote
                        if ($options[$i] == '"') {
                            $inQuotes = !$inQuotes;
                        } elseif ($options[$i] == ';' && !$inQuotes) {
                            // Replace semicolon with a <br> tag if we're outside of quotes
                            $result .= '<br>';
                            continue;
                        }
                
                        // Safely convert characters using htmlspecialchars
                        $result .= htmlspecialchars($options[$i], ENT_QUOTES, 'UTF-8');
                    }
                
                    // Convert newlines to <br> tags for better readability
                    echo $result;
                    @endphp
                </td>
                
                  <td><a href="{{ route('signatures.edit', $signature->id) }}"><button class="btn1">Edit</button></a><form action="{{ route('signatures.destroy', $signature->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this signature?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn2">Delete</button>
                    </form></td>
              </tr>
              @endforeach
              
             
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
</body>

</html>