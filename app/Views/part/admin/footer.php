<footer class="footer">
    <?php $siteSettings = getSiteSettings(); ?>
    <p>&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($siteSettings['site_name']); ?> - Admin Panel. Phiên bản 1.0.0</p>
</footer>
</div>

<!-- JavaScript Tương tác -->
<script src="<?php echo _HOST_URL_PUBLIC; ?>/assets/js/admin/script.js"></script>
</body>

</html>