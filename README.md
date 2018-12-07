# Symfony 4 multi-tenancy configuration #

## Installation ##
* Configure .env.dev.local for Main database connection

Example:

```
APP_ENV=dev
APP_SECRET=yoursecretkey
DATABASE_URL=mysql://root:@127.0.0.1:3306/multitenancy
```
* Run console commands for Main DB

```
bin/console doctrine:database:create
bin/console doctrine:schema:create
bin/console doctrine:fixtures:load --no-interaction
```

It will generate such database:

```
'1', 'Fist Tenant', 'multitenancy_tenant1', 'root', ''
'2', 'Second Tenant', 'multitenancy_tenant2', 'root', ''
```

* Run console commands for Tenant DBs

```
bin/console doctrine:database:create --tenant=multitenancy_tenant1
bin/console doctrine:database:create --tenant=multitenancy_tenant2
bin/console doctrine:schema:create --tenant=multitenancy_tenant1
bin/console doctrine:schema:create --tenant=multitenancy_tenant2
```

* Add some data to User table in tenant's databases.

* Run ```bin/console s:r```
