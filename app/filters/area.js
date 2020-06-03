const areaOptions = {
	
	groups: [
		{
			name: "Display",
			options: [
				{
					type: "checkbox",
					checked: true,
					name: "showlegend",
					caption: "Show legend"					
				}								
			]
		},
		{
			name: "Lines",
			target: 'line',
			options: [
				{
					type: "number",
					name: "width",
					caption: "Thickness",
					min: 0,
					max: 2,
					step: "0.1",
					value: 1.5
				},
				{
					type: "color",
					name: "color",
					caption: "Color"
				},
							
				{
					type: "line"
				},
				{
					type: "shape"
				}																	
			]			
		},
		{
			name: "Points",
			target: 'marker',
			options: [
				{
					type: "number",
					name: "opacity",
					caption: "Opacity",
					min: 0,
					max: 1,
					step: "0.1",
					value: 1
				},
				{
					type: "number",
					name: "size",
					caption: "Diameter",
					min: 0,
					max: 99,
					step: 1,
					value: 6
				},			
				{
					type: "symbol"
				}								
			]			
		}		
	]
};

export default areaOptions;
