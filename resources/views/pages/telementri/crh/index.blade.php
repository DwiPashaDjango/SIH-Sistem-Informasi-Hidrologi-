@extends('layouts.pages')

@section('title')
    Telemetri Curah Hujan
@endsection

@push('css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css" integrity="sha512-hoalWLoI8r4UszCkZ5kL8vayOGVae1oxXe/2A4AO6J9+580uKHDO3JdHb7NzwwzK5xr/Fs0W40kiNHxM9vyTtQ==" crossorigin=""/>
    <style>
        #map {
            height: 70vh;
            width: 100%;
        }
    </style>
@endpush    

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" aria-current="page">
            <h3 style="color: #000000dd">@yield('title')</h3>
        </li>
    </ol>
</nav>
<div class="row">
    <div class="col-lg-2">
        <ul class="list-group" style="height: 70vh; overflow: scroll;" id="post-all">
            <li class="text-white list-group-item" style="background-color: rgb(10, 71, 147)">Daftar Pos :</li>
        </ul>
    </div>
    <div class="col-lg-10">
        <div id="map"></div>
    </div>
</div>
@endsection

@push('js')
    <script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js" integrity="sha512-BB3hKbKWOc9Ez/TAwyWxNXeoV9c1v6FIeYiBieIWkpLjauysF18NzgR1MBNBXf8/KABdlkX68nAhlwcDFLGPCQ==" crossorigin=""></script>
    <script>
        $(document).ready(function() {
            filterDayNow();

            let geoJsonLayer;
            const map = L.map('map').setView([-1.91048, 101.30296], 8);

            const tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 10,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            $.getJSON("{{asset('pages/Sub_DAS_WS_Batanghari.geojson')}}", function(data) {
                L.geoJSON(data, {
                    style: function(feature) {
                        return {
                            color: '#0d6efd',
                            weight: 2,   
                            fill: false,
                            fillOpacity: 0 
                        };
                    }
                }).addTo(map);

                L.geoJSON(data, {
                    style: function(feature) {
                        return {
                            color: feature.properties.color,
                            weight: 4,                      
                            fill: false
                        };
                    }
                }).addTo(map);
            }).fail(function() {
                console.error('Error loading the polyline JSON.');
            });

            function filterDayNow() {
                $.ajax({
                    url: "{{url('api/telementriCRH')}}",
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        let no = 1;
                        let html = `<li class="text-white list-group-item" style="background-color: rgb(10, 71, 147)">Daftar Pos :</li>`;
                        $.each(data.data, function(index, value) {
                            html += `<li class="list-group-item" style="cursor: pointer;" data-koordinatx="${value.gps_location_lat}" data-koordinaty="${value.gps_location_lng}">
                                        ${no++}. ${value.name_alias}
                                            <div class="show-detail d-none">
                                                <div>
                                                    <div class="mt-2 p-1" style="background-color: rgb(80, 200, 120)">
                                                        <h6 class="mt-1"><strong>Curah Hujan : ${value.value_calibration} mm</strong></h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>`;
                        })
                        $('#post-all').html(html);

                        getGeoJsonmarker(data);
                    },
                    error: function(err) {
                        console.log(err);
                    }
                })
            }

            function getGeoJsonmarker(data) {
                if (geoJsonLayer) {
                    map.removeLayer(geoJsonLayer);
                }

                let geojson = {
                    "type": "FeatureCollection",
                    "features": data.data.map(marker => {
                        return {
                            "type": "Feature",
                            "geometry": {
                                "type": "Point",
                                "coordinates": [marker.gps_location_lng, marker.gps_location_lat]
                            },
                            "properties": {
                                "name": marker.name_alias,
                                "status": marker.status,
                                "popupContent": `
                                    <div class="leaflet-pane leaflet-popup-pane">
                                        <div class="leaflet-popup">
                                            <div class="leaflet-popup-content-wrapper">
                                                <div class="leaflet-popup-content" style="width: 350px;">
                                                    <div class="p-3">
                                                        <div class="col">
                                                            <h5>${marker.name_alias}</h5>
                                                            <p>Device ID : <strong>${marker.device_id || '-'}</strong></p>
                                                            <p>Koordinat : <strong>${marker.gps_location_lat}, ${marker.gps_location_lng}</strong></p>
                                                            <p>Terakhir Kirim Data : <strong>${marker.datetime}</strong></p>
                                                        </div>
                                                        <div class="col">
                                                            <div>
                                                                <div class="mt-2">
                                                                    <h6><strong>Curah Hujan : ${marker.value_calibration} mm</strong></h6>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <hr class="divide">
                                                        <a class="btn w-100" style="background-color: rgb(10, 71, 147); color: #fff" href="{{url('telementris/crhs/${marker.sensor_company_id}/show')}}">Lihat Data Selengkapnya</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `
                            }
                        };
                    })
                }

                geoJsonLayer = L.geoJSON(geojson, {
                    pointToLayer: function(feature, latlng) {
                        let iconUrl = "{{asset('img/marker-icon-2x-green.png')}}";
                        let markerIcon = L.icon({
                            iconUrl: iconUrl,
                            iconSize: [25, 41],
                            iconAnchor: [12, 41],
                            popupAnchor: [1, -34],
                        });

                        return L.marker(latlng, { icon: markerIcon });
                    },
                    onEachFeature: function(feature, layer) {
                        if (feature.properties && feature.properties.popupContent) {
                            layer.bindPopup(feature.properties.popupContent);
                            
                            layer.on('click', function() {
                                map.setView(layer.getLatLng(), 10);
                                layer.openPopup();
                            });
                        }
                    }
                }).addTo(map);
            }

            $(document).on('click', '.list-group-item', function() {
                var koordinatx = $(this).data('koordinatx');
                var koordinaty = $(this).data('koordinaty');

                $(".show-detail").addClass('d-none');
                $(this).find(".show-detail").removeClass('d-none');

                geoJsonLayer.eachLayer(function(layer) {
                    if (layer.feature.geometry.coordinates[0] == koordinaty && layer.feature.geometry.coordinates[1] == koordinatx) {
                        map.setView(layer.getLatLng(), 10);
                        layer.openPopup();
                    }
                });
            });
        });
    </script>
@endpush