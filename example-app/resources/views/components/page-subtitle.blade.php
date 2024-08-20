@props(['text'])

<div class="subtitle-container flex justify-center items-center mt-2 mb-4">
    <!-- Les deux barres verticales -->
    <div class="vertical-line double-bar"></div>
    
    <!-- Le sous-titre -->
    <p class="text-xl text-gray-600 subtitle-text">
        {{ $text }}
    </p>
</div>

<style>
.subtitle-container {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 20px; /* Marge au-dessus */
    margin-bottom: 20px; /* Marge en dessous */
}

.vertical-line {
    width: 4px;
    height: 40px; /* Hauteur des barres verticales */
    background-color: var(--secondary-color);
    margin-right: 4px; /* Espace entre les deux barres */
}

.double-bar {
    position: relative;
    display: inline-block;
}

.double-bar::before {
    content: '';
    position: absolute;
    left: 8px; /* Espacement entre les deux barres verticales */
    width: 4px;
    height: 40px;
    background-color: var(--secondary-color);
}

/* Texte du sous-titre */
.subtitle-text {
    font-size: 1.25rem;
    color: var(--primary-color);
    margin-left: 16px; /* Espace entre les barres et le texte */
    font-family: 'Bebas Neue', sans-serif;
    letter-spacing: 1px;
}

/* Couleurs pour remplacer les variables */
:root {
    --primary-color: {{ $primaryColor }};
    --secondary-color: {{ $secondaryColor }};
}
</style>
