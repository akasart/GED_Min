<?php
namespace App\Http\Controllers;

use App\Models\Direction;
use Illuminate\Http\Request;

class DirectionController extends Controller
{
    public function index()
    {
        // Cache directions as they rarely change
        $items = \Cache::remember('directions_list', 3600, function() {
            return Direction::orderBy('libelle')->get();
        });
        return view('directions.index', ['directions'=>$items]);
    }

    public function create()
    {
        return view('directions.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate(['code'=>'nullable|string','libelle'=>'required|string']);
        Direction::create($data);
        \Cache::forget('directions_list');
        \Cache::forget('directions');
        return redirect()->route('directions.index')->with('success','Direction créée');
    }

    public function show(Direction $direction)
    {
        return view('directions.show', compact('direction'));
    }

    public function edit(Direction $direction)
    {
        return view('directions.edit', compact('direction'));
    }

    public function update(Request $request, Direction $direction)
    {
        $data = $request->validate(['code'=>'nullable|string','libelle'=>'required|string']);
        $direction->update($data);
        \Cache::forget('directions_list');
        \Cache::forget('directions');
        return redirect()->route('directions.index')->with('success','Direction mise à jour');
    }

    public function destroy(Direction $direction)
    {
        $direction->delete();
        \Cache::forget('directions_list');
        \Cache::forget('directions');
        return redirect()->route('directions.index')->with('success','Direction supprimée');
    }
}
