<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Statistics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class CountriesController extends Controller
{
    /**
     * Get all the resources with the statistics.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resources(Request $request): \Illuminate\Http\JsonResponse
    {
        // Get filters values
        $perPage = intval($request->per_page ?? 10);
        $searchString = $request->string ?? null;
        $order = $request->order ?? 'asc';
        $sort = $request->sort ?? 'country';
        if(!in_array($sort, ['confirmed', 'recovered', 'death'])) $sort = 'country';

        // Filter Countries
        $resources = Country::withTodayStatistics();
        if($searchString) $resources = $resources->whereRaw("UPPER(name) LIKE '%". strtoupper($searchString)."%'")->orwhereRaw("UPPER(code) LIKE '%". strtoupper($searchString)."%'");
        if($sort == 'country') $resources = $resources->orderBy('name', $order, App::getLocale());
        else $resources = $resources->orderBy(('statistics.' . $sort), $order);

        // Return paginated countries
        return response()->json($resources->paginate($perPage));
    }

    /**
     * Get information about the country.
     *
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function resource(string $code): \Illuminate\Http\JsonResponse
    {
        $resource = Country::withTodayStatistics()->where('code', $code)->firstOrFail();
        return response()->json($resource);
    }

    /**
     * Get total information about today.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function total(): \Illuminate\Http\JsonResponse
    {
        return response()->json(['death' => Statistics::total('death'), 'recovered' => Statistics::total('recovered'), 'confirmed' => Statistics::total('confirmed')]);
    }
}
