<?php

class VerifySymbol extends BaseHandler
{
    public function handle($params)
    {

        $symbol = $params['symbol'];
        $companySymbols = $params['company_symbols'];
        $symbols =  array_column($companySymbols, 'Symbol');
        $index = array_search($symbol, $symbols);

        if (!$index) {
            return ['success' => false, "code" => 404, 'data' => [], 'message' => 'Symbol is not correct'];
        }

        $companyDetails = $companySymbols[$index];
        $params['company_details'] = $companyDetails;

        $nexthandlerResponse = $this->callNextHandler($params);
        $data = $nexthandlerResponse['data'];
        $data['company_details'] = $companyDetails;
        $nexthandlerResponse['data'] = $data;
        return $nexthandlerResponse;
    }
}
