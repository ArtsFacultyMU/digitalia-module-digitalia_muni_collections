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

These are defined in the .module file. The `digitalia_muni_collections_set_allowed_values()` function must be executed after the configuration has been created, so it is not called during module installation.



Create the field DM Collection (field_dm_collection) on all content types except the default Islandora content types (Article, Basic Page a Islandora Object):

`drush eval 'digitalia_muni_collections_create_field()'`


Set allowed values for this field to collections in the configuration:

`drush eval 'digitalia_muni_collections_set_allowed_values()'`


Note: there is also function `digitalia_muni_collections_populate_field($collection_id)`, that populates **all nodes of all the relevant content types** with the specified collection. Use it only in case there's currently just one collection. This rewrites any data that are in the field_dm_collection, so use it with caution.


**Add the Current collection block for users**

In Structure - Block layour, add block Current collection to Highlighted region.

