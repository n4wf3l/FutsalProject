@props(['text'])

<div class="subtitle-container flex justify-center items-center mt-2 mb-4">
    <div class="vertical-line double-bar"></div>
    
    <p class="text-xl text-gray-600 subtitle-text">
        {!! $text !!}
    </p>
</div>

<style>
.subtitle-container {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 20px;
    margin-bottom: 20px;
}

.vertical-line {
    width: 4px;
    height: 40px;
    background-color: var(--secondary-color);
    margin-right: 4px;
}

.double-bar {
    position: relative;
    display: inline-block;
}

.double-bar::before {
    content: '';
    position: absolute;
    left: 8px;
    width: 4px;
    height: 40px;
    background-color: var(--secondary-color);
}

.subtitle-text {
    font-size: 1.25rem;
    color: var(--primary-color);
    margin-left: 16px;
    font-family: 'Bebas Neue', sans-serif;
    letter-spacing: 1px;
}

:root {
    --primary-color: {{ $primaryColor }};
    --secondary-color: {{ $secondaryColor }};
}
</style>