![Art Institute of Chicago](https://raw.githubusercontent.com/Art-Institute-of-Chicago/template/master/aic-logo.gif)

# JourneyMaker CMS User Documentation

JourneyMaker CMS leverages [Twill](https://twillcms.com/) to build the easy-to-use system to add and edit content.

## Navigation bar

The CMS uses a simple navigation system with straightforward form pages for content entry. The navigation bar is always at the top of the CMS to help direct you from one page to the other.

Upon logging into the CMS, you will see the main navigation menu at the top.

The navigation contains the following links from left to right:

| Menu Item | Description |
|----------:|-------------|
| App Name | This links to the index page that shows the user activity log and is not used for content entry (name is configurable).  |
| Themes | From Themes, you’ll navigate to add or edit themes, prompts, and associate  artworks existing artworks to prompts. |
| Artworks | Artwork is organized here and can be searched and queried to add new artwork into the CMS. |
| Directory | Themes and prompts are organized here in a list view as another way to easily review information. This page is not used for content entry. |
| Media Library | The images that are uploaded to the CMS live here, e.g. if you upload an image on a form page such as an alternate image for artwork–it will live here. |
| User Profile (‘Admin’) | User profile information lives here. |
| Search | Global search is opened by clicking on the magnifying glass. |

## Languages

The CMS handles content entry for the following languages:

- English (EN)
- Spanish (ES)
- Chinese (ZH)

All translations must be present for the content to be available in JourneyMarker. That includes translations for artwork, prompts, and themes. Themes, Artworks, and Prompts all have a language toggle on the right-hand side of the page to allow content entry in these three languages.

## Publishing Status

When entering new content (e.g. a new artwork ) – that content will need to be published in the CMS when you are ready to take that step.

**Keep in mind**

- Artwork entries must be published to appear in the JSON output which is used by the frontend.
- If artwork is not on view that will automatically not be available for the frontend.
- All translations must be entered in the CMS to appear in the JSON output that the frontend uses. If translations are not present, that content will not appear.
- If an object is located in Regenstein Hall it will not appear in the JSON output.

## Themes (& Associating Prompts and Artworks)

This is one of the main content entry pages.

You can not only add or edit new themes from this page, but also navigate to add or edit prompts, as well as associate artwork to prompts that are already loaded into the CMS.

### New Theme

- To create a new theme, navigate to the Themes page in the top navigation bar.
- Click on the green ‘Add new’ button’ on the right-hand side of the screen.
- This will bring up the Title field – to enter in a new theme title (in all three languages). After that is complete, click on the green ‘Create’ button.
- This will bring up the Theme form with the following fields:
  - Title (limit of 23 characters + 5 for padding for a total of 28)
  - Intro (limit of 226 characters + 10 for padding for a total of 236)
  - Journey Hide Cover: (limit of 25 characters + 5 for padding for a total of 30.)
  - Guide Covert Art: (1125 x 1500)
  - Guide Covert Art (Home Companion): (1125 x 1500)
  - Backgrounds Image: (Min of 1920 x 1080)
- Keep in mind, some of the above fields have a corresponding Spanish and Chinese translation form that also needs to be filled out with the translated fields. The language toggle lives on the top right of the screen. You can also toggle between languages using the language label next to the form label. Eg Title [EN]
- Each field needs all three languages to be filled out in order to appear in the output JSON and be available to JourneyMaker.

Once done with the content entry, save as a draft or change the publication status with the toggle.

### New Prompt

- Creating a new prompt first requires that either a new theme is added, or a new prompt is added to an existing theme.
- Once a theme is created (instructions in the section above), click on the PROMPTS module on the right-hand side. If it’s empty and no prompts are associated with that theme yet, it will say ‘ALL PROMPTS’
- Click on ‘All Prompts’ if creating a new prompt to go to the prompt navigation, or click on an existing prompt title to edit an existing prompt.
- For new prompts, once you’ve clicked on the prompt navigation (e.g. 'All Prompts'), click on the green 'Add new' button to add a new prompt title to associate with that theme.
- The title field for this prompt will also have the language toggle to translate the title into all languages.
- Once you save the prompt title, click on the  ‘create’ button.’
- The prompt form will appear with the following fields:
  - Title: (limit of 21 characters + 5 for padding for a total of 26)
  - Subtitle (limit of 100 characters + 10 for padding for a total of 110)
  - Artwork: Add Artwork to a prompt by clicking on the blue ‘Add Artwork’ button

#### Artwork Prompts

- Once you click on the ‘Add Artwork’ button – the artwork form will appear underneath the prompt. The Artwork form contains the following fields:
  - Artwork - Clicking on Add Artwork here, you can attach an artwork here after searching for it.
  - Detail Narrative - Interface) (limit of 100 characters + 10 for padding for a total of 110).
  - Look Again - Journey Guide) (limit of 125 characters + 10 for padding for a total of 135).
  - Activity Template  - Journey Guide): Dropdown choice.
  - Activity Instructions - Journey Guide) (limit of 128 characters + 10 for padding for a total of 138).
