<?php
namespace App\Http\Controllers;

use App\Models\Confidentiality;
use Illuminate\Http\Request;

class ConfidentialityController extends Controller
{
    public function index()
    {
        // Cache confidentialities as they rarely change
        $items = \Cache::remember('confidentialities_list', 3600, function() {
            return Confidentiality::orderBy('label')->get();
        });
        return view('confidentialities.index', ['confidentialities'=>$items]);
    }

    public function create()
    {
        return view('confidentialities.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate(['code'=>'nullable|string','label'=>'required|string']);
        Confidentiality::create($data);
        \Cache::forget('confidentialities_list');
        return redirect()->route('confidentialities.index')->with('success','Niveau créé');
    }

    public function show(Confidentiality $confidentiality)
    {
        return view('confidentialities.show', compact('confidentiality'));
    }

    public function edit(Confidentiality $confidentiality)
    {
        return view('confidentialities.edit', compact('confidentiality'));
    }

    public function update(Request $request, Confidentiality $confidentiality)
    {
        $data = $request->validate(['code'=>'nullable|string','label'=>'required|string']);
        $confidentiality->update($data);
        \Cache::forget('confidentialities_list');
        return redirect()->route('confidentialities.index')->with('success','Niveau mis à jour');
    }

    public function destroy(Confidentiality $confidentiality)
    {
        $confidentiality->delete();
        \Cache::forget('confidentialities_list');
        return redirect()->route('confidentialities.index')->with('success','Niveau supprimé');
    }
}
