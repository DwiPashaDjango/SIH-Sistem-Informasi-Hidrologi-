@extends('layouts.pages')

@section('title')
    Telmetri Tinggi Muka Air
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

            function filterDayNow() {
                $.ajax({
                    url: "{{url('api/telementriTMA')}}",
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        let no = 1;
                        let html = `<li class="text-white list-group-item" style="background-color: rgb(10, 71, 147)">Daftar Pos :</li>`;
                        html += `<li class="list-group-item" style="cursor: pointer;" data-koordinatx="${data.data.gps_location_lat}" data-koordinaty="${data.data.gps_location_lng}">
                                        1. ${data.data.name_alias}
                                        <div class="show-detail d-none">
                                            <div>
                                                <div class="mt-2 p-1" style="background-color: rgb(80, 200, 120)">
                                                    <h6 class="mt-1"><strong>TMA : ${data.data.value_calibration} cm</strong></h6>
                                                </div>
                                            </div>
                                        </div>
                                    </li>`;
                        $('#post-all').html(html);

                        getGeoJsonmarker(data);
                    },
                    error: function(err) {
                        console.log(err);
                    }
                })
            }

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

            function getGeoJsonmarker(data) {   
                if (geoJsonLayer) {
                    map.removeLayer(geoJsonLayer);
                }

                let geojson = {
                    "type": "Feature",
                    "geometry": {
                        "type": "Point",
                        "coordinates": [data.data.gps_location_lng, data.data.gps_location_lat]
                    },
                    "properties": {
                        "popupContent": `
                            <div class="leaflet-pane leaflet-popup-pane">
                                <div class="leaflet-popup">
                                    <div class="leaflet-popup-content-wrapper">
                                        <div class="leaflet-popup-content" style="width: 350px;">
                                            <div class="p-3">
                                                <div class="col">
                                                    <h5>${data.data.name_alias}</h5>
                                                    <p>Device ID : <strong>${data.data.device_id || '-'}</strong></p>
                                                    <p>Koordinat : <strong>${data.data.gps_location_lat}, ${data.data.gps_location_lng}</strong></p>
                                                    <p>Terakhir Kirim Data : <strong>${data.data.datetime}</strong></p>
                                                </div>
                                                <div class="col">
                                                    <div>
                                                        <div class="mt-2">
                                                            <h6><strong>TMA : ${data.data.value_calibration} cm</strong></h6>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="divide">
                                                <a class="btn w-100" style="background-color: rgb(10, 71, 147); color: #fff" href="{{url('telementris/tmas/${data.data.sensor_company_id}/show')}}">Lihat Data Selengkapnya</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `
                    }
                };

                geoJsonLayer = L.geoJSON(geojson, {
                    onEachFeature: function (feature, layer) {
                        if (feature.properties && feature.properties.popupContent) {
                            layer.bindPopup(feature.properties.popupContent);
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