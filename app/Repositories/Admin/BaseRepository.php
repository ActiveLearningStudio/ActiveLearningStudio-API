<?php

namespace App\Repositories\Admin;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Concerns\BuildsQueries;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class BaseRepository.
 *
 * Modified from: https://github.com/kylenoland/laravel-base-repository
 */
abstract class BaseRepository implements RepositoryContract
{
    /**
     * The repository model.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * The query builder.
     *
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $query;

    /**
     * Alias for the query limit.
     *
     * @var int
     */
    protected $take;

    /**
     * Array of related models to eager load.
     *
     * @var array
     */
    protected $with = [];

    /**
     * Array of one or more where clause parameters.
     *
     * @var array
     */
    protected $wheres = [];

    /**
     * Array of one or more where in clause parameters.
     *
     * @var array
     */
    protected $whereIns = [];

    /**
     * Array of one or more ORDER BY column/value pairs.
     *
     * @var array
     */
    protected $orderBys = [];

    /**
     * Array of scope methods to call on the model.
     *
     * @var array
     */
    protected $scopes = [];

    /**
     * Yajra/DataTables
     * DataTables properties
     */
    protected $dtStart = 0;
    protected $dtLength = 25;
    protected $dtPage = 1;
    protected $dtOrder = 'created_at';
    protected $dtOrderDir = 'asc';
    protected $dtSearch = false;
    protected $dtSearchValue = '';
    protected $dtRelSearchValue = '';
    protected $dtSearchColumns = [];
    protected $dtRelSearchColumns = [];

    /**
     * Get all the model records in the database.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        $this->newQuery()->eagerLoad();

        $models = $this->query->get();

        $this->unsetClauses();

        return $models;
    }

    /**
     * Count the number of specified model records in the database.
     *
     * @return int
     */
    public function count()
    {
        return $this->get()->count();
    }

    /**
     * Get the first specified model record from the database.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function first()
    {
        $this->newQuery()->eagerLoad()->setClauses()->setScopes();

        $model = $this->query->firstOrFail();

        $this->unsetClauses();

        return $model;
    }

    /**
     * Get all the specified model records in the database.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function get()
    {
        $this->newQuery()->eagerLoad()->setClauses()->setScopes();

        $models = $this->query->get();

        $this->unsetClauses();

        return $models;
    }

    /**
     * Get the specified model record from the database.
     *
     * @param $id
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getById($id)
    {
        $this->unsetClauses();

        $this->newQuery()->eagerLoad();

        return $this->query->findOrFail($id);
    }

    /**
     * @param $item
     * @param $column
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getByColumn($item, $column, array $columns = ['*'])
    {
        $this->unsetClauses();

        $this->newQuery()->eagerLoad();

        return $this->query->where($column, $item)->first($columns);
    }

    /**
     * Delete the specified model record from the database.
     *
     * @param $id
     *
     * @return bool|null
     * @throws \Exception
     */
    public function deleteById($id)
    {
        $this->unsetClauses();

        return $this->getById($id)->delete();
    }

    /**
     * Set the query limit.
     *
     * @param int $limit
     *
     * @return $this
     */
    public function limit($limit)
    {
        $this->take = $limit;

        return $this;
    }

    /**
     * Set an ORDER BY clause.
     *
     * @param string $column
     * @param string $direction
     * @return $this
     */
    public function orderBy($column, $direction = 'asc')
    {
        $this->orderBys[] = compact('column', 'direction');

        return $this;
    }

    /**
     * @param int $limit
     * @param array $columns
     * @param string $pageName
     * @param null $page
     *
     * @return LengthAwarePaginator
     */
    public function paginate($limit = 25, array $columns = ['*'], $pageName = 'page', $page = null)
    {
        $this->newQuery()->eagerLoad()->setClauses()->setScopes();

        $models = $this->query->paginate($limit, $columns, $pageName, $page);

        $this->unsetClauses();

        return $models;
    }

    /**
     * Add a simple where clause to the query.
     *
     * @param string $column
     * @param string $value
     * @param string $operator
     *
     * @return $this
     */
    public function where($column, $value, $operator = '=')
    {
        $this->wheres[] = compact('column', 'value', 'operator');

        return $this;
    }

    /**
     * Add a simple where in clause to the query.
     *
     * @param string $column
     * @param mixed $values
     *
     * @return $this
     */
    public function whereIn($column, $values)
    {
        $values = is_array($values) ? $values : [$values];

        $this->whereIns[] = compact('column', 'values');

        return $this;
    }

    /**
     * Set Eloquent relationships to eager load.
     *
     * @param $relations
     *
     * @return $this
     */
    public function with($relations)
    {
        if (is_string($relations)) {
            $relations = func_get_args();
        }

        $this->with = $relations;

        return $this;
    }

    /**
     * Create a new instance of the model's query builder.
     *
     * @return $this
     */
    protected function newQuery()
    {
        $this->query = $this->model->newQuery();

        return $this;
    }

    /**
     * Add relationships to the query builder to eager load.
     *
     * @return $this
     */
    protected function eagerLoad()
    {
        foreach ($this->with as $relation) {
            $this->query->with($relation);
        }

        return $this;
    }

