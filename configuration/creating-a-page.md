---
layout: master
title: Creating a new page
---

You can create as many Grideditor pages as you need by creating [Menu Items](http://rtfm.modx.com/display/revolution20/Actions+and+Menus)
in the MODx manager. Each page can be controlled by a different config chunk.


## Step 1: Create a new Menu Item for the page
* In the MODx manager menu, select System >> Actions.
* Click the 'Create Menu' button
![Create a Menu item](assets/img/images/modx-menu-items.png)


## Step 2: Setup the menu item
* The name of the page can be set in the 'Lexicon Key' field. This can either
  be a lexicon string, or just a straight-up string.
* An optional description
* 'Action' should be `grideditor - controller`. It is usually found on page 4 of
  the dropdown menu
* Set the configuration chunk to use in the Parameters field. It should be in the form
  of `&config=[configName]`

![Create a Menu item](assets/img/images/create-menu-item.png)
