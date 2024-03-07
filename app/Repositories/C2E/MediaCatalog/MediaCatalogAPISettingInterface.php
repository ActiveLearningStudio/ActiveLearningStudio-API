<?php

namespace App\Repositories\C2E\MediaCatalog;

use App\Repositories\EloquentRepositoryInterface;

interface MediaCatalogAPISettingInterface extends EloquentRepositoryInterface
{
	/**
     * Get all media catalog api settings
     *
     * @param array $data
     * @param Organization $suborganization
     * 
     * @return mixed
     */
    public function getAll($data, $suborganization);

    /**
     * Create media catalog api settings
     *
     * @param array $data
     * 
     * @return mixed
     * 
     * @throws GeneralException
     */
    public function create($data);

    /**
     * Update media catalog api settings
     *
     * @param MediaCatalogAPISetting $setting
     * @param array $data
     * 
     * @return mixed
     * 
     * @throws GeneralException
     */
    public function update($setting, $data);

    /**
     * Find media catalog api settings by id
     *
     * @param int $id
     * 
     * @return mixed
     * 
     * @throws GeneralException
     */
    public function find($id);

    /**
     * Delete media catalog api settings
     *
     * @param MediaCatalogAPISetting $setting
     * 
     * @return mixed
     * 
     * @throws GeneralException
     */
    public function destroy($setting);
    
    /**
     * To get row record by org and source type match
     *
     * @param $orgId integer
     * @param $mediaSourcesId int
     * @return object
     * @throws GeneralException
     */
    public function getRowRecordByOrgAndSourceType($orgId, $mediaSourcesId);

    /**
     * Get all media catalog srt contents
     *
     * @param array $data
     * @param MediaCatalogAPISetting $apisetting
     * 
     * @return mixed
     */
    public function getAllVideoSrtContent($data, $apisetting);

    /**
     * Create media catalog video srt content
     *
     * @param array $data
     * 
     * @return mixed
     * 
     * @throws GeneralException
     */
    public function createMediaCatalogSrtContent($data);

    /**
     * Update media catalog srt content
     *
     * @param MediaCatalogSrtContent $setting
     * @param array $data
     * 
     * @return mixed
     * 
     * @throws GeneralException
     */
    public function updateMediaCatalogSrtContent($setting, $data);

    /**
     * Delete media catalog srt content
     *
     * @param MediaCatalogSrtContent $setting
     * 
     * @return mixed
     * 
     * @throws GeneralException
     */
    public function destroyVideoSrtContent($setting);

    /**
     * Get media catalog srt search based record
     *
     * @param int apiSettingId, string $srtSearch
     * 
     * @return mixed
     * 
     * @throws GeneralException
     */
    public function getMediaCatalogSrtSearchRecord($apiSettingId, $srtSearch);
}
