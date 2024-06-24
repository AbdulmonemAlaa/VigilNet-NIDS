<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\PacketLog;
Use App\Models\AlertLog;
use App\Models\Signature;
use Illuminate\Support\Facades\DB;  // Import DB facade
use Barryvdh\DomPDF\Facade\pdf as PDF;  // Import PDF facade

class ReportController extends Controller
{
    public function generateReport(Request $request)
    {
        $type = $request->report_type;
        $title = '';
        $description = '';
        $imagePath = '';

        switch ($type) {
            case 'Network Traffic':
                $content = '';
                $content .= $this->generateNetworkTrafficReport();
                $title = 'Network Traffic Report';
                $description = 'This report provides a summary of network traffic.';
                $imagePath = 'assets/image/packet.png';
                break;
            case 'Alert Logs':
                $content = '<h1>Alert Logs Report</h1>';
                $content .= $this->generatePdfFromAlertTable();
                $title = 'Alert Logs Report';
                $description = 'This report provides a summary of Alert Logs.';
                $imagePath = 'assets/image/alert.png';
                break;
            case 'Signatures':
                $content = '<h1>Signatures Report</h1>';
                $content .= $this->generatePdfFromTable();
                $title = 'Signatures Report';
                $description = 'This report provides a summary of signatures.';
                $imagePath = 'assets/image/signature.png';
                break;
        }

        $pdf = PDF::loadHTML($content);
        $filename = 'report_' . time() . '.pdf';
        $pdf->save(storage_path('app/public/reports/' . $filename));

        $report = new Report();
        $report->name = $filename;
        $report->title = $title;
        $report->description = $description;
        $report->image_path = $imagePath;
        $report->save();

        return redirect()->route('reports', ['id' => $report->id]);
    }

    public function generatePdfFromTable()
    {
        $signatures = Signature::all();

        $html = '<div class="col-md-11">
                    <table style="border-collapse: collapse; width: 100%;">
                        <thead>
                            <tr style="color: black; font-weight: bold;">
                            <th style="border-bottom: 1px solid black; padding: 5px; width: 10%; text-align: center;">Signature ID</th>
                            <th style="border-bottom: 1px solid black; padding: 5px; width: 10%; text-align: center;">Action</th>
                            <th style="border-bottom: 1px solid black; padding: 5px; width: 10%; text-align: center;">Protocol</th>
                            <th style="border-bottom: 1px solid black; padding: 5px; width: 10%; text-align: center;">Source IP</th>
                            <th style="border-bottom: 1px solid black; padding: 5px; width: 10%; text-align: center;">Source Port</th>
                            <th style="border-bottom: 1px solid black; padding: 5px; width: 10%; text-align: center;">Destination IP</th>
                            <th style="border-bottom: 1px solid black; padding: 5px; width: 20%; text-align: center;">Destination Port</th>
                            <th style="border-bottom: 1px solid black; padding: 5px; width: 15%; text-align: center;">Options</th>
                        </tr>
                        </thead>
                        <tbody>';

        foreach ($signatures as $signature) {
            $html .= '<tr>
                        <td style="border-bottom: 1px solid black; padding: 5px; text-align: center;">'.$signature->id.'</td>
                        <td style="border-bottom: 1px solid black; padding: 5px; text-align: center;">'.$signature->action.'</td>
                        <td style="border-bottom: 1px solid black; padding: 5px; text-align: center;">'.$signature->protocol.'</td>
                        <td style="border-bottom: 1px solid black; padding: 5px; text-align: center;">'.$signature->source_ip.'</td>
                        <td style="border-bottom: 1px solid black; padding: 5px; text-align: center;">'.$signature->source_port.'</td>
                        <td style="border-bottom: 1px solid black; padding: 5px; text-align: center;">'.$signature->destination_ip.'</td>
                        <td style="border-bottom: 1px solid black; padding: 5px; text-align: center;">'.$signature->destination_port.'</td>
                        <td style="border-bottom: 1px solid black; padding: 5px; text-align: center;">';
    
            $options = $signature->options;
            $result = '';
            $inQuotes = false;
            for ($i = 0; $i < strlen($options); $i++) {
                if ($options[$i] == '"') {
                    $inQuotes = !$inQuotes;
                } elseif ($options[$i] == ';' && !$inQuotes) {
                    $result .= '<br>';
                    continue;
                }
                $result .= $options[$i];
            }
            $html .= $result;

            $html .= '</td>
                    </tr>';
        }

        $html .= '</tbody>
                </table>
            </div>';
        return $html;
    }

