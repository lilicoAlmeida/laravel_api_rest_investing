<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Registrar um novo usuário.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.unique' => 'Este e-mail está em uso.',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Usuário registrado com sucesso',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }
    /**
     * Mostra usuário atualmente logado.
     */
    public function usuarioAutenticado(Request $request)
    {
        try {
            return response()->json($request->user());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao obter usuário autenticado: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Mostra lista de usuários.
     */
    public function index()
    {
        try {
            $users = User::all();
            return response()->json($users);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao listar usuários: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Login do usuário.
     */
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:8',
            ], [
                'email.required' => 'O campo e-mail é obrigatório.',
                'email.email' => 'O e-mail deve ser válido.',
                'password.required' => 'O campo senha é obrigatório.',
                'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message' => 'Credenciais inválidas.'
                ], 401);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao realizar login: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Logout do usuário.
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();

            return response()->json([
                'message' => 'Deslogado com sucesso'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao realizar logout: ' . $e->getMessage()], 500);
        }
    }
}
