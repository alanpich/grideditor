---
layout: master
title: Available configuration options
---

## Page title
Sets the page title (both on-screen and in browser toolbar)
{% highlight javascript %}
{
    /** @type String */
    "title": "My Grideditor Page"
}
{% endhighlight %}



## Resource selection query
An xPDOQuery WHERE array for selecting which resources to display
in the grid. See [here](http://rtfm.modx.com/display/xPDO20/xPDOQuery.where) for details.
{% highlight javascript %}
{
    /** @type Object (converted to associative array) */
    "resourceQuery": {
        "template:=": 7
    }
}
{% endhighlight %}


## New resource defaults
Array of properties to set as defaults on all resources created through the grid. In this example
all resources will default to the template with an ID of 7 and will be a child of resource #5.
{% highlight javascript %}
{
    /** @type Object (converted to associative array) */
    "newResourceDefaults": {
        "template": 7,
        "parent": 5
    }
}
{% endhighlight %}


## Page size
Set the number of resources displayed per page in the grid
{% highlight javascript %}
{
    /** @type Integer */
    "perPage": 10
}
{% endhighlight %}


## Grid control buttons
Define which (if any) controls are available in the grid. Available options are
`publish` - Publish/Unpublish resources
`edit` - Show a button allowing users to edit a resource
`delete` - Show a button allowing users to delete a resource
`new` - Show a button allowing users to create a resource
{% highlight javascript %}
{
    /** @type Array of String */
    "controls": ["publish","edit","delete","create"]
}
{% endhighlight %}


## Text Search
If set, will display a text field allowing users to search the resources
in the grid. Accepted value is an array of field names. All fields in the
array must also appear in either the 'fields' or 'tvs' definition arrays
{% highlight javascript %}
{
    /** @type Array of String */
    "search": ["pagetitle","introtext","description"]
}
{% endhighlight %}



