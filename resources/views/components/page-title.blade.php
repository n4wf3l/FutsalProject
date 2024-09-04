@props(['subtitle' => null])

<div class="section-title-container text-center my-12 flex justify-center items-center" style="margin-top: 10px; margin-bottom: 10px;">
    <!-- La ligne verticale -->
    <div class="vertical-line"></div>

    <!-- Le titre avec le style section-title -->
    <h2 class="section-title">
        {{ $slot }}
    </h2>
</div>

@if($subtitle)
<div class="flex justify-center items-center mt-2 mb-4">
    <p class="text-xl text-gray-300">{{ $subtitle }}</p>
</div>
@endif

<style>
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap');

  .section-title-container {
    justify-content: center;
    align-items: center;
    font-family: 'Bebas Neue', sans-serif;
    letter-spacing:2px;
}

.vertical-line {
    width: 8px;
    height: 60px; /* Ajustez la hauteur selon votre préférence */
    background-color: var(--secondary-color);
    margin-right: 16px; /* Espace entre la ligne et le titre */
}

.section-title {
    font-size: 2.5rem;
    color: var(--primary-color);
    display: inline-block;
    font-weight: bold;
    position: relative;
    text-align: left;
}

/* Couleurs pour remplacer les variables */
:root {
    --primary-color: {{ $primaryColor }};
    --secondary-color: {{ $secondaryColor }};
}
</style>
