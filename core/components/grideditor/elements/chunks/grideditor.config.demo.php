{
/** Page Title */
"title": "Projects",

/** Resource selection filters */
"resourceQuery": {
    "parent:IN": [4,6,7]
},

/** Default properties for new Resources */
"newResourceDefaults": {
    "parent": {"data": [4,6,7], "label": "Select Category"},
    "published": true,
    "template": 3
},


/** Resource Fields to include in view */
"fields": [{
    "field": "pagetitle",
    "label": "Page Title",
    "editable": true,
    "order": 0,
    "width": 400
},{
    "field": "template",
    "label": "Type!!!",
    "order": 3,
    "editable": true
},{
    "field": "introtext",
    "label": "Summary  ",
    "editable": true,
    "order": 2
}],


/** Fields to include in text search */
"search": ["pagetitle","introtext"],


/** Field to group resources by */
"grouping": "template",


/** Field to filter on via dropdown */
"filter": {
    "field": "template",
    "label": "Colour"
},

/** Resource controls to offer */
"controls": ["publish","edit","delete","new"]


}
