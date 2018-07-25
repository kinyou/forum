<?php
namespace App;

trait Favoritable {

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

    /**
     * 判断用户是否点过赞
     * @return bool
     */
    public function isFavorited()
    {
        return !! $this->favorites->where('user_id',auth()->id())->count();
    }


    public function getFavoritersCountAttribute()
    {
        return $this->favorites->count();
    }
}
