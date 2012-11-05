{
	"title": "My First GridEditor",

	"templates": ["BaseTemplate"],

	"filter": {
			"field": "tv_AnotherComboTv",
			"label": "Colour"
		},
                
        "grouping": "AnotherComboTv",

	"search": ["pagetitle"],

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

	"tvs": [{
			"field": "MyTvName",
			"label": "Text TV",
			"editable": false
		},{
			"field": "AnotherComboTv",
			"label": "Combo TV",
			"editable": true
		}],

	"controls": ["publish","edit","delete"]
}