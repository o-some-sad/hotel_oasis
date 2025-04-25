<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Show the registration page.
     */
    public function create(): Response
    {
        return Inertia::render('auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validate= $request->validate([
            'name' => 'required|string|max:255',
            'password' => ['required', 'confirmed', 'min:8'],
            'email' => 'required|string|email|lowercase|max:255|unique:users',
            'national_id' => 'required|string|max:255|unique:users',
            'mobile' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'gender' => 'required|string|in:male,female',
            'avatar_img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if ($request->hasFile('avatar_img')) {
            $path = $request->file('avatar_img')->store('receptionists', 'public');
            $validate['avatar_img'] = $path;
        }

        $user = User::create([
            'name' => $validate['name'],
            'email' => $validate['email'],
            'password' => Hash::make($validate['password']),
            'national_id'=>$validate['national_id'],
            'mobile'=>$validate['mobile'],
            'country'=>$validate['country'],
            'gender'=>$validate['gender'],
            'avatar_img'=>$validate['avatar_img'],
            'role'=>'client',
            'is_approved'=>false,
            'last_login_in'=>now(),

        ]);

        event(new Registered($user));

        Auth::login($user);

        return to_route('dashboard');
    }
}
