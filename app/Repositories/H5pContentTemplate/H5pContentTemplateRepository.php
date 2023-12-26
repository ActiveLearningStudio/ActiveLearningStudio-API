<?php

namespace App\Repositories\H5pContentTemplate;

use App\Repositories\BaseRepository;
use Djoudi\LaravelH5p\Eloquents\H5pContentTemplate;

class H5pContentTemplateRepository extends BaseRepository implements H5pContentTemplateRepositoryInterface
{
    /**
     * H5PContentTemplateTemplateRepository constructor.
     *
     * @param H5pContentTemplate $model
     */
    public function __construct(H5pContentTemplate $model)
    {
        parent::__construct($model);
    }


    public function getH5pContentTemplate($library)
    {
       return $this->model->where('library', $library)->firstOrFail();
    }
}
