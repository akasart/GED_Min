<?php
namespace App\Http\Controllers;

use App\Models\DocumentType;
use Illuminate\Http\Request;

class DocumentTypeController extends Controller
{
    public function index()
    {
        // Cache document types as they rarely change
        $types = \Cache::remember('document_types_list', 3600, function() {
            return DocumentType::orderBy('libelle')->get();
        });
        return view('document_types.index', compact('types'));
    }

    public function create()
    {
        return view('document_types.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate(['libelle'=>'required|string']);
        DocumentType::create($data);
        \Cache::forget('document_types');
        \Cache::forget('document_types_list');
        return redirect()->route('document-types.index')->with('success','Type créé');
    }

    public function show(DocumentType $documentType)
    {
        return view('document_types.show', compact('documentType'));
    }

    public function edit(DocumentType $documentType)
    {
        return view('document_types.edit', compact('documentType'));
    }

    public function update(Request $request, DocumentType $documentType)
    {
        $data = $request->validate(['libelle'=>'required|string']);
        $documentType->update($data);
        \Cache::forget('document_types');
        \Cache::forget('document_types_list');
        return redirect()->route('document-types.index')->with('success','Type mis à jour');
    }

    public function destroy(DocumentType $documentType)
    {
        $documentType->delete();
        \Cache::forget('document_types');
        \Cache::forget('document_types_list');
        return redirect()->route('document-types.index')->with('success','Type supprimé');
    }
}
