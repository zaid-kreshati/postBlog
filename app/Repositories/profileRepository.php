<?php

namespace App\Repositories;

use App\Models\Description;
use Illuminate\Support\Facades\Auth;
use App\Models\Media;
use App\Models\User;

class ProfileRepository
{
    public function getDescriptions()
    {
        $userID=Auth::id();
        return Description::where('user_id', $userID)->get();
    }

    public function getProfileImage($owner_id)
    {
        $profile_image=Media::where('mediable_id', $owner_id)->where('type','user_profile_image')->first();
        return $profile_image;
    }

    public function getCoverImage($owner_id)
    {
        $cover_image=Media::where('mediable_id', $owner_id)->where('mediable_type',User::class)->where('type','user_cover_image')->first();
        return $cover_image;
    }

    public function deleteOldCoverImage()
    {
        $userID=Auth::id();
        $cover_image=Media::where('mediable_id', $userID)->where('type','user_cover_image')->first();
        if($cover_image){
            $cover_image->delete();
        }
    }

    public function deleteOldProfileImage()
    {
        $userID=Auth::id();
        $profile_image=Media::where('mediable_id', $userID)->where('type','user_profile_image')->first();
        if($profile_image){
            $profile_image->delete();
        }
    }

    public function addDescription($request)
    {
        $userID=Auth::id();
        return Description::create([
            'user_id' => $userID,
            'text' => $request->text
        ]);
    }

    public function updateDescription($request, $id)
    {
        $description=Description::find($id)->update($request->all());
        return $description;
    }

    public function deleteDescription($id)
    {
        $description = Description::find($id);
        if (!$description) {
            throw new \Exception('Description not found.');
        }
        return $description->delete();
    }

    public function saveDescriptions($request)
    {
        return Description::where('user_id', Auth::id())->update(['text' => $request->text]);
    }

    public function checkIfImageExists($image_name)
    {
        return Media::where('mediable_id', Auth::id())->where('type', $image_name)->first();
    }

    public function switchPrivacy($status)
    {
        if($status==1&&Auth::user()->is_private==1){
            throw new \Exception('You cannot turn on privacy when it is already on.');
        }
        else if($status==0&&Auth::user()->is_private==0){
            throw new \Exception('You cannot turn off privacy when it is already off.');
        }
        else{
            return User::find(Auth::id())->update(['is_private' => $status]);
        }
    }

}
