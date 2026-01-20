<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Like;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Connection;
use App\Enums\ConnectionStatusEnum;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, MustVerifyEmailTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'bio',
        'image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function receivedConnections()
    {
        return $this->hasMany(Connection::class, 'receiver_id', 'id');
    }

    public function sentConnections()
    {
        return $this->hasMany(Connection::class, 'sender_id', 'id');
    }

    /**
     * Get all accepted connections (friends)
     */
    public function friends()
    {
        $sent = $this->sentConnections()
            ->where('status', ConnectionStatusEnum::ACCEPTED->value)
            ->get()
            ->pluck('receiver_id');

        $received = $this->receivedConnections()
            ->where('status', ConnectionStatusEnum::ACCEPTED->value)
            ->get()
            ->pluck('sender_id');

        $friendIds = $sent->merge($received)->unique();

        return User::whereIn('id', $friendIds);
    }

    /**
     * Check if user is friends with another user
     */
    public function isFriendWith(User $user): bool
    {
        if ($this->id === $user->id) {
            return false;
        }

        return Connection::where(function ($query) use ($user) {
            $query->where('sender_id', $this->id)
                ->where('receiver_id', $user->id);
        })
            ->orWhere(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                    ->where('receiver_id', $this->id);
            })
            ->where('status', ConnectionStatusEnum::ACCEPTED->value)
            ->exists();
    }

    /**
     * Get connection status with another user
     */
    public function getConnectionStatus(User $user): ?ConnectionStatusEnum
    {
        if ($this->id === $user->id) {
            return null;
        }

        $connection = Connection::where(function ($query) use ($user) {
            $query->where('sender_id', $this->id)
                ->where('receiver_id', $user->id);
        })
            ->orWhere(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                    ->where('receiver_id', $this->id);
            })
            ->first();

        return $connection ? $connection->status : null;
    }

    /**
     * Get pending friend requests received
     */
    public function pendingFriendRequests()
    {
        return $this->receivedConnections()
            ->where('status', ConnectionStatusEnum::PENDING->value)
            ->with('sender');
    }

    /**
     * Get pending friend requests sent
     */
    public function sentFriendRequests()
    {
        return $this->sentConnections()
            ->where('status', ConnectionStatusEnum::PENDING->value)
            ->with('receiver');
    }
}
