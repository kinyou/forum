<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    /**
     * 表示所有字段都可以填充
     * @var array
     */
    protected $guarded = [];


    /**
     * 生成单个帖子的url地址
     * 备注:这里可以访问单个模型的任意属性[也就是数据库中表的字段]
     * @return string
     */
    public function path()
    {
        return '/threads/' . $this->channel->slug . '/' .  $this->id;
    }

    /**
     * 定义关联关系 一个帖子有多个回复
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    /**
     * 定义关联关系,一个帖子属于具体的某一个用户
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class,'user_id');

    }

    /**
     * 添加帖子回复
     * @param array $reply
     */
    public function addReply(array $reply)
    {
        //调用$this->>replies()的关联关系方法,可返回一个Reply的model
        $this->replies()->create($reply);
    }


    /**
     * 定义关联关系一篇帖子属于一个分类
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function channel()
    {
        return $this->belongsTo(Channel::class);

    }

    /**
     * laravel本地作用域的使用-只需简单在对应 Eloquent 模型方法前加上一个 scope 前缀,作用域总是返回查询构建器
     * @param $query
     * @param $filters
     * @return mixed
     */
    public function scopeFilter($query,$filters)
    {
        return $filters->apply($query);
    }

}
