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
        return '/threads/' . $this->id;
    }

    /**
     * 定义关联关系 一个帖子有多个回复
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }
}
