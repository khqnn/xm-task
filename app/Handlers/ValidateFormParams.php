<?php

class ValidateFormParams extends BaseHandler
{
    public function handle($params)
    {

        $validation = \Config\Services::validation();

        if (empty($params['symbol']) || !$validation->check($params['symbol'], 'required')) {
            return ["success" => false, "code" => 601, "data" => [], "message" => "Symbol is required"];
        }

        if (empty($params['email']) || !$validation->check($params['email'], 'required|valid_email')) {
            return ["success" => false, "code" => 605, "data" => [], "message" => "Valid email is required"];
        }

        if (empty($params['from_date']) || !$validation->check($params['from_date'], 'required|valid_date')) {
            return ["success" => false, "code" => 602, "data" => [], "message" => "from date is not valid"];
        }

        if (empty($params['to_date']) || !$validation->check($params['to_date'], 'required|valid_date')) {
            return ["success" => false, "code" => 603, "data" => [], "message" => "to date is not valid"];
        }

        $curr_date =  (new DateTime())->format("Y-m-d");

        if ($params['from_date'] < $curr_date || $params['from_date'] > $params['to_date']) {
            return ["success" => false, "code" => 604, "data" => [], "message" => "from date is incorrect"];
        }

        $nexthandlerResponse = $this->callNextHandler($params);
        return $nexthandlerResponse;
    }
}
