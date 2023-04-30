<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\ControllerTestTrait;

/**
 * @internal
 */
final class ParamsValidation extends CIUnitTestCase
{
    use ControllerTestTrait;

    public function testProcessFormWithEmptyBody()
    {
        $params = [];

        $response = (new ValidateFormParams())->handle($params);

        $success = $response['success'];
        $code = $response['code'];


        $this->assertTrue(!$success && $code == 601);
    }

    public function testInvalidEmail()
    {
        $params = ['symbol' => 'abc'];

        $response = (new ValidateFormParams())->handle($params);

        $success = $response['success'];
        $code = $response['code'];


        $this->assertTrue(!$success && $code == 605);
    }

    public function testEmptyFromDate()
    {
        $params = ['symbol' => 'abc', 'email' => 'some@person.com'];

        $response = (new ValidateFormParams())->handle($params);

        $success = $response['success'];
        $code = $response['code'];


        $this->assertTrue(!$success && $code == 602);
    }

    public function testEmptyToDate()
    {
        $params = ['symbol' => 'abc', 'email' => 'some@person.com', 'from_date' => '2020-01-02'];

        $response = (new ValidateFormParams())->handle($params);

        $success = $response['success'];
        $code = $response['code'];


        $this->assertTrue(!$success && $code == 603);
    }

    public function testInvalidDates()
    {
        /**
         * Case 1: from date and to date is earlier to current date
         */ {
            $from_date = '2020-02-01';  // less than current date
            $to_date = '2020-02-02';  // less than current date

            $params = ['symbol' => 'abc', 'email' => 'some@person.com', 'from_date' => $from_date, 'to_date' => $to_date];

            $response = (new ValidateFormParams())->handle($params);

            $success = $response['success'];
            $code = $response['code'];


            $this->assertTrue(!$success && $code == 604);
        }

        /**
         * Case 2: from date and to date is greater than current date but from date is greater than to date
         */ {
            $curr_date = new DateTime();
            $curr_date->modify('+1 day');
            $to_date = $curr_date->format('Y-m-d');
            $curr_date->modify('+3 day');
            $from_date = $curr_date->format('Y-m-d');

            $params = ['symbol' => 'abc', 'email' => 'some@person.com', 'from_date' => $from_date, 'to_date' => $to_date];

            $response = (new ValidateFormParams())->handle($params);

            $success = $response['success'];
            $code = $response['code'];


            $this->assertTrue(!$success && $code == 604);
        }

        /**
         * Case 3: to date is greater than current date but from date is less than current date
         */ {
            $curr_date = new DateTime();
            $from_date = '2020-03-01';
            $curr_date->modify('+4 day');
            $to_date = $curr_date->format('Y-m-d');

            $params = ['symbol' => 'abc', 'email' => 'some@person.com', 'from_date' => $from_date, 'to_date' => $to_date];

            $response = (new ValidateFormParams())->handle($params);

            $success = $response['success'];
            $code = $response['code'];


            $this->assertTrue(!$success && $code == 604);
        }
    }

    public function testVerifySymolInvalid()
    {
        /**
         * Valid symbol: PFIN
         * Invalid symbols: PFINN, PFI
         */
        $curr_date = new DateTime();
        $curr_date->modify('+1 day');
        $from_date = $curr_date->format('Y-m-d');
        $curr_date->modify('+2 day');
        $to_date = $curr_date->format('Y-m-d');
        $params = ['symbol' => 'PFINN', 'email' => 'some@person.com', 'from_date' => $from_date, 'to_date' => $to_date];


        $response = BaseHandler::createChain([
            new CacheHandler(new FetchCompanySymbols(), 'company_symbols'),
            new VerifySymbol()
        ])->handle($params);

        $success = $response['success'];
        $code = $response['code'];


        $this->assertTrue(!$success && $code == 404);
    }

    public function testVerifySymolValid()
    {
        /**
         * Valid symbol: PFIN
         * Invalid symbols: PFINN, PFI
         */
        $curr_date = new DateTime();
        $curr_date->modify('+1 day');
        $from_date = $curr_date->format('Y-m-d');
        $curr_date->modify('+2 day');
        $to_date = $curr_date->format('Y-m-d');
        $params = ['symbol' => 'PFIN', 'email' => 'some@person.com', 'from_date' => $from_date, 'to_date' => $to_date];

        $response = BaseHandler::createChain([
            new CacheHandler(new FetchCompanySymbols(), 'company_symbols'),
            new VerifySymbol()
        ])->handle($params);

        $success = $response['success'];
        $code = $response['code'];


        $this->assertTrue($success && $code == 200);
    }
}
