<?php

namespace App\Http\Controllers;

use App\Services\PeopleCsvParser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PeopleController extends Controller
{
    public function index(PeopleCsvParser $parser)
    {
        $path = Storage::path('people.csv');
        $lines = file($path);

        $people = $parser->parseCsv($lines);

        return response()->json($people);
    }
}
