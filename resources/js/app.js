import "./bootstrap";
import Alpine from "alpinejs";
import AOS from "aos";
import "aos/dist/aos.css";

window.Alpine = Alpine;

Alpine.start();

// Initialisation d'AOS avec des options personnalisées
document.addEventListener("DOMContentLoaded", function () {
    AOS.init({
        duration: 1000, // Durée de l'animation en millisecondes
        easing: "ease-in-out", // Fonction d'assouplissement
        once: true, // L'animation ne se déclenche qu'une seule fois
        startEvent: "DOMContentLoaded", // L'animation démarre dès que le DOM est chargé
    });

    AOS.init({
        duration: 1000,
        easing: "ease-in-out",
        once: true,
        startEvent: "DOMContentLoaded",
        offset: 0, // L'animation se déclenche même si l'élément est au sommet de la page
    });
});

console.log("Le fichier app.js est chargé correctement et AOS est initialisé");
