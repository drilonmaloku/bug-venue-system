<?php

declare(strict_types=1);

namespace App\Modules\Users\Traits;

trait UserMetaTrait
{
    public function loadUserMeta() : void
    {
        $this->load("userMeta");
    }

    public function setUserMeta($key, $value)
    {
        return $this->userMeta()->updateOrCreate(
            [
                "meta_key" => $key,
                "user_id" => $this->id,
            ],
            [
                "meta_value" => $value,
            ],
        );
    }

    public function getUserMeta($key)
    {
        return $this->userMeta()
            ->where("meta_key", $key)
            ->value("meta_value");
    }

    public function hasUserMeta($key) : bool
    {
        return $this->userMeta()
            ->where("meta_key", $key)
            ->exists();
    }
}