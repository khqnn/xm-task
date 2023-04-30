<?php


class FetchCompanySymbols extends BaseHandler
{


    public function handle($params)
    {


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => getenv('SYMBOLS_URL'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $companySymbols = json_decode($response, true);




        $nextHandleResponse = $this->callNextHandler($params);
        $data = $nextHandleResponse['data'];
        $data['company_symbols'] = $companySymbols;
        $nextHandleResponse['data'] = $data;
        return $nextHandleResponse;
    }
}
