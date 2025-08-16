// ===== ECHARTS FUNCTIONS =====

// Function to render barchart for komoditas kota teratas
function renderBarchartKomoditasKotaTeratas(data, namaKota) {
    var chartDom2 = document.getElementById("barchart-komoditas-kota-teratas");
    var judul = document.getElementById("judul-barchart-kota-teratas");
    if (judul) {
        judul.textContent =
            "Inflasi MtM Komoditas Utama di " + (namaKota || "-");
    }
    if (chartDom2 && data && data.length > 0) {
        var myChart2 = echarts.init(chartDom2);
        var labels2 = data.map((item) => item.nama_kom);
        var values2 = data.map((item) => Number(item.inflasi_mtm));
        var option2 = {
            tooltip: {
                trigger: "axis",
                axisPointer: {
                    type: "shadow",
                },
            },
            grid: {
                left: "5%",
                right: "5%",
                bottom: "3%",
                top: 10,
                containLabel: true,
            },
            xAxis: {
                type: "value",
                boundaryGap: [0, 0.01],
            },
            yAxis: {
                type: "category",
                data: labels2,
                inverse: true,
                axisLabel: {
                    color: "#000000",
                    fontSize: 12,
                    fontWeight: 330,
                },
            },
            series: [
                {
                    type: "bar",
                    data: values2,
                    itemStyle: {
                        color: "#E82D1F",
                    },
                    label: {
                        show: true,
                        position: "outside",
                        color: "#063051",
                        fontSize: 12,
                        fontWeight: 350,
                        formatter: function (params) {
                            return params.value.toFixed(2).replace(".", ",");
                        },
                    },
                },
            ],
        };
        myChart2.setOption(option2);
    }
}

// Function to render andil komoditas charts
function renderChart(id, data, inflationValue) {
    if (!data || data.length === 0) {
        return;
    }
    let numericValue = parseFloat(
        inflationValue.textContent.replace(",", ".").trim()
    );
    var chartDom = document.getElementById(id);
    if (!chartDom) {
        return;
    }
    var myChart = echarts.init(chartDom);
    var komoditas = data.map((item) => {
        return item.nama_kom.length > 15
            ? item.nama_kom.substring(0, 15) + "..."
            : item.nama_kom;
    });
    var komoditasFull = data.map((item) => item.nama_kom); // Nama lengkap untuk tooltip
    var values = data.map((item) => Number(item.andil));
    var option = {
        tooltip: {
            trigger: "axis",
            axisPointer: {
                type: "shadow",
            },
            formatter: function (params) {
                // Tooltip menampilkan nama lengkap
                var index = params[0].dataIndex;
                return (
                    komoditasFull[index] +
                    "<br/>" +
                    "Andil: " +
                    params[0].value.toFixed(2).replace(".", ",") +
                    "%"
                );
            },
        },
        grid: {
            left: "3%",
            right: "5%",
            bottom: "3%",
            top: "3%",
            containLabel: true,
        },
        xAxis: {
            type: "value",
            boundaryGap: [0, 0.01],
            axisLabel: {
                color: "#063051",
                fontSize: 12,
                fontWeight: "semibold",
                formatter: function (value) {
                    return value.toFixed(2).replace(".", ",");
                },
            },
        },
        yAxis: {
            type: "category",
            data: komoditas,
            inverse: true,
            axisLabel: {
                color: "#063051",
                fontSize: 12,
                fontWeight: "semibold",
            },
        },
        series: [
            {
                type: "bar",
                data: values,
                itemStyle: {
                    color: "#4C84B0",
                },
                label: {
                    show: true,
                    position: "outside",
                    color: "#063051",
                    fontSize: 12,
                    fontWeight: 350,
                    formatter: function (params) {
                        return params.value.toFixed(2).replace(".", ",");
                    },
                },
            },
        ],
    };
    myChart.setOption(option);
}

// ===== AJAX FUNCTIONS =====

// Fetch inflasi komoditas kabupaten/kota data
async function fetchInflasiKomoditasKabKota(kodeWil) {
    try {
        const params = new URLSearchParams({
            kode_wil: kodeWil,
            periode: window.periodeBarchart || "",
            jenis_data_inflasi: window.jenisDataInflasiBarchart || "",
        });
        const response = await fetch(
            `/dashboard/spasial/komoditas-kabkota-data?${params.toString()}`
        );
        if (!response.ok) throw new Error("Gagal fetch data");
        return await response.json();
    } catch (e) {
        return [];
    }
}

// ===== MAIN INITIALIZATION =====

