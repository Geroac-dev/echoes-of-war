<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Traits\FirestoreTrait;
use App\Models\CustomFirebaseUser; // O un objeto usuario simulado para la sesión
use App\Models\User;

class LoginController extends Controller
{
    use FirestoreTrait;

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'user' => ['required'],
            'password' => ['required'],
        ]);

        $users = $this->fbGet('users');

        $foundUser = null;
        foreach ($users as $u) {
            if (isset($u['user']) && $u['user'] === $credentials['user']) {
                $foundUser = $u;
                break;
            }
        }

        $passHash = $foundUser['contraseña'] ?? ($foundUser['password'] ?? '');

        if ($foundUser && Hash::check($credentials['password'], $passHash)) {
            
            // Guardamos los datos directamente en la sesión de Laravel
            session([
                'firebase_user' => [
                    'id' => $foundUser['id'],
                    'name' => $foundUser['nombreUsuario'] ?? ($foundUser['name'] ?? ''),
                    'user' => $foundUser['user'],
                    'email' => $foundUser['correo'] ?? ($foundUser['email'] ?? ''),
                    'dark_mode' => (int)($foundUser['dark_mode'] ?? 0)
                ]
            ]);

            $request->session()->regenerate();

            return response()->json(['status' => '200']);
        }

        return response()->json(['status' => '500', "msg" => "Las credenciales no coinciden."]);
    }

    public function logout(Request $request)
    {
        $request->session()->forget('firebase_user');
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}