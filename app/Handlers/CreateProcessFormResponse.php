<?php


class CreateProcessFormResponse extends BaseHandler
{

    public function handle($params)
    {

        $nexthandlerResponse = $this->callNextHandler($params);
        $data = $nexthandlerResponse['data'];
        unset($data['historical_data']);
        unset($data['company_symbols']);
        $nexthandlerResponse['data'] = $data;
        return $nexthandlerResponse;
    }
}
