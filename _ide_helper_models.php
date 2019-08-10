<?php

// @formatter:off

/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models {
    /**
     * App\Models\User
     *
     * @property string $id
     * @property int $uid
     * @property string $email 账号
     * @property string $password 密码
     * @property string|null $nickname 昵称
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User query()
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User uuid($uuid, $first = true)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmail($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereNickname($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePassword($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUid($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUpdatedAt($value)
     */
    class User extends \Eloquent
    {
    }
}

namespace App {
    /**
     * App\User
     *
     * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User query()
     */
    class User extends \Eloquent
    {
    }
}

