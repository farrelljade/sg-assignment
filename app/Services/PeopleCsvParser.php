<?php
 
namespace App\Services;

class PeopleCsvParser
{
    public function parseCsv(array $lines): array
    {
        $people = [];

        foreach ($lines as $line) {
            if ($line === "homeowner,\n") {
                continue;
            }

            foreach ($this->separatePeople($line) as $text) {
                $people[] = $this->parsePerson($text);
            }
        }

        return $people;
    }

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
    
    private function expandBySeparator(string $line, string $separator): array
    {
        $parts = array_map('trim', explode($separator, $line));

        [$first, $second] = $parts;

        $titles = ['Mr', 'Mrs', 'Ms', 'Dr', 'Prof', 'Mister'];
        $firstParts = explode(' ', trim($first));
        $secondParts = explode(' ', trim($second));

        $firstTitleRaw = $firstParts[0] ?? '';
        $secondTitleRaw = $secondParts[0] ?? '';

        // Handle the case of "Mr and Mrs Smith" by sharing the surname.
        if (count($firstParts) === 1 && in_array($firstTitleRaw, $titles, true) && in_array($secondTitleRaw, $titles, true)) {
            $restOfName = trim(substr($second, strlen($secondTitleRaw)));

            return [
                trim($firstTitleRaw . ' ' . $restOfName),
                trim($secondTitleRaw . ' ' . $restOfName),
            ];
        }

        return [$first, $second];
    }

    private function parsePerson(string $text): array
    {
        $parts = explode(' ', trim($text));

        $title = array_shift($parts) ?? '';
        // dd($title);

        $firstName = null;
        $initial = null;
        $lastName = null;

        if (count($parts) === 1) {
            $lastName = $parts[0];
        } elseif (count($parts) >= 2) {
            $nameTitle = $parts[0];
            $restOfName = array_slice($parts, 1);
            $lastName = implode(' ', $restOfName);

            $firstNameOrInitial = rtrim($nameTitle, '.');

            if (strlen($firstNameOrInitial) === 1) {
                $initial = strtoupper($firstNameOrInitial);
            } else {
                $firstName = $nameTitle;
            }
        }

        return [
            'title' => $title,
            'first_name' => $firstName,
            'initial' => $initial,
            'last_name' => $lastName,
        ];
    }
}