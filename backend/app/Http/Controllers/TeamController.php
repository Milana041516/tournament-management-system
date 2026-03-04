<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;

class TeamController extends Controller
{
    public function index() {
        return Team::with('tournament')->get();
    }

    public function store(Request $req) {
        $team = Team::create([
            'name' => $req->name,
            'tournament_id' => $req->tournament_id,
        ]);
        return response()->json($team, 201);
    }

    public function show($id) {
        $team = Team::findOrFail($id);
        return response()->json($team);
    }

    public function update(Request $req, $id) {
        $team = Team::findOrFail($id);
        $team->update($req->all());
        return response()->json($team);
    }

    public function delete($id) {
        Team::destroy($id);
        return response()->json(['message' => 'Team deleted successfully!']);
    }
}
