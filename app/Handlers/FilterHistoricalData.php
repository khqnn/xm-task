<?php


class FilterHistoricalData extends BaseHandler
{


    private function filterData($historicalData, $fromDate, $toDate)
    {
        $filteredData = [];
        foreach ($historicalData as $item) {
            $date = $item['date'];
            if ($date >= $fromDate && $date <= $toDate) {
                $filteredData[] = $item;
            }
        }

        return $filteredData;
    }

    public function handle($params)
    {
        /**
         * Filter and sort prices data based on timestamp
         */

        $fromDate = strtotime($params['from_date']);
        $toDate = strtotime($params['to_date']);


        $historicalData = $params['historical_data'];
        $prices = $historicalData['prices'];
        $filteredPrices = $this->filterData($prices, $fromDate, $toDate);

        usort($filteredPrices, function ($a, $b) {
            return strcmp($b['date'], $a['date']);
        });

        $nexthandlerResponse = $this->callNextHandler($params);
        $data = $nexthandlerResponse['data'];
        $data['filtered_prices'] = $filteredPrices;
        $nexthandlerResponse['data'] = $data;
        return $nexthandlerResponse;
    }
}
