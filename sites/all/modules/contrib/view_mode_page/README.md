# Introduction

This module helps create separate pages for different view modes for a given
content type.

On the manage display page for a given content type, you can assign patterns
for view modes so that they will become a page at the given path. For
instance, if you want to show the teaser at a specific URL, you could do
`node/%/teaser`. If you also use [Display Suite][ds] or other modules that allow you
to create new view modes, you can use these modes to display one or more fields
from a node at a separate URL.

Restrictions: currently, the node path must be contained in the URL. This means
view mode pages must follow the form [node path/node alias]/[view mode url].
These pages are added via hook_menu. Multiple wildcard characters can be used.

# How to add a View Mode Page

1. Create a content type
2. Configure a view mode. If you are using Display Suite, or another means of
   adding view modes, you may need to add a new one.
3. Go to the "manage display" tab for your content type.
4. In the "View mode pages" tab, enter your URL pattern for the view mode page.
5. Save the content type. You may also need to clear the Drupal cache, but the
   page should be available!

## Some Examples: using URL Patterns

If you're using the [Pathauto module][pathauto], it can be confusing
which path you should use with View Mode Page. So, assuming we have a content type
called Project, we can:

1. Set the URL pattern for Project (in the Pathauto settings) to: `project/[node:title]`
2. If we have a Project with a title of "My Example", that project will have an alias
   of `project/my-example`.
3. To associate a View Mode Page pattern for this, we need to ensure that the node
   alias is part of the pattern. In this case, we'd use `project/%/my-view-mode` in
   the View Mode Page settings. The wildcard character, `%`, will take the place of the
   project title from the alias.
4. Our View Mode Page will now be accessible as: `project/my-example/my-view-mode`

View Mode Page uses [`hook_menu_alter`][hook] to add the exact pattern you specify
as a menu route. The wildcard character should be used for variable parts of
the alias. This may be the NID, but it could be other things.

If our Project could have a category, our Pathauto pattern may be:

    category/[node:field-category]/[node:title]

In this case, our View Mode Page pattern will need two wildcards:

    category/%/%/my-view-mode

**NOTE:** You cannot use a pattern like `%/my-view-mode` as Drupal does not support menu
items with a wildcard in the first position. This is true for any menu items, not just
VMP.

# Entity Support

The VMP module now supports entities and not just nodes. To accomplish this, the node-based
functions were left in the module for now but are *deprecated*. Please update to use the
new entity based functions to ensure forward compatibility.

Entity based functions now take an array as an argument with certain required settings. This
should hopefully help in the future where new arguments need to be added. There are also new
entity based hooks that should be used. The existing hooks will also still be used.

# API

This module includes several API hooks that are helpful for modifying the VMP default
functionality, including:

- hook_view_mode_page_get_entity_patterns
- hook_view_mode_page_pre_entity_view
- hook_view_mode_page_post_entity_view
- hook_view_mode_page_entity_pattern_added
- hook_view_mode_page_entity_patterns_deleted

More information about the hooks can be found in the `view_mode_page.api.php` file.

[pathauto]: http://drupal.org/project/pathauto
[ds]: http://drupal.org/project/ds
[hook]: http://api.drupal.org/api/drupal/modules%21system%21system.api.php/function/hook_menu_alter/7
