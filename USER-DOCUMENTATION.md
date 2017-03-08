![Art Institute of Chicago](https://raw.githubusercontent.com/Art-Institute-of-Chicago/template/master/aic-logo.gif)

# JourneyMaker CMS User Documentation

For a broad overview of how to use Drupal 7, please see their documentation on
[Adminstering Drupal 7 sites](https://www.drupal.org/docs/7/administering-drupal-7-site).
This documentation doesn't assume you have a basic understanding of how to administer
content in Drupal 7, but this might be helpful for futher context.


## Navigation bar

In the upper left hand corner of the Drupal menu is a "Home" button. This button will
always take you back to the home screen. Next to the Home Button, you will find three menu
items highlighted in grey: Manage, Shortcuts, and your username. 
Home is the most important screen. Here is a description of the management options you have access to: 

| Menu Item | Description |
|----------:|-------------|
| Home | The Home view shows you an at-a-glance summary of recent activity on the project site. |
| Content | Content Screen |
| Structure | Not accessible (Ignore) |
| Configuration | Change content authoring settings, search settings, and shortcuts |
| Help | Help provides links to specific help sections, pages, and handbooks as needed. |
| Username | Edit Passwords, View your Profile and Log out |
| **Grey Line:** | **Shortcuts** |
| Add content | Add content function |
| Find content | Search function |
| Art by Theme | Edit Art by Theme function |
| Search | The search navigation bar can search the entire CMS for a specific word. Use this when you need a quick way to land upon a specific Theme, Artwork, or Activity. |
| Edit shortcuts | Make your own shortcuts! |


## Artworks

The CMS is broken up into three content type categories: Activity Template, Artwork, and
Theme. This section focuses on adding Artwork, which can then be linked to Themes. Once
linked, the back-end system will automatically output all required image sizes as needed
throughout various placements in the software interface and Journey Guide. One Artwork
can be added to multiple Themes, as required. 

### Create an Artwork

1. Click "Home," then underneath click the "Add content" link.
2. Click the "Artwork" link.
3. A blank Artwork form will appear. In order to save the Item, a title is required (see
the following table for character count details). This should be the title of the artwork
and will appear in the front-end of the interactive.
4. Before clicking "Save," consider if you want this content to be published or not. If
you are editing content before public opening, this is less crucial. If you are making
edits after opening and this is live to the public, you’ll want to ensure "Published" is
left unchecked before saving, otherwise your changes will be shown in the front-end.
Navigate to the bottom of the page, and click the "Publishing options" tab and make your
selection. 
5. Once a title is entered and you've selected your publishing preference, you may click
"Save," and can revisit this Artwork at any time to continue editing.
6. If you've created an Artwork by mistake or wish to delete it, you can easily access it
again using the upper navigation. Click "Manage," then "Content." A list will appear of
all existing content items. You can sort Items by Title, Type, Author, or Date. If you
wish to edit, select "edit." If you wish to delete, click "delete." A confirmation will
appear. Be sure you wish to delete, as this action **cannot be undone**! From the
confirmation page you can either delete, or cancel and keep your Item.

### Edit an Artwork

1. Click "Home," then "Find Content," "Art by Theme," "Art," or "Search."  A list will
appear of all existing content items. You can sort items by Title, Type, Author, or Date.
2. Select the Artwork you'd like to edit. You"ll be taken to that item's page in the CMS.
Click the "Edit" tab in the upper right corner. 
3. From here you have an array of fields and selection boxes. See the above table for
detailed explanations of the fields. Be sure to click "Save" when you’ve completed your
changes. 
4. To add italics to any field, place `<em>` where you’d like italics to start, and `</em>` where you’d like italics to stop.

| Field | Description | Character Count/Field Specs |
|-------|-------------|-----------------------------|
| Title | The title of the artwork that will appear in the front end. This will be pulled from the API. | N/A |
| Query API | You'll see this set of fields only if a Collections API has been configured. When adding an artwork it is recommended you utilize your Collections API, which will auto-fill and auto update fields as they are edited from the API itself. Select the term you'd like to search by, either Object ID (recommended), Title, or Artist Name. When you select Query, a list of suggestions will appear. When you see the correct object, select "Populate Form." All available fields will populate. | N/A |
| Image Override Uploader | If your Collections API does not include an image for your desired object, the object image is low resolution, or is not the view you are desiring, you can override this here. If this is blank, the system will pull the default API image. | Preferred minimum: 1920px in width or 1080px in height.  Maximum: Highest based on quality of source, up to 4000px on the longest side. |
| Artist | The artist of the artwork that will appear in the front-end. This will be pulled from the API. | N/A |
| Year | The year the artwork was created that will appear in the front end. This will be pulled from the API. | N/A |
| Copyright | The copyright of the artwork that will appear in the front end. This will be pulled from the API. | N/A |
| Detail Narrative (software interface) | When an image is selected within a Theme, this text will appear next to the artwork and explain what the image represents. | Maximum 100 characters. |
| Look Again (Journey Guide) | This interpretive description text appears in the Journey Guide and prompts the users how to view the artwork they are observing. | Maximum 125 characters. |
| Activity Template drop-down menu (Journey Guide) | Provided by Bell & Wissell Co., these are the blank canvases that we’re asking Journey Guide users to fill in. You’ll want to select the best option for your activity. See [Activity Templates](#activity-templates) for more information about Activity Templates. | N/A |
| Activity Instructions (Journey Guide) | These instructions will appear in the Journey Guide and will instruct the user how to complete the activity for this artwork. The text here needs to correspond with the Activity Template selected above—if the Activity Template is a blank space for drawing, the instructions should reflect this. | Maximum 128 characters. |
| Location Directions (Journey Guide) | Located near the map on the Journey Guide, these instructions offer short orientation instructions as to where the artwork can be found. | Maximum 145 characters. |
| Map Location Coordinates (X,Y) (Journey Guide) | These coordinates will help determine what order the objects should be listed on the Journey Guide path. If an object is on display, these coordinates will auto populate form the API. | N/A |
| Floor (Journey Guide) | The floor on which the artwork is located. This will be pulled from the API. | N/A |


## Themes

Themes can then be linked to Artworks that have been previously created. In addition to text and images of the artwork, designs will need to be created to populate the selector shape and Journey Guide. 

### Create or delete a Theme

1. Click "Manage," then "Content," then the "Add content" link.
2. Click the "Theme" link. 
3. A blank Theme format will appear. In order to save the Theme, a title is required
(see the following table for character count details).
4. Before clicking "Save," consider if you want this content published or not. If you
are editing content before public opening, this is less crucial. If you are making edits
after opening and this is live to the public, you’ll want to ensure "Published" is left
unchecked before saving, otherwise your changes will be shown in the front-end. Navigate
to the bottom of the page, and click the "Publishing options" tab and make your
selection. 
5. Once a title is entered and you've selected your publishing preference, you may click
"Save," and can revisit this Theme at any time to continue editing. 
6. If you've created a Theme by mistake or wish to delete it, you can easily access it
again using the upper navigation. Click "Dashboard," then "Content." A list will appear
of all existing content items. You can sort items by Title, Type, Author, or Date. If
you wish to edit, select "edit." If you wish to delete, click "delete." A confirmation 
will appear. Be sure you wish to delete, as this action **cannot be undone**! From the
confirmation page you can either delete, or cancel and keep your Theme.

### Edit a Theme

1. Click "Dashboard," then "Content." A list will appear of all existing content items.
You can sort items by Title, Type, Author, or Date. Select the Theme you’d like to edit.
You'll be taken to the Theme page in the CMS. Click the "Edit" tab in the upper right
corner. 
2. From here you have an array of fields and selection boxes. See below for details. Be
sure to click "Save" when you’ve completed your changes. 
3. To add italics to any field, place `<em>` where you’d like italics to start, and
`</em>` where you'd like italics to stop. 

### Edit Artworks by Theme

After creating your Themes and linking your Artworks, you may want the ability to edit 
and sort Artworks by Themes. There are two ways to do this. 

#### Approach One – At the Theme Level

1. Select the theme you wish to edit, and go to the "View" tab. 
2. Scroll down to the prompt section containing the Artwork you want to edit. Hover over
the upper right corner of the image, and an edit gear will appear. 
3. Select the edit gear. A drop down menu will appear with the option to either Edit or
Delete. 
4. Select Edit. You will be taken directly to that Artworks page and can edit from
there. 

#### Approach Two – Sorting Shortcut 

1. Select the shortcuts tab in the main menu. 
2. A drop down menu will appear with the option "Art by Theme." Select this option. 
3. You will be shown a list of Artworks sorted by Theme and Prompts. From here you can
view or edit Artworks directly. 


| Field | Description | Character Count/Field Specs |
|-------|-------------|-----------------------------|
| Title | Theme title, as it will appear on the Theme selector page and subsequent pages as users build their Journey Guide. | Maximum 23 characters. |
| Theme Intro | Theme subtitle, as it will appear on Theme selector page. | Maximum 226 characters. |
| Shape Face (image uploader) | Animated Theme icon as it will appear on the Theme selector shape. | N/A |
| Icon (image uploader) | Simplified Theme icon as it will appear on subsequent pages as users build their Journey Guide. | N/A |
| Guide Cover Art (image uploader) | Customized art cover that appears on the Journey Guide to correspond with the selected Theme. | N/A |
| Journey Guide Cover Title | Customized text that appears on the Journey Guide to correspond with the selected Theme. To correspond with a users name, ex: [User]’s [Journey Guide Cover Title] | Maximum 25 characters. |
| Background (image uploader) | The background images that will appear behind the Theme on the Theme selector page. Multiple images can be added here, and they will fade as appropriate. You can upload manually (recommended) or pull from an API Query. If pulling from the API, please ensure the image is a minimum of 1920x1080 and in landscape format. | 1920x1080 |
| Prompt Title | For each of the five Artwork selection steps per Theme, a prompt title and subtitle will appear to give the user context of why these objects are there. This title appears when its corresponding prompt step is selected, and on the lower navigation. | Maximum 21 characters. Minimum 5 titles per theme. |
| Prompt Subtitle | Appearing below the corresponding prompt title. | Maximum 100 characters. Minimum 5 subtitles per theme. |
| Prompt Artwork (selector) | This is where artworks created previously can be linked to themes.  If you’re adding an artwork that is in the system multiple times, be sure to match the node ID to the correct artwork. The node ID can be found in the URL of the artwork. Example: in the URL http://journeymaker.institution.org/?q=node/519 the node ID is 519. When you add artwork in the prompt artwork field, you'll see the artworks name and its corresponding node ID. For example, "Fish Plate (nid 519)." | N/A |

## Activity Templates

Activity Templates can be linked to Artworks. Once linked, the back-end system will
automatically output all required image sizes and colors for both the Kiosk and Home
Companion Journey Guides. One Activity Template can be added to multiple Artworks. 

This distribution has created five activity templates created by Belle & Wissel Co.
Creating a new Activity Template does require some hard coding in the system, so new
templates cannot be added without an update to the build. 

## Other General Tools

The below features may be of use to your team for this project, although aren’t
necessary for data entry.

### Create Content Footers

When adding or editing content, standard options appear at the bottom of each
page. While not necessary for content editing, they may be used to help track changes or
add comments and questions for the team. 

|   |   |
|---|---|
| Revision Information | Over time as edits are made to content, the revisions tab allows you to track differences between multiple versions of your content, and revert back to older versions. When making an edit that you want to track, check the "Create new revision" box and add a description of the change you made. To see a list of past revisions select the "Revisions" tab. From here you can revert back to an old change if needed. |
| URL path settings | While not applicable to this project, this allows you to specify an alternative URL by which this content can be accessed. |
| Comment settings | This is where the ability to leave comments can be turned on or off. Comments are shown on the "View" tab and can add notes to other users about required updates, thoughts, or questions. It is recommended comments remain "Open" throughout editing. |
| Authoring Information | This is the username and date stamp of the person who is editing the content, and the date and time in which the edit was made. |
| Publishing Options | This notes whether the content should be published, or shown in the front-end. Anything you’d like to appear in the front-end should be marked "Published." |
