{
  /** Page Title */
  "title": "My First GridEditor",

  /** Templates to restrict view to */
  "templates": ["BaseTemplate"],

  /** Fields to include in text search */
  "search": ["pagetitle","introtext"],

  /** Resource Fields to include in view */
  "fields": [{
             "field": "pagetitle",
             "label": "Page Title",
             "editable": true,
	     "order": 0,
             "width": 400
         },{
             "field": "alias",
             "label": "URL Alias",
             "editable": true,
	     "order": 3
         },{
             "field": "introtext",
             "label": "Summary  ",
             "editable": true,
	     "order": 2
         }],


  /** Resource controls to offer */
  "controls": ["publish","edit","delete","new"]
}