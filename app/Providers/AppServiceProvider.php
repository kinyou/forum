<?php

namespace App\Providers;

use App\Channel;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //解决单元测试中报[SQLSTATE[42S02]: Base table or view not found]
        try{
            $channels = Channel::all();
        }catch (QueryException $queryException) {
            $channels = [];
        }
        //所有模板文件共享此数据
        View::share('channels',$channels);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
