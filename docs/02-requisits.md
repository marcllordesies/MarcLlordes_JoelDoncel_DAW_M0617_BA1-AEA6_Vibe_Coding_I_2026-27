# Requisits del Sistema: MergeYGol

**Autors:** Marc Llordes i Joel Doncel  
**Referència de Disseny:** Sofascore MVP Concept

---

## 1. Rols d'Usuari

### A. Usuari Visitant / Aficionat (no autenticat)
*   **Definició:** Usuari anònim que realitza consultes de lectura a la plataforma.
*   **Permisos:** Té accés lliure a llistats de jornades, fitxes de partits, taules de classificació i rànquings estadístics de jugadors o equips sense necessitat d'autenticació.

### B. Usuari Registrat (Aficionat amb compte)
*   **Definició:** Usuari autenticat mitjançant credencials d'accés nates de l'aplicació.
*   **Permisos:** Manté tots els privilegis de lectura del visitant i hi afegeix capes d'interacció amb la base de dades (sistema de predicció 1X2 per partit i inserció de comentaris).

### C. Administrador de la Plataforma (Admin)
*   **Definició:** Usuari privilegiat amb accés exclusiu al panell de control de gestió (*Backend*).
*   **Permisos:** Encarregat del manteniment del catàleg d'entitats (CRUD de lligues, equips, jugadors) i de la simulació de la telemetria en directe (generació d'esdeveniments minut a minut i finalització de partits).

---

## 2. Històries d'Usuari i Criteris d'Acceptació

### Rol: Usuari Visitant

#### H01: Pàgina d'Inici i Quadre de Comandaments de la Jornada
*   **Descripció:** Com a aficionat, vull veure un llistat cronològic de tots els partits programats per a la data actual, agrupats jeràrquicament per competició, per conèixer l'estat global de la jornada.
*   **Criteris d'acceptació:**
    *   La interfície ha de segmentar clarament els partits segons tres estats exclusius: *No començat* (mostra l'hora d'inici), *En joc* (mostra el minut actual i un indicador visual intermitent de "Directe") i *Finalitzat* (mostra el resultat definitiu).
    *   Cada línia d'esdeveniment ha d'incloc el nom dels dos equips, els seus respectius escuts o logotips, i el marcador parcial/final en temps real.

#### H02: Vista de Detall de Partit i Cronologia d'Esdeveniments
*   **Descripció:** Com a aficionat, vull accedir a la fitxa singular d'un partit per inspeccionar-ne el full d'alineacions i la línia de temps detallada de les accions registrades.
*   **Criteris d'acceptació:**
    *   **Alineacions:** S'ha de llistar la plantilla titular i la posició dels jugadors de cada equip assignats a aquest partit.
    *   **Línia de temps (Timeline):** Eix vertical cronològic que renderitza icones específiques segons l'esdeveniment: pilota per a *Gol* (indicant autor i assistent), targeta groga/vermella per a *Sanció*, i una fusta/pal per a *Tir al pal*.

#### H03: Taula de Classificació Dinàmica i Estadístiques d'Equip
*   **Descripció:** Com a aficionat, vull consultar la taula de posicions de la lliga i les mètriques col·lectives per analitzar el rendiment global dels equips de la competició.
*   **Criteris d'acceptació:**
    *   La taula de classificació ha de calcular i ordenar de manera automatitzada els equips en base a les següents columnes estàndard: *Posició, Equip, Punts, Partits Jugats (PJ), Victòries (V), Empats (E), Derrotes (D), Gols a Favor (GF), Gols Encaixats (GC)* i *Diferència de Gols (DG)*.
    *   **Mètriques Avançades d'Equip:** Dins del perfil o vista de l'equip, s'ha de mostrar un acumulador històric de la temporada de: *Total de tirs al pal*, *Mitjana de gols per partit*, i *Targetes acumulades*.

#### H04: Rànquings i Líders Estadístics de Jugadors
*   **Descripció:** Com a aficionat, vull veure taules de líders individuals dins de la lliga per identificar quins són els jugadors amb millor rendiment.
*   **Criteris d'acceptació:**
    *   L'aplicació ha d'oferir un rànquing o taula completa de **Màxims Golejadors** (ordenat per gols totals) i **Màxims Assistents** (ordenat per assistències totals).
    *   En cas d'empat en la mètrica principal, el sistema utilitzarà com a criteri secundari de desempat el jugador que hagi acumulat menys targetes vermelles.

---

### Rol: Usuari Registrat

