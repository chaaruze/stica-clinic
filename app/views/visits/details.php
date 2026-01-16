<?php require APPROOT . '/views/layouts/header.php'; ?>
<style>
    /* Print-friendly / Receipt style */
    body {
        background-color: #f5f5f5;
    }

    .visit-receipt {
        max-width: 800px;
        margin: 30px auto;
        background: white;
        padding: 40px;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .header-blue {
        color: var(--sti-blue);
    }

    .label-text {
        font-weight: 600;
        color: #6c757d;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .value-text {
        font-size: 1.1rem;
        color: #212529;
        font-weight: 500;
    }

    .divider {
        border-bottom: 2px dashed #dee2e6;
        margin: 20px 0;
    }
</style>

<div class="container">
    <div class="visit-receipt">
        <div class="text-center mb-5">
            <img src="<?= URLROOT ?>/assets/images/logo.png" alt="STI Logo" style="width: 80px; margin-bottom: 10px;">
            <h3 class="fw-bold header-blue">STI College Clinic</h3>
            <p class="text-muted">Medical Examination Record</p>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="mb-3">
                    <div class="label-text">Patient ID</div>
                    <div class="value-text">
                        <?= $data['visit']->{strtolower($data['type']) . ' number'} ?>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="label-text">Patient Type</div>
                    <div class="value-text badge bg-primary">
                        <?= $data['type'] ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="mb-3">
                    <div class="label-text">Date & Time</div>
                    <div class="value-text">
                        <?= date('F d, Y', strtotime($data['visit']->{'date visit'})) ?><br>
                        <?= date('h:i A', strtotime($data['visit']->{'time visit'})) ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="divider"></div>

        <h5 class="header-blue mb-4"><i class="fas fa-heartbeat me-2"></i>Vital Signs</h5>
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="p-3 bg-light rounded text-center h-100">
                    <div class="label-text mb-1">Blood Pressure</div>
                    <div class="value-text text-primary fw-bold">
                        <?= $data['visit']->blood_pressure ?? '--' ?>
                    </div>
                    <small class="text-muted">mmHg</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 bg-light rounded text-center h-100">
                    <div class="label-text mb-1">Temperature</div>
                    <div class="value-text text-danger fw-bold">
                        <?= $data['visit']->temperature ?? '--' ?>
                    </div>
                    <small class="text-muted">°C</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 bg-light rounded text-center h-100">
                    <div class="label-text mb-1">Weight</div>
                    <div class="value-text text-success fw-bold">
                        <?= $data['visit']->weight ?? '--' ?>
                    </div>
                    <small class="text-muted">kg</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 bg-light rounded text-center h-100">
                    <div class="label-text mb-1">Pulse Rate</div>
                    <div class="value-text text-warning fw-bold">
                        <?= $data['visit']->pulse_rate ?? '--' ?>
                    </div>
                    <small class="text-muted">bpm</small>
                </div>
            </div>
        </div>

        <div class="divider"></div>

        <div class="mb-4">
            <div class="label-text mb-2">Reason / Diagnosis</div>
            <div class="p-3 bg-white border rounded">
                <?php 
                    // Different column names for student vs employee
                    if ($data['type'] == 'Student') {
                        $reason = $data['visit']->diagnosis ?? 'N/A';
                    } else {
                        $reason = $data['visit']->{'reason / diagnosis'} ?? 'N/A';
                    }
                    echo nl2br(htmlspecialchars($reason));
                ?>
            </div>
        </div>

        <div class="mb-4">
            <div class="label-text mb-2">Treatment / Intervention</div>
            <div class="p-3 bg-white border rounded">
                <?php 
                    // Different column names for student vs employee
                    if ($data['type'] == 'Student') {
                        $treatment = $data['visit']->intervention ?? 'N/A';
                    } else {
                        $treatment = $data['visit']->treatment ?? 'N/A';
                    }
                    echo nl2br(htmlspecialchars($treatment));
                ?>
            </div>
        </div>

        <div class="mt-5 text-center">
            <button onclick="window.close()" class="btn btn-primary">Close</button>
        </div>
    </div>
</div>
<?php require APPROOT . '/views/layouts/footer.php'; ?>