document.addEventListener("DOMContentLoaded", function () {
    // Initialize charts
    var inflasiKomoditasKotaTeratas = window.inflasiKomoditasKotaTeratas || [];
    var rankingKabKota = window.rankingKabKota || [];
    var currentNamaKota =
        rankingKabKota.length > 0 ? rankingKabKota[0].nama_wil : "";

    setTimeout(() => {
        // Render barchart komoditas kota teratas
        renderBarchartKomoditasKotaTeratas(
            inflasiKomoditasKotaTeratas,
            currentNamaKota
        );

        // Render andil komoditas charts
        const inflasiMtM = document.getElementById("inflasiMtM");
        const inflasiYtD = document.getElementById("inflasiYtD");
        const inflasiYoY = document.getElementById("inflasiYoY");

        // Debug: Check if data exists
        console.log("topAndilMtM:", window.topAndilMtM);
        console.log("topAndilYtD:", window.topAndilYtD);
        console.log("topAndilYoY:", window.topAndilYoY);

        if (window.topAndilMtM && window.topAndilMtM.length > 0) {
            renderChart("andilmtm", window.topAndilMtM, inflasiMtM);
        } else {
            console.warn("Data topAndilMtM is empty or undefined");
        }

        if (window.topAndilYtD && window.topAndilYtD.length > 0) {
            renderChart("andilytd", window.topAndilYtD, inflasiYtD);
        } else {
            console.warn("Data topAndilYtD is empty or undefined");
        }

        if (window.topAndilYoY && window.topAndilYoY.length > 0) {
            renderChart("andilyoy", window.topAndilYoY, inflasiYoY);
        } else {
            console.warn("Data topAndilYoY is empty or undefined");
        }

        // Add click event listeners for kabupaten/kota names
        document.querySelectorAll(".nama-kabkota").forEach(function (el) {
            el.addEventListener("click", async function () {
                const kodeWil = this.getAttribute("data-kode-wil");
                const namaWil = this.getAttribute("data-nama-wil");
                // Fetch data via AJAX
                const data = await fetchInflasiKomoditasKabKota(kodeWil);
                renderBarchartKomoditasKotaTeratas(data, namaWil);
            });
        });
    }, 500);

    // ===== EXPORT FUNCTIONALITY =====

    // PNG Export
    const exportPNGBtn = document.getElementById("exportPNG");
    if (exportPNGBtn) {
        exportPNGBtn.addEventListener("click", function () {
            const bulan = document.getElementById("bulan")?.value || "";
            const tahun = document.getElementById("tahun")?.value || "";
            const jenisDataInflasi = window.jenisDataInflasiBarchart || "";
            const target = document.getElementById("main-dashboard-content");
            if (!target) {
                alert(
                    "Dashboard content not found. Coba cek selector atau id elemen."
                );
                return;
            }
            window.exportDashboardToPNG({
                targetSelector: "#main-dashboard-content",
                filename: `dashboard-inflasi-spasial-${bulan}-${tahun}-${jenisDataInflasi}.png`,
                loadingText: "Generating PNG...",
            });
        });
    }

    // Excel Export
    const exportExcelBtn = document.getElementById("exportExcel");
    if (exportExcelBtn) {
        exportExcelBtn.addEventListener("click", function () {
            const bulan = document.getElementById("bulan")?.value || "";
            const tahun = document.getElementById("tahun")?.value || "";
            const jenisDataInflasi = window.jenisDataInflasiBarchart || "";
            const komoditasUtama = window.komoditasUtama || "";
            const kabkota = window.kabkota || "";
            // Build query string for spasial export
            const params = new URLSearchParams({
                spasial: "1",
                bulan,
                tahun,
                jenis_data_inflasi: jenisDataInflasi,
                komoditas_utama: komoditasUtama,
                kabkota: kabkota,
            });
            window.open(
                `/dashboard/export-excel?${params.toString()}`,
                "_blank"
            );
        });
    }

    // ===== FORM HANDLING =====

    // Auto-select bulan terakhir jika bulan tidak tersedia di tahun baru
    const tahunSelect = document.getElementById("tahun");
    const bulanSelect = document.getElementById("bulan");
    if (tahunSelect && bulanSelect) {
        tahunSelect.addEventListener("change", function () {
            const tahun = tahunSelect.value;
            const bulanSekarang = bulanSelect.value;
            const bulanList = window.bulanPerTahun
                ? window.bulanPerTahun[tahun] || []
                : [];
            // Jika bulan sekarang tidak ada di tahun baru, set ke bulan terakhir
            if (!bulanList.includes(bulanSekarang)) {
                if (bulanList.length > 0) {
                    bulanSelect.value = bulanList[bulanList.length - 1];
                } else {
                    bulanSelect.value = "";
                }
            }
            // Submit form
            bulanSelect.form.submit();
        });
    }
});
