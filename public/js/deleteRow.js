function deleteRow(marker){
    for (var i = marker.arrayIndex; i < markers.length-1; i++) {
        var lng = $("#lng"+(i+1)).val();
        var addr = $("#address"+(i+1)).val();
        var lat = $("#lat"+(i+1)).val();
        $("#lng"+i).attr('value',lng);
        // $("#td_lng"+marker.arrayIndex).text(results[0].geometry.location.lng());
        $("#lat"+i).attr('value',lat);
        // $("#td_lat"+marker.arrayIndex).text(results[0].geometry.location.lat());
        $("#address"+i).attr('value',addr);
        $("#td_address"+i).text(addr);
        // updateRow(markers[i]);
    }
    // $("#td_order_num"+markers.length-1).remove();
    // $("#order_num"+markers.length-1).remove();
    // $("#lng"+markers.length-1).remove();
    // $("#td_lng"+markers.length-1).remove();
    // $("#lat"+markers.length-1).remove();
    // $("#td_lat"+markers.length-1).remove();
    // $("#address"+markers.length-1).remove();
     $("#"+(markers.length-1)).remove();
}

function deleteMarker_id(id){
if(!end_plan){
    for( var i = 1; i < markers.length ; i++){
    if(markers[i].arrayIndex == id){
        deleteMarker(markers[i]);
    }
}
}

}