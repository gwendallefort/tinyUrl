<script>
(function () {
    var key = 'tinyurl-theme';
    var root = document.documentElement;
    var t = localStorage.getItem(key);
    if (t === 'light') root.classList.remove('dark');
    else if (t === 'dark') root.classList.add('dark');
    else root.classList.toggle('dark', window.matchMedia('(prefers-color-scheme: dark)').matches);
})();
</script>
