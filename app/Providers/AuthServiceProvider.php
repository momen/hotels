<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Services\HotelApiService;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    public $hotelService;
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(HotelApiService $apiService)
    {
        $this->registerPolicies();

        $this->app->singleton('hotelService',function() use ($apiService){
            return $this->hotelService =$apiService;
        });
    }
}
