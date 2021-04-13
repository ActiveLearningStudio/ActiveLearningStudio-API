<?php

declare(strict_types=1);

use ElasticAdapter\Indices\Mapping;
use ElasticAdapter\Indices\Settings;
use ElasticMigrations\Facades\Index;
use ElasticMigrations\MigrationInterface;

final class CreateProjectsIndex implements MigrationInterface
{
    /**
     * Run the migration.
     */
    public function up(): void
    {
        Index::create('projects', function (Mapping $mapping, Settings $settings) {
            $mapping->keyword('organization_id');
            $mapping->keyword('organization_visibility_type_id');
            $mapping->keyword('project_id');
            $mapping->text('name');
            $mapping->text('description');
            $mapping->keyword('indexing');
            $mapping->date('created_at');
            $mapping->date('updated_at');
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Index::dropIfExists('projects');
    }
}
