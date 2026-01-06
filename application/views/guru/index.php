<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<small class="text-muted">Ringkasan aktivitas pembelajaran PBL</small>
	</div>

	<div class="row mb-4">

		<div class="col-xl-4 col-md-6 mb-4">
			<div class="card border-left-primary shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Siswa Ajar</div>
							<div class="h5 mb-0 font-weight-bold text-gray-800" id="card-students">
								<div class="spinner-border spinner-border-sm text-primary" role="status"></div>
							</div>
						</div>
						<div class="col-auto">
							<i class="fas fa-users fa-2x text-gray-300"></i>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-xl-4 col-md-6 mb-4">
			<div class="card border-left-danger shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Perlu Pemeriksaan (Esai)</div>
							<div class="h5 mb-0 font-weight-bold text-gray-800" id="card-pending">
								<div class="spinner-border spinner-border-sm text-danger" role="status"></div>
							</div>
						</div>
						<div class="col-auto">
							<i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-xl-4 col-md-6 mb-4">
			<div class="card border-left-success shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-success text-uppercase mb-1">Rata-rata Nilai Kuis</div>
							<div class="h5 mb-0 font-weight-bold text-gray-800" id="card-avg">
								<div class="spinner-border spinner-border-sm text-success" role="status"></div>
							</div>
						</div>
						<div class="col-auto">
							<i class="fas fa-chart-line fa-2x text-gray-300"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row mb-4">
		<div class="col-lg-8">
			<div class="card shadow mb-4">
				<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
					<h6 class="m-0 font-weight-bold text-primary">Sebaran Nilai Kuis (Seluruh Kelas)</h6>
				</div>
				<div class="card-body">
					<div class="chart-area" style="height: 320px;">
						<canvas id="quizBarChart"></canvas>
					</div>
				</div>
			</div>
		</div>

		<div class="col-lg-4">
			<div class="card shadow mb-4">
				<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
					<h6 class="m-0 font-weight-bold text-primary">Status Pemeriksaan Esai</h6>
				</div>
				<div class="card-body">
					<div class="chart-pie pt-4 pb-2" style="height: 250px;">
						<canvas id="essayPieChart"></canvas>
					</div>
					<div class="mt-4 text-center small">
						<span class="mr-2">
							<i class="fas fa-circle text-success"></i> Sudah Dinilai
						</span>
						<span class="mr-2">
							<i class="fas fa-circle text-warning"></i> Menunggu
						</span>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row mb-4">
		<div class="col-12">
			<div class="card shadow mb-4">
				<div class="card-header py-3 bg-gradient-warning text-white">
					<h6 class="m-0 font-weight-bold"><i class="fas fa-exclamation-triangle mr-2"></i>Prioritas: 5 Tugas Esai Terbaru (Belum Dinilai)</h6>
				</div>
				<div class="card-body p-0">
					<div class="table-responsive">
						<table class="table table-striped table-hover mb-0" id="priorityTable">
							<thead class="bg-light">
								<tr>
									<th>Nama Siswa</th>
									<th>Judul Tugas</th>
									<th>Tanggal Kirim</th>
									<th class="text-center">Aksi</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="4" class="text-center py-3">Memuat data...</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	<h5 class="h5 text-gray-800 mb-3 ml-2 border-bottom pb-2">Daftar Kelas Anda</h5>
	<div class="row">
		<?php if (!empty($kelas_list)): ?>
			<?php foreach ($kelas_list as $kelas): ?>
				<div class="col-lg-6 col-xl-4 mb-4">
					<div class="card shadow h-100 py-2 border-left-info">
						<div class="card-body">
							<div class="row no-gutters align-items-center">
								<div class="col mr-2">
									<div class="text-xs font-weight-bold text-info text-uppercase mb-1">
										<?= htmlspecialchars($kelas->code, ENT_QUOTES, 'UTF-8'); ?>
									</div>
									<div class="h5 mb-0 font-weight-bold text-gray-800">
										<?= htmlspecialchars($kelas->name, ENT_QUOTES, 'UTF-8'); ?>
									</div>
								</div>
								<div class="col-auto">
									<a href="<?= base_url('guru/dashboard/class_detail/' . $kelas->id) ?>" class="btn btn-sm btn-info shadow-sm">
										Masuk Kelas <i class="fas fa-arrow-right ml-1"></i>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
			<?php else: ?>
				<div class="col-12 text-center text-muted">Belum ada kelas yang ditugaskan.</div>
			<?php endif; ?>
		</div>

	</div>

	<!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
	 <script src="<?= base_url('assets/js/jquery-3.6.0.min.js') ?>"></script>
	 <script src="<?= base_url('assets/vendor/chart.js/chart.umd.js'); ?>"></script>
	<script>
		document.addEventListener("DOMContentLoaded", function() {

    // Konfigurasi Font Global Chart.js (Menyesuaikan template SB Admin 2 biasanya)
    Chart.defaults.font.family = 'Nunito, -apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.color = '#858796';

    const baseUrl = "<?= base_url(); ?>";

    // Fungsi Fetch Data
    function loadDashboardStats() {
    	$.ajax({
    		url: baseUrl + 'guru/dashboard/dashboard_stats',
    		method: 'GET',
    		dataType: 'json',
    		success: function(response) {
    			if(response.status === 'success') {
    				updateCards(response.cards);
    				initBarChart(response.charts.quiz_dist);
    				initPieChart(response.charts.essay_stats);
    				updatePriorityTable(response.priority_list);
    			} else {
    				console.log('No class data available');
    				$('#card-students').text('0');
    				$('#card-pending').text('0');
    				$('#card-avg').text('0');
    			}
    		},
    		error: function(xhr, status, error) {
    			console.error("Gagal mengambil data statistik:", error);
    		}
    	});
    }

    // 1. Update Info Cards
    function updateCards(cards) {
    	$('#card-students').text(cards.students);
    	$('#card-pending').text(cards.pending);
    	$('#card-avg').text(cards.avg_quiz);
    }

    // 2. Chart Bar: Sebaran Nilai
    function initBarChart(dataValues) {
    	const ctx = document.getElementById("quizBarChart").getContext('2d');
    	new Chart(ctx, {
    		type: 'bar',
    		data: {
    			labels: ["0-50 (Remedial)", "51-70 (Cukup)", "71-85 (Baik)", "86-100 (Sempurna)"],
    			datasets: [{
    				label: "Jumlah Siswa",
    				backgroundColor: "#4e73df",
    				hoverBackgroundColor: "#2e59d9",
    				borderColor: "#4e73df",
                    data: dataValues, // [Range E, Range C, Range B, Range A]
                    borderRadius: 5,
                    barPercentage: 0.6
                  }],
                },
                options: {
                	maintainAspectRatio: false,
                	layout: { padding: { left: 10, right: 25, top: 25, bottom: 0 } },
                	scales: {
                		x: { grid: { display: false, drawBorder: false } },
                		y: { 
                			grid: { color: "rgb(234, 236, 244)", zeroLineColor: "rgb(234, 236, 244)", drawBorder: false, borderDash: [2] },
                        ticks: { padding: 10, precision: 0 } // precision 0 agar tidak ada desimal untuk jumlah orang
                      },
                    },
                    plugins: {
                    	legend: { display: false },
                    	tooltip: {
                    		backgroundColor: "rgb(255,255,255)",
                    		bodyColor: "#858796",
                    		titleColor: '#6e707e',
                    		borderColor: '#dddfeb',
                    		borderWidth: 1,
                    		padding: 15,
                    		displayColors: false,
                    		caretPadding: 10,
                    	}
                    }
                  }
                });
    }

    // 3. Chart Doughnut: Status Essay
    function initPieChart(dataValues) {
    	const ctx = document.getElementById("essayPieChart").getContext('2d');
    	new Chart(ctx, {
    		type: 'doughnut',
    		data: {
    			labels: ["Sudah Dinilai", "Menunggu Pemeriksaan"],
    			datasets: [{
    				data: dataValues,
    				backgroundColor: ['#1cc88a', '#f6c23e'],
    				hoverBackgroundColor: ['#17a673', '#dda20a'],
    				hoverBorderColor: "rgba(234, 236, 244, 1)",
    			}],
    		},
    		options: {
    			maintainAspectRatio: false,
    			tooltips: {
    				backgroundColor: "rgb(255,255,255)",
    				bodyFontColor: "#858796",
    				borderColor: '#dddfeb',
    				borderWidth: 1,
    				xPadding: 15,
    				yPadding: 15,
    				displayColors: false,
    				caretPadding: 10,
    			},
    			plugins: {
    				legend: { display: false },
                    cutout: '75%', // Membuat lubang tengah lebih besar
                  }
                },
              });
    }

    // 4. Update Table Priority
    function updatePriorityTable(data) {
    	const tbody = $('#priorityTable tbody');
    	tbody.empty();

    	if (data.length === 0) {
    		tbody.append('<tr><td colspan="4" class="text-center py-3 text-muted">Tidak ada tugas esai yang perlu diperiksa saat ini.</td></tr>');
    		return;
    	}

    	data.forEach(item => {
            // Link ke halaman penilaian essay (sesuaikan URL dengan routing Anda)
            // Asumsi: URLnya adalah guru/pbl/esai_detail/{class_id}
            const link = `${baseUrl}guru/Pbl_esai/detail/${item.id}`;
            
            // Format Tanggal
            const date = new Date(item.created_at).toLocaleDateString('id-ID', {
            	day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute:'2-digit'
            });

            const row = `
            <tr>
            <td class="font-weight-bold text-gray-800">${item.student_name}</td>
            <td>${item.task_title}</td>
            <td><small>${date}</small></td>
            <td class="text-center">
            <a href="${link}" class="btn btn-warning btn-sm btn-icon-split">
            <span class="icon text-white-50"><i class="fas fa-pen"></i></span>
            <span class="text">Nilai</span>
            </a>
            </td>
            </tr>
            `;
            tbody.append(row);
          });
    }

    // Jalankan
    loadDashboardStats();
  });
</script>