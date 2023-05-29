<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserService {


    const ROLE_CLIENTE = "Cliente";

    private static function isCliente($user){
        return $user->roles[0]?->name == self::ROLE_CLIENTE;
    }

    public function createCliente(array $params){

        return $this->createUsuario($params,self::ROLE_CLIENTE);
    }

    public function createUsuario(array $params, string $rol){

        return User::create([
            'name'     => $params['name'],
            'email'    => $params['email'],
            'password' => Hash::make($params['password']),
        ])->assignRole($rol);
    }

    public function createToken($user){

        $expired = now()->addMinutes(30);

        if(self::isCliente($user))
            return $user->createToken('auth_token',['producto_read'], $expired)->plainTextToken;
        else
            return $user->createToken('auth_token',['*'], $expired)->plainTextToken;
    }

    public function getUserAdminTest(){
        $user = User::where('email','admintest@gmail.com')->first();

        if(!$user)
            $user = User::create([
                'name'      => "User Admin Test",
                'email'     => "admintest@gmail.com",
                'password'  => bcrypt("Ajjd4353kfdsf"),
                'created_at'=> date("Y-m-d H:i:s")
            ])->assignRole('Administrador');

        return $user;
    }

    public function getUserClientTest(){
        $user = User::where('email','clientetest@gmail.com')->first();

        if(!$user)
            $user = User::create([
                'name'      => "User Client Test",
                'email'     => "clientetest@gmail.com",
                'password'  => bcrypt("Ajjd4353kfdsf"),
                'created_at'=> date("Y-m-d H:i:s")
            ])->assignRole('Cliente');

        return $user;
    }
}