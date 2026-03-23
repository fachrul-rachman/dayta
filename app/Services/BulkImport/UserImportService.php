<?php

namespace App\Services\BulkImport;

use App\Enums\UserRole;
use App\Models\Division;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Spatie\SimpleExcel\SimpleExcelReader;

class UserImportService
{
    public function preview(string $path): UserImportResult
    {
        $result = new UserImportResult();

        $rows = SimpleExcelReader::create($path)->getRows();

        $roles = array_map(fn (UserRole $r) => strtolower($r->value), UserRole::cases());

        $index = 1;

        foreach ($rows as $row) {
            $name = trim((string) ($row['name'] ?? ''));
            $email = trim((string) ($row['email'] ?? ''));
            $roleRaw = strtolower(trim((string) ($row['role'] ?? '')));
            $divisionName = isset($row['division_name']) ? trim((string) $row['division_name']) : null;
            $isActiveRaw = $row['is_active'] ?? null;

            $rowData = [
                'row' => $index,
                'name' => $name,
                'email' => $email,
                'role' => $roleRaw,
                'division_name' => $divisionName,
                'division_id' => null,
                'is_active_raw' => $isActiveRaw !== null ? (string) $isActiveRaw : null,
                'is_active' => null,
                'action' => null,
                'status' => 'ok',
                'error' => null,
            ];

            if ($name === '') {
                $rowData['status'] = 'error';
                $rowData['error'] = 'name is required.';
                $result->rows[] = $rowData;
                $result->errors++;
                $result->total++;
                $index++;

                continue;
            }

            if ($email === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $rowData['status'] = 'error';
                $rowData['error'] = 'email is missing or invalid.';
                $result->rows[] = $rowData;
                $result->errors++;
                $result->total++;
                $index++;

                continue;
            }

            if (! in_array($roleRaw, $roles, true)) {
                $rowData['status'] = 'error';
                $rowData['error'] = 'role is not allowed.';
                $result->rows[] = $rowData;
                $result->errors++;
                $result->total++;
                $index++;

                continue;
            }

            if (in_array($roleRaw, ['manager', 'hod'], true)) {
                if (! $divisionName) {
                    $rowData['status'] = 'error';
                    $rowData['error'] = 'division_name is required for this role.';
                    $result->rows[] = $rowData;
                    $result->errors++;
                    $result->total++;
                    $index++;

                    continue;
                }

                $division = Division::query()
                    ->whereRaw('TRIM(LOWER(name)) = TRIM(LOWER(?))', [$divisionName])
                    ->first();

                if (! $division) {
                    $rowData['status'] = 'error';
                    $rowData['error'] = 'division_name does not match any existing division.';
                    $result->rows[] = $rowData;
                    $result->errors++;
                    $result->total++;
                    $index++;

                    continue;
                }

                $rowData['division_id'] = $division->id;
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

            $user = User::query()
                ->whereRaw('LOWER(email) = LOWER(?)', [$email])
                ->first();

            if ($user) {
                $rowData['action'] = 'update';
                $result->updates++;
            } else {
                $rowData['action'] = 'create';
                $result->creates++;
            }

            $passwordRaw = isset($row['password']) ? (string) $row['password'] : '';

            if ($rowData['action'] === 'create') {
                if ($passwordRaw === '') {
                    $rowData['status'] = 'error';
                    $rowData['error'] = 'password is required for new users.';
                    $result->rows[] = $rowData;
                    $result->errors++;
                    $result->total++;
                    $index++;

                    continue;
                }

                $validator = Validator::make(
                    ['password' => $passwordRaw],
                    ['password' => [Password::default()]]
                );

                if ($validator->fails()) {
                    $rowData['status'] = 'error';
                    $rowData['error'] = 'password does not meet security requirements.';
                    $result->rows[] = $rowData;
                    $result->errors++;
                    $result->total++;
                    $index++;

                    continue;
                }
            } elseif ($rowData['action'] === 'update' && $passwordRaw !== '') {
                $validator = Validator::make(
                    ['password' => $passwordRaw],
                    ['password' => [Password::default()]]
                );

                if ($validator->fails()) {
                    $rowData['status'] = 'error';
                    $rowData['error'] = 'password does not meet security requirements.';
                    $result->rows[] = $rowData;
                    $result->errors++;
                    $result->total++;
                    $index++;

                    continue;
                }
            }

            $result->rows[] = $rowData;
            $result->total++;
            $index++;
        }

        return $result;
    }

    /**
     * Commit valid rows from the given file path.
     */
    public function commit(string $path): array
    {
        $created = 0;
        $updated = 0;

        $rows = SimpleExcelReader::create($path)->getRows();

        $roles = array_map(fn (UserRole $r) => strtolower($r->value), UserRole::cases());

        foreach ($rows as $row) {
            $name = trim((string) ($row['name'] ?? ''));
            $email = trim((string) ($row['email'] ?? ''));
            $roleRaw = strtolower(trim((string) ($row['role'] ?? '')));
            $divisionName = isset($row['division_name']) ? trim((string) $row['division_name']) : null;
            $isActiveRaw = $row['is_active'] ?? null;

            if ($name === '' || $email === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                continue;
            }

            if (! in_array($roleRaw, $roles, true)) {
                continue;
            }

            $divisionId = null;

            if (in_array($roleRaw, ['manager', 'hod'], true)) {
                if (! $divisionName) {
                    continue;
                }

                $division = Division::query()
                    ->whereRaw('TRIM(LOWER(name)) = TRIM(LOWER(?))', [$divisionName])
                    ->first();

                if (! $division) {
                    continue;
                }

                $divisionId = $division->id;
            }

            $isActive = true;

            if ($isActiveRaw !== null && $isActiveRaw !== '') {
                $normalized = $this->normalizeBoolean((string) $isActiveRaw);

                if ($normalized === null) {
                    continue;
                }

                $isActive = $normalized;
            }

            $user = User::query()
                ->whereRaw('LOWER(email) = LOWER(?)', [$email])
                ->first();

            $passwordRaw = isset($row['password']) ? (string) $row['password'] : '';

            if (! $user) {
                if ($passwordRaw === '') {
                    continue;
                }

                $validator = Validator::make(
                    ['password' => $passwordRaw],
                    ['password' => [Password::default()]]
                );

                if ($validator->fails()) {
                    continue;
                }

                $data = [
                    'name' => $name,
                    'email' => $email,
                    'role' => $roleRaw,
                    'division_id' => $divisionId,
                    'is_active' => $isActive,
                    'password' => Hash::make($passwordRaw),
                ];

                User::create($data);
                $created++;
            } else {
                $data = [
                    'name' => $name,
                    'email' => $email,
                    'role' => $roleRaw,
                    'division_id' => $divisionId,
                    'is_active' => $isActive,
                ];

                if ($passwordRaw !== '') {
                    $validator = Validator::make(
                        ['password' => $passwordRaw],
                        ['password' => [Password::default()]]
                    );

                    if ($validator->fails()) {
                        continue;
                    }

                    $data['password'] = Hash::make($passwordRaw);
                }

                $user->fill($data);
                $user->save();
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
