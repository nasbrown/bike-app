
    </main>
    <?php if(!str_contains($_SERVER['REQUEST_URI'], "view-all-loc.php")): ?>
    
    <script src="/bike-app/js/app.js" type="module" defer></script>

    <?php else: ?>

    <script src="/bike-app/js/all-app.js" type="module" defer></script>

    <?php endif; ?>
</html>