<?php

// test('example', function () {
//     $response = $this->get('/');

//     $response->assertStatus(200);
// });

use App\Data\PersonData;
use App\Services\PeopleCsvParser;
use Tests\TestCase;

class PeopleCsvParserTest extends TestCase
{
    private PeopleCsvParser $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->parser = new PeopleCsvParser();
    }

    /**
     * @param PersonData[] $people
     */
    private function asArray(array $people): array
    {
        return array_map(
            static fn (PersonData $person): array => $person->toArray(),
            $people
        );
    }

    public function test_it_parses_single_person(): void
    {
        $lines = [
            "Mr John Smith",
        ];

        $result = $this->asArray($this->parser->parseCsv($lines));

        $expected = [
            [
                'title' => 'Mr',
                'first_name' => 'John',
                'initial' => null,
                'last_name' => 'Smith',
            ],
        ];

        $this->assertEquals($expected, $result);
    }

    public function test_it_parses_multiple_people_with_and_separator(): void
    {
        $lines = [
            "Mr John Smith and Mrs Jane Smith",
        ];

        $result = $this->asArray($this->parser->parseCsv($lines));

        $expected = [
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
        ];

        $this->assertEquals($expected, $result);
    }

    public function test_it_parses_multiple_people_with_ampersand_separator(): void
    {
        $lines = [
            "Dr & Mrs Joe Bloggs",
        ];

        $result = $this->asArray($this->parser->parseCsv($lines));

        $expected = [
            [
                'title' => 'Dr',
                'first_name' => 'Joe',
                'initial' => null,
                'last_name' => 'Bloggs',
            ],
            [
                'title' => 'Mrs',
                'first_name' => 'Joe',
                'initial' => null,
                'last_name' => 'Bloggs',
            ],
        ];

        $this->assertEquals($expected, $result);
    }

    public function test_it_parses_initials_with_period(): void
    {
        $lines = [
            "Mr F. Fredrickson",
        ];

        $result = $this->asArray($this->parser->parseCsv($lines));

        $expected = [
            [
                'title' => 'Mr',
                'first_name' => null,
                'initial' => 'F',
                'last_name' => 'Fredrickson',
            ],
        ];

        $this->assertEquals($expected, $result);
    }

    public function test_it_parses_initials_without_period(): void
    {
        $lines = [
            "Mr M Mackie",
        ];

        $result = $this->asArray($this->parser->parseCsv($lines));

        $expected = [
            [
                'title' => 'Mr',
                'first_name' => null,
                'initial' => 'M',
                'last_name' => 'Mackie',
            ],
        ];

        $this->assertEquals($expected, $result);
    }

    public function test_it_skips_homeowner_header(): void
    {
        $lines = [
            "homeowner",
            "Mr John Smith",
        ];

        $result = $this->asArray($this->parser->parseCsv($lines));

        $expected = [
            [
                'title' => 'Mr',
                'first_name' => 'John',
                'initial' => null,
                'last_name' => 'Smith',
            ],
        ];

        $this->assertEquals($expected, $result);
    }
}
