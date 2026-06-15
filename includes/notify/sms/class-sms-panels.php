<?php

if (!defined('ABSPATH')) {
    exit;
}

class Factorchi_SMS_Panels
{
    private string $username;
    private string $password;
    private string $sender_number;
    public string $pattern_id = '';

    public function __construct(string $username, string $password, string $sender_number)
    {
        $this->username      = $username;
        $this->password      = $password;
        $this->sender_number = $sender_number;
    }

    public function send(string $panel, string $to, string $text): bool
    {
        $method = $panel . '_shared';
        if (!method_exists($this, $method)) {
            return $this->payamak_panel($to, $text);
        }

        return (bool) $this->{$method}($to, $text);
    }

    public function smsir_shared(string $to, string $text): bool
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL            => 'https://api.sms.ir/v1/send/verify',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POSTFIELDS     => json_encode([
                'mobile'     => $to,
                'templateId' => (int) $this->pattern_id,
                'parameters' => [['name' => 'url', 'value' => $text]],
            ]),
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Accept: text/plain',
                'x-api-key: ' . $this->password,
            ],
        ]);
        $response = curl_exec($curl);
        curl_close($curl);
        $decoded = json_decode((string) $response);

        return isset($decoded->status) && (int) $decoded->status === 1;
    }

    public function payamak_panel_shared(string $to, $text): bool
    {
        $vars = is_array($text) ? $text : [$text];
        try {
            $client     = new SoapClient('http://api.payamak-panel.com/post/Send.asmx?wsdl');
            $parameters = [
                'username' => $this->username,
                'password' => $this->password,
                'from'     => $this->sender_number,
                'to'       => $to,
                'text'     => $vars,
                'bodyId'   => $this->pattern_id,
            ];
            $result = $client->SendByBaseNumber($parameters)->SendByBaseNumberResult;
        } catch (Throwable $ex) {
            return false;
        }

        return is_numeric($result) && strlen((string) $result) > 15;
    }

    public function payamak_panel(string $to, string $text, bool $is_flash = false): bool
    {
        try {
            $client     = new SoapClient('http://api.payamak-panel.com/post/Send.asmx?wsdl');
            $parameters = [
                'username' => $this->username,
                'password' => $this->password,
                'from'     => $this->sender_number,
                'to'       => $to,
                'text'     => trim($text),
                'isflash'  => $is_flash,
            ];
            $result = $client->SendSimpleSMS2($parameters)->SendSimpleSMS2Result;
        } catch (Throwable $ex) {
            return false;
        }

        return is_numeric($result) && strlen((string) $result) > 15;
    }

    public function farapayamak_shared(string $to, string $text): bool
    {
        return $this->payamak_panel_shared($to, $text);
    }

    public function melipayamak_shared(string $to, string $text): bool
    {
        return $this->payamak_panel_shared($to, $text);
    }

    public function ippanel_shared(string $to, $text): bool
    {
        try {
            $client = new SoapClient('http://ippanel.com/class/sms/wsdlservice/server.php?wsdl');
            $data   = is_array($text) ? $text : ['url' => $text];
            $result = $client->sendPatternSms(
                $this->sender_number,
                [$to],
                $this->username,
                $this->password,
                $this->pattern_id,
                $data
            );
        } catch (Throwable $ex) {
            return false;
        }

        return is_numeric($result) && strlen((string) $result) > 5;
    }

    public function farazsms_shared(string $to, string $text): bool
    {
        return $this->ippanel_shared($to, $text);
    }

    public function maxsms_shared(string $to, string $text): bool
    {
        return $this->ippanel_shared($to, $text);
    }

    public function modirpayamak_shared(string $to, string $text): bool
    {
        return $this->ippanel_shared($to, $text);
    }
}
