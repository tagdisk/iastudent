</main>
<?php echo (isset($success) AND $success === true) ? resultBlock($message, 'success') : resultBlock($message); ?>
<div class="container mt-auto">
    <footer class="d-flex flex-wrap justify-content-between align-items-center  py-3 border-top">
        <div class="col-md-4 d-flex align-items-center">
            <figure class="rounded-0 d-inline-block position-relative">
               <img class="rounded-0" src="<?php echo SERVER['url'].'assets/img/favicons/setup_small.webp'; ?>" height="40" width="40" alt="..." name="<?php echo SERVER['title']; ?>">
            </figure>
            <div class="vstack mx-2">
                <small class="text-body-secondary">© 2023 <?php echo SERVER['title']; ?>.</small>
                <small class="text-body-secondary">Todos los derechos reservados.</small>
            </div>
            
        </div>
    
        <ul class="nav col-md-4 justify-content-end list-unstyled d-flex">
            <li class="ms-3"><a href="https://facebook.com/" class="text-decoration-none" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top"
            data-bs-title="Facebook"><i class="ias ias-facebook ias-hc-lg text-body-emphasis"></i></a></li>
    
            <li class="ms-3"><a href="https://instagram.com/" class="text-decoration-none" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top"
            data-bs-title="Instragram"><i class="ias ias-instagram ias-hc-lg text-body-emphasis"></i></a></li>
    
            <li class="ms-3"><a href="https://twitter.com/" class="text-decoration-none" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top"
            data-bs-title="Twitter"><i class="ias ias-twitter ias-hc-lg text-body-emphasis"></i></a></li>
        </ul>
    </footer>
</div>

<script src="<?php echo SERVER['url'].'assets/js/bootstrap.bundle.min.js'; ?>"></script>
<script>
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

    (() => {
    'use strict'
    const forms = document.querySelectorAll('.needs-validation');

    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
        }, false);
    })
    })();
</script>
</body>
</html>