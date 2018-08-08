function deleteRow(marker){
    for (var i = marker.arrayIndex; i < markers.length; i++) {
        // $("#td_order_num"+i).attr('id', 'td_order_num');
        updateRow(markers[i]);
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