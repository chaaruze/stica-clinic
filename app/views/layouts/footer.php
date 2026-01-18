    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Storage listener for consultation end
        window.addEventListener('storage', function(e) {
            if (e.key === 'consultation_ended' && e.newValue === 'true') {
                location.reload();
                localStorage.removeItem('consultation_ended');
            }
        });
    </script>
</body>
</html>
