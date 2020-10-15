<?php

namespace App\Models\QueryBuilders;

use ElasticScoutDriverPlus\Builders\QueryBuilderInterface;
use Carbon\Carbon;

final class SearchFormQueryBuilder implements QueryBuilderInterface
{
    /**
     * @var string
     */
    private $query;

    /**
     * @var boolean
     */
    private $isPublic;

    /**
     * @var boolean
     */
    private $elasticsearch;

    /**
     * @var array
     */
    private $organisationVisibilityTypeIds;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $startDate;

    /**
     * @var string
     */
    private $endDate;

    /**
     * @var array
     */
    private $organisationIds;

    /**
     * @var array
     */
    private $subjectIds;

    /**
     * @var array
     */
    private $educationLevelIds;

    /**
     * @var array
     */
    private $playlistIds;

    /**
     * @var array
     */
    private $projectIds;

    /**
     * @var array
     */
    private $h5pLibraries;

    /**
     * @var string
     */
    private $negativeQuery;

    public function query(string $query): self
    {
        $this->query = $query;
        return $this;
    }

    public function isPublic(bool $isPublic): self
    {
        $this->isPublic = $isPublic;
        return $this;
    }

    public function elasticsearch(bool $elasticsearch): self
    {
        $this->elasticsearch = $elasticsearch;
        return $this;
    }

    public function organisationVisibilityTypeIds(array $organisationVisibilityTypeIds): self
    {
        $this->organisationVisibilityTypeIds = $organisationVisibilityTypeIds;
        return $this;
    }

    public function type(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function organisationIds(array $organisationIds): self
    {
        $this->organisationIds = $organisationIds;
        return $this;
    }

    public function startDate(string $startDate): self
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function endDate(string $endDate): self
    {
        $this->endDate = $endDate;
        return $this;
    }

    public function subjectIds(array $subjectIds): self
    {
        $this->subjectIds = $subjectIds;
        return $this;
    }

    public function educationLevelIds(array $educationLevelIds): self
    {
        $this->educationLevelIds = $educationLevelIds;
        return $this;
    }

    public function playlistIds(array $playlistIds): self
    {
        $this->playlistIds = $playlistIds;
        return $this;
    }

    public function projectIds(array $projectIds): self
    {
        $this->projectIds = $projectIds;
        return $this;
    }

    public function h5pLibraries(array $h5pLibraries): self
    {
        $this->h5pLibraries = $h5pLibraries;
        return $this;
    }

    public function negativeQuery(string $negativeQuery): self
    {
        $this->negativeQuery = $negativeQuery;
        return $this;
    }

    public function buildQuery(): array
    {
        $queries = [];
        $boolQueries = [];
        $dateRange = [];

        if (!empty($this->startDate)) {
            $carbonStartDate = new Carbon($this->startDate);
            $dateRange['gte'] = $carbonStartDate->toAtomString();
        }

        if (!empty($this->endDate)) {
            $carbonEndDate = new Carbon($this->endDate);
            $dateRange['lte'] = $carbonEndDate->toAtomString();
        }

        if (!empty($dateRange)) {
            $queries[] = [
                'range' => [
                    'created_at' => $dateRange
                ]
            ];
        }

        if (!empty($this->isPublic)) {
            $queries[] = [
                'term' => [
                    'is_public' => $this->isPublic
                ]
            ];
        }

        if (!empty($this->elasticsearch)) {
            $queries[] = [
                'term' => [
                    'elasticsearch' => $this->elasticsearch
                ]
            ];
        }

        if (!empty($this->organisationVisibilityTypeIds)) {
            if (in_array(null, $this->organisationVisibilityTypeIds, true)) {
                $queries[] = [
                    'bool' => [
                        'should' => [
                            [
                                'terms' => [
                                    'organisation_visibility_type_id' => array_values(array_filter($this->organisationVisibilityTypeIds))
                                ]
                            ],
                            [
                                'bool' => [
                                    'must_not' => [
                                        'exists' => [
                                            'field' => 'organisation_visibility_type_id'
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ];
            } else {
                $queries[] = [
                    'terms' => [
                        'organisation_visibility_type_id' => $this->organisationVisibilityTypeIds
                    ]
                ];
            }
        }

        if (!empty($this->type)) {
            $queries[] = [
                'term' => [
                    'type' => $this->type
                ]
            ];
        }

        if (!empty($this->organisationIds)) {
            $queries[] = [
                'terms' => [
                    'organisation_id' => $this->organisationIds
                ]
            ];
        }

        if (!empty($this->subjectIds)) {
            $queries[] = [
                'terms' => [
                    'subject_id' => $this->subjectIds
                ]
            ];
        }

        if (!empty($this->educationLevelIds)) {
            $queries[] = [
                'terms' => [
                    'education_level_id' => $this->educationLevelIds
                ]
            ];
        }

        if (!empty($this->playlistIds)) {
            $queries[] = [
                'terms' => [
                    'playlist_id' => $this->playlistIds
                ]
            ];
        }

        if (!empty($this->projectIds)) {
            $queries[] = [
                'terms' => [
                    'project_id' => $this->projectIds
                ]
            ];
        }

        if (!empty($this->query)) {
            $queries[] = [
                'bool' => [
                    'should' => [
                        [
                            'multi_match' => [
                                'query' => $this->query,
                                'fields' => ['title^5', 'name^5', 'description^3']
                            ]
                        ],
                        [
                            'multi_match' => [
                                'query' => $this->query
                            ]
                        ]
                    ]
                ]
            ];
        }

        if (!empty($this->h5pLibraries)) {
            $queries[] = [
                'terms' => [
                    'h5p_library' => $this->h5pLibraries
                ]
            ];
        }

        if (!empty($this->negativeQuery)) {
            $boolQueries['must_not'] = [
                'multi_match' => [
                    'query' => $this->negativeQuery
                ]
            ];
        }

        if (!empty($queries)) {
            $boolQueries['must'] = $queries;
        }

        return [
            'bool' => $boolQueries
        ];
    }
}
