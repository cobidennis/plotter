@extends("layouts.app")

@section("content")
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <form>
                        <div class="form-row">
                            <div class="col">
                                <input id="lat" type="text" class="form-control" placeholder="Latitude">
                            </div>
                            <div class="col">
                                <input id="lng" type="text" class="form-control" placeholder="Longitude">
                            </div>
                            <div class="col">
                                <label id="plot" class="btn btn-primary form-control">Plot</label>
                            </div>
                        </div>
                    </form>
                </div>
                <div id="errorMsg" class="card-body text-danger" hidden>
                    
                </div>
               
            </div>
            
        </div>
    </div>
    <div id="map"></div>
    

@endsection

@section("page-js")
<script>
    $(document).ready(function(){
        //Latitude input field selector
        var latInput = $("#lat");

        //Longitude input field selector
        var lngInput  = $("#lng");

        //The Error Message Div Selector
        var messageDiv = $('#errorMsg');

        //Default Map Marker to Crawley
        latInput.val(51.1048156);
        lngInput.val(-0.1909342);

        $("#plot").click(function(){

            //Ensure that the Error Message Div is cleared and hidden
            clearErrorDiv();

            markerPos = {lat: Number(latInput.val()), lng: Number(lngInput.val())};

            $.ajax({
                url: "{{ route('validate') }}",
                method: "POST",
                data: {"latitude": latInput.val(), "longitude": lngInput.val(), "_token": "{{ csrf_token() }}"},
                success: function(res){
                    loadMap();
                },
                error: function(err){
                    var errors = err.responseJSON.errors;
                    showValidationError(errors);
                }
            });
            //alert(markerPos.lng);
            //loadMap();
        });
         
        //Sets and displays the validation errors
        function showValidationError(errors){
            var latError = errors.latitude;
            var lngError = errors.longitude;
            
            if(latError !== undefined && latError.length > 0){
                latError.forEach(element => {
                    messageDiv.append("<p>" + element + "</p>");
                });
            }

            if(lngError !== undefined && lngError.length > 0){
                lngError.forEach(element => {
                    messageDiv.append("<p>" + element + "</p>");
                });
            }  
            
            messageDiv.prop('hidden',false);

            setTimeout(clearErrorDiv, 5000);
        }

        //used to clear the error message div
        function clearErrorDiv(){
            messageDiv.html('');
            messageDiv.prop('hidden',true);
        }

        function loadMap(){
            var url = "https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_API_KEY') }}&callback=initMap";
            $.ajax({
                url: url,
                dataType: "script",
                success: function(resp){

                },
                error: function(resp){
                    console.log(resp);
                }
            });
        }

        //Loads a map of Crawley once on page load
        loadMap();
    });
    
    
    var map;
    var marker;
    var center = {lat: 51.1048156, lng: -0.1909342};
    var markerPos = {lat: null, lng: null};

    function initMap() {
        map = new google.maps.Map(document.getElementById("map"), {
        center: center,
        zoom: 13
        });
        if(markerPos.lat !== null && markerPos.lng !== null){
            marker = new google.maps.Marker({position: markerPos, map: map});
        }
    }
</script>
@endsection
