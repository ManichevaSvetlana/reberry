<?php

namespace Tests\Unit;

use App\Models\Country;
use Tests\AuthProtectedTest;

class ResourcesTest extends AuthProtectedTest
{
    /** Test Fail: get countries without proper token
     *
     * @return void
     */
    public function testCountriesWithoutAuth()
    {
        $this->json('get', 'api/countries')
            ->assertStatus(401);
    }

    /** Test Success: get countries with search parameters
     *
     * @return void
     */
    public function testCountriesWithParameters()
    {
        $headers = $this->headers();

        $this->json('get', 'api/countries', ['string' => 'al', 'order' => 'desc', 'sort' => 'recovered'], $headers)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data', 'last_page', 'current_page', 'total', 'per_page'
            ]);
    }

    /** Test Success: get countries without search parameters
     *
     * @return void
     */
    public function testCountriesWithoutParameters()
    {
        $headers = $this->headers();

        $this->json('get', 'api/countries', [], $headers)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data', 'last_page', 'current_page', 'total', 'per_page'
            ]);
    }


    /** Test Fail: get country without proper token
     *
     * @return void
     */
    public function testCountryWithoutAuth()
    {
        $country = Country::first();
        $this->json('get', ('api/countries/' . $country->code))
            ->assertStatus(401);
    }

    /** Test Fail: get country that does not exist
     *
     * @return void
     */
    public function testCountryNotExists()
    {
        $headers = $this->headers();

        $this->json('get', ('api/countries/' . 0), [], $headers)
            ->assertStatus(404);
    }

    /** Test Success: get country
     *
     * @return void
     */
    public function testCountrySuccess()
    {
        $headers = $this->headers();
        $country = Country::first();

        $this->json('get', ('api/countries/' . $country->code), [], $headers)
            ->assertStatus(200);
    }




    /** Test Fail: get total without proper token
     *
     * @return void
     */
    public function testTotalWithoutAuth()
    {
        $this->json('get', 'api/total')
            ->assertStatus(401);
    }

    /** Test Success: get total
     *
     * @return void
     */
    public function testTotalSuccess()
    {
        $headers = $this->headers();

        $this->json('get', 'api/total', [], $headers)
            ->assertStatus(200)
            ->assertJsonStructure([
                'confirmed', 'recovered', 'death'
            ]);
    }
}
