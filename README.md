Laravel 7 Access Control
======================

Laravel 7 Access Control brings a simple and light-weight role-based permissions system to Laravel's built in Auth system. Laravel Access Control brings support for the following ACL structure:

- Every user can have zero or more permissions.
- Every user can have zero or more roles.
- Every role can have zero or more permissions.
- Every role can have one special flags, all-access or no-access

Quick Installation
------------------

1. `composer require pierresilva/laravel-access-control`

2. `php artisan migrate`

3. Add the `AccessControlTrait` trait to your `User` model.

```
...
use pierresilva\AccessControl\Traits\AccessControlTrait;

class User extends Authenticatable
{
    ...
    use AccessControlTrait;
    ...
}
```

## Usage

### Middleware

AccessControl provides middleware that you may assign to specific routes in your application. To register, simply append the following middleware to your app/Http/Kernel.php file under the $routeMiddleware property.

#### Check If User Has A Given Role

```
'has.role' => \pierresilva\AccessControl\Middleware\UserHasRole::class,
```

#### Check If User Has A Given Permission

```
'has.permission' => \pierresilva\AccessControl\Middleware\UserHasPermission::class,
```

### Facade

AccessControl provides a facade to check user roles and permissions.

#### Check user has a role

`\AccessControl::userIs('role.slug')`

#### Check user has any of given roles

`\AccessControl::userIs(['role.slug.1', 'role.slug.2'])`

#### Check user if user has a permission

`\Accesscontrol::userCan('permission.slug')` 

#### Check user if user has any of given permissions

`\Accesscontrol::userCan(['permission.slug.1', 'permission.slug.2'])`
