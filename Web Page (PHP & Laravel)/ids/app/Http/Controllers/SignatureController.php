<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Signature;
use App\Models\Category;

use Illuminate\View\View;
class SignatureController extends Controller
{
    public function create(): View
    {
        $signatures = Signature::all();
        return view('admin.sign',['signatures'=> $signatures]);
    }

    public function create1(): View
    {
        return view('admin.add_sign');
    }
    public function store(Request $request)
    {
        $request->validate([
            'action' => 'required|string',
            'protocol' => 'required|string',
            'source_ip' => 'required|string',
            'source_port' => 'required|string',
            'destination_ip' => 'required|string',
            'destination_port' => 'required|string',
            'options' => 'nullable|string',
        ]);

        $signature = new Signature($request->all());
        $signature->save();

        return redirect()->route('signatures')->with('success', 'Signature added successfully.');
    }


    public function edit($id)
    {
      $signature = Signature::findOrFail($id); // Find signature by ID
      return view('admin.edit_sign', compact('signature'));
    }

    public function update(Request $request, $signature)
{
  // Find the signature by ID
  $signature = Signature::findOrFail($signature);

  // Validate the request data (optional, adjust validation rules as needed)
  $this->validate($request, [
    'action' => 'required|string',
    'protocol' => 'required|string',
    'source_ip' => 'required|string',
    'source_port' => 'nullable|string',
    'destination_ip' => 'required|string',
    'destination_port' => 'nullable|string',
    'options' => 'nullable|string',
  ]);

  // Update the signature data
  $signature->update($request->all());

  // Flash a success message (optional)
  session()->flash('success', 'Signature updated successfully!');

  // Redirect to a desired location (e.g., edit page or signature list)
  return redirect()->route('signatures');
}

public function destroy(Signature $signature)
{
  // Delete the signature
  $signature->delete();

  // Flash a success message (optional)
  session()->flash('success', 'Signature deleted successfully!');

  // Redirect to a desired location (e.g., signatures list)
  return redirect()->route('signatures');
}


    
}
