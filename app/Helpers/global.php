<?php




if (!function_exists('create_chain')) {
    function create_chain($handlers = [])
    {
        for ($handlerIndex = 1; $handlerIndex < sizeof($handlers); $handlerIndex++) {
            $handlers[$handlerIndex - 1]->nextHandler = $handlers[$handlerIndex];
        }

        return $handlers[0];
    }
}
