# Digitalia MUNI Collections

## Installation and configuration

**Install and enable the module:**

`git clone-alias git@github.com:ArtsFacultyMU/digitalia-module-digitalia_muni_collections.git digitalia_muni_collections`

`drush en digitalia_muni_collections`


**Create the config file:**

`drush cset digitalia_muni_collections.settings collections '[]' -y`

Edit the file with command `drush cedit digitalia_muni_collections.settings collections` or in admin UI.

Add collections in format "id: label", e.g.

<code>collections:
&nbsp;&nbsp;privateletters: Private Letters</code>


**Run all the neccessary set-up functions:**

These are located in .module file. The set_allowed_values function needs to be ran after the config is created, which is why it is not ran on installation.


Create the field DM Collection (field_dm_collection) on all content types except the default Islandora content types (Article, Basic Page a Islandora Object):

`drush eval 'digitalia_muni_collections_create_field()'`


Set allowed values for this field to collections in the configuration:

`drush eval 'digitalia_muni_collections_set_allowed_values()'`


**Add the Current collection block for users**

In Structure - Block layour, add block Current collection to Highlighted region.