#### H05: Autenticació Segura i Gestió de Sessió
*   **Descripció:** Com a aficionat, vull disposar d'un formulari de registre i entrada per obtenir una identitat digital persistent dins de la plataforma.
*   **Criteris d'acceptació:**
    *   El formulari de registre requerirà: *Nom d'usuari (únic), Correu electrònic (vàlid i únic)* i *Contrasenya* (encriptada forçosament mitjançant mecanismes natius de Laravel com Bcrypt).
    *   Es farà ús del sistema de control d'autenticació natiu per protegir les rutes i accions de creació de dades en l'entorn client.

#### H06: Mòdul de Prediccions de la Comunitat (Votació 1X2)
*   **Descripció:** Com a usuari registrat, vull emetre el meu vot sobre el resultat esperat d'un partit abans o durant el transcurs d'aquest, per contrastar-ho amb la comunitat.
*   **Criteris d'acceptació:**
    *   Els botons de votació (*Guanya Local (1) / Empat (X) / Guanya Visitant (2)*) només seran interactius si la sessió de l'usuari està activa.
    *   La base de dades ha de validar que existeix una restricció d'**un únic vot per usuari i partit** (clau composta o validació estricta en controlador).
    *   Un cop l'usuari fa clic i registra el seu vot, els botons s'oculten i es mostra un gràfic de percentatges lògics calculats dinàmicament (Exemple: `(Vots Local / Vots Totals) * 100`).

#### H07: Fòrum de Debat i Comentaris en Directe
*   **Descripció:** Com a usuari registrat, vull publicar comentaris dins del fil actiu d'un partit per interactuar directament amb la resta d'aficionats.
*   **Criteris d'acceptació:**
    *   La secció de comentaris bloquejarà la capsa de text als visitants, mostrant un avís indicant que cal iniciar sessió per poder participar.
    *   Cada comentari s'ha d'inserir lligat a l'ID del partit i l'ID de l'usuari, renderitzant el *Nom d'usuari*, el contingut del text i el *timestamp* en format humà ("Fa 3 minuts").
    *   L'ordre de visualització predeterminat serà cronològic descendent (els comentaris més recents primer).

---

### Rol: Administrador

#### H08: Accés Protegit al Panell de Gestió (Middleware)
*   **Descripció:** Com a administrador, vull tenir una passarel·la d'accés tancada que impedeixi l'entrada de qualsevol usuari que no disposi de permisos d'administració.
*   **Criteris d'acceptació:**
    *   L'accés a les rutes de gestió del backend de MergeYGol estarà controlat de manera estricta per un *Middleware* de Laravel que comprovi el camp o rol del model d'usuari (`is_admin == true`).
    *   Qualsevol intent d'accés no autoritzat retornarà un codi d'error HTTP 403 Forbidden o redirigirà a la pàgina d'inici pública de l'aplicació.

#### H09: Manteniment Base d'Entitats Esportives (CRUD Complet)
*   **Descripció:** Com a administrador, vull disposar d'interfícies netes per crear, llegir, modificar o eliminar els registres estructurals del sistema.
*   **Criteris d'acceptació:**
    *   **Equips:** Formulari per introduir el nom oficial de l'entitat, el seu estadi i el logo de l'escut.
    *   **Jugadors:** Formulari per assignar *Nom, Dorsal, Posició al camp (Porter, Defensa, Migcampista, Davanter)* i la relació nullable (*Foreign Key*) ja que poden haver-hi jugadors sense equip.
    *   **Partits:** Selector per escollir un equip local, un equip visitant (validant que no siguin el mateix), data, hora i definició de l'estat inicial (*No començat*).

#### H10: Telemetria en Directe i Actualització de Transaccions de Dades
*   **Descripció:** Com a administrador, vull un tauler de control operatiu de partit per introduir accions en temps real que actualitzin els marcadors i l'històric estadístic.
*   **Criteris d'acceptació:**
    *   El tauler oferirà un formulari per registrar un esdeveniment. L'admin indicarà el *Minut*, el *Tipus d'acció* (Gol, Assistència, Targeta Groga, Targeta Vermella, Tir al pal, Corner, Falta comesa, Remat (Bloquejat, fora o a porteria) ) i el *Jugador implicat* d'una llista desplegable filtrada automàticament pels membres d'aquell equip.
    *   Si l'esdeveniment és un **Gol**, el sistema de backend sumarà immediatament +1 al marcador de l'equip corresponent i sumarà +1 al caseller individual de gols del jugador escollit. El mateix procediment s'aplicarà per a les assistències i els tirs al pal col·lectius.
    *   **Canvi d'estat a Finalitzat:** En prémer el botó "Finalitzar Partit", el backend realitzarà una transacció a la base de dades per reajustar la taula de classificació: assignarà +3 punts al guanyador (o +1 a cadascun en cas d'empat), recalcularà els gols a favor/encaixats de les dues entitats i tancarà el partit per a noves modificacions o votacions.
