<?php require APPROOT . '/views/layouts/header.php'; ?>
<?php require APPROOT . '/views/layouts/navbar.php'; ?>
<?php require APPROOT . '/views/layouts/sidebar.php'; ?>

<style>
    /* Compact Dashboard Styles */
    body {
        overflow-y: hidden;
        background-color: #f0f2f5;
    }

    .dashboard-container {
        height: calc(100vh - 70px);
        padding: 15px;
        overflow-y: auto;
    }

    /* Cards */
    .card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        background: white;
    }

    .card-title {
        font-weight: 700;
        color: var(--sti-blue);
    }

    /* Greeting Widget */
    .greeting-widget {
        background: linear-gradient(135deg, var(--sti-blue) 0%, var(--sti-dark-blue) 100%);
        color: white;
        border-radius: 12px;
    }

    .greeting-widget .icon {
        color: var(--sti-yellow);
    }

    /* Chart Container (Scrollable) */
    .chart-card-body {
        flex: 1;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        padding: 0;
        position: relative;
    }

    .chart-scroll-wrapper {
        flex: 1;
        overflow-x: auto;
        overflow-y: hidden;
        display: flex;
        align-items: stretch;
    }

    .chart-box {
        position: relative;
        height: 100%;
        min-width: 800px;
        flex-grow: 1;
    }

    /* Clinic Activity List */
    .activity-list {
        flex: 1;
        overflow-y: auto;
        min-height: 0;
    }

    .activity-item {
        border-left: 4px solid transparent;
        transition: all 0.2s;
    }

    .activity-item:hover {
        background-color: #f8f9fa;
    }

    .activity-item.student {
        border-left-color: var(--sti-yellow);
    }

    .activity-item.employee {
        border-left-color: var(--sti-blue);
    }

    /* Emergency Button - Small Header Version */
    .btn-emergency-header {
        background-color: #dc3545;
        color: white;
        font-weight: bold;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.8rem;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }

    .btn-emergency-header:hover {
        background-color: #c82333;
        transform: scale(1.05);
        color: white;
    }

    .blink {
        animation: blinker 2s linear infinite;
    }

    @keyframes blinker {
        50% {
            opacity: 0.8;
        }
    }

    /* Nurse Widgets */
    .notes-widget textarea {
        resize: none;
        border: 1px solid #dee2e6;
        background-color: #fff9c4;
        /* Post-it yellow */
        font-family: 'Courier New', Courier, monospace;
    }

    .calendar-widget {
        background: white;
        text-align: center;
    }

    .calendar-day {
        font-size: 3rem;
        font-weight: bold;
        color: var(--sti-blue);
        line-height: 1;
    }

    .calendar-month {
        font-size: 1.2rem;
        font-weight: bold;
        color: var(--sti-yellow);
        background: var(--sti-blue);
        border-radius: 5px;
        padding: 2px 10px;
        display: inline-block;
    }

    .calendar-weekday {
        font-size: 1.1rem;
        color: #6c757d;
    }

    /* Scrollbar styling */
    ::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    ::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 3px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #aaa;
    }
</style>

