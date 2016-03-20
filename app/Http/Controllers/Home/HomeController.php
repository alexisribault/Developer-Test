<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Carbon;
use App\RoomType;
use Illuminate\Support\Facades\View;

class HomeController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Home Controller
    |--------------------------------------------------------------------------
    | display the cheapest rate per room types and the cheapest overall
    |
    */

    /**
     * home page display
     *
     * @return View
     */
    protected function index()
    {
        // set the date range to 21 days from now
        $dateRangeFromToday = 21;
        // call the function that will call the API
        $response = $this->getDataApi($dateRangeFromToday);

        // set both variables to null if no data is returned
        $cheapestRoomTypes = null;
        $roomType = null;

        // if the response code is 200 the result will appear in the page
        if($response->getStatusCode() === 200)
        {
            // decode the response
            $results = json_decode($response->getBody())[0];

            // set the variables
            $cheapestRoomTypes = $this->cheapestRoomTypes($results);
            $roomType = $this->roomType($cheapestRoomTypes);
        }

        // return view
        return View::make('index')->with(compact('roomType', 'cheapestRoomTypes'));       
    }

    /**
     * return the response from the API call
     *
     * @param $dateRangeFromToday
     *
     * @return \GuzzleHttp\Message\FutureResponse|\GuzzleHttp\Message\ResponseInterface|\GuzzleHttp\Ring\Future\FutureInterface|null
     */
    protected function getDataApi($dateRangeFromToday) {
        // get the variables from the config file to call the right API
        $baseSiteUri = config('api.baseSiteUrl');
        $channelCode = config('api.channelCode');
        $format = config('api.format');

        // set the end date to be '$dateRangeFromToday' days ahead from now
        $endDate = Carbon\Carbon::now("Australia/Brisbane")->addDays($dateRangeFromToday)->toDateString();

        // set the url with the config variables
        $apiUrl = $baseSiteUri . "/api/v1/properties/" . $channelCode . "/rates" . $format . "?end_date=" . $endDate;

        // use guzzle to make the API call
        $client = new Client();
        $response = $client->get($apiUrl);

        return $response;
    }

    /**
     * display the cheapest rate per room types
     *
     * @param $results
     *
     * @return array|object
     */
    protected function cheapestRoomTypes($results) {

        // set empty array
        $cheapestRoomTypes = [];

        // loop through all room types
        foreach($results->room_types as $room_type) {
            $roomType = null;
            // select the cheapest rate out of all options
            foreach($room_type->room_type_dates as $room_type_date) {
                if($roomType === null || $room_type_date->rate < $roomType->rate){
                    // create an object RoomType
                    $roomType = new RoomType;
                    $roomType->rate = $room_type_date->rate;
                    $roomType->date = $room_type_date->date;
                    $roomType->name = $room_type->name;
                }
            }
            // add the object to the array
            array_push($cheapestRoomTypes, $roomType);
        }

        // transform the array into an object
        $cheapestRoomTypes = (object) $cheapestRoomTypes;
        
        return $cheapestRoomTypes;
    }

    /**
     * display the cheapest rate per room type overall from the room types list
     *
     * @param $cheapestRoomTypes
     *
     * @return RoomType|array|object
     */
    protected function roomType($cheapestRoomTypes) {
        // set empty roomType
        $roomType = null;

        // loop through all room types cheapest prices
        foreach($cheapestRoomTypes as $cheapestRoomType) {
            // select the cheapest rate out of all room types
            if($roomType === null || $cheapestRoomType->rate < $roomType->rate){
                // create an object RoomType
                $roomType = new RoomType;
                $roomType->rate = $cheapestRoomType->rate;
                $roomType->date = $cheapestRoomType->date;
                $roomType->name = $cheapestRoomType->name;
            }
        }

        // transform the array into an object
        $roomType = (object) $roomType;

        return $roomType;
    }
}