    public function generatePdfFromAlertTable()
    {
        $alerts = AlertLog::all();

        $html = '<div class="col-md-11">
                    <table style="border-collapse: collapse; width: 100%;">
                        <thead>
                            <tr style="color: black; font-weight: bold;">
                                <th style="border-bottom: 1px solid black; padding: 5px; width: 5%; text-align: center;">ID</th>
                                <th style="border-bottom: 1px solid black; padding: 5px; width: 10%; text-align: center;">Timestamp</th>
                                <th style="border-bottom: 1px solid black; padding: 5px; width: 10%; text-align: center;">Source IP</th>
                                <th style="border-bottom: 1px solid black; padding: 5px; width: 10%; text-align: center;">Source Port</th>
                                <th style="border-bottom: 1px solid black; padding: 5px; width: 10%; text-align: center;">Destination IP</th>
                                <th style="border-bottom: 1px solid black; padding: 5px; width: 10%; text-align: center;">Destination Port</th>
                                <th style="border-bottom: 1px solid black; padding: 5px; width: 10%; text-align: center;">Protocol</th>
                                <th style="border-bottom: 1px solid black; padding: 5px; width: 20%; text-align: center;">Alert Info</th>
                                <th style="border-bottom: 1px solid black; padding: 5px; width: 15%; text-align: center;">Packet ID</th>
                            </tr>
                        </thead>
                        <tbody>';

        foreach ($alerts as $alert) {
            $html .= '<tr>
                        <td style="border-bottom: 1px solid black; padding: 5px; text-align: center;">'.$alert->id.'</td>
                        <td style="border-bottom: 1px solid black; padding: 5px; text-align: center;">'.$alert->timestamp.'</td>
                        <td style="border-bottom: 1px solid black; padding: 5px; text-align: center;">'.$alert->source_ip.'</td>
                        <td style="border-bottom: 1px solid black; padding: 5px; text-align: center;">'.$alert->source_port.'</td>
                        <td style="border-bottom: 1px solid black; padding: 5px; text-align: center;">'.$alert->destination_ip.'</td>
                        <td style="border-bottom: 1px solid black; padding: 5px; text-align: center;">'.$alert->destination_port.'</td>
                        <td style="border-bottom: 1px solid black; padding: 5px; text-align: center;">'.$alert->protocol.'</td>
                        <td style="border-bottom: 1px solid black; padding: 5px; text-align: center;">'.$alert->alert_info.'</td>
                        <td style="border-bottom: 1px solid black; padding: 5px; text-align: center;">'.$alert->packet_id.'</td>
                    </tr>';
        }

        $html .= '</tbody>
                </table>
            </div>';
        return $html;
    }

    public function view($id)
    {
        $report = Report::findOrFail($id);
        $path = storage_path('app/public/reports/' . $report->name);
        if (file_exists($path)) {
            return response()->file($path);
        }
        return abort(404, 'File not found.');
    }

