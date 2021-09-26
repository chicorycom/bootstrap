<?php

namespace Boot\Support;

use App\Models\User;

class Auth
{
    /**
     * @param $email
     * @param $password
     * @return bool
     */
    public static function attempt($email, $password): bool
    {
        $exists = User::where(compact('email'))->first();


        $validator = session()->validator(compact('email', 'password'), [
            'email' => 'required|exists:admin,email',
            //'password' => ['required', 'exists:admin,password', $password_exists_with_email],
        ],
        [
            'email.exists' => 'Email not found',
            'password.exists' => 'Password not found'
        ]);

        if ($validator->fails()) return false;

        if(!password_verify($password, $exists->password)) return false;

       $user = User::where('email', $email)->first();


        $id = $user->id;
        $email = $user->email;
        $role = $user->role;
        $token = bin2hex(openssl_random_pseudo_bytes(16));
        User::where('id', $user->id)->update(['statue'=>true, 'token'=>$token]);

        session()->set('user', compact('id', 'email', 'token', 'role'));

        return true;
    }


    public static function logout()
    {
        User::where(session()->get('user'))->update(['statue'=>false, 'token'=>null]);
        $id = null;
        $email = null;
        $token = null;
        $profil = null;
        session()->set('user', compact('id', 'email', 'token', 'profil'));
    }


    public static function user()
    {
        if (!session()->has('user')) return false;


        $query = User::where(session()->get('user'));


        app()->bind(
            Auth::class,
            $query->exists() ? $query->first() : false
        );

        return app()->resolve(Auth::class);
    }


    public static function check() : bool
    {
        return (bool) self::user();
    }


    public static function guest() : bool
    {
        return (bool) self::user() === false;
    }
}
