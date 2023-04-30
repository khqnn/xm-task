<?php


abstract class BaseHandler
{
    private  $nextHandler = null;

    abstract public function handle($params);

    public function setNextHandler($handler)
    {
        if ($handler) {
            $this->nextHandler = $handler;
        }
    }

    public function callNextHandler($params)
    {
        if ($this->nextHandler) {
            return $this->nextHandler->handle($params);
        }

        return [
            "success" => true,
            "data" => [],
            "code" => 200
        ];
    }

    public static function createChain($handlers)
    {
        for ($handlerIndex = 1; $handlerIndex < sizeof($handlers); $handlerIndex++) {
            $handlers[$handlerIndex - 1]->setNextHandler($handlers[$handlerIndex]);
        }

        return $handlers[0];
    }
}
