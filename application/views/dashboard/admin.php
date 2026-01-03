<div class="container-fluid">

  <div class="row g-3 mb-4">
    <?php
    $cards = [
      ['Users', $total_users, 'primary'],
      ['Guru', $total_teachers, 'success'],
      ['Siswa', $total_students, 'info'],
      ['Kelas', $total_classes, 'warning'],
      ['Quiz PBL', $total_quizzes, 'secondary'],
      ['UTS/UAS', $total_exams, 'danger'],
    ];
    foreach ($cards as $c): ?>
      <div class="col-md-2 col-sm-6">
        <div class="card border-left-<?= $c[2]; ?> shadow-sm">
          <div class="card-body text-center">
            <h6 class="text-dark"><?= $c[0]; ?></h6>
            <h3 class="fw-bold"><?= $c[1]; ?></h3>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="row mb-6">
    <div class="col-md-4">
      <div class="card">
        <div class="card-header text-primary fw-bold">User per Role</div>
        <div class="card-body">
          <canvas id="chartUserRole"></canvas>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card">
        <div class="card-header text-primary fw-bold">
          Perbandingan Jumlah Guru & Siswa
        </div>
        <div class="card-body">
          <canvas id="chartTeacherStudent"></canvas>
        </div>
      </div>
    </div>


    <div class="col-md-6">
      <div class="card">
        <div class="card-header text-primary fw-bold">Rata-rata Nilai PBL</div>
        <div class="card-body">
          <canvas id="chartAvgScore"></canvas>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card mb-4">
        <div class="card-header">Rata-rata Nilai UTS vs UAS</div>
        <div class="card-body">
          <canvas id="chartExamUTSUAS"></canvas>
          <div class="alert alert-info">
            <?= $exam_insight; ?>
          </div>
        </div>

      </div>
    </div>

  </div>

  <div class="card mb-4">
    <div class="card-header text-primary fw-bold">5 Kelas Terbaru</div>
    <div class="table-responsive">
      <table class="table table-striped mb-0">
        <thead>
          <tr>
            <th>Nama Kelas</th>
            <th>Kode</th>
            <th>Dibuat</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($latest_classes as $c): ?>
            <tr>
              <td><?= $c->name; ?></td>
              <td><?= $c->code; ?></td>
              <td><?= date('d M Y', strtotime($c->created_at)); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="card mb-4">
    <div class="card-header text-primary fw-bold">5 Guru Terbaru</div>
    <table class="table table-hover">
      <thead>
        <tr>
          <th>Nama</th>
          <th>Email</th>
          <th>Tanggal</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($latest_teachers as $t): ?>
          <tr>
            <td><?= $t->name; ?></td>
            <td><?= $t->email; ?></td>
            <td><?= date('d M Y', strtotime($t->created_at)); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  
  <div class="card mb-5">
    <div class="card-header text-primary fw-bold">Ujian Aktif</div>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Nama Ujian</th>
          <th>Tipe</th>
          <th>Kelas</th>
          <th>Waktu</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($active_exams as $e): ?>
          <tr>
            <td><?= $e->exam_name; ?></td>
            <td><span class="badge bg-info text-dark"><?= $e->type; ?></span></td>
            <td><?= $e->class_name; ?></td>
            <td><?= date('d M Y H:i', strtotime($e->start_time)); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

</div> <!-- container -->


<script src="<?= base_url('assets/vendor/chart.js/chart.umd.js'); ?>"></script>

<script>
  const userRoleData = <?= json_encode($user_per_role); ?>;
  const classYearData = <?= json_encode($class_per_year); ?>;
  const avgScoreData = <?= json_encode($avg_scores); ?>;

  /* User per Role */
  new Chart(document.getElementById('chartUserRole'), {
    type: 'pie',
    data: {
      labels: userRoleData.labels,
      datasets: [{
        data: userRoleData.data,
        backgroundColor: ['#0d6efd','#198754','#0dcaf0','#6c757d']
      }]
    }
  });

  /* Class per Year */
  /*new Chart(document.getElementById('chartClassYear'), {
    type: 'bar',
    data: {
      labels: classYearData.labels,
      datasets: [{
        label: 'Jumlah Kelas',
        data: classYearData.data,
        backgroundColor: '#ffc107'
      }]
    }
  });*/
  const teacherStudentData = <?= json_encode($teacher_student_ratio); ?>;

  new Chart(document.getElementById('chartTeacherStudent'), {
    type: 'doughnut',
    data: {
      labels: teacherStudentData.labels,
      datasets: [{
        data: teacherStudentData.data,
        backgroundColor: ['#198754', '#0d6efd']
      }]
    },
    options: {
      plugins: {
        legend: {
          position: 'bottom'
        },
        tooltip: {
          callbacks: {
            label: function(ctx) {
              return `${ctx.label}: ${ctx.raw} orang`;
            }
          }
        }
      }
    }
  });

  /* Average Score */
  /*new Chart(document.getElementById('chartAvgScore'), {
    type: 'radar',
    data: {
      labels: avgScoreData.labels,
      datasets: [{
        label: 'Rata-rata Nilai',
        data: avgScoreData.data,
        backgroundColor: 'rgba(13,110,253,0.2)',
        borderColor: '#0d6efd'
      }]
    }
  });*/

  new Chart(document.getElementById('chartAvgScore'), {
    type: 'radar',
    data: {
      labels: avgScoreData.labels,
      datasets: [{
        label: 'Rata-rata Nilai',
        data: avgScoreData.data,
        fill: true,
        backgroundColor: 'rgba(13,110,253,0.2)',
        borderColor: '#0d6efd',
        pointBackgroundColor: '#0d6efd',
        pointRadius: 4
      }]
    },
    options: {
      scales: {
        r: {
          min: 0,
          max: 100,

          /* ===== PAKSA RENTANG ===== */
          ticks: {
            stepSize: 25,
            callback: function(value) {
              if (value === 100) return '100';
              if (value === 75)  return '75';
              if (value === 50)  return '60';
              if (value === 25)  return '< 50';
              return '';
            }
          },

          /* ===== GRID & LABEL ===== */
          grid: {
            color: '#dee2e6'
          },
          angleLines: {
            color: '#ced4da'
          },
          pointLabels: {
            font: {
              size: 12,
              weight: 'bold'
            }
          }
        }
      },
      plugins: {
        legend: {
          position: 'top'
        }
      }
    }
  });

  const examData = <?= json_encode($avg_exam_uts_uas); ?>;

  new Chart(document.getElementById('chartExamUTSUAS'), {
    type: 'bar',
    data: {
      labels: examData.labels,
      datasets: [{
        label: 'Rata-rata Nilai',
        data: examData.data
      }]
    },
    options: {
      scales: {
        y: {
          min: 0,
          max: 100,
          ticks: {
            stepSize: 10
          }
        }
      }
    }
  });


</script>
