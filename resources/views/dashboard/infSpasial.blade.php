@extends('layouts.dashboard')

@section('body')
    <div id="map" style="height: 400px;"></div>

    <!-- Tambahkan Leaflet CSS dan JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var wilayahs = @json($wilayahs); // Data dari database master_wilayahs

            // Inisialisasi peta
            var map = L.map('map').setView([-7.536064, 112.238401], 8);

            // Tambahkan tile layer tanpa jalan (gunakan layer dari CartoDB Positron tanpa label)
            L.tileLayer('https://{s}.basemaps.cartocdn.com/light_nolabels/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/">CARTO</a>'
            }).addTo(map);

            // Tambahkan GeoJSON untuk kabupaten/kota
            fetch('/data/east-java-districts.geojson')
                .then(response => response.json())
                .then(data => {
                    // Filter features to only include those with matching kode_wil
                    var filteredFeatures = data.features.filter(feature => {
                        return wilayahs.some(wilayah => wilayah.kode_wil == feature.properties.CC_2);
                    });

                    // Create a new GeoJSON object with filtered features
                    var filteredGeoJSON = {
                        type: "FeatureCollection",
                        features: filteredFeatures
                    };

                    L.geoJSON(filteredGeoJSON, {
                        style: function(feature) {
                            // Cari wilayah yang sesuai
                            var wilayah = wilayahs.find(w => w.kode_wil == feature.properties.CC_2);

                            return {
                                fillColor: wilayah ? '#3498db' :
                                '#e0e0e0', // Warna biru untuk yang ada di master_wilayahs, abu-abu untuk yang tidak
                                weight: 2,
                                opacity: 1,
                                color: 'white',
                                dashArray: '3',
                                fillOpacity: 0.7
                            };
                        },
                        onEachFeature: function(feature, layer) {
                            // Tambahkan tooltip permanen dengan nama wilayah
                            layer.bindTooltip(feature.properties.NAME_2, {
                                permanent: true,
                                direction: 'center',
                                className: 'label-tooltip'
                            }).openTooltip();
                        }
                    }).addTo(map);
                });
        });
    </script>

    <style>
        /* Tambahkan gaya untuk tooltip agar lebih terlihat */
        .label-tooltip {
            background-color: rgba(0, 123, 255, 0.8); /* Warna biru */
            border: 2px solid #007bff; /* Border biru */
            padding: 5px 10px; /* Padding lebih besar */
            font-size: 14px; /* Ukuran font lebih besar */
            color: white; /* Warna teks putih */
            border-radius: 5px; /* Membuat sudut melengkung */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3); /* Menambahkan bayangan */
            text-align: center; /* Teks di tengah */
        }
    </style>
@endsection
