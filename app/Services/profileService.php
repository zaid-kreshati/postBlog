<?php

namespace App\Services;

use App\Models\Description;
use App\Repositories\ProfileRepository;

class ProfileService
{
    protected $profileRepository;

    public function __construct(ProfileRepository $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

    public function getDescriptions()
    {
        return $this->profileRepository->getDescriptions();
    }

    public function getProfileImage($owner_id)
    {
        return $this->profileRepository->getProfileImage($owner_id);
    }

    public function getCoverImage($owner_id)
    {
        return $this->profileRepository->getCoverImage($owner_id);
    }

    public function deleteOldCoverImage()
    {
        return $this->profileRepository->deleteOldCoverImage();
    }

    public function deleteOldProfileImage()
    {
        return $this->profileRepository->deleteOldProfileImage();
    }

    public function addDescription($request)
    {
        return $this->profileRepository->addDescription($request);
    }

    public function updateDescription($request, $id)
    {
        return $this->profileRepository->updateDescription($request, $id);
    }

    public function deleteDescription($id)
    {
        return $this->profileRepository->deleteDescription($id);
    }

    public function saveDescriptions($request)
    {
        return $this->profileRepository->saveDescriptions($request);
    }

    public function checkIfImageExists($image_name)
    {
        return $this->profileRepository->checkIfImageExists($image_name);
    }

    public function switchPrivacy($status)
    {
        return $this->profileRepository->switchPrivacy($status);
    }


}
