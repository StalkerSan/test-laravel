<?php

namespace App\Providers;

use App\Contracts\Service\ProductRepositoryContract;
use App\Repositories\ProductRepositoryEloquent;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public $bindings = [
        ProductRepositoryContract::class => ProductRepositoryEloquent::class,
    ];

}
