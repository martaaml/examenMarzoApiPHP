<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Registro de usuario
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'verification_token' => Str::random(60),
            'confirmado' => false,
            'role' => 'user',
        ]);

        // Enviar correo de verificación
        Mail::raw('Verifica tu cuenta en: ' . url('/api/auth/verify/' . $user->verification_token), function ($message) use ($user) {
            $message->to($user->email)->subject('Verificación de cuenta');
        });

        return response()->json([
            'message' => 'Usuario registrado. Revisa tu correo para verificar tu cuenta.'
        ]);
    }

    /**
     * Confirmación de cuenta por email
     */
    public function verify($token)
    {
        $user = User::where('verification_token', $token)->first();

        if (!$user) {
            return response()->json(['message' => 'Token inválido o ya verificado'], 400);
        }

        $user->email_verified_at = now(); // Asignar la fecha de verificación
        $user->verification_token = null; // Limpiar el token de verificación
        $user->save();

        return response()->json(['message' => 'Cuenta verificada correctamente']);
    }

    /**
     * Login y generación de tokens
     */
    public function login(Request $request)
    {
        try {
            // Validar los datos del request
            $credentials = $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            // Verificar si el usuario existe y si está verificado
            $user = User::where('email', $credentials['email'])->first();

            if (!$user) {
                return response()->json(['message' => 'Credenciales incorrectas'], 401);
            }

            if (!$user->email_verified_at) {
                return response()->json(['message' => 'Por favor verifica tu correo electrónico primero.'], 403);
            }

            if (!Hash::check($credentials['password'], $user->password)) {
                return response()->json(['message' => 'Credenciales incorrectas'], 401);
            }

            // Crear el token
            $token = $user->createToken('API Token')->accessToken;

            return response()->json(['token' => $token], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Captura los errores de validación
            return response()->json([
                'error' => 'Error de validación',
                'messages' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Captura otros errores generales
            return response()->json([
                'error' => 'Error de inicio de sesión',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Logout (revocar token)
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Sesión cerrada']);
    }
}
