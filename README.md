<h1 align="center">phalcon-permission</h1>
<p align="center">An authorization library that supports access control models like ACL, RBAC, ABAC in Phalcon.</p>

## Installing

Require this package in the `composer.json` of your phalcon project. This will download the package.

```shell
$ composer require
```

Configure database connection:
```php
'database' => [
    'adapter' => 'mysql',
    'host' => '127.0.0.1',
    'username' => 'root',
    'password' => '',
    'dbname' => 'db-name',
    'charset' => 'utf8',
],
```

Then phalcon-permission migrations must run to create the needed tables. For this, you need to have installed the [Phalcon Dev Tools](https://github.com/phalcon/phalcon-devtools).

use [Phalcon Dev Tools](https://github.com/phalcon/phalcon-devtools) to migrate the migrations, run the command:

```shell
$ phalcon migration run --migrations=vendor/casbin/phalcon-permission/migrations
```

This will create a new table named `casbin_rules`.

## Usage

### Quick start

```php
use Easyswolle\Permission\Casbin;
use Easyswolle\Permission\Config;

$config = new Config();
$casbin = new Casbin($config);

// adds permissions to a user
$casbin->addPermissionForUser('eve', 'articles', 'read');
// adds a role for a user.
$casbin->addRoleForUser('eve', 'writer');
// adds permissions to a rule
$casbin->addPolicy('writer', 'articles', 'edit');
```

You can check if a user has a permission like this:

```php
// to check if a user has permission
if ($casbin->enforce('eve', 'articles', 'edit')) {
  // permit eve to edit articles
} else {
  // deny the request, show an error
}
```

### Using Enforcer Api

It provides a very rich api to facilitate various operations on the Policy:

Gets all roles:

```php
$casbin->getAllRoles(); // ['writer', 'reader']
```

Gets all the authorization rules in the policy.:

```php
$casbin->getPolicy();
```

Gets the roles that a user has.

```php
$casbin->getRolesForUser('eve'); // ['writer']
```

Gets the users that has a role.

```php
$casbin->getUsersForRole('writer'); // ['eve']
```

Determines whether a user has a role.

```php
$casbin->hasRoleForUser('eve', 'writer'); // true or false
```

Adds a role for a user.

```php
$casbin->addRoleForUser('eve', 'writer');
```

Adds a permission for a user or role.

```php
// to user
$casbin->addPermissionForUser('eve', 'articles', 'read');
// to role
$casbin->addPermissionForUser('writer', 'articles','edit');
```

Deletes a role for a user.

```php
$casbin->deleteRoleForUser('eve', 'writer');
```

Deletes all roles for a user.

```php
$casbin->deleteRolesForUser('eve');
```

Deletes a role.

```php
$casbin->deleteRole('writer');
```

Deletes a permission.

```php
$casbin->deletePermission('articles', 'read'); // returns false if the permission does not exist (aka not affected).
```

Deletes a permission for a user or role.

```php
$casbin->deletePermissionForUser('eve', 'articles', 'read');
```

Deletes permissions for a user or role.

```php
// to user
$casbin->deletePermissionsForUser('eve');
// to role
$casbin->deletePermissionsForUser('writer');
```

Gets permissions for a user or role.

```php
$casbin->getPermissionsForUser('eve'); // return array
```

Determines whether a user has a permission.

```php
$casbin->hasPermissionForUser('eve', 'articles', 'read');  // true or false
```

See [Casbin API](https://casbin.org/docs/en/management-api) for more APIs.