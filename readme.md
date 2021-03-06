# laravel-schemaspy-sample

## Build

```
$ docker-compose up -d --build web sqlsrv
```

## Database Migrations

### Create Database

```
$ docker-compose exec sqlsrv /opt/mssql-tools/bin/sqlcmd -H localhost -U SA -Q "create database laraveldb"
```

Enter the password that was set database.

### Running Migrations

```
$ docker-compose exec web php artisan migrate
```

## Show DB definition and ER diagrams

Build and Run schemaspy container for executing Schemaspy

```
$ docker-compose run --rm schemaspy
```

Open `schemaspy/output/index.html` in Web Broeser view.

## Generate schemaMeta XML for Schemaspy

Create or Update XML file showing relationship.

```
$ docker-compose exec web php artisan schemaspy-meta:generate App\\Models --excludeClass=Flight --xmlFile=schemaspy/schema-meta.xml
```

Run schemaspy container again after generating xml.

## Dependencies

https://github.com/mya-zaki/laravel-schemaspy-meta
