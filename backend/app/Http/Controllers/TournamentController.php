<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tournament;

class TournamentController extends Controller
{
    public function index() {
        $tournaments = Tournament::all();
        return response()->json($tournaments);
    }

    public function store(Request $req) {
        $tournament = Tournament::create([
            'name' => $req->name,
            'location' => $req->location,
            'description' => $req->description,
            'start_date' => $req->start_date,
            'end_date' => $req->end_date,
            'status' => $req->status ?? 'upcoming',
            'max_teams' => $req->max_teams ?? 16,
            'organizer_id' => 1, 
        ]);

        return response()->json($tournament, 201);
    }

    public function show($id) {
        $tournament = Tournament::findOrFail($id);
        return response()->json($tournament);
    }

    public function update(Request $req, $id) {
        $tournament = Tournament::findOrFail($id);
        $tournament->update($req->all());
        return response()->json($tournament);
    }

    public function delete($id) {
        Tournament::destroy($id);
        return response()->json(['message' => 'Tournament deleted successfully!']);
    }
}