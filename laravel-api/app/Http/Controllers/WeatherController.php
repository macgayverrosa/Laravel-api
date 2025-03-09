<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WeatherController extends Controller
{
    public function getWeather()
    {
        $weatherData = Cache::remember('weather_data', 15 * 60, function () {
            $apiKey = env('WEATHER_API_KEY');
            $city = 'Perth';
            $url = "https://api.weatherapi.com/v1/current.json?key={$apiKey}&q={$city}";

            $response = Http::get($url);

            if ($response->failed()) {
                return response()->json(['error' => 'Failed to fetch weather data'], 500);
            }

            return $response->json();
        });

        return response()->json($weatherData, 200);
    }
}