@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <h3 class="font-weight-bold mt-3">{{ __('Area Zone') }}</h3>
        <!-- Instructions -->
        <div class="card rounded-12 shadow border-0 ">
            <div class="card-body p-4">
                <form action="{{ route('area.update', $area->id) }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="row justify-content-between">
                        <div class="col-xl-5 mb-5 mb-xl-0">
                            <h3 class="mb-3 font-weight-bold text-primary">Instructions</h3>
                            <div class="d-flex flex-column">
                                <p>Create zone by click on map and connect the dots together</p>

                                <div class="media mb-2 gap-3 align-items-center">
                                    <img src="{{ asset('images/icons/map-drag.png') }}" />
                                    <div class="media-body ">
                                        <p class="m-0 text-dark" style="line-height: 18px; font-size: 15px;">
                                            Use this to drag map to find proper area
                                        </p>
                                    </div>
                                </div>

                                <div class="media gap-3 align-items-center">
                                    <img src="{{ asset('images/icons/map-draw.png') }}" />
                                    <div class="media-body ">
                                        <p class="m-0 text-dark" style="line-height: 18px; font-size: 15px;">
                                            Click this icon to start pin points in the map and connect them to draw a zone .
                                            Minimum 3 points required
                                        </p>
                                    </div>
                                </div>
                                <div class="map-img mt-4">
                                    <img src="{{ asset('images/icons/instructions.gif') }}" width="100%" />
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-7">
                            <div class="form-floating mb-3">
                                <label for="floatingInput" class="mb-0">Area name</label>
                                <input type="text" class="form-control" id="floatingInput" name="name"
                                    placeholder="Area name" required value="{{ $area->name }}">
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-3" style="display: none">
                                <label class="input-label" for="exampleFormControlInput1">Coordinates
                                    <span class="input-label-secondary">Draw your zone on the map</span>
                                </label>
                                <textarea type="text" rows="8" name="coordinates" id="coordinates" class="form-control" readonly></textarea>
                            </div>

                            <!-- Start Map -->
                            <div class="map-warper dark-support rounded overflow-hidden">
                                <input id="pac-input" class="controls rounded" style="height: 3em;width:fit-content;"
                                    title="Search your location here" type="text" placeholder="Search here" />
                                <div id="map-canvas" style="height: 420px"></div>
                            </div>
                            <!-- End Map -->
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-3 mt-3">
                                <button class="btn btn-secondary" type="reset" id="reset_btn">
                                    {{ __('Reset') }}
                                </button>
                                <button class="btn btn-primary" type="submit">{{ __('Update') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- End Instructions -->
    </div>
@endsection

@push('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ mapApiKey() }}&libraries=drawing,places"></script>

    <script>
        auto_grow();

        function auto_grow() {
            let element = document.getElementById("coordinates");
            element.style.height = "5px";
            element.style.height = (element.scrollHeight) + "px";
        }
    </script>

    <script>
        var map; // Global declaration of the map
        var lat_longs = [];
        var drawingManager;
        var lastpolygon = null;
        var bounds = new google.maps.LatLngBounds();
        var polygons = [];

        function resetMap(controlDiv) {
            // Set CSS for the control border.
            const controlUI = document.createElement("div");
            controlUI.style.backgroundColor = "#fff";
            controlUI.style.border = "2px solid #fff";
            controlUI.style.borderRadius = "3px";
            controlUI.style.boxShadow = "0 2px 6px rgba(0,0,0,.3)";
            controlUI.style.cursor = "pointer";
            controlUI.style.marginTop = "8px";
            controlUI.style.marginBottom = "22px";
            controlUI.style.textAlign = "center";
            controlUI.title = "Reset map";
            controlDiv.appendChild(controlUI);
            // Set CSS for the control interior.
            const controlText = document.createElement("div");
            controlText.style.color = "rgb(25,25,25)";
            controlText.style.fontFamily = "Roboto,Arial,sans-serif";
            controlText.style.fontSize = "10px";
            controlText.style.lineHeight = "16px";
            controlText.style.paddingLeft = "2px";
            controlText.style.paddingRight = "2px";
            controlText.innerHTML = "X";
            controlUI.appendChild(controlText);
            // Setup the click event listeners: simply set the map to Chicago.
            controlUI.addEventListener("click", () => {
                if (lastpolygon) {
                    lastpolygon.setMap(null);
                }
                $('#coordinates').val('');

            });
        }

        function initialize() {
            var myLatlng = new google.maps.LatLng('23.77477879539784', '90.3663365994097');
            var myOptions = {
                zoom: 13,
                center: myLatlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            map = new google.maps.Map(document.getElementById("map-canvas"), myOptions);

            var jsonArray = @json($latLng);

            const polygonCoords = [];
            jsonArray.forEach(coord => {
                polygonCoords.push({
                    lat: parseFloat(coord.lat),
                    lng: parseFloat(coord.lng)
                })
            });

            var zonePolygon = new google.maps.Polygon({
                paths: polygonCoords,
                strokeColor: "#050df2",
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillOpacity: 0,
            });

            zonePolygon.setMap(map);

            zonePolygon.getPaths().forEach(function(path) {
                path.forEach(function(latlng) {
                    bounds.extend(latlng);
                    map.fitBounds(bounds);
                });
            });


            drawingManager = new google.maps.drawing.DrawingManager({
                drawingMode: google.maps.drawing.OverlayType.POLYGON,
                drawingControl: true,
                drawingControlOptions: {
                    position: google.maps.ControlPosition.TOP_CENTER,
                    drawingModes: [google.maps.drawing.OverlayType.POLYGON]
                },
                polygonOptions: {
                    editable: true
                }
            });
            drawingManager.setMap(map);

            google.maps.event.addListener(drawingManager, "overlaycomplete", function(event) {
                var newShape = event.overlay;
                newShape.type = event.type;
            });

            google.maps.event.addListener(drawingManager, "overlaycomplete", function(event) {
                if (lastpolygon) {
                    lastpolygon.setMap(null);
                }
                $('#coordinates').val(event.overlay.getPath().getArray());
                lastpolygon = event.overlay;
                auto_grow();
            });
            const resetDiv = document.createElement("div");
            resetMap(resetDiv, lastpolygon);
            map.controls[google.maps.ControlPosition.TOP_CENTER].push(resetDiv);

            // Create the search box and link it to the UI element.
            const input = document.getElementById("pac-input");
            const searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
            // Bias the SearchBox results towards current map's viewport.
            map.addListener("bounds_changed", () => {
                searchBox.setBounds(map.getBounds());
            });
            let markers = [];
            // Listen for the event fired when the user selects a prediction and retrieve
            // more details for that place.
            searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }
                // Clear out the old markers.
                markers.forEach((marker) => {
                    marker.setMap(null);
                });
                markers = [];
                // For each place, get the icon, name and location.
                const bounds = new google.maps.LatLngBounds();
                places.forEach((place) => {
                    if (!place.geometry || !place.geometry.location) {
                        console.log("Returned place contains no geometry");
                        return;
                    }
                    const icon = {
                        url: place.icon,
                        size: new google.maps.Size(71, 71),
                        origin: new google.maps.Point(0, 0),
                        anchor: new google.maps.Point(17, 34),
                        scaledSize: new google.maps.Size(25, 25),
                    };
                    // Create a marker for each place.
                    markers.push(
                        new google.maps.Marker({
                            map,
                            icon,
                            title: place.name,
                            position: place.geometry.location,
                        })
                    );

                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });
        }

        initialize();

        $('#reset_btn').click(function() {
            $('#name').val(null);

            if (lastpolygon) {
                lastpolygon.setMap(null);
            }
            $('#coordinates').val(null);
        })
    </script>
@endpush
