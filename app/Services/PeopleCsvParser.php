<?php
 
namespace App\Services;

class PeopleCsvParser
{
    // function to parse the csv
    public function parseCsv(array $lines): array
    {
        $people = [];

        foreach ($lines as $line) {
            if (strtolower($line) === "homeowner") {
                continue;
            }
        }
    }

    // function to separate people (Mr Tom Staff and Mr John Doe)
    // functin to expand on 'and' and '&' 
    // function to parse the people into json
}