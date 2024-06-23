<?php

declare(strict_types=1);

namespace App\Core\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Core\Dtos\PersonalNameDto;
use App\Core\Enums\Permission;
use App\Core\Traits\Media\InteractsWithMediaUsingNumericKey;
use App\Infrastructure\Casts\PersonalNameCast;
use Carbon\Carbon;
use Codestage\Authorization\Traits\HasPermissions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;

/**
 * @property int $id
 * @property PersonalNameDto $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string $remember_token
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Educator|null $educatorProfile The educator profile record associated with this identity.
 * @property-read StudentRegistration|null $studentRegistration The student registration record associated with this identity.
 */
class Identity extends Authenticatable implements HasMedia
{
    use HasFactory;
    use Notifiable;
    use InteractsWithMediaUsingNumericKey;

    /** @use HasPermissions<Permission> */
    use HasPermissions;

    public const string ProfilePictureMediaCollection = 'profilePicture';

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

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::ProfilePictureMediaCollection)
            ->useFallbackUrl(
                'https://ui-avatars.com/api/?name=' .
                    urlencode($this->name->getFullName()) .
                    '&background=random&size=128',
            );
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

    /**
     * Get the educator profile record associated with this identity.
     *
     * @return HasOne<StudentRegistration>
     */
    public function studentRegistration(): HasOne
    {
        return $this->hasOne(StudentRegistration::class, 'identity_id');
    }
}
