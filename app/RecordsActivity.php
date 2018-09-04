<?php
/**
 * @author   xingyuanwang <kinyou_xy@126.com>
 * @time     2018-9-4 11:54
 */

namespace App;

trait RecordsActivity {

    /**
     * 不知道怎么执行到这里的,后续要xdebug去调试
     * @return void
     */
    protected static function bootRecordsActivity()
    {
        //如果用户未登陆直接返回
        if (auth()->guest()) return ;

        //观察器创建帖子的时
        foreach (static::getActivitiesToRecord() as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }
    }

    /**
     *因为监听不光是created还可能有deleted,updated之类的
     *要是监听别的事件只需要在这个数组里面添加对应的事件即可
     * @return array
     */
    protected static function getActivitiesToRecord()
    {
        return ['created'];
    }

    /**
     * 抽取可复用的动作流方法
     *
     * @param $event
     *
     * @throws \ReflectionException
     * @return void
     */
    protected function recordActivity($event)
    {
        Activity::create([
            'user_id'=>auth()->id(),
            'type'=>$this->getActivityType($event),
            'subject_id'=>$this->id,
            'subject_type'=>get_class($this)
        ]);

    }

    /**
     *多态关联
     *
     * @return mixed
     */
    protected function activity()
    {
        return $this->morphMany('App\Activity','subject');
    }

    /**
     * 获取事件的类型
     *
     * @param $event
     *
     * @throws \ReflectionException
     * @return string
     */
    protected function getActivityType($event)
    {
        $type = strtolower((new \ReflectionClass($this))->getShortName());

        return "{$event}_{$type}";
    }

}