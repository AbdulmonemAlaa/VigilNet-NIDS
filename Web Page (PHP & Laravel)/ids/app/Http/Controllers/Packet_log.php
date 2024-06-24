<?php

namespace App\Http\Controllers;
use App\Models\PacketLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
class Packet_log extends Controller
{
    public function create(): View
    {
        $logs = PacketLog::paginate(100);
        return view('admin.logs',['logs'=> $logs]);
    }
    public function show($packet_id)
    {
        $log = PacketLog::where('packet_id', $packet_id)->firstOrFail();
        // Passing multiple pieces of information to the view
        return view('admin.show', [
            'ip_info' => $log->ip_info,
            'transport_info' => $log->transport_info,
            'application_info' => $log->application_info,
        ]);
    }
    
    
    public function more($packet_id) {
      
        $packetId = $packet_id;
      
        // Fetch surrounding packets (including the specific packet)
        $packets = PacketLog::where('packet_id', '>=', $packetId - 50)
                            ->where('packet_id', '<=', $packetId + 50)
                            ->orderBy('packet_id')
                            ->get();
      
        // Return data including matched packet id
        return view('admin.logs1', ["logs" => $packets, "matched_packet_id" => $packetId]);  
      }
      
      

      public function search(Request $request)
      {
          $ip = $request->input('ip');
          $port = $request->input('port');
          $protocol = $request->input('protocol');
          $content = $request->input('content');
      
          $logs = PacketLog::query();
      
          if ($ip) {
              // Apply the IP filter
              $logs->where(function ($query) use ($ip) {
                  $query->where('source_ip', 'like', "%$ip%")
                        ->orWhere('destination_ip', 'like', "%$ip%");
              });
          }
      
          if ($port) {
              // Apply the Port filter
              $logs->where(function ($query) use ($port) {
                  $query->where('source_port', $port)
                        ->orWhere('destination_port', $port);
              });
          }
      
          if ($protocol) {
              // Apply the Protocol filter
              $logs->where('protocol', $protocol);
          }
      
          if ($content) {
              // Apply the Content filter
              $logs->where('application_info', 'like', "%$content%");
          }
      
          // Only get logs that match all conditions
          $logs = $logs->get();
      
          return view('admin.logs_search', compact('logs'));
      }
      

    public function clearLogs()
    {
        // Delete all records from the Packet_logs table
        DB::table('packet_logs')->delete();
        
        // Redirect back to the logs page or any other desired page
        return redirect()->route('packets_log')->with('success', 'Packet logs cleared successfully.');
    }
      
}
