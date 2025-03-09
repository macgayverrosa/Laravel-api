<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class UpdateWeatherJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $apiKey = env('WEATHER_API_KEY');
        $city = 'Perth';
        $url = "https://api.weatherapi.com/v1/current.json?key={$apiKey}&q={$city}";

        $response = Http::get($url);

        if ($response->successful()) {
            Cache::put('weather_data', $response->json(), now()->addMinutes(15));
        }
    }
}