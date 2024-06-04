<?php

declare(strict_types=1);

namespace App\Core\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Core\Dtos\PersonalNameDto;
use App\Core\Enums\Permission;
use App\Infrastructure\Casts\PersonalNameCast;
use Carbon\Carbon;
use Codestage\Authorization\Traits\HasPermissions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int $id
 * @property PersonalNameDto $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string $remember_token
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Educator|null $educator The educator profile record associated with this identity.
 */
class Identity extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    /** @use HasPermissions<Permission> */
    use HasPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name_prefix',
        'first_name',
        'middle_names',
        'last_name',
        'name_suffix',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = ['password', 'remember_token'];

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
            'name' => PersonalNameCast::class,
        ];
    }

    /**
     * Get the educator profile record associated with this identity.
     *
     * @return HasOne<Educator>
     */
    public function educatorProfile(): HasOne
    {
        return $this->hasOne(Educator::class);
    }
}
