<?php

namespace App\Http\Controllers;

use App\Data\PersonData;
use App\Services\PeopleCsvParser;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class PeopleController extends Controller
{
    public function index(PeopleCsvParser $parser): JsonResponse
    {
        $path = Storage::path('people.csv');

        if (!is_file($path) || !is_readable($path)) {
            return response()->json([
                'message' => 'people.csv not found or not readable',
            ], 404);
        }

        $lines = file($path);

        if ($lines === false) {
            return response()->json([
                'message' => 'Failed to read people.csv'
            ], 500);
        }

        $people = $parser->parseCsv($lines);
        $payload = array_map(
            static fn (PersonData $person): array => $person->toArray(),
            $people
        );

        return response()->json($payload);
    }
}
