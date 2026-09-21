<script>
(function () {
    if (!('serviceWorker' in navigator)) return;
    navigator.serviceWorker.register(@json(asset('sw.js')), { scope: '/' }).catch(function () {});
})();
</script>
