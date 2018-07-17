<?php
/**
 *
 * Author: xingyuanwang
 * Email : kinyou_xy@126.com
 * Date  :  2018-7-11-19:38
 */
namespace App\Filters;

use App\User;

class ThreadsFilters extends AbstractFilter {

    protected $filters = ['by','popularity'];

    /**
     * 每一搜索条件拆分成为一个方法
     *
     * @param $username
     * @return mixed
     */
    public function by($username) {

        $user = User::where('name',$username)->firstOrFail();

        return $this->builder->where('user_id',$user->id);
    }


    /**
     * 按照评论数排序
     * @return mixed
     */
    public function popularity() {
        //清空其他的排序条件
        $this->builder->getQuery()->orders = [];

        return $this->builder->orderBy('replies_count','desc');
    }


}