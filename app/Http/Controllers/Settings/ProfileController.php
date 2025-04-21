<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Show the user's profile settings page.
     */
    public function edit(Request $request): Response
    {
        $isDefaultAvatar = $request->user()->avatar_img == '/public/default.png';
        return Inertia::render('settings/Profile', [
            'status' => $request->session()->get('status'),
            'avatar_url' => $isDefaultAvatar ? $request->user()->avatar_img : Storage::url($request->user()->avatar_img),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $request->user()->fill($data);
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        if (!is_null($data['avatar'])) {
            //delete old avatar (if not equal /public/default.png)
            if ($request->user()->avatar_img != '/public/default.png') {
                Storage::drive("public")->delete(
                    $request->user()->avatar_img
                );
            }
            /** @var \Illuminate\Http\UploadedFile */
            $avatar = $data['avatar'];
            $image = $avatar->store('avatars', 'public');
            $request->user()->avatar_img = $image;
        }

        $request->user()->save();

        return to_route('profile.edit');
    }

    /**
     * Delete the user's profile.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
