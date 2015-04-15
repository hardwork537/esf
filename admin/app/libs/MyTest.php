<?php
use Phalcon\Events\EventsAwareInterface;

class MyTest implements EventsAwareInterface
{

    public $name = null;

    protected $_eventsManager;

    public function setEventsManager($eventsManager)
    {
        $this->_eventsManager = $eventsManager;
    }

    public function getEventsManager()
    {
        return $this->_eventsManager;
    }


    public function setName()
    {
        $this->name = "张三";
        return $this;
    }


    public function say()
    {
        $this->_eventsManager->fire("my-test:beforeSay", $this);

        echo "我的名字:".$this->name;

        $this->_eventsManager->fire("my-test:afterSay", $this);

    }
}