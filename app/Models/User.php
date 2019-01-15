<?php

namespace App\Models;

use App\Models\Traits\UserTrait;
use App\Utils\Traits\MakesHash;
use App\Utils\Traits\UserSessionAttributes;
use App\Utils\Traits\UserSettings;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laracasts\Presenter\PresentableTrait;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    use SoftDeletes;
    use PresentableTrait;
    use MakesHash;
    use UserSessionAttributes;
    use UserSettings;
    
    protected $guard = 'user';

    protected $dates = ['deleted_at'];

    protected $presenter = 'App\Models\Presenters\UserPresenter';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'signature',
        'avatar',
        'accepted_terms_version'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
        'oauth_user_id',
        'oauth_provider_id',
        'google_2fa_secret',
        'google_2fa_phone',
        'remember_2fa_token',
        'slack_webhook_url',
    ];

    /**
     * Returns all companies a user has access to.
     * 
     * @return Collection
     */
    public function companies()
    {
        return $this->belongsToMany(Company::class)->withPivot('permissions','settings');
    }

    /**
     * Returns the current company
     * 
     * @return Collection
     */
    public function company()
    {
        return $this->companies()->where('company_id', $this->getCurrentCompanyId())->first();
    }

    /**
     * Returns a object of user permissions
     * 
     * @return stdClass
     */
    public function permissions()
    {
        
        $permissions = json_decode($this->company()->pivot->permissions);
        
        if (! $permissions) 
            return [];

        return $permissions;
    }

    /**
     * Returns a object of User Settings
     * 
     * @return stdClass
     */
    public function settings()
    {
        return json_decode($this->company()->pivot->settings);
    }

    /**
     * Returns a boolean of the administrator status of the user
     * 
     * @return bool
     */
    public function is_admin()
    {
        return $this->company()->pivot->is_admin;
    }

    /**
     * Returns all user created contacts
     * 
     * @return Collection
     */
    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    /**
     * Returns a boolean value if the user owns the current Entity
     * 
     * @param  string Entity
     * @return bool
     */
    public function owns($entity) : bool
    {
        return ! empty($entity->user_id) && $entity->user_id == $this->id;
    }

    /**
     * Flattens a stdClass representation of the User Permissions
     * into a Collection
     * 
     * @return Collection
     */
    public function permissionsFlat()
    {
        return collect($this->permissions())->flatten();
    }

    /**
     * Returns a array of permission for the mobile application
     * 
     * @return array
     */
    public function permissionsMap()
    {
        
        $keys = array_values((array) $this->permissions());
        $values = array_fill(0, count($keys), true);

        return array_combine($keys, $values);
    }

}
