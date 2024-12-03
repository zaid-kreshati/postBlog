<?php

namespace App\Http\Controllers\User\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\Description\descriptionRequest;
use App\Http\Requests\Description\descriptionExistRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Post\profile_imageRequest;
use App\Http\Requests\Post\cover_imageRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Description;
use App\Services\MediaService;
use App\Services\ProfileService;
use App\Services\PostService;
use App\Services\CategoryService;
use Illuminate\Support\Facades\DB;
use App\Traits\JsonResponseTrait;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Database\QueryException; // Import for database exception handling
use Illuminate\Validation\ValidationException; // Import for validation exception handling



class ProfileController extends Controller
{
    protected $mediaService;
    protected $profileService;
    protected $postService;
    protected $categoryService;
    use JsonResponseTrait;

    public function __construct(MediaService $mediaService, ProfileService $profileService, PostService $postService, CategoryService $categoryService)
    {
        $this->mediaService = $mediaService;
        $this->profileService = $profileService;
        $this->postService = $postService;
        $this->categoryService = $categoryService;
    }

    public function index($id=null)
    {

        if($id==null){
            $owner_id=Auth::id();
        }else{
            $owner_id=$id;
        }
        $status='published';
        $descriptions=$this->profileService->getDescriptions();
        $post_list=$this->postService->postList($status,1);
        $profile_image=$this->profileService->getProfileImage($owner_id);
        $cover_image=$this->profileService->getCoverImage($owner_id);
        $user=User::find($owner_id);
        $data=[
            'cover_image'=>$cover_image,
            'profile_image'=>$profile_image,
            'descriptions'=>$descriptions,
            'post_list'=>$post_list,
            'user'=>$user
        ];
        return $this->successResponse($data,'Profile fetched successfully');
    }

    public function upload_profile_image(profile_imageRequest $request)
    {
        $request['type']='user_profile_image';
        $this->profileService->deleteOldProfileImage();
        $photo_path=$this->mediaService->uploadPhoto($request);

        return $this->successResponse($photo_path,'Profile image uploaded successfully');
    }

    public function upload_background_image(cover_imageRequest $request)
    {
        $request['type']='user_cover_image';
        $this->profileService->deleteOldCoverImage();
        $photo_path=$this->mediaService->uploadPhoto($request);

        return $this->successResponse($photo_path,'Cover image uploaded successfully');


    }

    public function addDescription(descriptionRequest $request)
    {
        $description=$this->profileService->addDescription($request);
        return $this->successResponse($description,'Description added successfully');
    }

    public function updateDescription(descriptionRequest $request, $id)
    {
        $description=$this->profileService->updateDescription($request, $id);
        return $this->successResponse($description,'Description updated successfully');


    }

    public function deleteDescription($id)
    {
        try {
            $this->profileService->deleteDescription($id);
            return $this->successResponse(null, 'Description deleted successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }

    }



    public function removeProfileImage()
    {
        $image=$this->profileService->checkIfImageExists('user_profile_image');
        if($image){
            $this->profileService->deleteOldProfileImage();
            return $this->successResponse(null,'Profile image removed successfully');
        }
        else{
            return $this->errorResponse('Profile image not found');
        }
    }

    public function removeCoverImage()
    {
        $image=$this->profileService->checkIfImageExists('user_cover_image');
        if($image){
            $this->profileService->deleteOldCoverImage();
            return $this->successResponse(null,'Cover image removed successfully');

        }
        else{
            return $this->errorResponse('Cover image not found');
        }
    }



    public function switchPrivacy(Request $request)
    {
        try {
            $status=$request->privacy_on;
            $this->profileService->switchPrivacy($status);
            return $this->successResponse(null,'Privacy switched successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }


}
