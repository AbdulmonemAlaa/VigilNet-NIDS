<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="description" content="Intrusion Detection System">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="author" content="Security team for benha university">
  <meta name="keywords" content=" IDS security protection data attacks virus">
  <title>Home | IDS</title>
  <link rel="shortcut icon" href="/assets/image/logo2.png" type="image/x-icon">
  <link rel="stylesheet" href="/assets/css/all.css">
  <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="/assets/css/style.css">
  <script src="/assets/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Alkatra&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Delicious+Handrawn&display=swap" rel="stylesheet">
  <style>
    .chart-container {
      width: 46%;
      margin: 2%;
    }
  </style>
</head>

<body style="background-color: #1b3649; color: #ffffff;">

  <section class="index" id="index">
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
      <div class="row justify-content-between">
        <!-- Total Counts Chart -->
        <div class="col-md-5 chart-container">
          <canvas id="chartTotalCounts"></canvas>
        </div>

                        <!-- Top 5 Source IPs Chart -->
                        <div class="col-md-5 chart-container">
                          <canvas id="chartTopSourceIPs"></canvas>
                        </div>
                <!-- Top 5 Destination IPs Chart -->
                <div class="col-md-5 chart-container">
                  <canvas id="chartTopDestinationsByPacket"></canvas>
                </div>





        <!-- Source Ports Chart -->
        <div class="col-md-5 chart-container">
          <canvas id="chartSourcePorts"></canvas>
        </div>

        <!-- Destination Ports Chart -->
        <div class="col-md-5 chart-container">
          <canvas id="chartDestinationPorts"></canvas>
        </div>
        <!-- Alert Counts by Protocol Chart -->
        <div class="col-md-5 chart-container">
          <canvas id="chartAlertsByProtocol"></canvas>
        </div>
                <!-- Alerts Timeline Chart -->
                <div class="col-md-5 chart-container">
                  <canvas id="chartAlertsTimeline"></canvas>
                </div>

                <!-- Packet Counts by Hour Chart -->
                <div class="col-md-5 chart-container">
                  <canvas id="chartPacketCountsByHour"></canvas>
                </div>



      </div>
    </div>
  </section>
  <script>
