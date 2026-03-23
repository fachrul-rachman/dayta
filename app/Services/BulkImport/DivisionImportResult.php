<?php

namespace App\Services\BulkImport;

/**
 * @phpstan-type DivisionImportRow array{
 *   row: int,
 *   division_name: string,
 *   is_active_raw: ?string,
 *   is_active: ?bool,
 *   action: 'create'|'update'|null,
 *   status: 'ok'|'error',
 *   error: ?string
 * }
 */
class DivisionImportResult
{
    /**
     * @var array<int, DivisionImportRow>
     */
    public array $rows = [];

    public int $total = 0;

    public int $creates = 0;

    public int $updates = 0;

    public int $errors = 0;
}
