<?php

namespace App\Repositories\CurrikiGo\ContentUserDataGo;

use App\Models\CurrikiGo\ContentUserDataGo;
use App\Repositories\BaseRepository;
use App\Repositories\CurrikiGo\ContentUserDataGo\ContentUserDataRepositoryGoInterface;
use Illuminate\Support\Collection;

class ContentUserDataGoRepository extends BaseRepository implements ContentUserDataGoRepositoryInterface
{
    /**
     * ContentUserDataGo constructor.
     *
     * @param ContentUserDataGo $model
     */
    public function __construct(ContentUserDataGo $model)
    {
        parent::__construct($model);
    }

    /**
     * find record based on composite key.
     *
     * @param ContentUserDataGo $model
     */
    public function fetchByCompositeKey($content_id, $user_id, $sub_content_id, $data_id)
    {
        return $this->model->where(['content_id' => $content_id, 'user_id' => $user_id, 'sub_content_id' => $sub_content_id, 'data_id' => $data_id])->get();
    }

    /**
     * update based on composite key.
     *
     * @param ContentUserDataGo $model
     */
    public function updateComposite($attributes, $content_id, $user_id, $sub_content_id, $data_id)
    {
        return $this->model->where([
            'content_id' => $content_id, 'user_id' => $user_id, 'sub_content_id' => $sub_content_id, 'data_id' => $data_id
        ])->update($attributes);
    }

    /**
     * delelte based on composite key.
     *
     * @param ContentUserDataGo $model
     */
    public function deleteComposite($content_id, $user_id, $sub_content_id, $data_id)
    {
        return $this->model->where([
            'content_id' => $content_id, 'user_id' => $user_id, 'sub_content_id' => $sub_content_id, 'data_id' => $data_id
        ])->delete();
    }
}
