<?php

namespace App\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LegacyUserProvider implements UserProvider
{
    protected $hasher;
    protected $model;

    public function __construct($hasher, $model)
    {
        $this->hasher = $hasher;
        $this->model = $model;
    }

    public function retrieveById($identifier)
    {
        return $this->createModel()->newQuery()->find($identifier);
    }

    public function retrieveByToken($identifier, $token)
    {
        return $this->createModel()->newQuery()
            ->where($this->createModel()->getAuthIdentifierName(), $identifier)
            ->where($this->createModel()->getRememberTokenName(), $token)
            ->first();
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        $user->setRememberToken($token);
        $user->save();
    }

    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials) ||
           (count($credentials) === 1 &&
            array_key_exists('password', $credentials))) {
            return;
        }

        $query = $this->createModel()->newQuery();

        foreach ($credentials as $key => $value) {
            if (! str_contains($key, 'password')) {
                $query->where($key, $value);
            }
        }

        return $query->first();
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        $plain = $credentials['password'];

        // First try standard Laravel password check
        if ($this->hasher->check($plain, $user->getAuthPassword())) {
            return true;
        }

        // If user has legacy password, check it
        if (!empty($user->legacy_password)) {
            // Legacy password format: MD5:SALT
            if (str_contains($user->legacy_password, ':')) {
                [$hashType, $saltAndHash] = explode(':', $user->legacy_password, 2);

                if ($hashType === 'MD5' && str_contains($saltAndHash, ':')) {
                    [$salt, $storedHash] = explode(':', $saltAndHash, 2);

                    // Check if input password matches legacy hash
                    if (md5($salt . $plain) === $storedHash) {
                        // Password is correct, migrate to bcrypt
                        $user->password = Hash::make($plain);
                        $user->legacy_password = null;
                        $user->save();

                        return true;
                    }
                }
            }
        }

        return false;
    }

    public function rehashPasswordIfRequired(Authenticatable $user, array $credentials, bool $force = false)
    {
        // Laravel 9+ handles this automatically
        return false;
    }

    protected function createModel()
    {
        $class = '\\'.ltrim($this->model, '\\');

        return new $class;
    }

    public function getModel()
    {
        return $this->model;
    }
}