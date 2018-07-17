<?php
/**
 *
 * Author: xingyuanwang
 * Email : kinyou_xy@126.com
 * Date  :  2018-7-10-16:32
 */

/**
 * 得到一个模型实例，并保存到数据库中
 * @param string $class
 * @param array $attributes
 * @param null $times
 * @return mixed
 */
function create(string $class, array $attributes = [],$times = null) {
    return factory($class,$times)->create($attributes);
}

/**
 * 得到一个模型实例（不保存）到数据库
 * @param string $class
 * @param array $attributes
 * @param null $times
 * @return mixed
 */
function make(string $class, array $attributes = [],$times = null){
    return factory($class,$times)->make($attributes);
}

/**
 * 得到一个模型实例转化后的数组
 * @param string $class
 * @param array $attributes
 * @param null $times
 * @return mixed
 */
function raw(string $class, array $attributes = [],$times = null) {
    return factory($class,$times)->raw($attributes);
}