@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('success'))
    <div id="success-alert" class="alert alert-success bg-green-600 text-white p-4 rounded-lg mb-4">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div id="error-alert" class="alert alert-danger bg-red-600 text-white p-4 rounded-lg mb-4">
        {{ session('error') }}
    </div>
@endif

<!-- Styles pour les alertes -->
<style>
    .alert {
        position: relative;
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 0.25rem;
    }

    .alert-success {
        background-color: #28a745;
        color: white;
    }

    .alert-danger {
        background-color: #dc3545;
        color: white;
    }
</style>

<!-- Script pour faire disparaître les alertes après 3 secondes -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            var successAlert = document.getElementById('success-alert');
            if (successAlert) {
                successAlert.style.transition = 'opacity 0.5s ease';
                successAlert.style.opacity = '0';
                setTimeout(function() {
                    successAlert.remove();
                }, 500); // Attend la fin de la transition avant de retirer l'élément
            }

            var errorAlert = document.getElementById('error-alert');
            if (errorAlert) {
                errorAlert.style.transition = 'opacity 0.5s ease';
                errorAlert.style.opacity = '0';
                setTimeout(function() {
                    errorAlert.remove();
                }, 500); // Attend la fin de la transition avant de retirer l'élément
            }
        }, 3000); // 3 secondes avant de commencer la disparition
    });
</script>
