<?php

namespace App\Repositories;

class HotelRepository implements HotelInterface{
    /**
     *
     * @var object 
     */
    protected $hotelService;
    
    /**
     * Intializing the hotelService object.
     */
    public function __construct() {
        $this->hotelService = \App::make('hotelService');
    }

    /**
     * Getting the hotel destinations. 
     * @param array $parameters
     * @return array
     */
    public function getDestinations($parameters){

    	$result = $this->hotelService->makeRequest($parameters);
      
        return $result;
    }
    
}
