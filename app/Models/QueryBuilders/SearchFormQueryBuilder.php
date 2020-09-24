<?php

namespace App\Models\QueryBuilders;

use ElasticScoutDriverPlus\Builders\QueryBuilderInterface;

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

    public function negativeQuery(string $negativeQuery): self
    {
        $this->negativeQuery = $negativeQuery;
        return $this;
    }

    public function buildQuery(): array
    {
        $queries = [];
        $boolQueries = [];

        if (isset($this->isPublic) && !empty($this->isPublic)) {
            $queries[] = [
                'term' => [
                    'is_public' => $this->isPublic
                ]
            ];
        }

        if (isset($this->elasticsearch) && !empty($this->elasticsearch)) {
            $queries[] = [
                'term' => [
                    'elasticsearch' => $this->elasticsearch
                ]
            ];
        }

        if (isset($this->organisationVisibilityTypeIds) && !empty($this->organisationVisibilityTypeIds)) {
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

        if (isset($this->type) && !empty($this->type)) {
            $queries[] = [
                'term' => [
                    'type' => $this->type
                ]
            ];
        }

        if (isset($this->organisationIds) && !empty($this->organisationIds)) {
            $queries[] = [
                'terms' => [
                    'organisation_id' => $this->organisationIds
                ]
            ];
        }

        if (isset($this->subjectIds) && !empty($this->subjectIds)) {
            $queries[] = [
                'terms' => [
                    'subject_id' => $this->subjectIds
                ]
            ];
        }

        if (isset($this->educationLevelIds) && !empty($this->educationLevelIds)) {
            $queries[] = [
                'terms' => [
                    'education_level_id' => $this->educationLevelIds
                ]
            ];
        }

        if (isset($this->playlistIds) && !empty($this->playlistIds)) {
            $queries[] = [
                'terms' => [
                    'playlist_id' => $this->playlistIds
                ]
            ];
        }

        if (isset($this->projectIds) && !empty($this->projectIds)) {
            $queries[] = [
                'terms' => [
                    'project_id' => $this->projectIds
                ]
            ];
        }

        if (isset($this->query) && !empty($this->query)) {
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

        if (isset($this->negativeQuery) && !empty($this->negativeQuery)) {
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
