<?php

namespace Tests\Unit;

use Tests\TestCase;

/**
 * @test
 * @covers  App\Utils\NumberHelper
 */
class NestedCollectionTest extends TestCase
{

    public function setUp()
    {
    
	    parent::setUp();

	    $this->map = (object)[
	        'client' => (object)[
	        	'datatable' => (object)[
	        		'per_page' => 20,
	        		'column_visibility' => (object)[
	        			'__checkbox' => true,
	        			'name' => true,
	        			'contact' => true,
	        			'email' => true,
	        			'client_created_at' => true,
	        			'last_login' => true,
	        			'balance' => true,
	        			'__component:client-actions' => true,
	        		]
	        	]
	        ]
	    ];

    }


    public function testPerPageAttribute()
    {
		$this->assertEquals($this->map->client->datatable->per_page, 20);
    }

    public function testNameAttributeVisibility()
    {
    	$this->assertEquals($this->map->client->datatable->column_visibility->name, true);
    }

    public function testStringAsEntityProperty()
    {
    	$entity = 'client';

    	$this->assertEquals($this->map->{$entity}->datatable->column_visibility->name, true);
    }


}