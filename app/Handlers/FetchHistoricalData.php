<?php

class FetchHistoricalData extends BaseHandler
{

    private function generateDummyHistoricalData()
    {
        $fromDate = 1685559600;
        $toDate = 1690830000;

        $diff = 86400;

        $dummyData = [];
        $decimalPoints = 1e15;

        for ($i = 0; $i < 50; $i++) {
            $fromDate =  $fromDate + $diff;
            $open = rand(13 * $decimalPoints, 16 * $decimalPoints) / $decimalPoints;
            $close = rand(13 * $decimalPoints, 16 * $decimalPoints) / $decimalPoints;
            $high = rand(13 * $decimalPoints, 16 * $decimalPoints) / $decimalPoints;
            $low = rand(13 * $decimalPoints, 16 * $decimalPoints) / $decimalPoints;
            $adjclose = rand(13 * $decimalPoints, 16 * $decimalPoints) / $decimalPoints;
            $volume = rand(23000000, 85000000);

            $dummyData[] = ['date' => $fromDate, 'open' => $open, 'close' => $close, 'high' => $high, 'low' => $low, 'adjclose' => $adjclose, 'volume' => $volume];
        }

        return $dummyData;
    }


    public function handle($params)
    {

        // $curl = curl_init();

        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => getenv('HISTORICAL_DATA_URL') . '?symbol=' . $params['symbol'] . '&region=' . $params['region'],
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => '',
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 0,
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => 'GET',
        //     CURLOPT_HTTPHEADER => array(
        //         'X-RapidAPI-Key: ' . getenv('API_KEY'),
        //         'X-RapidAPI-Hos: yh-finance.p.rapidapi.com'
        //     ),
        // ));

        // $response = curl_exec($curl);

        // curl_close($curl);


        // $historicalData = json_decode($response, true);


        $historicalData = ['prices' => $this->generateDummyHistoricalData()];


        if (!($historicalData && !empty($historicalData) && key_exists('prices', $historicalData))) {

            return ['success' => false, 'code' => 404, 'data' => [], 'message' => 'Could not get historical data'];
        }

        $params['historical_data'] = $historicalData;

        $nexthandlerResponse = $this->callNextHandler($params);
        $data = $nexthandlerResponse['data'];
        $data['historical_data'] = $historicalData;
        $nexthandlerResponse['data'] = $data;
        return $nexthandlerResponse;
    }
}
