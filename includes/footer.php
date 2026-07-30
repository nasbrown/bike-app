</main>

<?php if (str_contains($_SERVER['REQUEST_URI'], "admin/view-all-loc.php")): ?>

   <script src="/bike-app/js/all-app.js" type="module" defer></script>

<?php elseif(str_contains($_SERVER['REQUEST_URI'], "admin/index.php")): ?>

    <script src="/bike-app/js/app.js" type="module" defer></script>

<?php else: ?>

<?php endif; ?>

</html>