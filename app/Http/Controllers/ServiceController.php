<?php
namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        // Cache services with directions
        $services = \Cache::remember('services_list', 3600, function() {
            return Service::with('direction')->orderBy('name')->get();
        });
        return view('services.index', compact('services'));
    }

    public function create()
    {
        $directions = \Cache::remember('directions', 3600, function() {
            return \App\Models\Direction::orderBy('libelle')->get();
        });
        return view('services.create', compact('directions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate(['direction_id'=>'nullable|integer','name'=>'required|string']);
        Service::create($data);
        \Cache::forget('services_list');
        \Cache::forget('services');
        return redirect()->route('services.index')->with('success','Service créé');
    }

    public function show(Service $service)
    {
        return view('services.show', compact('service'));
    }

    public function edit(Service $service)
    {
        $directions = \Cache::remember('directions', 3600, function() {
            return \App\Models\Direction::orderBy('libelle')->get();
        });
        return view('services.edit', compact('service', 'directions'));
    }

    public function update(Request $request, Service $service)
    {
        $data = $request->validate(['direction_id'=>'nullable|integer','name'=>'required|string']);
        $service->update($data);
        \Cache::forget('services_list');
        \Cache::forget('services');
        return redirect()->route('services.index')->with('success','Service mis à jour');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        \Cache::forget('services_list');
        \Cache::forget('services');
        return redirect()->route('services.index')->with('success','Service supprimé');
    }
}
