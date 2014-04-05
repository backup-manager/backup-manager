Postgres [![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/zzet/ansible-postgresql-role/trend.png)](https://bitdeli.com/free "Bitdeli Badge")
========

A [role](https://galaxy.ansibleworks.com/list#/roles/101) for base setup and configuring [PostgreSQL](http://www.postgresql.org/) server with client on unix based hosts using [Ansible](http://www.ansibleworks.com/).

_This role under active development_.

**Attention**: default auth method: `trust` (managed via config)

#### Supported PostgreSQL versions:
  
  - PostgreSQL 9.1 (experimental * )
  - PostgreSQL 9.2
  - PostgreSQL 9.3 (experimental * )

* - Please, give me feedback if you have some problems on github issue tracker

#### Supported OS:

  - Ubuntu 12.04 (main test OS + Travis)
  - Debian (tested)

#### Extensions

   - [POSTGIS](http://postgis.refractions.net/)

Requirements
------------

None

Role Variables
--------------

Please, see available variables and defaults in [default variables file](https://github.com/zzet/ansible-postgresql-role/blob/master/defaults/main.yml)

Dependencies
------------

None

License
-------

MIT

ToDo
-------

 - Extensions
   - [HSTORE](http://www.postgresql.org/docs/9.2/static/hstore.html)
   - [POSTPIC](http://github.com/drotiro/postpic)
   - [PL/PROXY](http://pgfoundry.org/projects/plproxy/)
   - [TEXCALLER](http://www.profv.de/texcaller/)
   - [PGMEMCACHE](http://pgfoundry.org/projects/pgmemcache/)
   - [PREFIX](http://pgfoundry.org/projects/prefix)
   - [PGSPHERE](http://pgsphere.projects.postgresql.org/)
   - [MULTICORN](http://multicorn.org/)
   - [INTARRAY](http://www.postgresql.org/docs/9.2/static/intarray.html)
   - [DBLINK](http://www.postgresql.org/docs/9.2/static/dblink.html)

Author Information
------------------

[Andrew Kumanyaev](https://github.com/zzet)

Contributors Information
------------------

 - [Alexander Vagin](https://github.com/PlugIN73)
 - [DAUPHANT Julien](https://github.com/jdauphant)
