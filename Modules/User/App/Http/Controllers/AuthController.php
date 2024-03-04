<?php

namespace Modules\User\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Laravel\Socialite\Facades\Socialite;
use Modules\User\App\Enums\UserStatus;
use Modules\User\App\Http\Requests\ForgetPasswordRequest;
use Modules\User\App\Http\Requests\LoginRequest;
use Modules\User\App\Http\Requests\ResetPasswordRequest;
use Modules\User\App\Http\Resources\AuthResource;
use Modules\User\App\Models\User;
use Modules\User\App\Notifications\ResetPasswordNotification;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!isset($user->login_attempt)) {
            return $this->sendError('Authentication Failed', null, 400);
        }
        if ($user->login_attempt < 4 && $user->login_attempt >= 0) {
            if (Auth::attempt([
                'email' => $request->email,
                'password' => $request->password,
            ])) {
                $user = Auth::user();
                $user->update(['login_attempt' => 0]);
                $user['token'] = $user->createToken('API Token')->plainTextToken;

                return $this->sendResponse('Authentication Success', new AuthResource($user), 200);
            }
            $user->update(['login_attempt' => $user->login_attempt + 1]);

            return $this->sendError('Authentication Failed', null, 400);
        }

        return $this->sendError('Too many login attempts', null, 400);
    }

    public function logout()
    {
        $user = Auth::user();
        // Revoke the token that was used to authenticate the current request...
        $user->currentAccessToken()->delete();

        return $this->sendResponse('Logout Success', null, 200);
    }

    public function forgotPassword(ForgetPasswordRequest $request)
    {
        $tempToken = str()->random(30);
        $user = User::where('email', $request->email)->first();
        $user->update([
            'status' => UserStatus::PasswordReset,
            'invitation_token' => bcrypt($tempToken),
            'token_expiry_date' => Carbon::now()->addHour(),
        ]);
        $data = [
            'user' => $user,
            'token' => $tempToken,
        ];

        Notification::route('mail', $request->email)->notify(new ResetPasswordNotification($data));

        return $this->sendResponse('Successfully sent password request', null, 200);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $user = User::where('email', $request->email)->whereIn('status', [UserStatus::PasswordReset, UserStatus::Invited])->first();
        if (!$user) {
            return $this->sendError('User did not send password request change', null, 400);
        }

        if ($user->status == UserStatus::PasswordReset) {
            if (!Hash::check($request->token, $user->invitation_token) && $user->token_expiry_date < Carbon::now()) {
                return $this->sendError('invalid token', null, 400);
            }
            $user->update([
                'password' => bcrypt($request->password),
                'invitation_token' => str()->random(20),
                'status' => UserStatus::Registered,
                'login_attempt' => 0,
            ]);
        }

        return $this->sendResponse('Successfully update password', null, 200);
    }

    public function redirect(Request $request)
    {
        if (!URL::signatureHasNotExpired($request)) {
            return response('The URL has expired.');
        }

        if (!URL::hasCorrectSignature($request)) {
            return response('Invalid URL provided');
        }

        return redirect(
            request('front_url') ?
            request('front_url').'/reset-password?email='.urlencode($request->email).'&token='.urlencode($request->token)
            : config('services.ticketing.frontend_url').'/reset-password?email='.urlencode($request->email).'&token='.urlencode($request->token)
        );
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->user();
        // Check if the user exists in your system based on their email or other unique identifier.
        // If not, create a new user account.
        // Log in the user using JWT or other authentication method.

        $user = User::updateOrCreate([
            'google_id' => $googleUser->id,
        ], [
            'name' => $googleUser->name,
            'email' => $googleUser->email,
            'google_token' => $googleUser->token,
            'google_refresh_token' => $googleUser->refreshToken,
        ]);

        $user->refresh();
        $user['token'] = $user->createToken('API Token')->plainTextToken;
        
        return $this->sendResponse('Authentication Success', new AuthResource($user), 200);
    }
}
