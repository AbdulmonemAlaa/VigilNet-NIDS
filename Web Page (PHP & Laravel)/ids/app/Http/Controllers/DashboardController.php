<?php

namespace App\Http\Controllers;

use App\Models\AlertLog;
use App\Models\PacketLog;
use App\Models\Signature;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
  public function index()
  {
    // Counts
    $alertCount = AlertLog::count();
    $packetLogCount = PacketLog::count();
    $signatureCount = Signature::count();

    // Top 5 source IPs by packet count
    $topSourceIPs = PacketLog::select('source_ip', DB::raw('count(*) as total'))
      ->groupBy('source_ip')
      ->orderBy('total', 'DESC')
      ->take(5)
      ->get();

    // Alerts timeline for the last 7 days
    $alertsTimeline = AlertLog::select(DB::raw('Date(timestamp) as date'), DB::raw('count(*) as total'))
      ->where('timestamp', '>', now()->subDays(7))
      ->groupBy('date')
      ->orderBy('date', 'ASC')
      ->get();
    // Alert counts by protocol
    $alertsByProtocol = AlertLog::select('protocol', DB::raw('count(*) as total'))
      ->groupBy('protocol')
      ->get();

    // Top 5 destination IPs by packet count
    $topDestinationsByPacket = PacketLog::select('destination_ip', DB::raw('count(*) as total'))
      ->groupBy('destination_ip')
      ->orderBy('total', 'DESC')
      ->take(5)
      ->get();




    // Packet counts by day for the last 7 days
    $packetCountsByHour = PacketLog::select(DB::raw('Date(timestamp) as date'), DB::raw('count(*) as total'))
      ->where('timestamp', '>', now()->subDays(7))
      ->groupBy('date')
      ->orderBy('date', 'ASC')
      ->get();

    // Source ports distribution
    $sourcePorts = PacketLog::select('source_port', DB::raw('count(*) as total'))
      ->groupBy('source_port')
      ->orderBy('total', 'DESC')
      ->take(5)
      ->get();

    // Destination ports distribution
    $destinationPorts = PacketLog::select('destination_port', DB::raw('count(*) as total'))
      ->groupBy('destination_port')
      ->orderBy('total', 'DESC')
      ->take(5)
      ->get();
    // Prepare chart data
    // At the end of your controller method, right before returning the view:
    $chartData = [
      'counts' => [$alertCount, $packetLogCount, $signatureCount],
      'topSourceIPs' => $topSourceIPs->pluck('total')->toArray(),
      'topSourceIPsLabels' => $topSourceIPs->pluck('source_ip')->toArray(),
      'alertsTimeline' => $alertsTimeline->pluck('total')->toArray(),
      'alertsTimelineLabels' => $alertsTimeline->pluck('date')->toArray(),
      'alertsByProtocol' => $alertsByProtocol->pluck('total')->toArray(),
      'alertsByProtocolLabels' => $alertsByProtocol->pluck('protocol')->toArray(),
      'topDestinationsByPacket' => $topDestinationsByPacket->pluck('total')->toArray(),
      'topDestinationsByPacketLabels' => $topDestinationsByPacket->pluck('destination_ip')->toArray(),
      'packetCountsByHour' => $packetCountsByHour->pluck('total')->toArray(),
      'packetCountsByHourLabels' => $packetCountsByHour->pluck('date')->toArray(),
      'sourcePorts' => $sourcePorts->pluck('total')->toArray(),
      'sourcePortsLabels' => $sourcePorts->pluck('source_port')->toArray(),
      'destinationPorts' => $destinationPorts->pluck('total')->toArray(),
      'destinationPortsLabels' => $destinationPorts->pluck('destination_port')->toArray(),
    ];

    return view('admin.home', compact('chartData'));
  }
}
