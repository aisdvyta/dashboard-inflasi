document.addEventListener('DOMContentLoaded', function() {
    var wilayahs = window.wilayahs || [];
    var inflasiWilayah = window.inflasiWilayah || [];
    var map = L.map('map');

    // Add tile layer
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_nolabels/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/">CARTO</a>'
    }).addTo(map);

    // MAP GeoJSON
    fetch('/data/east-java-districts.geojson')
        .then(response => response.json())
        .then(data => {
            var filteredFeatures = data.features.filter(feature => {
                return wilayahs.some(wilayah => wilayah.kode_wil == feature.properties.CC_2);
            });

            var filteredGeoJSON = {
                type: "FeatureCollection",
                features: filteredFeatures
            };

            var geojsonLayer = L.geoJSON(filteredGeoJSON, {
                style: function(feature) {
                    var wilayah = inflasiWilayah.find(w => w.kode_wil == feature.properties.CC_2);
                    // Warna: merah jika inflasi >= 0, hijau jika < 0, abu jika tidak ada data
                    let fillColor = '#e0e0e0';
                    if (wilayah) {
                        fillColor = wilayah.inflasi_mtm < 0 ? '#27ae60' : '#e74c3c';
                    }
                    return {
                        fillColor: fillColor,
                        weight: 2,
                        opacity: 1,
                        color: 'white',
                        dashArray: '3',
                        fillOpacity: 0.7
                    };
                },
                onEachFeature: function(feature, layer) {
                    var wilayah = inflasiWilayah.find(w => w.kode_wil == feature.properties.CC_2);
                    let label = feature.properties.NAME_2;
                    let inflasi = null;
                    let inflasiStr = '';
                    if (wilayah) {
                        inflasi = parseFloat(wilayah.inflasi_mtm);
                        inflasiStr = `(${inflasi.toFixed(2).replace('.', ',')} %)`;
                        inflasiColor = inflasi < 0 ? '#388E3C' : '#E82D1F';
                        label =
                            `<div style='text-align:center;'>` +
                            `<div style='font-weight:bold;text-transform:uppercase;'>${wilayah.nama_wil}</div>` +
                            `<div style='color:${inflasiColor};font-weight:bold;'>${inflasiStr}</div>` +
                            `</div>`;
                    } else {
                        label =
                            `<div style='text-align:center;'>` +
                            `<div style='font-weight:bold;text-transform:uppercase;'>${feature.properties.NAME_2}</div>` +
                            `<div style='color:#888;'>-</div>` +
                            `</div>`;
                    }
                    layer.bindTooltip(label, {
                        permanent: true,
                        direction: 'top',
                        className: 'label-tooltip'
                    }).openTooltip();
                }
            }).addTo(map);

            if (filteredGeoJSON.features.length > 0) {
                map.fitBounds(geojsonLayer.getBounds().pad(-0.25));
                setTimeout(function() {
                    map.panBy([-90, 150]);
                }, 150);
            } else {
                map.setView([-7.6, 112.0], 8);
            }
        })
        .catch(error => {
            console.error('Gagal memuat atau memproses GeoJSON:', error);
            map.setView([-7.6, 112.0], 8);
        });
});
