<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    /**
     * 定义关联关系 一个分类有多个帖子
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function threads()
    {
        return $this->hasMany(Thread::class);
    }

    /**
     * 重写路由的隐式绑定自定义键名默认是id,如果不存在会自动抛出404异常
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
