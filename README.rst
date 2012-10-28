Gitosis Administrator
=====================

Manage gitosis over CLI- and Web-Interface.

Installation
------------

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

Remove Group
""""""""""""

.. code-block:: sh

  $ bin/n98-gitosis-admin group:remove name


Web Interface
-------------

Web Interface is coming.... First CLI interface must be finished.