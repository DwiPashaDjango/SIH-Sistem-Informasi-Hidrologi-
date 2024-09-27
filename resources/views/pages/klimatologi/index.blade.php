@extends('layouts.pages')

@section('title')
    Klimatologi
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
        <ul class="list-group" style="height: 80vh; overflow: scroll;" id="post-all">
            <li class="text-white list-group-item" style="background-color: rgb(10, 71, 147)">Daftar Pos :</li>
        </ul>
    </div>
    <div class="col-lg-10">
        <div class="row mb-2">
            <div class="col-lg-4">
                <label for="">Provinsi</label>
                <select name="provinceId" id="provinceId" class="form-control">
                    <option value="">- Pilih -</option>
                    @foreach ($province as $prv)
                        <option value="{{$prv->id}}">{{$prv->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-4">
                <label for="">Kabupaten/Kota</label>
                <select name="regencieId" id="regencieId" class="form-control">
                    <option value="">- Pilih -</option>
                </select>
            </div>
            <div class="col-lg-4">
                <label for="">Subdas</label>
                <select name="subdasId" id="subdasId" class="form-control">
                    <option value="">- Pilih -</option>
                    @foreach ($subdas as $sbd)
                        <option value="{{$sbd->id}}">{{$sbd->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div id="map"></div>
    </div>
</div>
@endsection

@push('js')
    <script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js" integrity="sha512-BB3hKbKWOc9Ez/TAwyWxNXeoV9c1v6FIeYiBieIWkpLjauysF18NzgR1MBNBXf8/KABdlkX68nAhlwcDFLGPCQ==" crossorigin=""></script>
    <script>
        $(document).ready(function() {
            filterDayNow();

            $("#provinceId").select2()
            $("#regencieId").select2()
            $("#subdasId").select2()

            let provinceId = null;
            let regencieId = null;
            let subdasId = null;

            $("#provinceId").change(function() {
                let provinceId = $(this).val();
                filterDayNow(provinceId)

                $.ajax({
                    url: "{{url('api/getRegencie')}}",
                    method: "POST",
                    data: {
                        provinceId: provinceId
                    },
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        let html = '';
                        $.each(data.data, function(index, value) {
                            html += `<option value="${value.id}">${value.name}</option>`;
                        })
                        $("#regencieId").html(html)
                    },
                    error: function(err) {
                        console.log(err);
                    }
                })
            })

            $("#regencieId").change(function() {
                regencieId = $(this).val();
                filterDayNow(provinceId, regencieId, subdasId);
            });

            $("#subdasId").change(function() {
                subdasId = $(this).val();
                filterDayNow(provinceId, regencieId, subdasId);
            });

            function filterDayNow(provinceId = null, regencieId = null, subdasId = null) {
                $.ajax({
                    url: "{{url('api/getAllPosKlimatologi')}}",
                    method: 'GET',
                    data: {
                        provinceId: provinceId,
                        regencieId: regencieId,
                        subdasId: subdasId
                    },
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        let no = 1;
                        let html = `<li class="text-white list-group-item" style="background-color: rgb(10, 71, 147)">Daftar Pos :</li>`;
                        $.each(data.data, function(index, value) {
                            let bgStatus = '';
                            if (value.status === 'Normal') {
                                bgStatus += 'rgb(80, 200, 120)';
                            } else if(value.status === 'Waspada') {
                                bgStatus += 'rgb(228, 208, 10)';
                            } else if(value.status === 'Siaga') {
                                bgStatus += 'rgb(255, 140, 0)';
                            } else if(value.status === 'Awas') {
                                bgStatus += 'rgb(255, 87, 51)';
                            } else {
                                bgStatus += 'rgb(80, 200, 120)';
                            }

                            html += `<li class="list-group-item" style="cursor: pointer;" data-index="${index}" data-koordinatx="${value.koordinatx}" data-koordinaty="${value.koordinaty}">
                                        ${no++}. ${value.nama}
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

            let geoJsonLayer;
            const map = L.map('map').setView([-1.5242, 102.1152], 8);

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
                    "type": "FeatureCollection",
                    "features": data.data.map(marker => {
                        let statusColor = '';
                        let iconUrl = '';

                        if (marker.status === 'Normal') {
                            statusColor += 'background-color: rgb(80, 200, 120);';
                            iconUrl = "{{asset('img/marker-icon-2x-green.png')}}";
                        } else if(marker.status === 'Waspada') {
                            statusColor += 'background-color: rgb(228, 208, 10);';
                            iconUrl = "{{asset('img/marker-icon-2x-yellow.png')}}"; 
                        } else if(marker.status === 'Siaga') {
                            statusColor += 'background-color: rgb(255, 140, 0);';
                            iconUrl = "{{asset('img/marker-icon-2x-orange.png')}}"; 
                        } else if(marker.status === 'Awas') {
                            statusColor += 'background-color: rgb(255, 87, 51);';
                            iconUrl = "{{asset('img/marker-icon-2x-red.png')}}"; 
                        }

                        return {
                            "type": "Feature",
                            "geometry": {
                                "type": "Point",
                                "coordinates": [marker.koordinatx, marker.koordinaty]
                            },
                            "properties": {
                                "name": marker.nama,
                                "status": marker.status,
                                "popupContent": `
                                    <div class="leaflet-pane leaflet-popup-pane">
                                        <div class="leaflet-popup">
                                            <div class="leaflet-popup-content-wrapper">
                                                <div class="leaflet-popup-content" style="width: 350px;">
                                                    <div class="p-3 row">
                                                        <div class="col">
                                                            <h5>${marker.nama}</h5>
                                                            <p>Device ID : <strong>${marker.device_id || '-'}</strong></p>
                                                            <p>Lokasi : <strong>${marker.lokasi}</strong></p>
                                                            <p>Kabupaten : <strong>${marker.kabupaten}</strong></p>
                                                            <p>Provinsi : <strong>${marker.provinsi}</strong></p>
                                                            <p>Koordinat : <strong>${marker.koordinatx}, ${marker.koordinaty}</strong></p>
                                                        </div>
                                                        <div class="col">
                                                            <img src="${marker.gambar}" alt="default" width="300" class="img-fluid">
                                                            <div>
                                                                <div>
                                                                    <div class="mt-2 row">
                                                                        <div class="col-md-12">Hasil Penguapan : ${marker.hasil_penguapan} mm</div>
                                                                        <div class="col-md-12">Anemometer Spedometer : ${marker.anemometer_spedometer} mm</div>
                                                                        <div class="col-md-12">Sinar Matahari : ${marker.sinar_matahari} %</div>
                                                                        <div class="col-md-12">Hujan Biasa : ${marker.hujan_biasa} mm</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <hr class="divide">
                                                        <a class="btn" style="background-color: rgb(10, 71, 147); color: #fff" href="{{url('klimatologis/${marker.id}/show')}}">Lihat Data Selengkapnya</a>
                                                    </div>
                                                </div>
                                            </div>
                                        <div>
                                    </div>
                                `
                            }
                        };
                    })
                }

                geoJsonLayer = L.geoJSON(geojson, {
                    pointToLayer: function(feature, latlng) {
                        let iconUrl = '';
                        if (feature.properties.status === 'Normal') {
                            iconUrl = "{{asset('img/marker-icon-2x-green.png')}}";
                        } else if (feature.properties.status === 'Waspada') {
                            iconUrl = "{{asset('img/marker-icon-2x-yellow.png')}}"; 
                        } else if (feature.properties.status === 'Siaga') {
                            iconUrl = "{{asset('img/marker-icon-2x-orange.png')}}"; 
                        } else if (feature.properties.status === 'Awas') {
                            iconUrl = "{{asset('img/marker-icon-2x-red.png')}}"; 
                        }

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
                var index = $(this).data('index');
                $(".show-detail").addClass('d-none');
                $(this).find(".show-detail").removeClass('d-none');

                var koordinatx = $(this).data('koordinatx');
                var koordinaty = $(this).data('koordinaty');

                geoJsonLayer.eachLayer(function(layer) {
                    if (layer.feature.geometry.coordinates[0] == koordinatx && layer.feature.geometry.coordinates[1] == koordinaty) {
                        map.setView(layer.getLatLng(), 10);
                        layer.openPopup();
                    }
                });
            });

        });

    </script>
@endpush
