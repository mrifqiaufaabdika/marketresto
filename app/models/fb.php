<?php
/**
 * Created by PhpStorm.
 * User: abdialam
 * Date: 9/20/18
 * Time: 10:57
 */

namespace App\Model;


class fb
{
    public function  send ($registration_ids,$message){
        $fields = array(
            'registration_ids' => $registration_ids,
            'data' => $message,
        );
        return $this->sendPushNotification($fields);
    }

    private function sendPushNotification($fields){
        //importing db
        $this->db;


        //firebase url
        $url = 'https://fcm.googleapis.com/fcm/send';


        $headers = array(
            'Authorization: key=AAAAWnIoi-M:APA91bG1MIVkjqkQYkMCIjK1NAmfSiLeRupTWU7pv0wJP7dWmmsxRBNq2cM5VEUmASnSV2uSyqQUeIChf3pUml6KfxAdEdQyuPx3ALlk-XhA1w_xO9YFL5-mlGOhKWVfQKw5sGokVegrXwHnOLY2EU2E-WN46q9ArQ',
            'Content-Type: application/json'
        );


        //Initializing curl to open a connection
        $ch = curl_init();

        //Setting the curl url
        curl_setopt($ch, CURLOPT_URL, $url);

        //setting the method as post
        curl_setopt($ch, CURLOPT_POST, true);

        //adding headers
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //disabling ssl support
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        //adding the fields in json format
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        //finally executing the curl request
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        //Now close the connection
        curl_close($ch);

        //and return the result
        return $result;
    }
}