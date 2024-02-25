<?php

namespace App\Providers;

use App\Models\UserAbility;
use App\Modules\Users\UserRepository;
use App\Modules\Users\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use App\Modules\Goods\GoodRepositoryInterface;
use App\Modules\Goods\GoodRepository;
use App\Modules\Stocks\StockRepositoryInterface;
use App\Modules\Stocks\StockRepository;
use App\Modules\GoodDetails\GoodDetailRepositoryInterface;
use App\Modules\GoodDetails\GoodDetailRepository;
use App\Modules\Deals\DealRepositoryInterface;
use App\Modules\Deals\DealRepository;
use App\Modules\Dealers\DealerRepositoryInterface;
use App\Modules\Dealers\DealerRepository;
use App\Modules\Employees\EmployeeRepositoryInterface;
use App\Modules\Employees\EmployeeRepository;
use App\Modules\PromisedPayments\PromisedPaymentRepository;
use App\Modules\PromisedPayments\PromisedPaymentRepositoryInterface;
use App\Modules\BuiltInTasks\BuiltInTaskRepository;
use App\Modules\BuiltInTasks\BuiltInTaskRepositoryInterface;
use App\Modules\UserAbilities\UserAbilityRepository;
use App\Modules\UserAbilities\UserAbilityRepositoryInterface;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {  
     $this->app->bind(PromisedPaymentRepositoryInterface::class,PromisedPaymentRepository::class);
     $this->app->bind(EmployeeRepositoryInterface::class,EmployeeRepository::class);
     $this->app->bind(DealRepositoryInterface::class,DealRepository::class);
     $this->app->bind(UserRepositoryInterface::class,UserRepository::class);
     $this->app->bind(GoodDetailRepositoryInterface::class,GoodDetailRepository::class);
     $this->app->bind(GoodRepositoryInterface::class,GoodRepository::class);
     $this->app->bind(StockRepositoryInterface::class,StockRepository::class);
     $this->app->bind(DealerRepositoryInterface::class,DealerRepository::class);
     $this->app->bind(BuiltInTaskRepositoryInterface::class,BuiltInTaskRepository::class);
     $this->app->bind(UserAbilityRepositoryInterface::class,UserAbilityRepository::class);        
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
