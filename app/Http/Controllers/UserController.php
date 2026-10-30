<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Traits\FirestoreTrait;

class UserController extends Controller
{
    use FirestoreTrait;

    public function getUser()
    {
        try {
            $user = session('firebase_user');
            
            if (!$user) {
                return response()->json(['status' => '401', 'msg' => 'Unauthenticated.'], 401);
            }

            return response()->json(['status' => '200', 'user' => $user]);
        } catch (\Exception $e) {
            return response()->json(['status' => "500", 'data' => $e->getMessage()]);
        }
    }

    public function getUsers()
    {
        try {
            $users = $this->fbGet('users');
            return response()->json(['status' => '200', 'users' => $users]);
        } catch (\Exception $e) {
            return response()->json(['status' => "500", 'data' => $e->getMessage()]);
        }
    }

    public function saveUser(Request $request)
    {
        try {
            // Verificamos si ya existe el nombre de usuario en Firebase
            $users = $this->fbGet('users');
            foreach ($users as $u) {
                if (isset($u['user']) && $u['user'] === $request->input("user")) {
                    return response()->json(['status' => '501', 'msg' => 'El nombre de usuario ya se encuentra en uso.']);
                }
            }

            $this->fbCreate('users', [
                "name" => $request->input("name"),
                "user" => $request->input("user"),
                "email" => $request->input("email"),
                "password" => Hash::make($request->input("pass")),
                "dark_mode" => 0 // Valor por defecto
            ]);

            return response()->json(['status' => '200']);
        } catch (\Exception $e) {
            return response()->json(['status' => "500", 'data' => $e->getMessage()]);
        }
    }

    public function editUser(Request $request)
    {
        try {
            $id = $request->input("id");
            
            $data = [
                "name" => $request->input("name"),
                "user" => $request->input("user"),
                "email" => $request->input("email"),
            ];

            // Si mandó contraseña nueva, la encriptamos y la actualizamos
            if ($request->input("pass") != null) {
                $data["password"] = Hash::make($request->input("pass"));
            }

            $this->fbUpdate('users', $id, $data);
            return response()->json(['status' => '200']);
        } catch (\Exception $e) {
            return response()->json(['status' => "500", 'data' => $e->getMessage()]);
        }
    }

    public function deleteUser(Request $request)
    {
        try {
            $id = $request->input("id");
            $currentUser = session('firebase_user');
            
            if ($currentUser && $id === $currentUser['id']) {
                return response()->json(['status' => '200', 'msg' => 'No puedes eliminar el usuario con el que estas logeado.', 'color' => 'warning']);
            }

            $this->fbDelete('users', $id);
            return response()->json(['status' => '200', 'msg' => 'Usuario eliminado correctamente.', 'color' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['status' => "500", 'data' => $e->getMessage()]);
        }
    }

    public function changeTheme(Request $request)
    {
        try {
            $currentUser = session('firebase_user');
            if (!$currentUser) {
                return response()->json(['status' => '401', 'msg' => 'Unauthenticated.'], 401);
            }

            $userId = $currentUser['id'];
            $theme = $request->input("theme");

            $this->fbUpdate('users', $userId, [
                "dark_mode" => $theme
            ]);

            return response()->json(['status' => '200']);
        } catch (\Exception $e) {
            return response()->json(['status' => "500", 'data' => $e->getMessage()]);
        }
    }
}