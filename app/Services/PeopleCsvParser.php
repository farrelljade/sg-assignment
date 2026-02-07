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

            foreach ($this->separatePeople($line) as $text) {
                $people[] = $this->parsePerson($text);
            }
        }

        return $people;
    }

    // function to separate people (Mr Tom Staff and Mr John Doe)
    private function separatePeople(string $line): array
    {
        if (str_contains($line, ' and ')) {
            return $this->expandBySeparator($line, ' and ');
        }

        if (str_contains($line, ' & ')) {
            return $this->expandBySeparator($line, ' & ');
        }

        return [$line];
    }

    
    // functin to expand on 'and' and '&' 
    private function expandBySeparator(string $line, string $separator): array
    {
        $parts = array_map('trim', explode($separator, $line));

        dd($parts);
    }
    
    // function to parse the person into json format
    private function parsePerson()
    {
        //
    }
}