<?php
namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AgentController extends Controller
{
    public function index()
    {
        // Optimized - eager load user relationship
        $agents = Agent::with('user')->orderBy('created_at', 'desc')->paginate(20);
        return view('agents.index', compact('agents'));
    }
    public function create()
    {
        return view('agents.create');
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string',
            'prenom' => 'nullable|string',
            'direction' => 'nullable|string',
            'service' => 'nullable|string',
            'fonction' => 'nullable|string',
            'ministere_rattachement' => 'nullable|string',
            'date_entree' => 'nullable|date',
            'username' => 'required|string|unique:users,username',
            'email' => 'nullable|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'nullable|in:admin,rh,utilisateur'
        ]);
        
        // Create User first (all agents must be users)
        $user = User::create([
            'username' => $data['username'],
            'email' => $data['email'] ?? null,
            'password' => Hash::make($data['password']),
            'role' => $data['role'] ?? 'utilisateur',
        ]);
        
        // Create Agent with user_id
        $agentData = [
            'nom' => $data['nom'],
            'prenom' => $data['prenom'] ?? null,
            'direction' => $data['direction'] ?? null,
            'service' => $data['service'] ?? null,
            'fonction' => $data['fonction'] ?? null,
            'ministere_rattachement' => $data['ministere_rattachement'] ?? null,
            'date_entree' => $data['date_entree'] ?? null,
            'user_id' => $user->id,
        ];
        
        Agent::create($agentData);
        return redirect()->route('agents.index')->with('success','Agent créé avec succès');
    }

    public function show(Agent $agent)
    {
        $agent->load('user');
        return view('agents.show', compact('agent'));
    }

    public function edit(Agent $agent)
    {
        $agent->load('user');
        return view('agents.edit', compact('agent'));
    }

    public function update(Request $request, Agent $agent)
    {
        $agent->load('user');
        
        $data = $request->validate([
            'matricule' => 'required|string|unique:agents,matricule,' . $agent->id,
            'nom' => 'required|string',
            'prenom' => 'nullable|string',
            'direction' => 'nullable|string',
            'service' => 'nullable|string',
            'fonction' => 'nullable|string',
            'ministere_rattachement' => 'nullable|string',
            'date_entree' => 'nullable|date',
            'username' => 'required|string|unique:users,username,' . $agent->user_id,
            'email' => 'nullable|email|unique:users,email,' . $agent->user_id,
            'password' => 'nullable|string|min:6',
            'role' => 'nullable|in:admin,rh,utilisateur'
        ]);
        
        // Update User
        $userData = [
            'username' => $data['username'],
            'email' => $data['email'] ?? null,
            'role' => $data['role'] ?? $agent->user->role,
        ];
        
        if (!empty($data['password'])) {
            $userData['password'] = Hash::make($data['password']);
        }
        
        $agent->user->update($userData);
        
        // Update Agent
        $agentData = [
            'matricule' => $data['matricule'],
            'nom' => $data['nom'],
            'prenom' => $data['prenom'] ?? null,
            'direction' => $data['direction'] ?? null,
            'service' => $data['service'] ?? null,
            'fonction' => $data['fonction'] ?? null,
            'ministere_rattachement' => $data['ministere_rattachement'] ?? null,
            'date_entree' => $data['date_entree'] ?? null,
        ];
        
        $agent->update($agentData);
        return redirect()->route('agents.index')->with('success','Agent mis à jour avec succès');
    }

    public function destroy(Agent $agent)
    {
        // Delete the user (this will cascade delete the agent if foreign key is set up correctly)
        // Or delete both explicitly
        $user = $agent->user;
        $agent->delete();
        $user->delete();
        return redirect()->route('agents.index')->with('success','Agent supprimé avec succès');
    }
}
