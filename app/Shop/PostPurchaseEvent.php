<?php

namespace BibleBowl\Shop;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Fluent;

abstract class PostPurchaseEvent extends Fluent
{

    /**
     * @return string
     */
    abstract public function successStep();

    /**
     * @param $event
     */
    protected function setEvent($event)
    {
        $this->event = $event;
    }

    /**
     * @param Arrayable $arrayable
     */
    protected function setEventData(Arrayable $arrayable)
    {
        $this->eventData = $arrayable->toArray();
    }

    /**
     * @param []
     */
    protected function eventData()
    {
        return $this->eventData;
    }

    /**
     * @return string
     */
    public function event()
    {
        return $this->event;
    }

    /**
     * Fire the event
     *
     * @return void
     */
    abstract public function fire();
}
