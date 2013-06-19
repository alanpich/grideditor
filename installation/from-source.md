---
layout: master
title: Building from Source
---

For people who think a stable release is just plain dull. Grideditor can be built from source to generate a
MODx transport package from the most up-to-date code (usually on the *develop* branch).


## Step 1: Clone the git repository
    $ git clone git@github.com:alanpich/grideditor.git
    $ cd grideditor


## Step 2: Create a configuration file
In the repository root, `copy config.core.sample.php` to `config.core.php`.
Update the MODX_BASE_PATH and MODX_BASE_URL definitions to point to a local MODx installation



## Step 3: Generate the transport package
Generate an installer zip ready for install. Package will be created in the repository root folder

    $ php _build/build.transport.php
        -or-
    $ phing build


## Step 4: Copy zip file to MODx installation
Copy `grideditor-X.X.X-X.transport.zip` to `[modx_installation]/core/packages/`


## Step 5: Install and enjoy!
In the MODx manager, go to the Package Manager, search for local packages and then install
