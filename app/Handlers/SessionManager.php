<?php


class SessionManager extends BaseHandler
{

    public function handle($params)
    {

        $db = db_connect();
        $db->transBegin();

        $nexthandlerResponse = $this->callNextHandler($params);


        if (!$nexthandlerResponse['success']) {
            $db->transRollback();
            return $nexthandlerResponse;
        }

        $db->transCommit();
        return $nexthandlerResponse;
    }
}