    private function generateNetworkTrafficReport()
    {
        $totalPackets = PacketLog::count();
        $timeRange = PacketLog::select(DB::raw('MIN(timestamp) as start_time, MAX(timestamp) as end_time'))->first();
        $topSourceIPs = $this->getTopIPs('source_ip');
        $topDestinationIPs = $this->getTopIPs('destination_ip');
        $topSourcePorts = $this->getTopPorts('source_port');
        $topDestinationPorts = $this->getTopPorts('destination_port');
        $protocolDistribution = $this->getProtocolDistribution();
        $peakTrafficTime = $this->getTrafficTime('MAX');
        $lowestTrafficTime = $this->getTrafficTime('MIN');
    
        // Generate charts and get paths
        $chartPaths = $this->generateCharts();
    
        $content = "<div style='text-align: center; margin-bottom: 20px;'>
                        <h1>Network Traffic Report</h1>
                    </div>
                    <br>
                    <p><strong>Summary of Collected Data:</strong> This report offers a comprehensive analysis of the network traffic captured over the specified time period. It provides in-depth statistics on the number of packets captured, as well as the top source and destination IPs, ports, protocol distribution, and peak traffic times.</p>
                    <br>
                    <p><strong>Total Packets Captured:</strong> Over the monitoring period, a total of <strong>{$totalPackets}</strong> packets were captured, indicating the volume of network activity observed. This metric serves as a baseline for understanding the overall traffic load on the network.</p>
                    <br>
                    <p><strong>Time Range:</strong> The data collection spanned from <strong>{$timeRange->start_time}</strong> to <strong>{$timeRange->end_time}</strong>. This range encapsulates all recorded network activities, providing a full spectrum of data for analysis.</p>
                    <br>
                    <p><strong>Top Source IPs:</strong> The following are the most active source IP addresses during the monitoring period. These IPs were responsible for sending the highest number of packets:</p>
                    <p>{$topSourceIPs}</p>";
        if (isset($chartPaths[0])) {
            $content .= "<div style='text-align: center; margin: 20px 0;'>
                            <img src='{$chartPaths[0]}' alt='Top Source IPs' style='max-width: 100%; height: 300px;'>
                        </div>";
        }
    
        $content .= "<br><br><br><br><br><p><strong>Top Destination IPs:</strong> Below are the IP addresses that received the most packets during the monitoring period. These IPs were the primary targets of network traffic:</p>
                    <p>{$topDestinationIPs}</p>";
        if (isset($chartPaths[1])) {
            $content .= "<div style='text-align: center; margin: 20px 0;'>
                            <img src='{$chartPaths[1]}' alt='Top Destination IPs' style='max-width: 100%; height: 300px;'>
                        </div>";
        }
    
        $content .= "<p><strong>Top Source Ports:</strong> The most frequently used source ports are listed below. These ports represent the originating points of significant network activities:</p>
                    <p>{$topSourcePorts}</p>";
        if (isset($chartPaths[2])) {
            $content .= "<div style='text-align: center; margin: 20px 0;'>
                            <img src='{$chartPaths[2]}' alt='Top Source Ports' style='max-width: 100%; height: 300px;'>
                        </div>";
        }
    
        $content .= "<p><strong>Top Destination Ports:</strong> The ports that received the highest number of packets are detailed below. These ports were the focal points of incoming network traffic:</p>
                    <p>{$topDestinationPorts}</p>";
        if (isset($chartPaths[3])) {
            $content .= "<div style='text-align: center; margin: 20px 0;'>
                            <img src='{$chartPaths[3]}' alt='Top Destination Ports' style='max-width: 100%; height: 300px;'>
                        </div>";
        }
    
        $content .= "<p><strong>Protocol Distribution:</strong> The distribution of protocols used during the monitoring period is illustrated below. This chart highlights the prevalence of various protocols within the network traffic:</p>
                    <p>{$protocolDistribution}</p>";
        if (isset($chartPaths[4])) {
            $content .= "<div style='text-align: center; margin: 20px 0;'>
                            <img src='{$chartPaths[4]}' alt='Protocol Distribution' style='max-width: 100%; height: 300px;'>
                        </div>";
        }
    
             return $content;
    }
    

    private function getTopIPs($column)
    {
        $topIPs = PacketLog::select($column, DB::raw('COUNT(*) as count'))
            ->groupBy($column)
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        if ($topIPs->isEmpty()) {
            return '<p>No data available for IPs</p>';
        }

        $html = '<ul>';
        foreach ($topIPs as $ip) {
            $html .= "<li>{$ip->$column} - {$ip->count} packets</li>";
        }
        $html .= '</ul>';
        return $html;
    }

    private function getTopPorts($column)
    {
        $topPorts = PacketLog::select($column, DB::raw('COUNT(*) as count'))
            ->groupBy($column)
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        $html = '<ul>';
        foreach ($topPorts as $port) {
            $html .= "<li>{$port->$column} - {$port->count} packets</li>";
        }
        $html .= '</ul>';
        return $html;
    }

