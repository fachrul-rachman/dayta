<?php

namespace App\Services\BulkImport;

use App\Models\Division;
use Spatie\SimpleExcel\SimpleExcelReader;

class DivisionImportService
{
    public function preview(string $path): DivisionImportResult
    {
        $result = new DivisionImportResult;

        $rows = SimpleExcelReader::create($path)->getRows();

        $index = 1;

        foreach ($rows as $row) {
            $divisionName = trim((string) ($row['division_name'] ?? ''));
            $isActiveRaw = $row['is_active'] ?? null;

            $rowData = [
                'row' => $index,
                'division_name' => $divisionName,
                'is_active_raw' => $isActiveRaw !== null ? (string) $isActiveRaw : null,
                'is_active' => null,
                'action' => null,
                'status' => 'ok',
                'error' => null,
            ];

            if ($divisionName === '') {
                $rowData['status'] = 'error';
                $rowData['error'] = 'division_name is required.';
                $result->rows[] = $rowData;
                $result->errors++;
                $result->total++;
                $index++;

                continue;
            }

            if ($isActiveRaw !== null && $isActiveRaw !== '') {
                $normalized = $this->normalizeBoolean((string) $isActiveRaw);

                if ($normalized === null) {
                    $rowData['status'] = 'error';
                    $rowData['error'] = 'is_active has an unrecognized value.';
                    $result->rows[] = $rowData;
                    $result->errors++;
                    $result->total++;
                    $index++;

                    continue;
                }

                $rowData['is_active'] = $normalized;
            } else {
                $rowData['is_active'] = true;
            }

            $division = Division::query()
                ->whereRaw('TRIM(LOWER(name)) = TRIM(LOWER(?))', [$divisionName])
                ->first();

            if ($division) {
                $rowData['action'] = 'update';
                $result->updates++;
            } else {
                $rowData['action'] = 'create';
                $result->creates++;
            }

            $result->rows[] = $rowData;
            $result->total++;
            $index++;
        }

        return $result;
    }

    /**
     * @param  array<int, array<string, mixed>>  $rows
     */
    public function commit(array $rows): array
    {
        $created = 0;
        $updated = 0;

        foreach ($rows as $row) {
            if (($row['status'] ?? null) !== 'ok') {
                continue;
            }

            $name = trim((string) ($row['division_name'] ?? ''));

            if ($name === '') {
                continue;
            }

            $isActive = $row['is_active'] ?? true;

            $division = Division::query()
                ->whereRaw('TRIM(LOWER(name)) = TRIM(LOWER(?))', [$name])
                ->first();

            if (! $division) {
                Division::create([
                    'name' => $name,
                    'is_active' => (bool) $isActive,
                ]);

                $created++;
            } else {
                $division->is_active = (bool) $isActive;
                $division->save();

                $updated++;
            }
        }

        return [
            'created' => $created,
            'updated' => $updated,
        ];
    }

    private function normalizeBoolean(string $value): ?bool
    {
        $normalized = strtolower(trim($value));

        return match ($normalized) {
            'true', '1', 'yes', 'active' => true,
            'false', '0', 'no', 'inactive' => false,
            default => null,
        };
    }
}
