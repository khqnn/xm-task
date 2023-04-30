<?php

class SendEmail extends BaseHandler
{
    public function handle($params)
    {

        $companyDetails = $params['company_details'];
        $companyName = $companyDetails['Company Name'];
        $fromDate = $params['from_date'];
        $toDate = $params['to_date'];
        $emailAddress = $params['email'];

        $subject = $companyName;
        $body = "From $fromDate to $toDate";


        $email = \Config\Services::email();
        $email->setTo($emailAddress);
        $email->setFrom('khaqan@gmail.com', 'XM Data');

        $email->setSubject($subject);
        $email->setMessage($body);
        $email->send();

        $nexthandlerResponse = $this->callNextHandler($params);
        return $nexthandlerResponse;
    }
}
