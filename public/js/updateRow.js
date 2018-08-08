function updateRow(marker){

    var geocoderr = new google.maps.Geocoder;
                    results = null;
                    geocoderr.geocode({
                        'location': marker.getPosition()
                    }, function(results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            $("#order_num"+marker.arrayIndex).attr('value',marker.arrayIndex);
                            $("#lng"+marker.arrayIndex).attr('value',results[0].geometry.location.lng());
                            // $("#td_lng"+marker.arrayIndex).text(results[0].geometry.location.lng());
                            $("#lat"+marker.arrayIndex).attr('value',results[0].geometry.location.lat());
                            // $("#td_lat"+marker.arrayIndex).text(results[0].geometry.location.lat());
                            $("#address"+marker.arrayIndex).attr('value',results[0].formatted_address);
                            $("#td_address"+marker.arrayIndex).text(results[0].formatted_address);
                        } else {
                            console.log('query limited');
                        }
                    });
}