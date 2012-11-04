#GridEditor ModX Component#
A quick and easy grid view for resources, with support for inline-editing and search/filtering.

## Usage ##
You can create as many different GridView pages as you like by creating manager 'Menu' items through 
the MODx manager. Configurations for individual pages are managed via MODx chunks for ease of use and 
modification. 

### Setting up a GridEditor page ###
To create a GridEditor page in your MODx manager interface, create a new Menu Action via the System/Actions screen.
Menu Action should be set to `grideditor - controller` and you specify the name of a configuration chunk in the 
following format in the parameters field `&config=[ConfigName]`

### Configuring GridEditor ###
GridEditor is designed to be as flexible as possible when it comes to displaying, editing and filtering resources & 
fields in the grid. All aspects of the view are controlled via a JSON object stored in a configuration chunk. When 
looking for a config chunk, GridEditor will prefix `grideditor.config.` to the start of a chunk's name. Therefore you 
would create a chunk called `grideditor.config.[ConfigName]`

PHP's JSON support is very strict and will fail at the smallest deviation from strict formatting rules. I have plans 
add a more stable and capable json decoder in future versions, but for the moment be sure your config file follows 
these guidelines:
* All field names should be enclosed with double quotation marks `"`
* All strings should be enclosed with double quotation marks `"`
* __No comments of any kind__ (Use MODx comments - these will be stripped out before parsing)
* No trailing commas on the property

### Example GridEditor config chunk ###
```javascript
{
  /** Page Title */
  "title": "My First GridEditor",

  /** Templates to restrict view to */
  "templates": ["BaseTemplate"],

  /** Field to filter on via dropdown */
  "filter": {
             "field": "tv_AnotherComboTv",
             "label": "Colour"
        },

  /** Fields to include in text search */
  "search": ["pagetitle"],

  /** Resource Fields to include in view */
  "fields": [{
             "field": "pagetitle",
             "label": "Product",
             "editable": true
	},{
             "field": "hidemenu",
             "label": "Checkbox",
             "editable": true
        },{
             "field": "template",
             "label": "Template",
             "editable": true,
             "editor": "modx-combo-template"
        }],

  /** Template Variable fields to include in view */
  "tvs": [{
             "field": "MyTvName",
             "label": "My Tv Name",
             "editable": false
         }],

  /** Resource controls to offer */
  "controls": ["publish","edit","delete"]
}
```
## Configuration Options

### Page Title `title`
Sets the page title (both on-screen and in browser toolbar)

### Template Selectors `templates`
An array of template names (string) or IDs (int) to restrict displayed resources to

### Filter Field `filter`
If specified, will create a dropdown selector of every unique value of the specified `name` field so that results can
be filtered accordingly. Optional `label` attribute will set a label on the dropdown

### Search Fields `search`
Array of field names to include in text-search. If one or more fields are specified, a text-search box will be 
shown with the grid.

### Resource Controls `controls`
An array of optional controls to offer on resources. Options are:
* `publish` - Enable toggling of publish state from grid
* `edit` - Provide a link to the resource editing page
* `delete` - Provide a button that will delete the resource
