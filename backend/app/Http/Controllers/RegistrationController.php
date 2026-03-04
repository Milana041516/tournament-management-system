<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends Controller
{
    public function index() {
        $registrations = Registration::with(['user', 'tournament', 'team'])->get();
        return response()->json($registrations);
    }

    public function store(Request $req) {
        $user = User::create([
            'name' => $req->name,
            'email' => $req->email,
            'password' => Hash::make('password123'),
            'role' => 'player'
        ]);

        $registration = Registration::create([
            'user_id' => $user->id,
            'tournament_id' => $req->tournament_id,
            'team_id' => $req->team_id,
            'name' => $req->name,
            'email' => $req->email,
            'status' => 'pending'
        ]);

        return response()->json($registration, 201);
    }

    public function show($id) {
        $registration = Registration::with(['user', 'tournament', 'team'])->findOrFail($id);
        return response()->json($registration);
    }

    public function update(Request $req, $id) {
        $registration = Registration::findOrFail($id);

        $registration->update([
            'status' => $req->status
        ]);

        return response()->json($registration);
    }

    public function delete($id) {
        Registration::destroy($id);
        return response()->json(['message' => 'Registration deleted successfully!']);
    }
}