    private function getProtocolDistribution()
    {
        $protocols = PacketLog::select('protocol', DB::raw('COUNT(*) as count'))
            ->groupBy('protocol')
            ->orderByDesc('count')
            ->get();

        $totalPackets = PacketLog::count();
        $html = '<ul>';
        foreach ($protocols as $protocol) {
            $percentage = round(($protocol->count / $totalPackets) * 100, 2);
            $html .= "<li>{$protocol->protocol}: {$percentage}% ({$protocol->count} packets)</li>";
        }
        $html .= '</ul>';
        return $html;
    }

    private function getTrafficTime($aggregation)
    {
        $time = PacketLog::select(DB::raw("{$aggregation}(timestamp) as time"))
            ->first();

        return $time->time;
    }

    private function generateCharts()
    {
        // Generate the data for the charts
        $topSourceIPs = PacketLog::select('source_ip', DB::raw('COUNT(*) as count'))
            ->groupBy('source_ip')
            ->orderByDesc('count')
            ->limit(5)
            ->get();
    
        $topDestinationIPs = PacketLog::select('destination_ip', DB::raw('COUNT(*) as count'))
            ->groupBy('destination_ip')
            ->orderByDesc('count')
            ->limit(5)
            ->get();
    
        $topSourcePorts = PacketLog::select('source_port', DB::raw('COUNT(*) as count'))
            ->groupBy('source_port')
            ->orderByDesc('count')
            ->limit(5)
            ->get();
    
        $topDestinationPorts = PacketLog::select('destination_port', DB::raw('COUNT(*) as count'))
            ->groupBy('destination_port')
            ->orderByDesc('count')
            ->limit(5)
            ->get();
    
        $protocolDistribution = PacketLog::select('protocol', DB::raw('COUNT(*) as count'))
            ->groupBy('protocol')
            ->orderByDesc('count')
            ->get();
    
        // Convert data to JSON
        $data = [
            'topSourceIPs' => $topSourceIPs->toArray(),
            'topDestinationIPs' => $topDestinationIPs->toArray(),
            'topSourcePorts' => $topSourcePorts->toArray(),
            'topDestinationPorts' => $topDestinationPorts->toArray(),
            'protocolDistribution' => $protocolDistribution->toArray(),
        ];
        $jsonData = json_encode($data);
    
        // Write JSON data to a temporary file
        $tempFile = tempnam(sys_get_temp_dir(), 'chart_data');
        file_put_contents($tempFile, $jsonData);
    
        // Path to Python executable and script
        $pythonPath = "C:\\Users\\asmaa\\AppData\\Local\\Programs\\Python\\Python312\\python.exe"; // Adjust this path if needed
        $scriptPath = "C:\\Users\\asmaa\\Desktop\\ids\\app\\Http\\Controllers\\generate_charts.py";
    
        // Command to run Python script with the path to the JSON data file
        $command = escapeshellcmd("$pythonPath $scriptPath") . " " . escapeshellarg($tempFile);
    
        // Execute command and capture output and errors
        $descriptorspec = [
            1 => ["pipe", "w"], // stdout is a pipe that the child will write to
            2 => ["pipe", "w"], // stderr is a pipe that the child will write to
        ];
    
        $process = proc_open($command, $descriptorspec, $pipes);
    
        if (is_resource($process)) {
            $output = stream_get_contents($pipes[1]);
            $errors = stream_get_contents($pipes[2]);
    
            fclose($pipes[1]);
            fclose($pipes[2]);
    
            $return_value = proc_close($process);
    
            // Remove the temporary file
            unlink($tempFile);
    
            if ($return_value !== 0) {
                throw new \Exception("Python script error: " . $errors);
            }
    
            $chartPaths = explode(',', trim($output));
    
            if (count($chartPaths) < 5) {
                throw new \Exception("Not all charts were generated: " . $output);
            }
    
            return $chartPaths; // This will be an array of the paths of the generated images
        } else {
            throw new \Exception("Failed to start Python process.");
        }
    }

    public function delete($id)
    {
        $report = Report::findOrFail($id);
        $filename = $report->name;

        $path = storage_path('app/public/reports/' . $filename);
        if (file_exists($path)) {
            unlink($path);
        }

        $report->delete();
        return redirect()->route('reports')->with('success', 'Report deleted successfully.');
    }
}
