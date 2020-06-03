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
			name: "Coloring",
			options: [		
				{
					type: "select",
					name: "contours.coloring",
					caption: "Fill",
					value: "fill",
					options: [
						{
							value: "fill",
							name: "Fill"
						},
						{
							value: "heatmap",
							name: "Heatmap"
						},
						{
							value: "lines",
							name: "Lines"
						}									
					]
				}				
			]			
		},
		{
			name: "Lines",
			target: "line",
			options: [		
				{
					type: "number",
					name: "width",
					caption: "Thickness",
					min: 0,
					max: 2,
					step: "0.1",
					value: 0.5
				},
				{
					type: "color",
					name: "color",
					caption: "Color"
				},					
				{
					type: "line"
				}		
			]			
		}		
	]
};

export default heatmapOptions;
