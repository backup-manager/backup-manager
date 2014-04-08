# Database Backup Manager

[![Latest Stable Version](https://poser.pugx.org/mccool/database-backup/version.png)](https://packagist.org/packages/mccool/database-backup)
[![License](https://poser.pugx.org/mccool/database-backup/license.png)](https://packagist.org/packages/mccool/database-backup)
[![Build Status](https://travis-ci.org/heybigname/database-backup-manager.svg?branch=master)](https://travis-ci.org/heybigname/database-backup-manager)
[![Coverage Status](https://coveralls.io/repos/heybigname/database-backup-manager/badge.png?branch=master)](https://coveralls.io/r/heybigname/database-backup-manager?branch=master)
[![Total Downloads](https://poser.pugx.org/mccool/database-backup/downloads.png)](https://packagist.org/packages/mccool/database-backup)

- supports MySQL and PostgreSQL
- backup to or restore databases from AWS S3, Dropbox, FTP, SFTP, Rackspace Cloud, and WebDAV
- compress with Gzip
- framework-agnostic
- dead simple configuration
- optional integrations for MVC framework [Laravel](http://laravel.com) and team-communication software [Slack](http://slack.com)

### Requirements

- PHP 5.4
- MySQL support requires `mysqldump` and `mysql` command-line binaries
- PostgreSQL support requires `pg_dump` and `psql` command-line binaries
- Gzip support requires `gzip` and `gunzip` command-line binaries

### License

MIT
