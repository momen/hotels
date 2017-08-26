<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Repositories\HotelRepository;

class HomeController extends Controller {

    protected $hotelRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(HotelRepository $hotelRepository) {
        $this->hotelRepository = $hotelRepository;
    }

    /**
     * 
     * @return type
     */
    public function index() {

        return view('welcome');
    }

    /**
     * 
     * @param Request $request
     * @return type
     */
    public function destination(Request $request) {


        $parameters = ['username' => 'test'];
        $destination = $this->hotelRepository->getDestinations($parameters);
        //echo "<pre>";print_r($destination);die;
        return view('destination', compact('destination'));
    }
    
    
}
