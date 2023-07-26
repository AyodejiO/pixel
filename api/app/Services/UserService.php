<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function __construct(
        private WeatherService $weatherService,
    ) {
    }

    /**
     * Handle the incoming request.
     */
    public function getUsers()
    {
        //
        $users = User::all();

        $batchSize = 5;
        $userBatches = $users->chunk($batchSize);

        $weatherData = collect();
        foreach ($userBatches as $userBatch) {
            $responses = $this->weatherService->fetchWeatherDataConcurrently($userBatch);
            $weatherData = $weatherData->merge($responses);
        }

        $userData = $users->map(function ($user) use ($weatherData) {
            $weather = $weatherData->first(function ($item) use ($user) {
                if (!$item) {
                    return false;
                }
                $apiLat = round($item['coord']['lat'], 4);
                $apiLon = round($item['coord']['lon'], 4);

                $userLat = round($user->latitude, 4);
                $userLon = round($user->longitude, 4);


                return $apiLat == $userLat && $apiLon == $userLon;
            });
            return [
                'user' => $user,
                'weather' => $weather,
            ];

        });

        return $userData;
    }
}
