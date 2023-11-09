<?php

namespace Djoudi\LaravelH5p\Eloquents;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class H5pContentsLibrary extends Model
{
    protected $primaryKey = ['content_id', 'library_id', 'dependency_type'];

    public $timestamps = false;

    public $incrementing = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'content_id',
        'library_id',
        'dependency_type',
        'weight',
        'drop_css',
    ];

    /**
     * Get the casts array.
     *
     * @return array
     */
    public function getCasts()
    {
        if ($this->getIncrementing()) {
            return array_merge([$this->getKeyName() => $this->getKeyType()], $this->casts);
        }
        return $this->casts;
    }

    /**
     * Clone the model into a new, non-existing instance.
     *
     * @param  array|null  $except
     * @return static
     */
    public function replicate(array $except = null)
    {
        $attributes = Arr::except(
            $this->getAttributes(), $except ? $except : array()
        );
        return tap(new static, function ($instance) use ($attributes) {
            $instance->setRawAttributes($attributes);

            $instance->setRelations($this->relations);

            $instance->fireModelEvent('replicating', false);
        });
    }



}
