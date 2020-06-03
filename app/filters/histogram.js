const histogramOptions = {
	
	groups: [
		{
			name: "Display",
			options: [
				{
					type: "checkbox",
					checked: true,
					name: "showlegend",
					caption: "Show legend"					
				},
				{
					type: "number",
					name: "opacity",
					caption: "Opacity",
					min: 0,
					max: 1,
					step: "0.1",
					value: 1
				}								
			]
		},
		{
			name: "Bars",
			target: 'marker',
			options: [
				{
					type: "color",
					name: "color",
					caption: "Fill color"
				},
				{
					type: "number",
					name: "line.width",
					caption: "Border",
					min: 0,
					max: 2,
					step: "0.1",
					value: 0
				},	
				{
					type: "color",
					name: "line.color",
					caption: "Border color"
				}
			]			
		}
	]
};

export default histogramOptions;
