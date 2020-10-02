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
     * @var string
     */
    private $type;

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

    public function type(string $type): self
    {
        $this->type = $type;
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
        $orQueries = [];
        $andQueries = [];
        $boolQueries = [];

        if (isset($this->isPublic) && is_bool($this->isPublic)) {
            $andQueries[] = [
                'term' => [
                    'is_public' => $this->isPublic
                ]
            ];
        }

        if (isset($this->elasticsearch) && is_bool($this->elasticsearch)) {
            $andQueries[] = [
                'term' => [
                    'elasticsearch' => $this->elasticsearch
                ]
            ];
        }

        if (isset($this->type) && !empty($this->type)) {
            $andQueries[] = [
                'term' => [
                    'type' => $this->type
                ]
            ];
        }

        if (isset($this->subjectIds) && !empty($this->subjectIds)) {
            $andQueries[] = [
                'terms' => [
                    'subject_id' => $this->subjectIds
                ]
            ];
        }

        if (isset($this->educationLevelIds) && !empty($this->educationLevelIds)) {
            $andQueries[] = [
                'terms' => [
                    'education_level_id' => $this->educationLevelIds
                ]
            ];
        }

        if (isset($this->playlistIds) && !empty($this->playlistIds)) {
            $andQueries[] = [
                'terms' => [
                    'playlist_id' => $this->playlistIds
                ]
            ];
        }

        if (isset($this->projectIds) && !empty($this->projectIds)) {
            $andQueries[] = [
                'terms' => [
                    'project_id' => $this->projectIds
                ]
            ];
        }

        if (isset($this->query) && !empty($this->query)) {
            $orQueries[] = [
                'multi_match' => [
                    'query' => $this->query,
                    'fields' => ['title^5', 'name^5', 'description^3']
                ]
            ];

            $orQueries[] = [
                'multi_match' => [
                    'query' => $this->query
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

        if (!empty($andQueries)) {
            $queries = $andQueries;
        }

        if (!empty($orQueries)) {
            $queries[] = [
                'bool' => [
                    'should' => $orQueries
                ]
            ];
        }

        $boolQueries['must'] = $queries;

        return [
            'bool' => $boolQueries
        ];
    }
}
