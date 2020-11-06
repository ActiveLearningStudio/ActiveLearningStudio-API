<?php

namespace App\Models\Traits;

/**
 * Trait GlobalScope
 * @package App\Models\Traits
 */
trait GlobalScope
{

    /**
     * Global Scope for searching in specific columns
     *
     * @param $query
     * @param $columns
     * @param $value
     * @return mixed
     */
    public function scopeSearch($query, $columns, $value)
    {
        foreach ($columns as $column) {
            // no need to perform search if searchable is false
            if (isset($column['searchable']) && $column['searchable'] === 'false') {
                continue;
            }
            $column = $column['name'] ?? $column;
            $query->orWhere($column, 'ILIKE', '%' . $value . '%');
        }
        return $query;
    }

    /**
     * Global scope for date range
     *
     * @param $query
     * @param $range
     * @param string $column
     * @return mixed
     *
     */
    public function scopeDateBetween($query, $range, $column = 'created_at')
    {
        $range[0] = date($range[0]); // start date
        $range[1] = date($range[1]) . ' 23:59:59'; // end date make sure everything for today is covered
        return $query->whereBetween($column, $range);
    }

}
