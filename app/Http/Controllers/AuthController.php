<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(Request $request){

        $validator = Validator::make($request->all(), [
            'name'              => 'required|string|max:255',
            'email'             => 'required|unique:users,email',
            'confirmPassword'   => 'required|same:password',
            'password'          => ['required',Password::min(8)
            ->letters()
            ->mixedCase()
            ->numbers()
            ->symbols()
            ->uncompromised()]
        ],[
            'confirmPassword.required'   => "El campo :attribute es obligatorio.",
            'confirmPassword.same'       => "El campo :attribute y el campo contraseña deben coincidir."
        ],[
            'confirmPassword'            => 'correo electrónico de confirmación'
        ]);

        if ($validator->fails())
            return response()->json(['success' => false, 'msg' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);

        $user  = $this->userService->createCliente($request->all());

        $token = $this->userService->createToken($user);

        if($user)
            return response()->json(['success' => true, 'msg' => "Se ha registrado correctamente.", 'access_token' => $token], Response::HTTP_OK);
        else
            return response()->json(['success' => false, 'msg' => "Ocurrió un error al registrarse."], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function login(Request $request){

        if(!Auth::attempt($request->only('email','password')))
            return response()->json(['success' => false, 'msg' => "Correo electrónico o contraseña no son correctos."], Response::HTTP_UNAUTHORIZED);

        $user =  User::where('email', $request->email)->firstOrFail();

        $token = $this->userService->createToken($user);

        return response()->json(['success' => true,'msg' => "Bienvenido $user->name", 'access_token' => $token]);
    }

    public function logout(Request $request){

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->json(['success' => true, 'msg'=> "La sesión ha sido cerrada con exito."], Response::HTTP_OK);
    }
}
