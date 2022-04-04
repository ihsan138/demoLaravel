<?php

namespace App\Http\Controllers;
use Alert;
use Auth;
use Log;
use App\Models\Purchase\Transaction\Main as testControllerModel;
use App\Models\Purchase\Transaction\Item as testControllerModel2;
// use Salman\Mqtt\MqttClass\Mqtt;
// use Mqtt;
use PhpMqtt\Client\Facades\MQTT;


use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    { 
        return view('home');
    }

    /******************************** Additional methods below ********************************/
    /* Handles user inactivity */
    public function inactivity()
    { 
        Auth::logout();
        // After logout, redirect to inactivity view
        return view('inactivity');
    }

    // public function SendMsgViaMqtt($topic, $message)
    // public function SendMsgViaMqtt()
    // {
    //     // http://stcapqp.test/home/SendMsgViaMqtt
    //         Log::info('entered MQTT');

    //         $mqtt = new Mqtt();
    //         $client_id = Auth::user()->id;
    //         Log::info('entered MQTT2');

    //         Log::info($client_id);

    //         $topic = 'local/test';
    //         $message = '{"channel": "channel2", "di":"2"}';

    //         $output = $mqtt->ConnectAndPublish($topic, $message, $client_id);
    //         Log::info($output);
    //         if ($output === true)
    //         {
    //             return "published";
    //         }
            
    //         return "Failed";
    // }

    //using facade
    public function SendMsgViaMqtt()
    {
        MQTT::publish('some/topic', 'Hello World!');    
    }



    public function SubscribetoTopic()
    {
        /** @var \PhpMqtt\Client\Contracts\MqttClient $mqtt */
        $mqtt = MQTT::connection();
        // Log::info($mqtt);
        $mqtt->subscribe('some/topic', function (string $topic, string $message) {
            echo sprintf('Received QoS level 1 message on topic [%s]: %s', $topic, $message);
        }, 1);
        $mqtt->loop(true);
        // Log::info('done');
    }

}
