---
layout: master
title: Example configuration chunk
---

This is an example configuration chunk

{% highlight javascript %}
{
/** Page Title */
"title": "People",

/** Name for resources (singular) */
"resourceName": "Person",

/** Resource selection filters */
"resourceQuery": {
    "template:=": 7
},

/** Default properties for new Resources */
"newResourceDefaults": {
    "published": true,
    "template": 7,
    "parent": 5
},

/** Resource Fields to include in view */
"fields": [{
    "field": "pagetitle",
    "label": "Page Title",
    "editable": true,
    "order": 1,
    "renderer": "myCustomRendererFunction.profileName"
}],

/** TV Fields to include in view */
"tvs": [{
    "field": "person-photo",
    "label": " ",
    "editable": false,
    "sortable": false,
    "renderer": "myCustomRendererFunctions.profilePhoto",
    "order": 0,
    "width": 20
},{
    "field": "person-level",
    "label": "Level",
    "order": 2,
    "editable": false,
    "sortable": true,
    "width": 0
},{
    "field": "person-role",
    "label": "Role",
    "order": 3,
    "editable": false,
    "sortable": true
}],

/** Fields to include in text search */
"search": ["pagetitle","person-role"],

/** Filter on Role */
"filter": {
    "label": "Select Role",
    "field": "person-level"
},

/** Resource controls to offer */
"controls": ["publish", "edit", "delete", "new"],

/** How many resources per page? */
"perPage": 10

}
{% endhighlight %}