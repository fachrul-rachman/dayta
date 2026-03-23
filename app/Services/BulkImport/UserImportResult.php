<?php

namespace App\Services\BulkImport;

/**
 * @phpstan-type UserImportRow array{
 *   row: int,
 *   name: string,
 *   email: string,
 *   role: string,
 *   division_name: ?string,
 *   division_id: ?int,
 *   is_active_raw: ?string,
 *   is_active: ?bool,
 *   action: 'create'|'update'|null,
 *   status: 'ok'|'error',
 *   error: ?string
 * }
 */
class UserImportResult
{
    /**
     * @var array<int, UserImportRow>
     */
    public array $rows = [];

    public int $total = 0;

    public int $creates = 0;

    public int $updates = 0;

    public int $errors = 0;
}

