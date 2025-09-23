<!-- Vendor Javascript (Require in all Page) -->
<script src="{{ asset('dashboard/assets/js/vendor.js') }}"></script>

<!-- App Javascript (Require in all Page) -->
<script src="{{ asset('dashboard/assets/js/app.js') }}"></script>

<!-- Vector Map Js -->
<!-- <script src="{{ asset('dashboard/assets/vendor/jsvectormap/js/jsvectormap.min.js') }}"></script> -->
<!-- <script src="{{ asset('dashboard/assets/vendor/jsvectormap/maps/world-merc.js') }}"></script> -->
<!--  <script src="{{ asset('dashboard/assets/vendor/jsvectormap/maps/world.js') }}"></script> -->

<!-- Dashboard Js -->
<!-- <script src="{{ asset('dashboard/assets/js/pages/dashboard.js') }}"></script> -->

@yield('scripts')

<script>
    // Initialize Quill only when present and library loaded
    (function () {
        if (typeof Quill === 'undefined') return;
        var container = document.querySelector('#snow-editor');
        if (!container) return;
        var quill = new Quill('#snow-editor', {
            theme: 'snow',
            modules: {
                'toolbar': [
                    [{ 'font': [] }, { 'size': [] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'script': 'super' }, { 'script': 'sub' }],
                    [{ 'header': [false, 1, 2, 3, 4, 5, 6] }, 'blockquote', 'code-block'],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }, { 'indent': '-1' }, { 'indent': '+1' }],
                    ['direction', { 'align': [] }],
                    ['link', 'image', 'video'],
                    ['clean']
                ]
            }
        });
    })();
</script>