<div class="container-fluid dashboard-container">
    <div class="row h-100 g-3">
        <!-- Left Column: Operations (Chart, Widgets) -->
        <div class="col-lg-8 d-flex flex-column h-100">
            <!-- Greeting (Compact) -->
            <div class="card greeting-widget mb-3 p-3 flex-shrink-0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0"><i id="greeting-icon" class="fas icon me-2"></i><span id="greeting"></span>
                            <?php echo htmlspecialchars($_SESSION['name']); ?></h4>
                        <small class="opacity-75">Welcome to STI Clinic Dashboard</small>
                    </div>
                    <div class="text-end">
                        <h5 class="mb-0" id="time-display"></h5>
                        <small id="date-display" class="opacity-75"></small>
                    </div>
                </div>
            </div>

            <!-- Traffic Chart (Scrollable) -->
            <div class="card mb-3 flex-grow-1" style="overflow: hidden;">
                <div class="card-header text-white border-bottom py-2 d-flex justify-content-between align-items-center"
                    style="background-color: var(--sti-blue);">
                    <h5 class="card-title mb-0 text-white"><i class="fas fa-chart-area me-2"></i>Clinic Traffic</h5>
                    <div class="d-flex gap-2">
                        <select id="filter-year" class="form-select form-select-sm" style="width: auto;">
                            <?php
                            $currentYear = date('Y');
                            for ($i = $currentYear; $i >= $currentYear - 2; $i--): ?>
                                <option value="<?= $i ?>"><?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                        <select id="filter-month" class="form-select form-select-sm" style="width: auto;">
                            <option value="all">All Months</option>
                            <?php for ($m = 1; $m <= 12; $m++): ?>
                                <option value="<?= $m ?>"><?= date('F', mktime(0, 0, 0, $m, 1)) ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                <div class="card-body chart-card-body">
                    <div class="chart-scroll-wrapper ps-2 pe-2 pt-2 pb-2">
                        <div class="chart-box">
                            <canvas id="trafficChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Widgets: Notes & Calendar -->
            <div class="row g-3 flex-shrink-0" style="height: 200px;">
                <!-- Quick Notes -->
                <div class="col-md-8">
                    <div class="card h-100 notes-widget">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="card-title mb-0"><i class="fas fa-sticky-note me-2"></i>Quick Notes</h6>
                                <button class="btn btn-sm btn-outline-secondary py-0"
                                    onclick="clearNotes()">Clear</button>
                            </div>
                            <textarea id="quickValues" class="form-control flex-grow-1"
                                placeholder="Type notes here... (Auto-saved)"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Simple Calendar -->
                <div class="col-md-4">
                    <div
                        class="card h-100 calendar-widget d-flex flex-column justify-content-center align-items-center p-3">
                        <div class="calendar-month mb-2" id="cal-month">DEC</div>
                        <div class="calendar-day" id="cal-day">25</div>
                        <div class="calendar-weekday" id="cal-week">Monday</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Clinic Activity -->
        <div class="col-lg-4 d-flex flex-column h-100">

            <!-- Active Visits Widget (Dynamic) -->
            <?php if (!empty($data['activeVisits'])): ?>
                <div class="card mb-3 border-danger shadow-sm">
                    <div class="card-header bg-danger text-white py-2 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold"><i
                                class="fas fa-heartbeat animate__animated animate__pulse animate__infinite me-2"></i>Ongoing
                            Consultations</h6>
                        <span class="badge bg-white text-danger"><?= count($data['activeVisits']) ?></span>
                    </div>
                    <div class="card-body p-0">
                        <?php foreach ($data['activeVisits'] as $active): ?>
                            <div class="p-3 border-bottom d-flex justify-content-between align-items-center"
                                style="cursor: pointer; background-color: #fff5f5;"
                                onclick="window.open('<?= URLROOT ?>/visits/active/<?= $active->type ?>/<?= $active->id ?>', '_blank')">
                                <div>
                                    <div class="fw-bold text-dark"><?= $active->name ?></div>
                                    <small class="text-muted"><i class="fas fa-clock me-1"></i>Started:
                                        <?= date('h:i A', strtotime($active->time_visit)) ?></small>
                                </div>
                                <div class="spinner-grow text-danger spinner-grow-sm" role="status"></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="card flex-grow-1 d-flex flex-column" style="min-height: 0;">
                <!-- Header with Emergency Button -->
                <div class="card-header py-3 text-white d-flex justify-content-between align-items-center"
                    style="background-color: var(--sti-blue);">
                    <div class="d-flex align-items-center">
                        <h5 class="mb-0 text-white"><i class="fas fa-clipboard-list me-2"></i>Clinic Activity</h5>
                    </div>
                    <!-- Emergency Button Moved Here -->
                    <button class="btn btn-emergency-header blink shadow" data-bs-toggle="modal"
                        data-bs-target="#emergencyModal">
                        <i class="fas fa-ambulance me-1"></i> EMERGENCY
                    </button>
                </div>

                <div class="card-body p-0 activity-list">
                    <?php if (!empty($data['recentVisits'])): ?>
                        <?php foreach ($data['recentVisits'] as $visit): ?>
                            <div class="p-3 border-bottom activity-item <?= strtolower($visit->type) ?>"
                                style="cursor: pointer;"
                                onclick="window.location.href='<?= URLROOT ?>/<?= strtolower($visit->type) ?>s/details/<?= $visit->id ?>'">
                                <div class="d-flex align-items-center">
                                    <!-- Mini Calendar Icon -->
                                    <div class="me-3 text-center border rounded shadow-sm"
                                        style="width: 50px; height: 50px; overflow: hidden;">
                                        <div class="bg-primary text-white small fw-bold text-uppercase py-1"
                                            style="font-size: 0.7rem;">
                                            <?= date('M', strtotime($visit->date_visit)) ?>
                                        </div>
                                        <div class="bg-white text-dark fw-bold display-6"
                                            style="font-size: 1.2rem; line-height: 28px;">
                                            <?= date('d', strtotime($visit->date_visit)) ?>
                                        </div>
                                    </div>

                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-bold text-dark"><?= htmlspecialchars($visit->name) ?></span>
                                            <small
                                                class="text-muted"><?= date('h:i A', strtotime($visit->time_visit)) ?></small>
                                        </div>
                                        <div class="small text-secondary mt-1">
                                            <i
                                                class="<?= $visit->type == 'Student' ? 'fas fa-user-graduate' : 'fas fa-briefcase' ?> me-1"></i><?= $visit->type ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center p-4 text-muted">
                            <i class="fas fa-inbox fa-3x mb-3 text-light"></i><br>
                            No recent activity found.
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-footer bg-white text-center">
                    <a href="<?= URLROOT ?>/students" class="small text-decoration-none me-3">View Students</a>
                    <a href="<?= URLROOT ?>/employees" class="small text-decoration-none">View Employees</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Emergency Modal -->
