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
}

