<?php

namespace App\Http\Controllers\User\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Auth;
use App\Models\Description;
use App\Services\MediaService;
use App\Services\ProfileService;
use App\Services\PostService;
use App\Services\CategoryService;
use Illuminate\Support\Facades\DB;


use App\Traits\JsonResponseTrait;
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
        if($owner_id==Auth::id()){
            $is_owner=true;
        }else{
            $is_owner=false;
        }


        $status='published';
        $profile_image=null;
        $cover_image=null;
        $descriptions=$this->profileService->getDescriptions();
        $Categories=$this->categoryService->getParentNullCategorieswithoutpagination();
        $post_list=$this->postService->postList($status,1);
        $profile_image=$this->profileService->getProfileImage($owner_id);
        $cover_image=$this->profileService->getCoverImage($owner_id);
        $Users=User::where('id','!=',$owner_id)->get();
        $home=false;
        $name=User::find($owner_id)->name;
        $user_id=$owner_id;
        $privacy_on=User::find($owner_id)->is_private;
        return view('profile', compact('Categories', 'post_list', 'profile_image', 'cover_image', 'descriptions', 'home', 'name', 'Users', 'status', 'user_id','is_owner','privacy_on'));
    }

    public function upload_profile_image(Request $request)
    {

        Log::info('upload_profile_image');
        Log::info($request->all());
        $request['type']='user_profile_image';
        $this->profileService->deleteOldProfileImage();

        $photo_path=$this->mediaService->uploadPhoto($request);


        $user_id=Auth::id();
        $status = 'published';

        // Get posts based on the status
        $post_list=$this->postService->postList($status,1);
        $home=false;
        $html = view('partials.posts', compact('post_list', 'home', 'status','user_id'))->render();


        $data=[
            'html'=>$html,
            'photo_path'=>$photo_path
        ];

        return $this->successResponse($data,'Profile image uploaded successfully');



    }

    public function upload_background_image(Request $request)
    {

        Log::info($request->all());
        $request['type']='user_cover_image';
        $this->profileService->deleteOldCoverImage();
        $photo_path=$this->mediaService->uploadPhoto($request);

        return $this->successResponse($photo_path,'Cover image uploaded successfully');


    }

    public function addDescription(Request $request)
    {
        $description=$this->profileService->addDescription($request);
        return $this->successResponse($description,'Description added successfully');


    }

    public function updateDescription(Request $request, $id)
    {
        $this->profileService->updateDescription($request, $id);
        return $this->successResponse(null,'Description updated successfully');


    }

    public function deleteDescription($id)
    {
        $this->profileService->deleteDescription($id);
        return $this->successResponse(null,'Description deleted successfully');
    }

    public function saveDescriptions(Request $request)
    {

        Log::info($request->all());
        try {
            DB::beginTransaction();

            $changes = $request->input('changes');
            $userId = auth()->id();


            foreach ($changes as $change) {
                Log::info($change);
                switch ($change['action']) {
                    case 'add':
                        Description::create([
                            'user_id' => $userId,
                            'text' => $change['text']
                        ]);
                        break;

                    case 'edit':
                        if (!str_starts_with($change['id'], 'temp_')) {
                            Description::where('id', $change['id'])
                                      ->where('user_id', $userId)
                                      ->update(['text' => $change['text']]);
                        }
                        break;

                    case 'delete':
                        if (!str_starts_with($change['id'], 'temp_')) {
                            Description::where('id', $change['id'])
                                      ->where('user_id', $userId)
                                      ->delete();
                        }
                        break;
                }
            }

            DB::commit();

            // Return updated descriptions
            $descriptions = Description::where('user_id', $userId)->get();
            $home=false;
            $is_owner=true;
            $html = view('partials.Descriptions', compact('descriptions', 'home','is_owner'))->render();
            return $this->successResponse($html,'Descriptions saved successfully');

        } catch (\Exception $e) {
            Log::info($e->getMessage());

            DB::rollBack();
            return $this->errorResponse('Error saving descriptions: ' . $e->getMessage());
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
        Log::info($request->all());
        $status=$request->privacy_on;
        $this->profileService->switchPrivacy($status);
        return $this->successResponse(null,'Privacy switched successfully');
    }


}
