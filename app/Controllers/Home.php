<?php

namespace App\Controllers;

use CacheHandler;
use FetchCompanySymbols;

class Home extends BaseController
{
    public function index()
    {
        $results = (new CacheHandler(new FetchCompanySymbols(), 'company_symbols'))->handle([]);
        return view('form', $results);
    }
}
