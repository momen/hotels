<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Repositories\HotelRepository;
use Illuminate\Http\JsonResponse;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        //$response = $this->get('/');
        //$response->assertStatus(200);
       
        $parameters = array();
        $hotelRepository = new HotelRepository();
        $data = $hotelRepository->getDestinations($parameters);
        //$data = [];
        $this->assertEquals(true, is_array($data));
        
        if(!empty($data)){
            
            $this->assertEquals(true, array_has($data, 'hotels'));
            
            $hotels = $data['hotels'];
            
            $hotelSize = count($hotels);
            $i = rand(0, $hotelSize);

            //$this->assertContains(false, $items);

            $this->assertEquals(true, array_has($hotels[$i], 'name'));
            $this->assertEquals(true, array_has($hotels[$i], 'price'));
            $this->assertEquals(true, array_has($hotels[$i], 'city'));
            $this->assertEquals(true, array_has($hotels[$i], 'availability'));
        }        
        
        
        
        $this->assertEquals(0, json_last_error());
        
    }
}
