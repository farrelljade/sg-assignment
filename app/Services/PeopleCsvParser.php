<?php
 
namespace App\Services;

use App\Data\PersonData;

class PeopleCsvParser
{
    private const HEADER = 'homeowner';
    private const SEPARATORS = [' and ', ' & '];
    private const TITLES = ['Mr', 'Mrs', 'Ms', 'Dr', 'Prof', 'Mister'];

    /**
     * @return PersonData[]
     */
    public function parseCsv(array $lines): array
    {
        $people = [];

        foreach ($lines as $line) {
            $line = trim($line);
            $line = rtrim($line, ',');

            if ($line === self::HEADER) {
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
        foreach (self::SEPARATORS as $separator) {
            if (str_contains($line, $separator)) {
                return $this->expandBySeparator($line, $separator);
            }
        }
        return [$line];
    }
    
    private function expandBySeparator(string $line, string $separator): array
    {
        $parts = array_values(array_filter(
            array_map('trim', explode($separator, $line)),
            static fn (string $part): bool => $part !== ''
        ));

        if (count($parts) !== 2) {
            return [trim($line)];
        }

        [$first, $second] = $parts;

        $firstParts = explode(' ', trim($first));
        $secondParts = explode(' ', trim($second));

        $firstTitleRaw = $firstParts[0] ?? '';
        $secondTitleRaw = $secondParts[0] ?? '';

        // Handle the case of "Mr and Mrs Smith" by sharing the surname.
        if (count($firstParts) === 1 && in_array($firstTitleRaw, self::TITLES, true) && in_array($secondTitleRaw, self::TITLES, true)) {
            $restOfName = trim(substr($second, strlen($secondTitleRaw)));

            return [
                trim($firstTitleRaw . ' ' . $restOfName),
                trim($secondTitleRaw . ' ' . $restOfName),
            ];
        }

        return [$first, $second];
    }

    private function parsePerson(string $text): PersonData
    {
        $parts = explode(' ', trim($text));

        $title = array_shift($parts) ?? '';

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

        return new PersonData(
            title: $title,
            firstName: $firstName,
            initial: $initial,
            lastName: $lastName,
        );
    }
}
