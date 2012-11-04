#GridEditor ModX Component#
A quick and easy grid view for resources, with support for inline-editing and search/filtering.

----------------------------------

**Note:** This component was originally commissioned by
       [Himmelberger Design](http://www.himmdesign.com) who have 
       consented that this code remain open-source 
       and publicly licensed.

## Roadmap ##
Several additions are planned to extend the functionality of this component. As an overview:
* Extend TV editor detection & implementation
* Using an advanced JSON decoder class to allow more flexibility for handling invalid json config
* Ability to specify pluggable field renderers
* Configurable sort order for fields


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
*Remove comments before use*
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
```javascript
{
  /* ... */
  "title": "This is my page title"
}
```

### Template Selectors `templates`
An array of template names (string) or IDs (int) to restrict displayed resources to
```javascript
{
  /* ... */
  "templates": ["BaseTemplate",2]
}
```

### Filter Field `filter`
If specified, will create a dropdown selector of every unique value of the specified `name` field so that results can
be filtered accordingly. Optional `label` attribute will set a label on the dropdown
```javascript
{
  /* ... */
  "filter": {
       "field": "contentType",
       "label": "Content Type"
    }
}
```

### Search Fields `search`
Array of field names to include in text-search. If one or more fields are specified, a text-search box will be 
shown with the grid.
```javascript
{
  /* ... */
  "search": ["pagetitle","longtitle","introtext"]
}
```

### Resource Controls `controls`
An array of optional controls to offer on resources. Options are:
* `publish` - Enable toggling of publish state from grid
* `edit` - Provide a link to the resource editing page
* `delete` - Provide a button that will delete the resource

```javascript
{
  /* ... */
  "controls": ["publish","edit","delete"]
}
```

### Resource Fields `fields`
Most resource fields are supported, with default editors and renderers built in.
Supported fields are: `contentType`,`pagetitle`,`description`,`alias`,`link_attributes`,
`pub_date`,`unpub_date`,`introtext`,`menutitle`,`menuindex`,`template`

Fields can be configured using several parameters to customize their behaviour:
* `field`: [required] Name of the resource field
* `label`: [optional] Label to use for grid column. Defaults to field name
* `sortable`: [optional] Can this column be sorted? Defaults to false
* `editable`: [optional] Can this field be edited? Defaults to false
* `editor`: [optional] Override the default editor xtype
* `hidden`: [optional] Allows a field to be used for search/filtering but not appear in grid. Defaults to false

```javascript
{
  /* ... */
  "fields": [{
       "field": "pagetitle",
       "label": "Title",
       "editable": true,
       "sortable": true
    },{
       "field": "contentType",
       "label": "Page Type",
       "editable": false
    },{
       "field": "introtext",
       "label": "Field hijacked as a date",
       "editable": true,
       "editor": "xdatetime"
    }]
}
```

### Template Variable fields `tvs`
Template Variable fields can be included alongside resource fields in the grid using the same parameters.
Editor inputs are automatically detected from TV input type unless overridden in config.

**Note:** At this time only text, textarea and single-select listboxes are fully supported by default. All 
          other TV input types will default to a textfield editor unless an alternative editor is explicitely 
          specified in the config file.

Fields can be configured using several parameters to customize their behaviour:
* `field`: [required] Name of the TV
* `label`: [optional] Label to use for grid column. Defaults to field name
* `sortable`: [optional] Can this column be sorted? Defaults to false
* `editable`: [optional] Can this field be edited? Defaults to false
* `editor`: [optional] Override the default editor xtype
* `hidden`: [optional] Allows a field to be used for search/filtering but not appear in grid. Defaults to false


```javascript
{
  /* ... */
  "tvs": [{
       "field": "MyTvName",
       "label": "My TV Name",
       "editable": true,
       "sortable": true
    },{
       "field": "AnotherTvName",
       "label": "A Different TV",
       "editable": false
    }]
}
```



## Resource Field Defaults
Most resource fields are supported, with default editors and renderers built in. 
Defaults can be overridden in the config chunk.

### Content Type `contentType`
* Default Renderer: text
* Default Editor: Native content type dropdown (modx-combo-content-type)

### Page Title `pagetitle`
* Default Renderer: text
* Default Editor: Text input (textfield)

### Description `description`
* Default Renderer: text
* Default Editor: Textarea input (textarea)

### URL Alias `alias`
* Default Renderer: text
* Default Editor: Text Input (textfield)

### Link Attributes `link_attributes`
* Default Renderer: text
* Default Editor: Text input (textfield)

### Publish Date `pub_date`
* Default Renderer: text
* Default Editor: Date picker (xdatetime)

### Unpublish Date `unpub_date`
* Default Renderer: text
* Default Editor: Date picker (xdatetime)

### Summary/Intro `introtext`
* Default Renderer: text
* Default Editor: Textarea input (textarea)

### Menu Order `menuindex`
* Default Renderer: text
* Default Editor: Text input (textfield)

### Menu Title `menutitle`
* Default Renderer: text
* Default Editor: Text input (textfield)

### Template `template`
* Default Renderer: text
* Default Editor: Native Template selector (modx-combo-template)
