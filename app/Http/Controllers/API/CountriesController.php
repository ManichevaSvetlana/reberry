<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

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
        $resources = Country::join('statistics', function ($join) {
            $join->on('statistics.country_id', '=', 'countries.id')->whereDate('statistics.created_at', Carbon::today());
        });
        if($searchString) $resources = $resources->whereRaw("UPPER(name) LIKE '%". strtoupper($searchString)."%'")->orwhereRaw("UPPER(code) LIKE '%". strtoupper($searchString)."%'");
        if($sort == 'country') $resources = $resources->orderBy('name', $order, App::getLocale());
        else $resources = $resources->orderBy(('statistics.' . $sort), $order);

        // Return paginated countries
        return response()->json($resources->paginate($perPage));
    }

    /**
     * Get information about the country.
     *
     * @param Request $request
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function resource(string $code): \Illuminate\Http\JsonResponse
    {
        $resource = Country::withTodayStatistics()->where('code', $code)->firstOrFail();
        return response()->json($resource);
    }
}
