<?php

namespace App\Providers;

use App\Models\Profession;
use App\Models\User;
use App\Models\Brand;
use App\Models\Modal;
use App\Models\Category;
use App\Models\Employee;
use App\Models\Dealer;
use App\Models\PromisedPayment;
use App\Models\Stock;


// use Illuminate\Support\Facades\Gate;
use App\Policies\BrandPolicy;
use App\Policies\ModalPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\DealerPolicy;
use App\Policies\EmployeePolicy;
use App\Policies\ProfessionPolicy;
use App\Policies\PromisedPaymentPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
     User::class=>UserPolicy::class,
     Brand::class=>BrandPolicy::class,
     Modal::class=>ModalPolicy::class,
     Category::class=>CategoryPolicy::class,
     Employee::class=>EmployeePolicy::class,
     Dealer::class=>DealerPolicy::class,
     PromisedPayment::class=>PromisedPaymentPolicy::class,
     Profession::class=>ProfessionPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
