<?php

namespace App\Http\Controllers;
use App\Models\AlertLog;
use Illuminate\Http\Request;
use Illuminate\View\View;
class Alert_log extends Controller
{
    public function create(): View
    {
        $alerts = AlertLog::all();
        return view('admin.alert',['alerts'=> $alerts]);
    }




    public function show($alert_id)
    {
        $alert = AlertLog::where('id', $alert_id)->firstOrFail();
        
        // Passing multiple pieces of information to the view
        return view('admin.show1', [
            "alert" => $alert
        ]);
    }
}
