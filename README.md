# People CSV Parser

Laravel endpoint that reads a CSV of people, splits combined names, and returns JSON.

## What It Does
- Reads `storage/app/private/people.csv`
- Expands lines like:
  - `Mr and Mrs Smith`
  - `Dr & Mrs Joe Bloggs`
  - `Mr Tom Staff and Mr John Doe`
- Splits each person into:
  - `title` (required)
  - `first_name` (optional)
  - `initial` (optional)
  - `last_name` (required)
- Returns a JSON array

## How To Run
1. Start the app
```bash
php artisan serve
```
2. Visit `http://127.0.0.1:8000/`

## Tests
Run:
```bash
php artisan test --filter=PeopleCsvParserTest
```