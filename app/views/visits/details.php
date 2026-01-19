<?php require APPROOT . '/views/layouts/header.php'; ?>
<style>
    /* Print-friendly / Receipt style */
    body {
        background-color: #f5f5f5;
    }

    .visit-receipt {
        max-width: 800px;
        margin: 20px auto;
        background: white;
        padding: 25px 30px;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .header-blue {
        color: var(--sti-blue);
    }

    .label-text {
        font-weight: 600;
        color: #6c757d;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .value-text {
        font-size: 1rem;
        color: #212529;
        font-weight: 500;
    }

    .divider {
        border-bottom: 1px dashed #dee2e6;
        margin: 15px 0;
    }

    .vital-box {
        padding: 10px;
        background: #f8f9fa;
        border-radius: 5px;
        text-align: center;
    }

    @media print {
        @page {
            size: A4;
            margin: 10mm;
        }
        body { background-color: white !important; font-size: 11pt; }
        .navbar, .navbar-custom, footer, .btn, .no-print { display: none !important; }
        .visit-receipt { box-shadow: none; border: none; margin: 0; padding: 15px; max-width: 100%; }
        .container { max-width: 100%; padding: 0; margin: 0; }
        .vital-box { padding: 8px; }
        .row.g-3 { --bs-gutter-y: 0.5rem; }
        /* Ensure watermark and colors are printed */
        * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
    }
</style>

<div class="container">
    <div class="visit-receipt position-relative">
        <!-- Watermark Logo -->
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); opacity: 0.1; z-index: 0; pointer-events: none;">
            <img src="<?= URLROOT ?>/assets/images/logo.png" alt="Watermark" style="width: 300px; filter: grayscale(100%);">
        </div>

        <div style="position: relative; z-index: 1;">
            <!-- Header -->
            <div class="text-center mb-3">
                <img src="<?= URLROOT ?>/assets/images/logo.png" alt="STI Logo" style="width: 60px; margin-bottom: 5px;">
                <h4 class="fw-bold header-blue mb-0">STI College Clinic</h4>
                <small class="text-muted">Medical Examination Record</small>
            </div>

            <!-- Patient Info Row -->
            <div class="row mb-3">
                <div class="col-6">
                    <div class="mb-2">
                        <div class="label-text">Patient ID</div>
                        <div class="value-text">
                            <?= $data['visit']->{strtolower($data['type']) . ' number'} ?>
                        </div>
                    </div>
                    <div>
                        <div class="label-text">Patient Type</div>
                        <span class="badge bg-primary"><?= $data['type'] ?></span>
                    </div>
                </div>
                <div class="col-6 text-end">
                    <div class="label-text">Date & Time</div>
                    <div class="value-text">
                        <?= date('M d, Y', strtotime($data['visit']->{'date visit'})) ?> |
                        <?= date('h:i A', strtotime($data['visit']->{'time visit'})) ?>
                    </div>
                </div>
            </div>

            <div class="divider"></div>

            <!-- Vital Signs -->
            <h6 class="header-blue mb-2"><i class="fas fa-heartbeat me-1"></i>Vital Signs</h6>
            <div class="row g-2 mb-3">
                <div class="col-3">
                    <div class="vital-box">
                        <div class="label-text mb-0">BP</div>
                        <div class="value-text text-primary fw-bold"><?= $data['visit']->blood_pressure ?? '--' ?></div>
                        <small class="text-muted">mmHg</small>
                    </div>
                </div>
                <div class="col-3">
                    <div class="vital-box">
                        <div class="label-text mb-0">Temp</div>
                        <div class="value-text text-danger fw-bold"><?= $data['visit']->temperature ?? '--' ?></div>
                        <small class="text-muted">°C</small>
                    </div>
                </div>
                <div class="col-3">
                    <div class="vital-box">
                        <div class="label-text mb-0">Weight</div>
                        <div class="value-text text-success fw-bold"><?= $data['visit']->weight ?? '--' ?></div>
                        <small class="text-muted">kg</small>
                    </div>
                </div>
                <div class="col-3">
                    <div class="vital-box">
                        <div class="label-text mb-0">Pulse</div>
                        <div class="value-text text-warning fw-bold"><?= $data['visit']->pulse_rate ?? '--' ?></div>
                        <small class="text-muted">bpm</small>
                    </div>
                </div>
            </div>

            <div class="divider"></div>

            <!-- Diagnosis -->
            <div class="mb-3">
                <div class="label-text mb-1">Reason / Diagnosis</div>
                <div class="p-2 bg-white border rounded" style="min-height: 40px;">
                    <?php 
                        if ($data['type'] == 'Student') {
                            $reason = $data['visit']->diagnosis ?? 'N/A';
                        } else {
                            $reason = $data['visit']->{'reason / diagnosis'} ?? 'N/A';
                        }
                        echo nl2br(htmlspecialchars($reason));
                    ?>
                </div>
            </div>

            <?php
                // Extract Dispensed Medicines from Treatment
                if ($data['type'] == 'Student') {
                    $treatment = $data['visit']->intervention ?? '';
                } else {
                    $treatment = $data['visit']->treatment ?? '';
                }
                
                // Parse [Dispensed: Qty x Name] patterns
                preg_match_all('/\[Dispensed:\s*(\d+)x\s*([^\]]+)\]/', $treatment, $matches, PREG_SET_ORDER);
                
                // Clean treatment text (remove dispensed tags for display)
                $cleanTreatment = preg_replace('/\[Dispensed:[^\]]+\]\s*/', '', $treatment);
                $cleanTreatment = trim($cleanTreatment);
            ?>

            <!-- Dispensed Medicines -->
            <?php if (!empty($matches)): ?>
            <div class="mb-3">
                <div class="label-text mb-1"><i class="fas fa-pills me-1"></i>Dispensed Medicines</div>
                <div class="d-flex flex-wrap gap-2">
                    <?php foreach ($matches as $med): ?>
                        <span class="badge bg-info text-dark px-3 py-2">
                            <i class="fas fa-capsules me-1"></i>
                            <?= htmlspecialchars($med[1]) ?>x <?= htmlspecialchars(trim($med[2])) ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Treatment -->
            <div class="mb-3">
                <div class="label-text mb-1">Treatment / Intervention</div>
                <div class="p-2 bg-white border rounded" style="min-height: 40px;">
                    <?= !empty($cleanTreatment) ? nl2br(htmlspecialchars($cleanTreatment)) : 'N/A' ?>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-4 text-center no-print">
                <button onclick="window.print()" class="btn btn-success me-2"><i class="fas fa-print me-1"></i> Print</button>
                <button onclick="window.close()" class="btn btn-secondary">Close</button>
            </div>

        </div>
    </div>
</div>
<?php require APPROOT . '/views/layouts/footer.php'; ?>