document.addEventListener("DOMContentLoaded", function() { 

const chartData = @json($chartData); // Adapt this line for your templating engine



// Source Ports Chart
new Chart("chartSourcePorts", {
        type: "bar",
        data: {
          labels: chartData.sourcePortsLabels,
          datasets: [{
            label: "Source Ports",
            data: chartData.sourcePorts,
            backgroundColor: "rgba(255, 99, 132, 0.6)",
          }]
        },
        options: {
          title: {
            display: true,
            text: "Top 5 Source Ports",
            fontColor: '#ffffff'
          },
          legend: {
            labels: {
              fontColor: '#ffffff'
            }
          },
          scales: {
            xAxes: [{
              ticks: {
                fontColor: '#ffffff'
              }
            }],
            yAxes: [{
              ticks: {
                fontColor: '#ffffff'
              }
            }]
          }
        }
      });

      // Destination Ports Chart
      new Chart("chartDestinationPorts", {
        type: "bar",
        data: {
          labels: chartData.destinationPortsLabels,
          datasets: [{
            label: "Destination Ports",
            data: chartData.destinationPorts,
            backgroundColor: "rgba(54, 162, 235, 0.6)",
          }]
        },
        options: {
          title: {
            display: true,
            text: "Top 5 Destination Ports",
            fontColor: '#ffffff'
          },
          legend: {
            labels: {
              fontColor: '#ffffff'
            }
          },
          scales: {
            xAxes: [{
              ticks: {
                fontColor: '#ffffff'
              }
            }],
            yAxes: [{
              ticks: {
                fontColor: '#ffffff'
              }
            }]
          }
        }
      });
      
// Total Counts Chart (Doughnut)
new Chart("chartTotalCounts", {
  type: "doughnut",
  data: {
    labels: ["Alerts", "Packet Logs", "Signatures"],
    datasets: [{
      data: chartData.counts, // Example: [300, 150, 50]
      backgroundColor: ["#D756B9", "#FFD700", "#5B6CFF"],
    }]
  },
  options: {
    title: {
      display: true,
      text: "Total Counts",
      fontColor: '#ffffff'
    },
    legend: {
      labels: {
        fontColor: '#ffffff'
      }
    }
  }
});


// Top 5 Source IPs (Bar Chart)
new Chart("chartTopSourceIPs", {
  type: "bar",
  data: {
    labels: chartData.topSourceIPsLabels,
    datasets: [{
      label: "Alerts",
      data: chartData.topSourceIPs,
      backgroundColor: "rgba(30, 144, 255, 0.6)",
    }]
  },
  options: {
    title: {
      display: true,
      text: "Top 5 Source IPs by Packet Count",
      fontColor: '#ffffff'
    },
    legend: {
      labels: {
        fontColor: '#ffffff'
      }
    },
    scales: {
      xAxes: [{
        ticks: {
          fontColor: '#ffffff'
        }
      }],
      yAxes: [{
        ticks: {
          fontColor: '#ffffff'
        }
      }]
    }
  }
});


// Alerts Timeline (Line Chart)
new Chart("chartAlertsTimeline", {
  type: "line",
  data: {
    labels: chartData.alertsTimelineLabels,
    datasets: [{
      label: "Alerts",
      data: chartData.alertsTimeline,
      fill: false,
      borderColor: "rgba(255, 69, 0, 1)",
      backgroundColor: "rgba(255, 69, 0, 0.2)",
      tension: 0.1
    }]
  },
  options: {
    title: {
      display: true,
      text: "Alerts Timeline for the Last 7 Days",
      fontColor: '#ffffff'
    },
    legend: {
      labels: {
        fontColor: '#ffffff'
      }
    },
    scales: {
      xAxes: [{
        ticks: {
          fontColor: '#ffffff'
        }
      }],
      yAxes: [{
        ticks: {
          fontColor: '#ffffff'
        }
      }]
    }
  }
});

// Alert Counts by Protocol (Pie Chart)
new Chart("chartAlertsByProtocol", {
  type: "pie",
  data: {
    labels: chartData.alertsByProtocolLabels,
    datasets: [{
      data: chartData.alertsByProtocol,
      backgroundColor: ["#FFD700", "#FF6347", "#40E0D0", "#EE82EE", "#FF4500"],
    }]
  },
  options: {
    title: {
      display: true,
      text: "Alert Counts by Protocol",
      fontColor: '#ffffff'
    },
    legend: {
      labels: {
        fontColor: '#ffffff'
      }
    }
  }
});


// Top 5 Destination IPs by Packet Count (Horizontal Bar Chart)
new Chart("chartTopDestinationsByPacket", {
    type: "horizontalBar",
    data: {
      labels: chartData.topDestinationsByPacketLabels,
      datasets: [{
        label: "Packets",
        data: chartData.topDestinationsByPacket,
        backgroundColor: "rgba(35, 215, 0, 0.6)", // Changed color
      }]
    },
    options: {
      title: {
        display: true,
        text: "Top 5 Destination IPs by Packet Count",
        fontColor: '#ffffff'
      },
      legend: {
        labels: {
          fontColor: '#ffffff'
        }
      },
      scales: {
        xAxes: [{
          ticks: {
            fontColor: '#ffffff'
          }
        }],
        yAxes: [{
          ticks: {
            fontColor: '#ffffff'
          }
        }]
      }
    }
  });

// Packet Counts by Hour (Line Chart)
new Chart("chartPacketCountsByHour", {
  type: "line",
  data: {
    labels: chartData.packetCountsByHourLabels,
    datasets: [{
      label: "Packet Counts",
      data: chartData.packetCountsByHour,
      borderColor: "rgba(60, 179, 113, 1)",
      backgroundColor: "rgba(60, 179, 113, 0.2)",
    }]
  },
  options: {
    title: {
      display: true,
      text: "Packet Counts for the Last 7 Days",
      fontColor: '#ffffff'
    },
    legend: {
      labels: {
        fontColor: '#ffffff'
      }
    },
    scales: {
      xAxes: [{
        ticks: {
          fontColor: '#ffffff'
        }
      }],
      yAxes: [{
        ticks: {
          fontColor: '#ffffff'
        }
      }]
    }
  }
});

});


  </script>
  
</body>

</html>
