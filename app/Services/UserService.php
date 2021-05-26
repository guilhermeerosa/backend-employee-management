<?php


namespace App\Services;

use App\Http\Requests\User\StoreUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{

    public function createUser(StoreUserRequest $request, User $user)
    {
        $test = User::where('cpf', $request->cpf)->first();
        if ($test){
            abort(400, 'O cpf ' . $request->cpf . 'já está vinculado a um usuário');
        }

        $password = Hash::make($request->password);
        $resource = $user->create($request->all() + ['password' => $password]);
        $resource->save();

        $resource->companies()->sync($request->companies);

        return $resource;
    }
}
