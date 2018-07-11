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

    protected $filters = ['by'];

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


}