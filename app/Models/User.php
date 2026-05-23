<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_SUPER_ADMIN = 'super_admin';
    public const ROLE_ADMIN_LOKET = 'admin_loket';
    public const ROLE_AGEN_TUJUAN = 'agen_tujuan';
    public const ROLE_OWNER = 'owner';

    public const ROLES = [
        self::ROLE_SUPER_ADMIN,
        self::ROLE_ADMIN_LOKET,
        self::ROLE_AGEN_TUJUAN,
        self::ROLE_OWNER,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'branch_id',
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

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function createdShipments(): HasMany
    {
        return $this->hasMany(Shipment::class, 'created_by');
    }

    public function destinationShipments(): HasMany
    {
        return $this->hasMany(Shipment::class, 'destination_agent_id');
    }

    public function shipmentStatusLogs(): HasMany
    {
        return $this->hasMany(ShipmentStatusLog::class, 'created_by');
    }

    public function originManifests(): HasMany
    {
        return $this->hasMany(Manifest::class, 'origin_admin_id');
    }

    public function destinationManifests(): HasMany
    {
        return $this->hasMany(Manifest::class, 'destination_agent_id');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return in_array($this->role, self::ROLES, true);
    }
}