- Additional artworks can be added to associate with that artwork and prompt by clicking on the blue ‘Add Artwork’ button at the bottom of the form.
- As on all form pages:
  - The translation toggle sits on the top right of the page, so the prompt content can be added in English, Spanish and Chinese.
  - To save a draft or publish the content use the status sidebar on the top right of the page.
  - You can remove artwork by using the three dot ellipsis menu to remove an attached artwork.

### Editing

- To edit an existing theme, navigate to the Themes page from the main navigation menu.
- From the Themes landing page, you’ll see a table that lists the existing theme titles, prompts, and languages.
- Click into any of the themes (e.g ‘Let’s Play’) to pull up the existing theme to change any of the content entry in the form.
- Clicking into a theme form will also provide the Prompt navigation, allowing you to make edits to the Prompt form and the artwork it is associated with.
- Click into the prompt that you would like to edit and the Prompt form will appear.
- Within prompts, you can add or delete any artwork blocks and drag and drop the order of them as shown in the screenshot below.

## Artworks
To add new artwork to the CMS, you will navigate to the Artworks page and pull that artwork into the CMS by running a query. The artwork available in the CMS is also organized here and can be searched.

### Query Artwork

- To query new artwork into the system, click on Artworks from the main navigation menu.
- Once on the Artworks page, click on the green ‘Add new’ button on the right side of the screen.
- Artwork can be queried by Object or Reference number. Start typing in either of those numbers, and the results will be to automatically be displayed
- When you find an artwork you are ready to add into the system:
  - Select the artwork you want to add
  - Click the green ‘Create’ button. That will bring up the artwork form page, which allows you to add the following content:
    - Update artwork Title, if necessary
    - Add an Override image, if necessary
    - Update the Artist Name, if necessary
    - Add Location Directions (Journey Guide) - this has a 145 character limit plus 10 for padding.
    - As with all forms, the language toggle lives on this form on the right-hand side, so you can update the artwork information in all three available languages.

### Artwork Search

When clicking on ‘Artworks’ page from the main navigation menu, you’ll be on the Artworks landing page, which allows you to search the artwork, see what artwork is on view, see what theme the artwork is associated with (if any), and see which languages the content has been translated in, and filter the artwork by theme and viewable and published status.

- Clicking on the filter button expands the filter dropdowns as shown in the above screenshot. Use the dropdown to select any filters you’d like to sort by and click on appy. Press ‘Clear’ to remove the filtered view on the page.
- Clicking on the the ‘All items’ page shows all the artwork available in the CMS
- Clicking on ‘Visible’ shows artwork that can appear in the JSON output and JourneyMaker. This is artwork that is on view, published, and not in Regenstein Hall
- Clicking on ‘Hidden’ shows artwork that will not appear in the JSON output. This artwork that is not on view, missing language translations, not published, or it appears in Regenstein Hall.


## Directory

The Directory page lists all themes, prompts and the associated artworks with links to each. Themes and prompts are organized here and show with a green check mark what is on display, and a red x when it is not on display.

## Media Library

Images uploaded to the CMS are organized here such as alternative images to artwork.

## User Profile

Currently, Twill is configured so that only admin users exist within the CMS.

**Admins have:**

- Access privileges to update and publish any piece of content within the CMS.
- Ability to update their email and preferred language under the ‘Profile’ tab by clicking the down arrow on their username.


## Search

Global search lives here by clicking in the magnifying glass. Keep in mind that search doesn’t account for things like typos or fuzzy search the provided term. As an example, to find a work of art titled 'Sunny day Frog-Man' you will need to search 'Frog-man' and not 'frogman'.

## JSON Output

The JSON output is a result of the CMS entry and what the frontend uses to inform what renders on the frontend.

**Important Reminders**

For the JSON output, there are a few important reminders to keep in mind:

- All content will need to be published before it appears in the JSON output. Please ensure all desired content is published including artwork, themes, and prompts.
- If a theme is not published it will not appear in the JSON output along with any of the theme’s prompts and prompt’s artwork.
- Artwork added to a published prompt will not appear in the JSON if it has not been published
- All fields must have their translations present for the content to be available in JourneyMarker. That includes translations for artwork, prompts, and themes that are slated for JourneyMaker.
- If a theme is missing a translation it will not appear in the JSON output along with any of the theme’s prompts and prompt’s artwork.
- If a prompt is missing a translation it will not appear in the JSON output along with the prompt’s artwork.
- Artwork missing translations will not appear in any prompts.
- Artwork that is not ‘on view’ will not appear in the JSON output.
- Artwork that is in Regenstein Hall will not appear in the JSON output
