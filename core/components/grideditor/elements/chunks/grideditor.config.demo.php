{
	"title": "My First GridEditor",

	"templates": ["BaseTemplate"],

	"filter": {
			"field": "template",
			"label": "Colour"
		},

	"search": ["pagetitle"],

	"fields": [{
			"field": "pagetitle",
			"label": "Product",
			"editable": true
		},{
			"field": "alias",
			"label": "URL Alias",
			"editable": true
		},{
			"field": "template",
			"label": "Template",
			"editable": true,
			"editor": "modx-combo-template"
		}],

	"controls": ["publish","edit","delete"]
}