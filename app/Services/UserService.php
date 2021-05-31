<?php


namespace App\Services;

use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{

    public function createUser(StoreUserRequest $request, User $user)
    {
        $testLogin = User::where('login', $request->login)->first();
        if ($testLogin) {
            abort(400, 'O login ' . $request->login . ' já está vinculado a um usuário');
        }

        $this->validateCPF($request->cpf);
        $testCPF = User::where('cpf', $request->cpf)->first();
        if ($testCPF) {
            abort(400, 'O cpf ' . $request->cpf . ' já está vinculado a um usuário');
        }

        $testEmail = User::where('email', $request->email)->first();
        if ($testEmail) {
            abort(400, 'O e-mail ' . $request->email . ' já está vinculado a um usuário');
        }

        $passwordHash = Hash::make($request->password);
        $request->merge([
            'password' => $passwordHash
        ]);
        $resource = $user->create($request->all());
        $resource->save();

        $resource->companies()->sync($request->companies);

        return $resource;
    }

    public function updateUser(UpdateUserRequest $request, $id)
    {
        $passwordHash = Hash::make($request->password);

        $user = User::where('id', $id)->first();

        if ($user->password != $passwordHash) {
            abort(400, 'Você não tem permissão para realizar essa ação!');
        }

        $request->merge([
            'password' => $passwordHash
        ]);
        $resource = $user->create($request->all());
        $resource->save();

        $resource->companies()->sync($request->companies);

        return $resource;
    }

    public function validateCPF($cpf)
    {
        // Verifica se foi informado todos os digitos corretamente
        if (strlen($cpf) != 11) {
            abort(400, 'Quantidade de caracteres inválida!');
        }

        // Faz o calculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                abort(400, 'CPF inválido!');
            }
        }
    }
}
