<?php

namespace App\Models\Traits;

/**
 * Trait GlobalScope
 * @package App\Models\Traits
 */
trait GlobalScope{

    /**
     * Global Scope for searching in specific columns
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
}
