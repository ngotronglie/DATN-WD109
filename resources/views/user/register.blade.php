<form method="POST" action="{{ route('user.register') }}">
    @csrf
    @include('partials.form-errors')
    <!-- ...existing form fields... -->
</form>

