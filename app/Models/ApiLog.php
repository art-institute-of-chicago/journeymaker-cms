<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ApiLog extends Model
{
    protected $table = 'api_log';

    protected $guarded = [];

    public function artwork(): BelongsTo
    {
        return $this->belongsTo(Artwork::class, 'datahub_id', 'datahub_id');
    }

    public static function getCurrentValues(): Collection
    {
        $table = (new static)->getTable();

        return DB::table($table)
            ->select('*')
            ->join(
                DB::raw(
                    <<<SQL
                    (
                        SELECT id, field, MAX(updated_at) as max_updated_at
                        FROM {$table}
                        GROUP BY id, field
                    ) max
                    SQL
                ),
                fn ($join) => $join->on($table.'.id', '=', 'max.id')
                    ->on($table.'.field', '=', 'max.field')
                    ->on($table.'.updated_at', '=', 'max.max_updated_at')

            )
            ->get()
            ->keyBy('hash');
    }

    /**
     * Get the most recent changes to the API data.
     * Uses the LAG function to get the previous value for each datahub_id/field combination.
     * LAG is used with the OVER clause, to partition the data by datahub_id/field and orders it by updated_at.
     * For each datahub_id/field combination, the LAG function will return the value from the previous record in order of updated_at.
     *
     * The query is filtered to only include records from the last 6 months.
     *
     * @see https://dev.mysql.com/doc/refman/8.0/en/window-function-descriptions.html#function_lag
     */
    public static function getRecentChanges(): Collection
    {
        return DB::table((new static)->getTable())
            ->select([
                'id',
                'datahub_id',
                'field',
                'updated_at',
                'value as new_value',
                DB::raw('LAG(value) OVER (PARTITION BY datahub_id, field ORDER BY updated_at) as old_value',
                )])
            ->where('updated_at', '>', DB::raw('DATE_SUB(NOW(), INTERVAL 6 MONTH)'))
            ->orderBy('datahub_id')
            ->orderBy('field')
            ->orderBy('updated_at', 'desc')
            ->get()
            ->reject(fn ($log) => $log->old_value === null)
            ->map(fn ($log) => json_decode(json_encode($log), true))
            ->mapInto(static::class);
    }
}
