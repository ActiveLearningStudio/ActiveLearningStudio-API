<?php
namespace App\Models\Traits;

/**
 * Trait GlobalScope
 * @package App\Models\Traits
 */
trait GlobalScope{

    /**
     * @param $query
     * @param $columns
     * @param $value
     * @return mixed
     * Scope for searching in specific columns
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
