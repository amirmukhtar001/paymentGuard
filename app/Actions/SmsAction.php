<?php

namespace App\Actions;

use Illuminate\Support\Facades\Http;

class SmsAction
{
    public function sendSms($to, $message, $module = null)
    {
        $to = $this->formatPhoneNumber($to);
        return  $this->sendKissanSms($to, $message);
        // $provider = setting('sms_provider', 'jazz');

        // return $provider === 'twilio'
        //     ? $this->sendViaTwilio($to, $message)
        //     : $this->sendViaJazz($to, $message);
    }

    protected function sendViaJazz($to, $message)
    {
        $url = setting('jazz_url', 'https://connect.jazzcmt.com/sendsms_url.html');

        $queryParams = [
            'Username' => setting('jazz_username'),
            'Password' => setting('jazz_password'),
            'From'     => setting('jazz_sender', 'Agri Dep.'),
            'To'       => $to,
            'Message'  => $message,
        ];

        $response = Http::withOptions(['verify' => false])->get($url, $queryParams);
        return $response->body();
    }

    protected function sendViaTwilio($to, $message)
    {
        $sid     = setting('twilio_sid');
        $token   = setting('twilio_token');
        $from    = setting('twilio_from');
        $baseUrl = setting('twilio_url', 'https://api.twilio.com/2010-04-01');

        $url = "{$baseUrl}/Accounts/{$sid}/Messages.json";

        $response = Http::withOptions(['verify' => false])->withBasicAuth($sid, $token)->asForm()->post($url, [
            'From' => $from,
            'To'   => '+' . $to,
            'Body' => $message,
        ]);


        return $response->successful() ? 'Sent' : $response->body();
    }

    // public function formatPhoneNumber($number)
    // {
    //     $number = preg_replace('/\\D/', '', $number);
    //     $number = preg_replace('/^0/', '92', $number);
    //     $number = preg_replace('/^3/', '92$0', $number);
    //     $number = preg_replace('/^92/', '92', $number);
    //     return $number;
    // }
    public function formatPhoneNumber($number)
    {
        // Remove all non-numeric characters like -, space, (, )
        $number = preg_replace('/\D/', '', $number);

        // If it starts with 0, replace it with 92
        if (strpos($number, '0') === 0) {
            $number = '92' . substr($number, 1);
        }

        // If it starts with 3 and length is 10 (like 3xxxxxxxxx), add 92
        if (strpos($number, '3') === 0 && strlen($number) === 10) {
            $number = '92' . $number;
        }

        // If it already starts with 92, keep it as is
        return $number;
    }

    function sendKissanSms(string $number, string $message): array
    {
        try {
            $response = Http::withOptions(['verify' => false])->asForm()->post('https://kissan.stepnexs.com/admin/sms_service', [
                'mobile_number' => $number,
                'message' => $message,
            ]);

            

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            } else {
                return [
                    'success' => false,
                    'error' => 'HTTP Error: ' . $response->status()
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
