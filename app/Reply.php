<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    /**
     * 表示所有字段都可以填充
     * @var array
     */
    protected $guarded = [];

    /**
     * 定义关联关系 一个回复属于具体的某一个用户
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        // 使用 user_id 字段进行模型关联
        return $this->belongsTo(User::class,'user_id');
    }

    /**
     * 定义多对多关联一个喜欢既可以属于帖子或者评论
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function favorites()
    {
        return $this->morphMany(Favorite::class,'favorited');
    }

    /**
     * 保存点赞
     * @return Model
     */
    public function favorite()
    {
        $attributes = ['user_id'=>auth()->id()];

        //一个同时对一个帖子点一次赞
        if (! $this->favorites()->where($attributes)->exists()) {

            return $this->favorites()->create($attributes);
        }
    }
}

