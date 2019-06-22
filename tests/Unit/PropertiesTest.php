<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Property;

class PropertiesTest extends TestCase
{
    use RefreshDatabase;

    private $property;

    public function setUp() : void {
        parent::setUp();
        $propety = Property::create([
            'id' => 1,
            'title' => 'imovel',
            'price' => 10.0,
            'description' => 'Bom imovel',
            'region' => 'vale dourado',
            'category' => 'casa'
        ]);

        $this->property = $propety; 
    }

    public function testStore() : void {
       
       /*  $data = [
            'id' => 1,
            'title' => 'imovel',
            'price' => 10.0,
            'description' => 'Bom imovel',
            'region' => 'vale dourado',
            'category' => 'casa'
        ]; */

        $response = $this->get('/api/scrapdata');
        $response->assertStatus(200);
    }

    public function testindex() : void {
        $response = $this->getJson('/api/properties');
        $response->assertStatus(200);
    }
}
