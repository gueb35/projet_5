var MaMap = {
    initMap : function(){
        var belin = {
            lat: 48.031393  ,
            lng: -1.568032
        }
        map = new google.maps.Map(document.getElementById('map'),{
            zoom : 13.6,
            center : belin
        })
        marker = new google.maps.Marker({
            position : belin,
            map : map,
            title : "L'ECO-FERME"
        })
    }
}