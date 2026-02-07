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
    private function separatePeople(string $line): array
    {
        if (str_contains($line, ' and ')) {
            // return this-> expandBySeparator 'and'
        }

        if (str_contains($line, ' & ')) {
            // return this-> expandBySeparator '&'
        }

        return [$line];
    }

    
    // functin to expand on 'and' and '&' 
    private function expandBySeparator(string $line, string $separator): array
    {
        //
    }
    
    // function to parse the person into json format
    private function parsePerson(): array
    {
        //
    }
}