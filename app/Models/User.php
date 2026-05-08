<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string|null $phone
 * @property string|null $avatar_path
 * @property bool $is_active
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    protected $fillable = [
        'first_name', 'last_name', 'email', 'password',
        'phone', 'date_of_birth', 'gender', 'country', 'city',
        'avatar_path', 'is_active', 'bio', 'preferences',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at'     => 'datetime',
            'date_of_birth'     => 'date',
            'is_active'         => 'boolean',
            'preferences'       => 'array',
            'password'          => 'hashed',
        ];
    }

    /* ---------------------------------------------------------------- */
    /* Relations                                                         */
    /* ---------------------------------------------------------------- */

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class, 'jury_id');
    }

    public function programs(): BelongsToMany
    {
        return $this->belongsToMany(Program::class, 'program_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function programsAsOrganizer(): BelongsToMany
    {
        return $this->programs()->wherePivot('role', 'organizer');
    }

    public function programsAsJury(): BelongsToMany
    {
        return $this->programs()->wherePivot('role', 'jury');
    }

    public function programsAsParticipant(): BelongsToMany
    {
        return $this->programs()->wherePivot('role', 'participant');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function partner(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Partner::class);
    }

    /* ---------------------------------------------------------------- */
    /* Accessors / Scopes                                                */
    /* ---------------------------------------------------------------- */

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => trim($this->first_name.' '.$this->last_name),
        );
    }

    protected function initials(): Attribute
    {
        return Attribute::make(
            get: fn () => mb_strtoupper(
                mb_substr($this->first_name, 0, 1).mb_substr($this->last_name, 0, 1)
            ),
        );
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeWithRole(Builder $query, UserRole|string $role): Builder
    {
        return $query->role($role instanceof UserRole ? $role->value : $role);
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(UserRole::Admin->value);
    }
}
