<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;

class AuthProtectedTest extends TestCase
{
    use WithFaker;

    /**
     * Return request headers needed to interact with the auth protected API.
     *
     * @return mixed.
     */
    protected function headers()
    {
        $user = User::create(['name' => $this->faker->name, 'email' => $this->faker->unique()->safeEmail, 'password' => $this->faker->password(8)]);
        //$headers = ['Accept' => 'application/json'];

        $token = $user->createToken('web')->plainTextToken;
        $headers['Authorization'] = 'Bearer ' . $token;

        return $headers;
    }
}
