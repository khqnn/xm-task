<?php

namespace App\Controllers;


use BaseHandler;
use CacheHandler;
use CreateProcessFormResponse;
use FetchCompanySymbols;
use FetchHistoricalData;
use FilterHistoricalData;
use SendEmail;
use SessionManager;
use ValidateFormParams;
use VerifySymbol;

class Api extends BaseController
{

    public function index()
    {
        return $this->response->setJSON(['data' => []]);
    }

    public function pergeCache()
    {
        $successResponse = ['success' => true, 'code' => 200, 'data' => []];
        cache()->clean();
        return $this->response->setJSON($successResponse);
    }

    public  function getCompanySymbols()
    {
        $body =  BaseHandler::createChain([
            new CacheHandler(new FetchCompanySymbols(), 'company_symbols'),
        ])->handle([]);

        return $this->response->setJSON($body);
    }


    public function processForm()
    {

        /**
         * @todo need to uncomment dummy historical data and fetch real historical data
         */

        /**
         * Get params list
         */
        $params = [
            "symbol" => $this->request->getVar('symbol'),
            "from_date" => $this->request->getVar('from_date'),
            "to_date" => $this->request->getVar('to_date'),
            "email" => $this->request->getVar('email'),
            "region" => $this->request->getVar('region')
        ];

        /**
         * Process form
         * Note: The email send handler is commented because it takes time to send email and client keep waiting for response
         * Suggestion: Use an independant service for email so the resources consumed for sending email should beared by an independant service
         */
        $response = BaseHandler::createChain([
            new CreateProcessFormResponse(),
            new SessionManager(),
            new ValidateFormParams(),
            new CacheHandler(new FetchCompanySymbols(), 'company_symbols'),
            new VerifySymbol(),
            new CacheHandler(new FetchHistoricalData(), 'historical_data'),
            new FilterHistoricalData(),
            // new SendEmail()
        ])->handle($params);


        return $this->response->setJSON($response);
    }
}
