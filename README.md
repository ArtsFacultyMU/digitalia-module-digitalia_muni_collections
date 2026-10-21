# Digitalia MUNI Collections

This module uses a session variable "collection" to help users navigate between different collections stored in the same repository. The variable is set whenever a user visits a collection homepage and is cleared when they return to the repository homepage. If the user visits another collection homepage, the variable is updated to the new collection.

When the variable is set, all browse and search pages are filtered to display only nodes belonging to the selected collection. A Current collection block is displayed above the main content to inform users of the active filter, with the message: “You are working in collection [collection ID]”.


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



**Create the field DM Collection (field_dm_collection) on all content types except the default Islandora content types (Article, Basic Page a Islandora Object):**

`drush eval 'digitalia_muni_collections_create_field()'`


**Set allowed values for this field to collections in the configuration:**

`drush eval 'digitalia_muni_collections_set_allowed_values()'`



**Note**: there is also function `digitalia_muni_collections_populate_field($collection_id)`, that populates **all nodes of all the relevant content types** with the specified collection. This rewrites any data that are in the field_dm_collection, so use it only in case there's currently just one collection.


**Add the Current collection block for users**

In Structure - Block layour, add block Current collection to Highlighted region.

## Conventions

For the session variable to work correctly, the following conventions must be followed:
- **Collection homepages**: Each collection homepage must use the URL pattern `/collections/collection-id`, e.g. `/collections/privateletters`.
- **Search pages**: The main search page must be located at `/search`. If multiple search pages are needed, use the `/search/*` URL pattern.
- **Browse pages**: Browse pages must use the `/browse/*` URL pattern, e.g. `/browse/works`.
- **Collection filtering**: Search and browse pages must include an exposed filter or facet for the DM Collection field. The Filter identifier setting in the views must be set to "collection".
