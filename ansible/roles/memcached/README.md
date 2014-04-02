memcached
========

This role helps to install a memcached server on the target host.

Requirements
------------

This role requires Ansible 1.4 or higher, and platform requirements are listed
in the metadata file.

Role Variables
--------------

The variables that can be passed to this role and a brief description about
them are as follows:

	memcached_port: 11211           # The port in which memcached server should be listening
	memcached_max_conn: 1024        # The number of max concurrent connections it shoud accept
	memcached_cache_size: 64        # The cache size
	memcached_fs_file_max: 756024   # The kernel paramter for max number of file handles

Example
-------

The following play setup's memcached with a different port number and
available memory.

	- hosts: all
	  sudo: true
	  roles:
	  - {role: bennojoy.memcached, memcached_port: 11244, memcached_cache_size: 512 }

Dependencies
------------

None

License
-------

BSD

Author Information
------------------

Benno Joy

