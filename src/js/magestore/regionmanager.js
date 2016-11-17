CityUpdater = Class.create();
CityUpdater.prototype = {
	initialize: function(country, region, cityText, citySelect, cities,content) {
		this.region = region;
		this.cityText = cityText;
		this.citySelect = citySelect;
		this.cities = cities;
		this.country = country;
		this.content = content;
		setInterval(this.updateContent.bind(this), 100);
	},
	
	updateContent: function() {
		if(typeof $(this.cityText) != 'undefined' && $(this.cityText) && (typeof $(this.citySelect) == 'undefined' || !$(this.citySelect))){
			$(this.cityText).insert({
				after: this.content
			});
			this.update();
			$(this.region).changeUpdater = this.update.bind(this);
		
			Event.observe($(this.region), 'change', this.update.bind(this));
			Event.observe($(this.country), 'change', this.update.bind(this));
			Event.observe($(this.citySelect), 'change', this.updateCity.bind(this));
		}
	},	
	
	update: function() {
		if (this.cities[$(this.region).value]) {
			var i, option, city, def;
			def = $(this.citySelect).getAttribute('defaultValue');
			if ($(this.cityText)) {
				if (!def) {
					def = $(this.cityText).value.toLowerCase();
				}
			}
			
			$(this.citySelect).options.length = 1;
			for (cityId in this.cities[$(this.region).value]) {
				city = this.cities[$(this.region).value][cityId];

				option = document.createElement('OPTION');
				option.value = city.code;
				option.text = city.name.stripTags();
				option.title = city.name;

				if ($(this.citySelect).options.add) {
					$(this.citySelect).options.add(option);
				} else {
					$(this.citySelect).appendChild(option);
				}				
				
				if (cityId==def || (city.name && city.name==def) ||
						(city.name && city.code.toLowerCase()==def)
				) {
					$(this.citySelect).value = city.code;
				}
			}
			
			if ($(this.cityText)) {
				$(this.cityText).style.display = 'none';
			}
			$(this.citySelect).style.display = '';
		}
		else {
			$(this.citySelect).options.length = 1;
			if ($(this.cityText)) {
				$(this.cityText).style.display = '';
			}
			$(this.citySelect).style.display = 'none';
			Validation.reset($(this.citySelect));
		} 
	}, 
	
	updateCity: function() {		
		var sIndex = $(this.citySelect).selectedIndex;
		$(this.cityText).value = $(this.citySelect).options[sIndex].value;
		if(typeof giaohangnhanh != 'undefined'){
			giaohangnhanh.changePickHub();
		}
		/* if(typeof giaohangnhanhbilling != 'undefined'){
			giaohangnhanhbilling.changePickHub();
		}
		if(typeof giaohangnhanhshipping != 'undefined'){
			giaohangnhanhshipping.changePickHub();
		}  */
	}
}