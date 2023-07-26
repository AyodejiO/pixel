<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\UserService;
use App\Services\WeatherService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Mockery;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function it_returns_users_with_weather_data()
    {
        User::factory()->count(10)->create();

        $mockWeatherData = $this->generateMockWeatherData(10);

        $mockWeatherService = $this->instance(
            WeatherService::class,
            Mockery::mock(WeatherService::class, function ($mock) use ($mockWeatherData) {
                $mock->shouldReceive('fetchWeatherDataConcurrently')->andReturn($mockWeatherData);
            })
        );

        $userService = new UserService($mockWeatherService);

        $userData = $userService->getUsers();

        $this->assertInstanceOf(Collection::class, $userData);
        $this->assertCount(10, $userData);


        foreach ($userData as $user) {
            $this->assertArrayHasKey('user', $user);
            $this->assertInstanceOf(User::class, $user['user']);

            $this->assertArrayHasKey('weather', $user);
        }
    }

    private function generateMockWeatherData($numberOfUsers): Collection
    {
        $mockWeatherData = collect(array_fill(0, $numberOfUsers, null));

        return $mockWeatherData;
    }

    protected function tearDown(): void
    {
        // Close the mockery.
        Mockery::close();
        parent::tearDown();
    }
}
