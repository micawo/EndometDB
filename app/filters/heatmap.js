const heatmapOptions = {
	
	groups: [
		{
			name: "Display",
			options: [
				{
					type: "checkbox",
					checked: true,
					name: "showscale",
					caption: "Show color bar"					
				},
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
					type: "select",
					name: "zsmooth",
					caption: "Smooth",
					value: "0",
					options: [
						{
							value: "0",
							name: "off"
						},
						{
							value: "best",
							name: "Smooth"
						}		
					]
				}												
			]
		},
		{
			name: "Colorscale",
			options: [		
				{
					type: "colorscale"
				}
			]			
		},
		{
			name: "Gaps",
			options: [		
				{
					type: "number",
					name: "xgap",
					caption: "Horizontal gap",
					min: 0,
					max: 100,
					step: "1",
					value: 0
				},
				{
					type: "number",
					name: "ygap",
					caption: "Vertical gap",
					min: 0,
					max: 100,
					step: "1",
					value: 0
				}				
			]			
		}		
	]
};

export default heatmapOptions;
