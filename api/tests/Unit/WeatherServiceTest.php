<?php

namespace Tests\Unit;

use App\Services\WeatherService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WeatherServiceTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function it_fetches_weather_data_concurrently_for_users()
    {
        $users = \App\Models\User::factory()->count(3)->create();

        $responseData = [
            $this->generateWeatherData(1),
            $this->generateWeatherData(2),
            $this->generateWeatherData(3),
        ];

        Http::fake([
          'example.com/*' => Http::sequence()
                                  ->push($responseData[0])
                                  ->push($responseData[1])
                                  ->push($responseData[2])->pushStatus(500),
        ]);

        $weatherService = new WeatherService();

        $weatherData = $weatherService->fetchWeatherDataConcurrently($users);

        $this->assertCount(3, $weatherData);

        foreach ($users as $user) {
            $this->assertArrayHasKey('coord', $weatherData[$user->id]);
        }
    }

    private function generateWeatherData($userId): array
    {
        return [
            'coord' => [
                'lon' => $this->faker->longitude,
                'lat' => $this->faker->latitude,
            ],
            'weather' => [
                [
                    'id' => 802,
                    'main' => 'Clouds',
                    'description' => 'scattered clouds',
                    'icon' => '03n',
                ],
            ],
            'base' => 'stations',
            'main' => [
                'temp' => $this->faker->randomFloat(2, 270, 310),
                'feels_like' => $this->faker->randomFloat(2, 270, 310),
                'temp_min' => $this->faker->randomFloat(2, 270, 310),
                'temp_max' => $this->faker->randomFloat(2, 270, 310),
                'pressure' => $this->faker->randomFloat(2, 980, 1050),
                'humidity' => $this->faker->randomNumber(2),
                'sea_level' => $this->faker->randomFloat(2, 980, 1050),
                'grnd_level' => $this->faker->randomFloat(2, 980, 1050),
            ],
            'id' => $userId,
            'name' => 'Test City ' . $userId,
            'cod' => 200,
        ];
    }
}
