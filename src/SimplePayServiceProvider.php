<?php

namespace KomjIT\SimplePay;

use Illuminate\Support\ServiceProvider;

class SimplePayServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/resources/views', 'simplepay');
    }

    public function register()
    {
    }
}

?>
