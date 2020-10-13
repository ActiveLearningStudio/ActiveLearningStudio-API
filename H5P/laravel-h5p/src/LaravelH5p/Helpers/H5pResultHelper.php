<?php

namespace Djoudi\LaravelH5p\Helpers;

use Illuminate\Support\Facades\DB;

class H5pResultHelper
{
    public static function get_results($content_id = NULL, $user_id = NULL, $offset = 0, $limit = 20000, $sort_by = 0, $sort_dir = 0, $filters = array())
    {
        $extra_fields = '';
        $joins = '';
        $query_args = array();

        // Add extra fields and joins for the different result lists
        if ($content_id === NULL) {
            $extra_fields .= ' hr.content_id, hc.title AS content_title,';
            $joins .= ' LEFT JOIN h5p_contents hc ON hr.content_id = hc.id';
        }

        if ($user_id === NULL) {
            $extra_fields .= ' hr.user_id, u.name AS user_name,';
            $joins .= ' LEFT JOIN users u ON hr.user_id = u.id';
        }

        // Add filters
        $where = self::get_results_query_where($query_args, $content_id, $user_id, $filters);

        // Order results by the select column and direction
        $order_by = self::get_order_by(
            $sort_by,
            $sort_dir,
            array(
                (object) array(
                    'name' => ($content_id === NULL ? 'hc.title' : 'hr.id'),
                    'reverse' => TRUE
                ),
                'hr.score',
                'hr.max_score',
                'hr.opened',
                'hr.finished'
            )
        );

        $query_args[] = $limit;
        $query_args[] = $offset;

        $query = "
            SELECT hr.id,
                {$extra_fields}
                hr.score,
                hr.max_score,
                hr.opened,
                hr.finished,
                hr.time
            FROM  h5p_results hr
                {$joins}
                {$where}
                {$order_by}
            LIMIT ? OFFSET ?
        "; // LIMIT ?, ?

        return DB::select($query, $query_args);
    }

    public static function get_results_query_where(&$query_args, $content_id = NULL, $user_id = NULL, $filters = array())
    {
        if ($content_id !== NULL) {
            $where = ' WHERE hr.content_id = ?';
            $query_args[] = $content_id;
        }

        if ($user_id !== NULL) {
            $where = (isset($where) ? $where . ' AND' : ' WHERE') . ' hr.user_id = ?';
            $query_args[] = $user_id;
        }

        if (isset($where) && isset($filters[0])) {
            $where .= ' AND ' . ($content_id === NULL ? 'hc.title' : 'u.email') . " LIKE '%%?%%'";
            $query_args[] = $filters[0];
        }

        return (isset($where) ? $where : '');
    }

    public static function get_order_by($field, $direction, $fields)
    {
        // Make sure selected sortable field is valid
        if (!isset($fields[$field])) {
            $field = 0; // Fall back to default
        }

        // Find selected sortable field
        $field = $fields[$field];

        if (is_object($field)) {
            // Some fields are reverse sorted by default, e.g. text fields.
            if (!empty($field->reverse)) {
                $direction = !$direction;
            }

            $field = $field->name;
        }

        return 'ORDER BY ' . $field . ' ' . ($direction ? 'ASC' : 'DESC');
    }
}
