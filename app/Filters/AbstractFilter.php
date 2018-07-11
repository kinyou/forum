<?php
/**
 *
 * Author: xingyuanwang
 * Email : kinyou_xy@126.com
 * Date  :  2018-7-11-19:56
 */
namespace App\Filters;

use Illuminate\Http\Request;

abstract class AbstractFilter {

    protected $request;
    protected $builder;
    protected $filters = [];

    /**
     * AbstractFilter constructor.
     * @param $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * 应用过滤器
     * @param $builder
     */
    public function apply($builder)
    {
        $this->builder = $builder;

        foreach ($this->getFilters() as $filter=>$value) {

            if (method_exists($this,$filter)) {
                $this->$filter($value);
            }
        }

        return $this->builder;
    }

    /**
     * 获取要过滤的参数
     * @return array
     */
    public function getFilters()
    {
        return $this->request->only($this->filters);
    }


}