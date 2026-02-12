<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PeopleEndPointTest extends TestCase
{
    public function test_it_returns_parsed_people_from_endpoint(): void
    {
        Storage::put('people.csv', "homeowner\nMr John Smith\nMrs Jane Smith\n");

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertJson([
            [
                'title' => 'Mr',
                'first_name' => 'John',
                'initial' => null,
                'last_name' => 'Smith',
            ],
            [
                'title' => 'Mrs',
                'first_name' => 'Jane',
                'initial' => null,
                'last_name' => 'Smith',
            ],
        ]);
    }

    public function test_it_returns_404_when_people_csv_missing(): void
    {
        Storage::delete('people.csv');

        $response = $this->get('/');

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'people.csv not found or not readable',
        ]);
    }
}
