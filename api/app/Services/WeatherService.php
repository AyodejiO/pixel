<?php

namespace App\Services;

use Illuminate\Http\Client\Pool;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class WeatherService
{
    protected $apiKey;
    protected $endpoint;

    public function __construct()
    {
        $this->apiKey = config('services.weather.key');
        $this->endpoint = config('services.weather.endpoint');
    }

    public function fetchWeatherDataConcurrently($users): Collection
    {
        $responses = Http::pool(function (Pool $pool) use ($users) {
            $pools = [];
            foreach ($users as $user) {
                $pools[] = $pool->as($user->id)->get("{$this->endpoint}?lat={$user->latitude}&lon={$user->longitude}&appid={$this->apiKey}");
            }
            return $pools;
        });

        $responses = collect($responses)->map(fn ($response) => $response->json());

        return $responses;
    }
}
