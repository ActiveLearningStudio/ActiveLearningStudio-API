<?php

namespace App\Models\QueryBuilders;

use ElasticScoutDriverPlus\Builders\QueryBuilderInterface;
use Carbon\Carbon;

final class SearchFormQueryBuilder implements QueryBuilderInterface
{
    /**
     * @var string
     */
    private $organizationParentChildrenIds;

    /**
     * @var string
     */
    private $searchType;

    /**
     * @var string
     */
    private $query;

    /**
     * @var array
     */
    private $organizationVisibilityTypeIds;

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
    private $organizationIds;

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

    /**
     * @var array
     */
    private $indexing;

    public function organizationParentChildrenIds(array $organizationParentChildrenIds): self
    {
        $this->organizationParentChildrenIds = $organizationParentChildrenIds;
        return $this;
    }

    public function searchType(string $searchType): self
    {
        $this->searchType = $searchType;
        return $this;
    }

    public function query(string $query): self
    {
        $this->query = $query;
        return $this;
    }

    public function organizationVisibilityTypeIds(array $organizationVisibilityTypeIds): self
    {
        $this->organizationVisibilityTypeIds = $organizationVisibilityTypeIds;
        return $this;
    }

    public function type(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function organizationIds(array $organizationIds): self
    {
        $this->organizationIds = $organizationIds;
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

    public function indexing(array $indexing): self
    {
        $this->indexing = $indexing;
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
            $carbonEndDate->addUnitNoOverflow('hour', 24, 'day');
            $dateRange['lte'] = $carbonEndDate->toAtomString();
        }

        if (!empty($dateRange)) {
            $queries[] = [
                'range' => [
                    'created_at' => $dateRange
                ]
            ];
        }

        if (!empty($this->type)) {
            $queries[] = [
                'term' => [
                    'type' => $this->type
                ]
            ];
        }

        if (!empty($this->searchType)) {
            if (
                $this->searchType === 'my_projects'
                || $this->searchType === 'org_projects_admin'
                || $this->searchType === 'org_projects_non_admin'
            ) {
                if (!empty($this->organizationIds)) {
                    $queries[] = [
                        'terms' => [
                            'organization_id' => $this->organizationIds
                        ]
                    ];
                }

                if ($this->searchType === 'org_projects_non_admin') {
                    $queries[] = [
                        'bool' => [
                            'must_not' => [
                                [
                                    'terms' => [
                                        'organization_visibility_type_id' => [config('constants.private-organization-visibility-type-id')]
                                    ]
                                ]
                            ]
                        ]
                    ];
                }
            } elseif ($this->searchType === 'showcase_projects') {
                // Get all public items
                $organizationIdsShouldQueries[] = [
                    'terms' => [
                        'organization_visibility_type_id' => [config('constants.public-organization-visibility-type-id')]
                    ]
                ];

                // Get all global items
                $globalOrganizationIdsQueries[] = [
                    'terms' => [
                        'organization_id' => $this->organizationParentChildrenIds
                    ]
                ];

                $globalOrganizationIdsQueries[] = [
                    'terms' => [
                        'organization_visibility_type_id' => [config('constants.global-organization-visibility-type-id')]
                    ]
                ];

                $organizationIdsShouldQueries[] = [
                    'bool' => [
                        'must' => $globalOrganizationIdsQueries
                    ]
                ];

                // Get all protected items
                $protectedOrganizationIdsQueries[] = [
                    'terms' => [
                        'organization_id' => $this->organizationIds
                    ]
                ];

                $protectedOrganizationIdsQueries[] = [
                    'terms' => [
                        'organization_visibility_type_id' => [config('constants.protected-organization-visibility-type-id')]
                    ]
                ];

                $organizationIdsShouldQueries[] = [
                    'bool' => [
                        'must' => $protectedOrganizationIdsQueries
                    ]
                ];

                $queries[] = [
                    'bool' => [
                        'should' => $organizationIdsShouldQueries
                    ]
                ];
            }
        } else {
            if (!empty($this->organizationVisibilityTypeIds)) {
                if (in_array(null, $this->organizationVisibilityTypeIds, true)) {
                    $queries[] = [
                        'bool' => [
                            'should' => [
                                [
                                    'terms' => [
                                        'organization_visibility_type_id' => array_values(array_filter($this->organizationVisibilityTypeIds))
                                    ]
                                ],
                                [
                                    'bool' => [
                                        'must_not' => [
                                            'exists' => [
                                                'field' => 'organization_visibility_type_id'
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
                            'organization_visibility_type_id' => $this->organizationVisibilityTypeIds
                        ]
                    ];
                }
            }
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

        if (!empty($this->h5pLibraries)) {
            $queries[] = [
                'terms' => [
                    'h5p_library' => $this->h5pLibraries
                ]
            ];
        }

        if (!empty($this->indexing)) {
            if (in_array('null', $this->indexing, true)) {
                $queries[] = [
                    'bool' => [
                        'should' => [
                            [
                                'terms' => [
                                    'indexing' => array_values(array_filter($this->indexing))
                                ]
                            ],
                            [
                                'bool' => [
                                    'must_not' => [
                                        'exists' => [
                                            'field' => 'indexing'
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
                        'indexing' => $this->indexing
                    ]
                ];
            }
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
                        ]
                    ]
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