<div class="modal fade" id="emergencyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-exclamation-triangle me-2"></i>EMERGENCY HOTLINES</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="display-4 text-danger fw-bold mb-4">137-175</div>

                <div class="row g-3">
                    <div class="col-12">
                        <div class="p-3 bg-light rounded d-flex justify-content-between align-items-center">
                            <span class="fw-bold"><i class="fas fa-phone-alt me-2 text-secondary"></i>Landline</span>
                            <span class="fs-5 text-dark select-all">8373-51-65</span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="p-3 bg-light rounded d-flex justify-content-between align-items-center">
                            <span class="fw-bold"><i class="fas fa-mobile-alt me-2 text-success"></i>Smart</span>
                            <span class="fs-5 text-dark select-all">0921-542-7123</span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="p-3 bg-light rounded d-flex justify-content-between align-items-center">
                            <span class="fw-bold"><i class="fas fa-mobile-alt me-2 text-primary"></i>Globe</span>
                            <span class="fs-5 text-dark select-all">0927-257-9322</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-center bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Time & Calendar Script
    function updateTime() {
        const now = new Date();
        document.getElementById('time-display').textContent = now.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
        document.getElementById('date-display').textContent = now.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' });

        // Update Calendar Widget
        document.getElementById('cal-month').textContent = now.toLocaleDateString('en-US', { month: 'short' }).toUpperCase();
        document.getElementById('cal-day').textContent = now.getDate();
        document.getElementById('cal-week').textContent = now.toLocaleDateString('en-US', { weekday: 'long' });

        const hour = now.getHours();
        const greetingElement = document.getElementById('greeting');
        const iconElement = document.getElementById('greeting-icon');

        if (hour < 12) {
            greetingElement.textContent = 'Good Morning, ';
            iconElement.className = 'fas fa-sun icon me-2';
        } else if (hour < 18) {
            greetingElement.textContent = 'Good Afternoon, ';
            iconElement.className = 'fas fa-cloud-sun icon me-2';
        } else {
            greetingElement.textContent = 'Good Evening, ';
            iconElement.className = 'fas fa-moon icon me-2';
        }
    }
    setInterval(updateTime, 1000);
    updateTime();

    // Notes Script
    const notesArea = document.getElementById('quickValues');
    notesArea.value = localStorage.getItem('nurseNotes') || '';
    notesArea.addEventListener('input', function () {
        localStorage.setItem('nurseNotes', this.value);
    });
    function clearNotes() {
        if (confirm('Clear notes?')) {
            notesArea.value = '';
            localStorage.removeItem('nurseNotes');
        }
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let trafficChart;

    async function fetchTrafficData() {
        const year = document.getElementById('filter-year').value;
        const month = document.getElementById('filter-month').value;

        try {
            const response = await fetch(`<?= URLROOT ?>/dashboard/chart_data?year=${year}&month=${month}`);
            const data = await response.json();
            renderChart(data);
        } catch (error) {
            console.error('Error fetching data:', error);
        }
    }

    function renderChart(data) {
        const ctx = document.getElementById('trafficChart').getContext('2d');

        if (trafficChart) {
            trafficChart.destroy();
        }

        let gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(0, 114, 188, 0.7)');
        gradient.addColorStop(1, 'rgba(0, 114, 188, 0.1)');

        trafficChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Visits',
                    data: data.visits,
                    backgroundColor: gradient,
                    borderColor: '#0072BC',
                    borderWidth: 2,
                    pointBackgroundColor: '#FFF200',
                    pointBorderColor: '#0072BC',
                    pointRadius: 4,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [2, 2] } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // Event Listeners
    document.getElementById('filter-year').addEventListener('change', fetchTrafficData);
    document.getElementById('filter-month').addEventListener('change', fetchTrafficData);

    // Initial Load
    fetchTrafficData();

    // Auto-refresh chart every 30 seconds to keep data live
    setInterval(fetchTrafficData, 30000);
</script>

<?php require APPROOT . '/views/layouts/footer.php'; ?>