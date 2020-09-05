<?php

namespace App\Repositories\H5pLibrary;

use Djoudi\LaravelH5p\Eloquents\H5pLibrary;
use App\Repositories\BaseRepository;
use App\Repositories\H5pLibrary\H5pLibraryRepositoryInterface;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class H5pLibraryRepository extends BaseRepository implements H5pLibraryRepositoryInterface
{
    /**
     * H5pLibraryRepository constructor.
     *
     * @param H5pLibrary $model
     */
    public function __construct(H5pLibrary $model)
    {
        parent::__construct($model);
    }

    /**
     * Get the libraries's fields semantics.
     *
     * @param Object $h5pLibraryObject
     * @return array
     */
    public function getFieldSemantics($h5pLibraryObject)
    {
        if ($h5pLibraryObject->semantics) {
            $semantics = json_decode($h5pLibraryObject->semantics, true);
            $results = array();
            $fieldTypes = ['text'];
            $this->getNestedFieldSemantics($h5pLibraryObject->id, $semantics, 'type', $results, [], [], $fieldTypes);
            return $results;
        }

        return [];
    }

    /**
     * Get the Nested Fields Semantics By Recursion
     *
     * @param  int      $libraryId
     * @param  array    $array
     * @param  string   $key
     * @param  array    $results
     * @param  array    $pathName
     * @param  array    $pathType
     * @param  array    $values
     * @param  array    $nameLike
     * @return array
     */
    private function getNestedFieldSemantics($libraryId, $array, $key, &$results, $pathName, $pathType, $values = null, $nameLike = null)
    {
        if (!is_array($array)) {
            return;
        }

        if (isset($array['name']) && isset($array['type'])) {
            $pathName[] = $array['name'];
            $pathType[] = $array['type'];
        }

        if (isset($array[$key]) && $array[$key] === 'library') {
            $pathName[] = 'params';
            $pathType[] = $array['type'];

            foreach ($array['options'] as $libraryName) {
                $libraryNameArray = explode(' ', $libraryName);
                $libraryVersions = explode('.', $libraryNameArray[1]);
                $libraryObj = $this->model->firstWhere([
                    ['name', '=', $libraryNameArray[0]],
                    ['major_version', '=', $libraryVersions[0]],
                    ['minor_version', '=', $libraryVersions[1]]
                ]);

                if ($libraryObj && $libraryObj->semantics) {
                    $librarySemantics = json_decode($libraryObj->semantics, true);
                }

                $this->getNestedFieldSemantics($librarySemantics, $key, $results, $pathName, $pathType, $values, $nameLike);
            }
        }

        if (isset($array[$key]) && $values && in_array($array[$key], $values, true)) {
            $fields = array();
            $fields['indexed'] = false;
            $fields['label'] = '';
            $fields['description'] = '';

            if ($array[$key] === 'text') {
                $pathTypeCount = count($pathType);

                if ($pathTypeCount > 2 && $pathType[$pathTypeCount - 3] === 'list' && $pathType[$pathTypeCount - 2] === 'group') {
                    // unset($pathType[$pathTypeCount - 2]);
                    // unset($pathName[$pathTypeCount - 2]);

                    $pathName[$pathTypeCount - 2] = 'group.nested.array';

                    if (
                        isset($pathType[$pathTypeCount - 4])
                        && $pathType[$pathTypeCount - 4] === 'group'
                        && $pathName[$pathTypeCount - 4] === $pathName[$pathTypeCount - 3]
                    ) {
                        unset($pathType[$pathTypeCount - 4]);
                        unset($pathName[$pathTypeCount - 4]);
                    }
                }

                if ($pathTypeCount > 1) {
                    if (isset($pathType[$pathTypeCount - 2]) && $pathType[$pathTypeCount - 2] === 'list') {
                        unset($pathType[$pathTypeCount - 1]);
                        unset($pathName[$pathTypeCount - 1]);

                        if (isset($pathType[$pathTypeCount - 3])
                            && $pathType[$pathTypeCount - 3] === 'group'
                            && isset($pathType[$pathTypeCount - 4])
                            && $pathType[$pathTypeCount - 4] === 'list'
                        ) {
                            unset($pathType[$pathTypeCount - 3]);
                            unset($pathName[$pathTypeCount - 3]);
                        }
                    }
                }

                if ($pathTypeCount > 3) {
                    if (
                        isset($pathType[$pathTypeCount - 4]) && $pathType[$pathTypeCount - 4] === 'list' &&
                        isset($pathType[$pathTypeCount - 3]) && $pathType[$pathTypeCount - 3] === 'group' &&
                        isset($pathType[$pathTypeCount - 2]) && $pathType[$pathTypeCount - 2] === 'group'
                    ) {
                        unset($pathType[$pathTypeCount - 3]);
                        unset($pathName[$pathTypeCount - 3]);
                        unset($pathType[$pathTypeCount - 2]);
                        unset($pathName[$pathTypeCount - 2]);
                    }
                }

                $fields['path'] = implode('.', $pathName);
            }

            if ($nameLike) {
                if (in_array(strtolower($array['name']), $nameLike, true)) {
                // if (Str::contains(strtolower($array['name']), $nameLike))
                    $fields['library_id'] = $libraryId;
                    $fields['name'] = $array['name'];
                    $fields['type'] = $array['type'];

                    if (isset($array['label'])) {
                        $fields['label'] = $array['label'];
                    }

                    if (isset($array['description'])) {
                        $fields['description'] = $array['description'];
                    }

                    $fields['created_at'] = Carbon::now()->format('Y-m-d H:i:s');
                    $fields['updated_at'] = Carbon::now()->format('Y-m-d H:i:s');

                    $results[] = $fields;
                }
            } else {
                $fields['library_id'] = $libraryId;
                $fields['name'] = $array['name'];
                $fields['type'] = $array['type'];

                if (isset($array['label'])) {
                    $fields['label'] = $array['label'];
                }

                if (isset($array['description'])) {
                    $fields['description'] = $array['description'];
                }

                $fields['created_at'] = Carbon::now()->format('Y-m-d H:i:s');
                $fields['updated_at'] = Carbon::now()->format('Y-m-d H:i:s');

                $results[] = $fields;
            }
        }

        foreach ($array as $subarray) {
            $this->getNestedFieldSemantics($libraryId, $subarray, $key, $results, $pathName, $pathType, $values, $nameLike);
        }
    }
}
