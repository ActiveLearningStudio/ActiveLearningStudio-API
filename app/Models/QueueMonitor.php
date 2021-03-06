<?php

namespace App\Models;

use App\Models\Traits\GlobalScope;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Throwable;

class QueueMonitor extends Model
{
    use GlobalScope;

    protected $guarded = [];

    protected $casts = [
        'failed' => 'bool',
    ];

    protected $dates = [
        'started_at',
        'finished_at',
    ];

    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable('queue_monitor');
    }

    /*
     *--------------------------------------------------------------------------
     * Scopes
     *--------------------------------------------------------------------------
     */

    public function scopeWhereJob(Builder $query, $jobId)
    {
        return $query->where('job_id', $jobId);
    }

    public function scopeOrdered(Builder $query)
    {
        return $query
            ->orderBy('started_at', 'desc')
            ->orderBy('started_at_exact', 'desc');
    }

    public function scopeLastHour(Builder $query)
    {
        return $query->where('started_at', '>', Carbon::now()->subHours(1));
    }

    public function scopeToday(Builder $query)
    {
        return $query->whereRaw('DATE(started_at) = ?', [Carbon::now()->subHours(1)->format('Y-m-d')]);
    }

    public function scopeFailed(Builder $query)
    {
        return $query->where('failed', true);
    }

    public function scopeSucceeded(Builder $query)
    {
        return $query->where('failed', false)->whereNotNull('finished_at');
    }

    public function scopeRunning(Builder $query)
    {
        return $query->where('failed', false)->whereNull('finished_at');
    }

    /*
     *--------------------------------------------------------------------------
     * Methods
     *--------------------------------------------------------------------------
     */

    public function getStartedAtExact(): ?Carbon
    {
        if (null === $this->started_at_exact) {
            return null;
        }

        return Carbon::parse($this->started_at_exact);
    }

    public function getFinishedAtExact(): ?Carbon
    {
        if (null === $this->finished_at_exact) {
            return null;
        }

        return Carbon::parse($this->finished_at_exact);
    }

    public function getRemainingSeconds(Carbon $now = null): float
    {
        if (null === $now) {
            $now = Carbon::now();
        }

        if (null == $this->progress || $this->isFinished()) {
            return 0.0;
        }

        if (!$this->started_at) {
            return 0.0;
        }

        if (0 === ($timeDiff = $now->getTimestamp() - $this->started_at->getTimestamp())) {
            return 0.0;
        }

        return (100 - $this->progress) / ($this->progress / $timeDiff);
    }

    public function getRemainingInterval(Carbon $now = null): CarbonInterval
    {
        return CarbonInterval::seconds(
            (int)$this->getRemainingSeconds($now)
        )->cascade();
    }

    /**
     * Get any optional data that has been added to the monitor model within the job.
     *
     * @return array
     */
    public function getData(): array
    {
        return json_decode($this->data, true) ?? [];
    }

    /**
     * Recreate the exception.
     *
     * @param bool $rescue Wrap the exception recreation to catch exceptions
     *
     * @return \Throwable|null
     */
    public function getException(bool $rescue = true): ?Throwable
    {
        if (null === $this->exception_class) {
            return null;
        }

        if (!$rescue) {
            return new $this->exception_class($this->exception_message);
        }

        try {
            return new $this->exception_class($this->exception_message);
        } catch (\Exception $exception) {
            return null;
        }
    }

    /**
     * Get the base class name of the job.
     *
     * @return string|null
     */
    public function getBasename(): ?string
    {
        if (null === $this->name) {
            return null;
        }

        return Arr::last(explode('\\', $this->name));
    }

    /**
     * check if the job is finished.
     *
     * @return bool
     */
    public function isFinished(): bool
    {
        if ($this->hasFailed()) {
            return true;
        }

        return null !== $this->finished_at;
    }

    /**
     * Check if the job has failed.
     *
     * @return bool
     */
    public function hasFailed(): bool
    {
        return true === $this->failed;
    }

    /**
     * check if the job has succeeded.
     *
     * @return bool
     */
    public function hasSucceeded(): bool
    {
        if (!$this->isFinished()) {
            return false;
        }

        return !$this->hasFailed();
    }
}
