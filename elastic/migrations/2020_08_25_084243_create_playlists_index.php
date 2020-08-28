<?php
declare(strict_types=1);

use ElasticAdapter\Indices\Mapping;
use ElasticAdapter\Indices\Settings;
use ElasticMigrations\Facades\Index;
use ElasticMigrations\MigrationInterface;

final class CreatePlaylistsIndex implements MigrationInterface
{
    /**
     * Run the migration.
     */
    public function up(): void
    {
        Index::create('playlists', function (Mapping $mapping, Settings $settings) {
            $mapping->text('title');
            $mapping->keyword('project_id');
            $mapping->boolean('shared');
            $mapping->date('created_at');
            $mapping->date('updated_at');
            $mapping->boolean('shared');
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Index::dropIfExists('playlists');
    }
}
