<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Traits\FirestoreTrait;
use Illuminate\Http\Request;

class ViewController extends Controller
{
    use FirestoreTrait;

    public function tris()
    {
        $user = session('firebase_user');
        if (!$user) {
            $pb = [
                'id' => null,
                'name' => null,
                'score' => null
            ];
        }else{
            $pb = $user;
        }
        $users = $this->fbGet('users');
        return view('layouts.tris', ['scores' => $users, 'pb' => $pb]);
    }
    public function store(Request $request)
    {
        $user = session('firebase_user');
            
        if (!$user) {
            return response()->json([
                'success' => true,
                'newScore' => 0,
                'message' => 'Inicia sesion para guardar puntuacion.'
            ]);
        }

        $request->validate([
            'score' => 'required|integer|min:0',
        ]);
        
        if ($request->score > $user['score']) {
            $this->fbUpdate('users', $user['id'], [
                "score" => $request->score
            ]);
        }
        
        return response()->json([
            'success' => true,
            'newScore' => $user['score'],
            'message' => 'Puntuación actualizada correctamente'
        ]);
    }
}
