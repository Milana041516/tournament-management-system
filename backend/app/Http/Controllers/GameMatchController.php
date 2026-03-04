<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GameMatch;

class GameMatchController extends Controller
{
    public function index() {
        $matches = GameMatch::with(['tournament', 'team1', 'team2'])->get();
        return response()->json($matches);
    }

    public function store(Request $req) {
        $match = GameMatch::create([
            'tournament_id' => $req->tournament_id,
            'team1_id' => $req->team1_id,
            'team2_id' => $req->team2_id,
            'match_date' => $req->match_date,
            'team1_score' => $req->team1_score,
            'team2_score' => $req->team2_score
        ]);
        return response()->json($match, 201);
    }

    public function show($id) {
        $match = GameMatch::with(['tournament', 'team1', 'team2'])->findOrFail($id);
        return response()->json($match);
    }

    public function update(Request $req, $id) {
        $match = GameMatch::findOrFail($id);

        $match->update($req->all());

        return response()->json($match);
    }

    public function delete($id) {
        GameMatch::destroy($id);
        return response()->json(['message' => 'Match deleted']);
    }
}
