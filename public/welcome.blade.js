document.addEventListener("DOMContentLoaded", function () {
    const text = "DINA KÉNITRA FC\nRISE UP & DOMINATE\nTHE GAME!";
    const typingElement = document.getElementById("typing-text");
    let index = 0;
    const speed = 100; // Vitesse d'écriture (ms)

    function typeWriter() {
        if (index < text.length) {
            const char = text.charAt(index);
            if (char === "\n") {
                typingElement.innerHTML += "<br>";
            } else {
                typingElement.innerHTML += char;
            }
            index++;
            setTimeout(typeWriter, speed);
        } else {
            typingElement.style.borderRight = "none";

            const logo = document.createElement("img");
            logo.src = "{{ $logoPath ? asset($logoPath) : '' }}"; // Récupérer et utiliser $logoPath ici
            logo.alt = "Club Logo";
            logo.style.width = "150px"; // Taille du logo
            logo.style.position = "absolute";
            logo.style.left = "50%";
            logo.style.transform = "translateX(-50%)";
            logo.style.marginTop = "10vh";
            logo.style.opacity = "0"; // Commence transparent
            logo.style.transition = "opacity 2s ease-in-out";

            // Vérifiez que le logoPath est valide avant de l'ajouter
            if (logo.src && logo.src !== "") {
                typingElement.parentNode.appendChild(logo);

                // Déclencher l'animation de fondu
                setTimeout(() => {
                    logo.style.opacity = "1";
                }, 100);

                // Ajouter le bouton après un délai pour afficher le logo d'abord
                setTimeout(function () {
                    const button = document.createElement("a");
                    button.id = "reserve-button";
                    button.href = "{{ route('fanshop.index') }}";
                    button.innerHTML = "Reserve your ticket";
                    button.style.display = "inline-block";
                    button.style.padding = "15px 30px";
                    button.style.backgroundColor = "{{ $secondaryColor }}";
                    button.style.color = "white";
                    button.style.fontFamily = "'Bebas Neue', sans-serif";
                    button.style.fontSize = "1.5rem";
                    button.style.border = "none";
                    button.style.borderRadius = "5px";
                    button.style.cursor = "pointer";
                    button.style.textDecoration = "none";
                    button.style.position = "absolute";
                    button.style.left = "50%";
                    button.style.transform = "translateX(-50%)";
                    button.style.marginTop = "30vh";
                    button.style.opacity = "0";
                    button.style.transition = "opacity 2s ease-in-out";

                    button.addEventListener("mouseover", function () {
                        button.style.backgroundColor = "{{ $primaryColor }}";
                    });

                    button.addEventListener("mouseout", function () {
                        button.style.backgroundColor = "{{ $secondaryColor }}";
                    });

                    typingElement.parentNode.appendChild(button);

                    // Déclencher l'animation de fondu pour le bouton
                    setTimeout(() => {
                        button.style.opacity = "1";
                    }, 100); // Légère pause pour que le bouton s'ajoute avant la transition
                }, 1000); // Le bouton apparaît 1 seconde après le logo
            }
        }
    }

    typeWriter();
});
