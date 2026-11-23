# Proposta de Projecte: Merge&Gol

**Autors:** Marc Llordes i Joel Doncel  

---

## 1. Descripció General
**MergeYGol** és una aplicació web inspirada en plataformes de telemetria esportiva com Sofascore. El projecte neix sota el concepte de *Vibe Coding* (desenvolupament assistit per Intel·ligència Artificial mitjançant llenguatge natural) i té com a finalitat oferir un portal dinàmic per al seguiment de marcadors, cronologies de partits i rànquings estadístics detallats de futbol en temps real.

## 2. Objectius del Projecte
*   **Consulta centralitzada:** Permetre als aficionats conèixer els resultats i l'estat dels partits de la jornada de forma immediata.
*   **Mètriques avançades:** Oferir taules de rendiment col·lectiu (equips) i individuals (màxims golejadors i assistents de la lliga).
*   **Comunitat activa:** Fomentar la interacció dels usuaris registrats mitjançant un sistema de prediccions 1X2 i fòrums de debat per a cada esdeveniment.
*   **Flux professional:** Treballar en equip utilitzant GitHub per branques, desplegar l'aplicació en producció i registrar l'ús de la IA a la documentació.

## 3. Arquitectura Tecnològica (Stack)
Per complir els objectius i adaptar-nos a les condicions acadèmiques, farem servir el següent entorn:
*   **Backend:** Laravel (PHP) per a l'arquitectura MVC, definició de rutes, controladors de negoci, ORM Eloquent i seguretat amb *middlewares*.
*   **Frontend:** Vistes natives de Laravel (Blade) combinades amb Tailwind CSS per a dissenyar una interfície altament dinàmica, intuïtiva i completament adaptable a mòbils (*responsive*).
*   **Entorn de Desenvolupament (EID):** Visual Studio Code com a editor principal de programació.
*   **Base de Dades:** MySQL com a sistema relacional per gestionar el modelatge complex d'equips, esdeveniments, usuaris i mètriques.
*   **Desplegament:** Operatiu en producció mitjançant una URL pública per a la demostració final.

## 4. Estratègia d'Ús de la Intel·ligència Artificial
Seguint les normes de l'activitat, farem un ús actiu i responsable de les eines d'IA (com GitHub Copilot, ChatGPT o Claude) per a l'acceleració del codi en entorn client/servidor. Es mantindrà un control estricte de la privacitat (sense pujar claus API ni dades reals) i es registrarà cada prompt significatiu al fitxer corresponent.




