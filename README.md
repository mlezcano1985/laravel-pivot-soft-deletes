# DEPRECATED
This is no longer supported, please consider using another package instead.

# Pivot soft deletes for the Laravel PHP Framework
Easy and fast way to soft deletes Eloquent pivot models using Laravel [SoftDeletes](https://laravel.com/docs/eloquent#soft-deleting) trait.

# Installation
This trait is installed via [Composer](http://getcomposer.org/). To install, simply add to your `composer.json` file:
```
$ composer require mlezcano1985/laravel-pivot-soft-deletes
```
# Example
Include SoftDeletes and PivotSoftDeletes in Many to Many models.
```php
<?php
namespace App;

use Mlezcano1985\Database\Support\PivotSoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class Account extends Authenticatable
{
    use Notifiable, SoftDeletes, PivotSoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    }

    /**
     * @return BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Automatically creates hash for the user password.
     *
     * @param  string $value
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
}
```
and
```php
<?php
namespace App;

use Mlezcano1985\Database\Support\PivotSoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use SoftDeletes, PivotSoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    }

    /**
     * @return BelongsToMany
     */
    public function accounts()
    {
        return $this->belongsToMany(Account::class);
    }
}
```
Now we can use `detach()` method on Account model to softdelete the pivot table
```php
$account = App\Role::find($role_id)->accounts()->findOrFail($account_id)
$account->detach(); // Soft delete the Intermediate Table
```
## Defining Custom Intermediate Table Model
If we want to define a [Custom Intermediate Table Model](https://laravel.com/docs/eloquent-relationships#many-to-many), the process works in the same way. For example:
```php
/**
 * @return BelongsToMany
 */
public function roles()
{
    return $this->belongsToMany(Role::class)->using(AccountRole::class);
}
```
but is hight reccommended to include **SoftDeletes** trait on custom pivot model
```php
<?php
namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountRole extends Pivot
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    }
}
```
and now
```php
$account = App\Role::find($role_id)->accounts()->findOrFail($account_id)
$account->detach(); // Soft delete the Intermediate Table
```

# Support
If you are having general issues with this package, feel free to contact me on [Twitter](https://twitter.com/mlezcano1985).

If you believe you have found an issue, please report it using the [GitHub issue tracker](https://github.com/mlezcano1985/laravel-pivot-soft-deletes/issues), or better yet, fork the repository and submit a pull request.

If you're using this package, I'd love to hear your thoughts. Thanks!
