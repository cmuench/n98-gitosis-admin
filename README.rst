Gitosis Administrator
=====================

Manage gitosis over CLI- and Web-Interface.

.. image:: https://secure.travis-ci.org/cmuench/n98-gitosis-admin.png
  :target: https://secure.travis-ci.org/cmuench/n98-gitosis-admin

Installation
------------

Download the composer.phar executable or use the installer.

.. code-block:: sh

   $ curl -s http://getcomposer.org/installer | php

Install dependencies over composer.

.. code-block:: sh

  $ php ./composer.phar install

Create **config.yaml** file from config.yaml.dist file.

Add path to your gitosis config (Cloned gitosis-admin repository):

.. code-block:: yaml

   locale: en
   security:
     authentification:
       enabled: false

   gitosis:
     root_directory: /path/to/gitosis-admin
     ssh_user: git
     ssh_host: myhost


CLI Interface
-------------

List Repositories
"""""""""""""""""

.. code-block:: sh

  $ bin/n98-gitosis-admin repo:list

Add Repository
""""""""""""""

.. code-block:: sh

  $ bin/n98-gitosis-admin repo:add name [owner] [description] [gitweb] [daemon]

Example:

.. code-block:: sh

  $ bin/n98-gitosis-admin repo:add my-repo "John Doe" "My awesome git repository" no no

Remove Repository
"""""""""""""""""

.. code-block:: sh

  $ bin/n98-gitosis-admin repo:remove name


List Groups
"""""""""""

.. code-block:: sh

  $ bin/n98-gitosis-admin group:list

Add Group
"""""""""

.. code-block:: sh

  $ bin/n98-gitosis-admin group:add name members [writable] [readonly]

Example:

.. code-block:: sh

  # Adds the repo "foo" with members "bar, zoz and bla" with write access to "repo1" and read access to "repo2"
  $ bin/n98-gitosis-admin group:add foo bar,zoz,bla repo1 repo2

Remove Group
""""""""""""

.. code-block:: sh

  $ bin/n98-gitosis-admin group:remove name

Add User to existing Group
""""""""""""""""""""""""""

.. code-block:: sh

  $  bin/n98-gitosis-admin group:user:add group username

Remove User from existing Group
"""""""""""""""""""""""""""""""

.. code-block:: sh

  $  bin/n98-gitosis-admin group:user:remove group username


Remove a user from all groups
"""""""""""""""""""""""""""""

.. code-block:: sh

  $ bin/n98-gitosis-admin user:remove username

List all existing users
"""""""""""""""""""""""

Lists all users across all groups.

.. code-block:: sh

  $  bin/n98-gitosis-admin user:list

Allow group write acccess to repository
"""""""""""""""""""""""""""""""""""""""

.. code-block:: sh

  $ bin/n98-gitosis-admin group:repo:add:writable

Allow group readonly acccess to repository
""""""""""""""""""""""""""""""""""""""""""

.. code-block:: sh

  $ bin/n98-gitosis-admin group:repo:add:readonly

Web Interface
=============

* Manage Repositories, Groups and Users

Installation
------------

* Clone `gitosis-admin` repository on your local machine.

* Make sure that webserver user has a valid ssh key which is assigned to `gitosis-admin` repository.

On Debian Systems with Apache User:

.. code-block:: sh

   $ mkdir /var/www/.ssh
   $ chown -R www-data:nobody /var/www/.ssh
   $ sudo -u www-data ssh-keygen -t rsa

TODO
====

* Auth Layer with LDAP support
* Translations