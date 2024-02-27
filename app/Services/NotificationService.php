<?php
namespace App\Services;


use App\Models\User;

class NotificationService
{
    public function sendNotification($user, $message)
    {
        $user = User::where('id', $user)->first();
        $body = array(
            "api_key" => "38f71e4b924e4b88aa1d5674db2db35f358087f7",
            "receiver" => $user->phone,
            "data" => array("message" => 'Selamat di Sistem SI-RIM, *'.$user->name.'* . '.$message)
        );

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://wa.timpandawalima.id/api/send-message",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($body),
            CURLOPT_HTTPHEADER => [
                "Accept: */*",
                "Content-Type: application/json",
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
        return $response;
    }

    public function sendNotificationWithImage($user, $message, $image)
    {
        $user = User::where('id', $user)->first();
        $body = array(
            "api_key" => "38f71e4b924e4b88aa1d5674db2db35f358087f7",
            "receiver" => $user->phone,
            "data" => array(
                "url" => "https://i.ibb.co/QbmsBqs/code.png",
                "media_type" => "image",
                "caption" => "Hello World"
            )
        );

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://wa.timpandawalima.id/api/send-media",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($body),
            CURLOPT_HTTPHEADER => [
                "Accept: */*",
                "Content-Type: application/json",
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
    }
}
