<?php

namespace Tests\Unit;

use Tests\AuthProtectedTest;

class AuthenticationTest extends AuthProtectedTest
{
    /** Test Fail: check required fields for login
     *
     * @return void
     */
    public function testLoginWithoutFields()
    {
        $this->json('post', 'api/login')
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.'],
                ]
            ]);
    }

    /** Test Fail: check required fields for registration
     *
     * @return void
     */
    public function testRegisterWithouFields()
    {
        $this->json('post', 'api/register')
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'name' => ['The name field is required.'],
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.'],
                ]
            ]);
    }




    /** Test Success: check proper data for login
     *
     * @return void
     */
    public function testLoginSuccess()
    {
        $user = [
            'email' => 'manichevassvetlana@gmail.com',
            'password' => 'password'
        ];

        $this->json('post', 'api/login', $user)
            ->assertStatus(200)
            ->assertJsonStructure([
                'token'
            ]);
    }

    /** Test Success: check proper data for register
     *
     * @return void
     */
    public function testRegisterSuccess()
    {
        $user = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => $this->faker->password(8)
        ];

        $this->json('post', 'api/register', $user)
            ->assertStatus(200)
            ->assertJsonStructure([
                'token'
            ]);
    }


    /** Test Fail: check for user information without proper token
     *
     * @return void
     */
    public function testUserUnauthorized()
    {
        $this->json('get', 'api/user')
            ->assertStatus(401);
    }

    /** Test Success: check for user information
     *
     * @return void
     */
    public function testUserSuccess()
    {
        $headers = $this->headers();

        $this->json('get', 'api/user', [], $headers)
            ->assertStatus(200)
            ->assertJsonStructure([
                'user'
            ]);
    }



    /** Test Fail: logout without proper token
     *
     * @return void
     */
    public function testLogoutUnauthorized()
    {
        $this->json('post', 'api/logout')
            ->assertStatus(401);
    }

    /** Test Success: logout for the current user
     *
     * @return void
     */
    public function testLogoutSuccess()
    {
        $headers = $this->headers();

        $this->json('post', 'api/logout', [], $headers)
            ->assertStatus(200)
            ->assertJson([
                'message' => 'The token was successfully deleted.'
            ]);
    }
}
