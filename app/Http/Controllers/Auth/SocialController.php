<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\User;
use App\SocialAccount;
use Illuminate\Support\Str;

/* 
    Authorization via social networks
*/
class SocialController extends Controller
{
    // Redirection function for authorization of the selected social network
    public function redirectToProvider($provider) {        
        return Socialite::driver($provider)->redirect();
    }

    // Response with a token from a social network after successful authorization
    public function callback($provider) {

        // Getting a response from a social network
        // Getting user data
        $socialiteUser = Socialite::driver($provider)->stateless()->user();
        // Search for a user in the database in different tables
        $user = $this->findOrCreateUser($provider, $socialiteUser);

        // After a successful search or adding a new user, the user logs in
        auth()->login($user[0], true);
        return['user'=>$user[0], 'filled'=>$user[1]];
    }
    // The function of searching for a user in the database or creating a new one
    public function findOrCreateUser($provider, $socialiteUser)
    {
        // Search in the social_accounts table whether the user has logged in at all
        if ($user = $this->findUserBySocialId($provider, $socialiteUser->getId())) {
            return [$user, 'true'];
        }

        // Search in the social_accounts table by the user's email 
        // in case the user has registered for other social networks
        if ($user = $this->findUserByEmail($provider, $socialiteUser->getEmail())) {
            // If such a user is found, then we simply add a new social network to it
            $this->addSocialAccount($provider, $user, $socialiteUser);
            return [$user, 'true'];
        }

        // If the user is not found anywhere, add to the users table
        $user = User::create([
            'name' => $socialiteUser->getName(),
            'email' => $socialiteUser->getEmail(),
            'password' => bcrypt(Str::random(25)),
        ]);
        $profile = Profile::create(['user_id' => $user->id]);
        // And also add the user to the social_accounts table
        $this->addSocialAccount($provider, $user, $socialiteUser);

        return [$user, 'false'];
    }

    // Search in the social_accounts table whether the user has logged in at all
    public function findUserBySocialId($provider, $id)
    {
        $socialAccount = SocialAccount::where('provider', $provider)
            ->where('provider_id', $id)
            ->first();

        return $socialAccount ? $socialAccount->user : false;
    }

    // User search function in the social_accounts table by email
    public function findUserByEmail($provider, $email)
    {
        return !$email ? null : User::where('email', $email)->first();
    }

    // The function of creating a new user authorization method in the social_accounts table
    public function addSocialAccount($provider, $user, $socialiteUser)
    {
        SocialAccount::create([
            'user_id' => $user->id,
            'provider' => $provider,
            'provider_id' => $socialiteUser->getId(),
            'token' => $socialiteUser->token,
        ]); 
    }
}  