    /**
     * Set clauses on the query builder.
     *
     * @return $this
     */
    protected function setClauses()
    {
        foreach ($this->wheres as $where) {
            $this->query->where($where['column'], $where['operator'], $where['value']);
        }

        foreach ($this->whereIns as $whereIn) {
            $this->query->whereIn($whereIn['column'], $whereIn['values']);
        }

        foreach ($this->orderBys as $orders) {
            $this->query->orderBy($orders['column'], $orders['direction']);
        }

        if (isset($this->take) and !is_null($this->take)) {
            $this->query->take($this->take);
        }

        return $this;
    }

    /**
     * Set query scopes.
     *
     * @return $this
     */
    protected function setScopes()
    {
        foreach ($this->scopes as $method => $args) {
            $this->query->$method(implode(', ', $args));
        }

        return $this;
    }

    /**
     * Reset the query clause parameter arrays.
     *
     * @return $this
     */
    protected function unsetClauses()
    {
        $this->wheres = [];
        $this->whereIns = [];
        $this->scopes = [];
        $this->take = null;

        return $this;
    }

    /**
     * Verify if DT Params present
     * @param $dtParams
     * @return bool
     */
    protected function isDtRequest($dtParams)
    {
        return isset($dtParams['columns'], $dtParams['order'], $dtParams['start'], $dtParams['length'], $dtParams['search']);
    }

    /**
     * Params of DataTables present in Request
     * @param $dtParams
     * @return BaseRepository
     */
    protected function setDtParams($dtParams)
    {
        if ($this->isDtRequest($dtParams)) {
            $sValue = $dtParams['search']['value'] ?? null; // set null if no search value present in request

            $this->enableDtSearch($dtParams['columns'], $sValue)->enablePagination($dtParams['start'], $dtParams['length'])
                ->enableDtOrder($this->dtSearchColumns[$dtParams['order'][0]['column']]['name'], $dtParams['order'][0]['dir']);
        }
        $this->dtPage = $dtParams['page'] ?? $this->dtPage; // if page parameter is already present in request then override
        return $this;
    }

    /**
     * @param $columns
     * @param $value
     * @return $this
     */
    public function enableDtSearch($columns, $value)
    {
        $this->dtSearchColumns = $columns ?? null;
        $this->dtSearchValue = $value ?? null;
        $this->dtSearch = $this->dtSearchValue ? true : false;
        return $this;
    }

    /**
     *  To allow the search override
     */
    public function disableDtSearch()
    {
        $this->dtSearch = false;
        return $this;
    }

    /**
     * @param $columns
     * @param $value
     * @return $this
     */
    public function enableRelDtSearch($columns, $value)
    {
        $this->dtRelSearchColumns = $columns ?? null;
        $this->dtRelSearchValue = $value ?? null;
        return $this;
    }

    /**
     * @param $order
     * @param $dir
     * @return $this
     */
    public function enableDtOrder($order, $dir)
    {
        $this->dtOrder = $order; // get datatable order column
        $this->dtOrderDir = $dir;
        return $this;
    }

    /**
     * @param $start
     * @param $length
     * @return $this
     */
    public function enablePagination($start, $length)
    {
        $this->dtStart = $start;
        $this->dtLength = $length;
        $this->dtPage = ($start / $length) + 1; // calculate page size
        return $this;
    }

    /**
     * Get the default datatables paginated response
     * Relational search can be performed only on 1st relation for now
     * @param array $with
     * @return mixed
     */
    protected function getDtPaginated($with = [])
    {
        // to make sure the instance of query builder
        if (!$this->query instanceof Builder && !$this->query instanceof \Illuminate\Database\Query\Builder) {
            $this->query = $this->model::query();
        }

        // search through each column and sort by - also append relation data if needed
        return $this->dtOrderBy()->dtWith($with)->dtSearch($with)->dtPaginate();
    }

    /**
     * @return BuildsQueries|Builder|mixed
     */
    protected function dtOrderBy()
    {
        $this->query = $this->query->when($this->dtOrderDir, function ($query) {
            return $query->orderBy($this->dtOrder, $this->dtOrderDir);
        });
        return $this;
    }

    /**
     * @param null $relations
     * @return BuildsQueries|Builder|mixed
     */
    public function dtSearch($relations = null)
    {
        $this->query = $this->query->when($this->dtSearch, function ($query) use ($relations) {
            // group the where clause to avoid the conflicting of other where clauses with search
            $query->where(function ($query) use ($relations) {
                // if search relation need to be perform -
                $this->dtRelSearch($query, $relations);
                return $query->search($this->dtSearchColumns, $this->dtSearchValue);
            });
        });
        return $this;
    }

    /**
     * @param $with
     * @return $this
     */
    protected function dtWith($with)
    {
        $this->query = $this->query->when($with, function ($query) use ($with) {
            return $query->with($with);
        });
        return $this;
    }

    /**
     * @return LengthAwarePaginator
     */
    protected function dtPaginate()
    {
        return $this->query->paginate($this->dtLength, '*', 'page', $this->dtPage);
    }

    /**
     * @param $query
     * @param $relation
     * @return mixed
     */
    protected function dtRelSearch($query, $relation)
    {
        return $query->when($this->dtRelSearchValue, function ($query) use ($relation) {
            // access the first relation from relations array
            $query->orWhereHas($relation[0], function ($query) {
                $query->where(function ($query) {
                    return $query->search($this->dtRelSearchColumns, $this->dtRelSearchValue);
                });
            });
        });
    }

}
