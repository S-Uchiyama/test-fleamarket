<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();

        return view('mypage.profile', compact('user'));
    }

    public function update(ProfileRequest $request)
    {
        $user = Auth::user();
        $data = $request->validated();

        if ($request->hasFile('profile_image')) {

            $path = $request->file('profile_image')->store('profile_images', 'public');
            $data['profile_image'] = $path;
        } else {
            unset($data['profile_image']);
        }

        $user->update($data);

        return redirect()->route('mypage.profile.edit')->with('status', 'プロフィールを更新しました');
    }
}
