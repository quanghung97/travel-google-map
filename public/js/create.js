var directionsService;
    var directionsRenderer;
    var map;
    var end_plan = false;

    function initialize() {
        var position = new google.maps.LatLng(21.016801, 105.784221);

        directionsService = new google.maps.DirectionsService();
        directionsRenderer = new google.maps.DirectionsRenderer();
        map = new google.maps.Map($('#map')[0], {
            zoom: 16,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            center: position
        });


        directionsRenderer.setMap(map);

        google.maps.event.addListener(map, 'click', function(event) {
            if(!end_plan){
                addWayPointToRoute(event.latLng);
            }
        });

        var contextMenuOptions={};
	    contextMenuOptions.classNames={menu:'context_menu', menuSeparator:'context_menu_separator'};

	    //	create an array of ContextMenuItem objects
	    //	an 'id' is defined for each of the four directions related items
	    var menuItems = [];
			//	a menuItem with no properties will be rendered as a separator
			menuItems.push({
				className: 'context_menu_item',
				eventName: 'end_plan',
				label: 'End plan'
			});
            menuItems.push({
				className: 'context_menu_item',
				eventName: 'continute_plan',
				label: 'Continute'
			});
			menuItems.push({});
			menuItems.push({
				className: 'context_menu_item',
				eventName: 'zoom_in_click',
				label: 'Zoom in'
			});
			menuItems.push({
				className: 'context_menu_item',
				eventName: 'zoom_out_click',
				label: 'Zoom out'
			});
			menuItems.push({});
			menuItems.push({
				className: 'context_menu_item',
				eventName: 'center_map_click',
				label: 'Center map here'
			});
	    contextMenuOptions.menuItems=menuItems;

	    var contextMenu=new ContextMenu(map, contextMenuOptions);

	    google.maps.event.addListener(map, 'rightclick', function(mouseEvent){
		    contextMenu.show(mouseEvent.latLng);
	    });

        google.maps.event.addListener(contextMenu, 'menu_item_selected', function (latLng, eventName) {
				//	latLng is the position of the ContextMenu
				//	eventName is the eventName defined for the clicked ContextMenuItem in the ContextMenuOptions
				switch (eventName) {
					case 'zoom_in_click':
						map.setZoom(map.getZoom() + 1);
						break;
					case 'zoom_out_click':
						map.setZoom(map.getZoom() - 1);
						break;
					case 'center_map_click':
						map.panTo(latLng);
						break;
					case 'end_plan':
                        addWayPointToRoute(markers[0].position);
                        $("#submit").removeAttr("disabled");
                        end_plan = true;
						break;
                    case 'continute_plan' :
                    if(end_plan){
                        end_plan = false;
                        $("#submit").attr("disabled","disabled");
                        deleteMarker(markers[markers.length-1]);
                    }
				}
			});


    }


    var markers = [];
    var polylines = [];
    var isFirst = true;

    


    

    google.maps.event.addDomListener(window, 'load', initialize);