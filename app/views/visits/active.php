<?php require APPROOT . '/views/layouts/header.php'; ?>

<div class="container-fluid min-vh-100 d-flex flex-column bg-light">
    <!-- Header -->
    <div class="row bg-white shadow-sm py-3 px-4 align-items-center">
        <div class="col-6">
            <h4 class="mb-0 text-sti-blue fw-bold"><i class="fas fa-heartbeat me-2"></i>Active Consultation</h4>
        </div>
        <div class="col-6 text-end">
            <div
                class="d-inline-flex align-items-center bg-danger text-white px-3 py-2 rounded-pill shadow-sm animate__animated animate__pulse animate__infinite">
                <i class="fas fa-clock me-2"></i>
                <span id="timer" class="fw-bold fs-5 font-monospace">00:00:00</span>
            </div>
        </div>
    </div>

    <div class="flex-grow-1 p-4">
        <div class="row h-100 justify-content-center">
            <div class="col-lg-10">
                <form id="activeVisitForm">
                    <input type="hidden" name="type" value="<?= $data['type'] ?>">
                    <input type="hidden" name="id"
                        value="<?= ($data['type'] == 'Student') ? $data['user']->{'student number'} : $data['user']->{'employee number'} ?>">

                    <div class="row g-4">
                        <!-- Patient Info Card -->
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body text-center pt-5">
                                    <?php if ($data['type'] == 'Student'): ?>
                                        <i class="fas fa-user-graduate fa-5x text-secondary mb-3"></i>
                                    <?php else: ?>
                                        <i class="fas fa-user-tie fa-5x text-secondary mb-3"></i>
                                    <?php endif; ?>

                                    <h5 class="fw-bold text-dark mt-2">
                                        <?= $data['user']->{'last name'} . ', ' . $data['user']->{'first name'} ?>
                                    </h5>
                                    <p class="text-muted mb-4">
                                        <?= $data['type'] ?> ID:
                                        <?= ($data['type'] == 'Student') ? $data['user']->{'student number'} : $data['user']->{'employee number'} ?>
                                    </p>

                                    <div class="text-start px-3">
                                        <div class="mb-2"><small class="text-muted d-block">Time In</small>
                                            <span class="fw-bold text-sti-blue fs-5">
                                                <?= date('h:i A', strtotime($data['visit']->{'time visit'})) ?>
                                            </span>
                                        </div>
                                        <div><small class="text-muted d-block">Date</small>
                                            <span class="fw-bold">
                                                <?= date('M d, Y', strtotime($data['visit']->{'date visit'})) ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Consultation Details -->
                        <div class="col-md-8">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-sti-blue text-white py-3">
                                    <h5 class="card-title mb-0">Record Details</h5>
                                </div>
                                <div class="card-body p-4">
                                    <!-- Vitals -->
                                    <h6 class="text-secondary fw-bold text-uppercase small mb-3">Vital Signs</h6>
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-3">
                                            <label class="form-label small text-muted">Blood Pressure</label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text"><i
                                                        class="fas fa-heartbeat text-danger"></i></span>
                                                <input type="text" class="form-control" name="blood_pressure"
                                                    placeholder="120/80">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small text-muted">Temperature</label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text"><i
                                                        class="fas fa-thermometer-half text-warning"></i></span>
                                                <input type="text" class="form-control" name="temperature"
                                                    placeholder="36.5">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small text-muted">Weight (kg)</label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text"><i
                                                        class="fas fa-weight text-primary"></i></span>
                                                <input type="text" class="form-control" name="weight" placeholder="50">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small text-muted">Pulse Rate</label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text"><i
                                                        class="fas fa-wave-square text-success"></i></span>
                                                <input type="text" class="form-control" name="pulse_rate"
                                                    placeholder="75">
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="text-muted opacity-25">

                                    <!-- Diagnosis & Treatment -->
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Reason / Diagnosis</label>
                                        <textarea class="form-control" name="diagnosis" rows="3"
                                            placeholder="Enter complaints or findings..."></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Treatment / Prescription</label>
                                        <textarea class="form-control" name="treatment" rows="3"
                                            placeholder="Enter given medication or procedure..."></textarea>
                                    </div>

                                    <div class="d-grid mt-4">
                                        <button type="submit" class="btn btn-success btn-lg">
                                            <i class="fas fa-save me-2"></i>End Consultation & Save
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Timer Logic
    const startTimeStr = "<?= $data['visit']->{'date visit'} . ' ' . $data['visit']->{'time visit'} ?>";
    const startTime = new Date(startTimeStr).getTime();

    function updateTimer() {
        const now = new Date().getTime();
        const distance = now - startTime;

        if (distance < 0) {
            document.getElementById("timer").innerHTML = "00:00:00";
            return;
        }

        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById("timer").innerHTML =
            (hours < 10 ? "0" + hours : hours) + ":" +
            (minutes < 10 ? "0" + minutes : minutes) + ":" +
            (seconds < 10 ? "0" + seconds : seconds);
    }

    setInterval(updateTimer, 1000);
    updateTimer();

    // Form Submit
    document.getElementById('activeVisitForm').addEventListener('submit', function (e) {
        e.preventDefault();
        if (!confirm('Are you sure you want to end this consultation?')) return;

        const formData = new FormData(this);

        fetch('<?= URLROOT ?>/visits/save_visit', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Visit saved successfully!');
                    // Signal other windows to refresh (dashboard, etc.)
                    localStorage.setItem('consultation_ended', Date.now().toString());
                    window.close(); // Close tab on success
                } else {
                    alert('Error saving visit.');
                }
            });
    });
</script>

<!-- Add some Animate.css for pulse effect if not already included in header logic, otherwise inline CSS -->
<style>
    @keyframes pulse-red {
        0% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7);
        }

        70% {
            transform: scale(1.05);
            box-shadow: 0 0 0 10px rgba(220, 53, 69, 0);
        }

        100% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
        }
    }

    .animate__pulse {
        animation: pulse-red 2s infinite;
    }
</style>

<!-- Clean Footer (No sidebar/nav for this focused view) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>