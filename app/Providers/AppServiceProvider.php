<?php

namespace App\Providers;

use App\Channel;
use Illuminate\Support\Facades\Cache;
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
        //视图合成器,只有在视图渲染的时候调用
        View::composer('*',function($view){
            //因为分类基本不变可以缓存起来
            $channels = Cache::rememberForever('channels',function (){
                return Channel::all();
            });
           $view->with('channels',$channels);
        });
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
