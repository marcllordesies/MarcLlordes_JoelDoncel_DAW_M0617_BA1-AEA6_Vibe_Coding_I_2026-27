# Instal·lació local (Windows): Laravel Sail + entorn natiu

Aquest document defineix una instal·lació local amb dues rutes:

- Ruta A (recomanada): Docker + Laravel Sail.
- Ruta B (alternativa): instal·lació nativa (PHP, Composer, Node i MySQL local).

L'objectiu és que qualsevol membre de l'equip pugui arrencar el projecte encara que no tingui exactament el mateix entorn.

## 1. Abast

Aquest repositori conté la documentació del projecte. Si el codi Laravel està en un altre repositori, fes servir la seva URL al pas de clonació.

Variables que has d'adaptar:

- `<URL_REPO_APP>`: URL del repositori de l'aplicació Laravel.
- `<NOM_BD>`: nom de la base de dades local.
- `<USUARI_BD>` i `<PASSWORD_BD>`: credencials de MySQL.

## 2. Prerequisits

### 2.1. Comuns

- Git 2.40+
- VS Code 1.95+

### 2.2. Ruta A (Sail)

- Docker Desktop 4.30+
- WSL2 habilitat (recomanat a Windows)

### 2.3. Ruta B (nativa)

- PHP 8.2.x
- Composer 2.7+
- Node.js 20 LTS + npm 10+
- MySQL 8.0+

## 3. Pas inicial comú

1. Clona el repositori de l'app (o entra a la carpeta on ja el tens):

```bash
git clone https://github.com/<org-o-usuari>/mergeygol-app.git
cd mergeygol-app
```

Si el vostre repositori és un altre, substitueix l'URL anterior per la real.

2. Crea el fitxer d'entorn:

```bash
cp .env.example .env
```

3. Obre `.env` i configura com a mínim:

- `APP_NAME=MergeYGol`
- `APP_ENV=local`
- `DB_CONNECTION=mysql`
- `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`

## 4. Ruta A (recomanada): Docker + Laravel Sail

1. Instal·la dependències PHP:

```bash
composer install
```

2. Arrenca Sail en segon pla:

```bash
./vendor/bin/sail up -d
```

3. Genera la clau de l'aplicació:

```bash
./vendor/bin/sail artisan key:generate
```

4. Instal·la dependències frontend:

```bash
./vendor/bin/sail npm install
```

5. Crea taules i dades inicials:

```bash
./vendor/bin/sail artisan migrate --seed
```

6. Inicia compilació frontend:

```bash
./vendor/bin/sail npm run dev
```

7. Obre l'aplicació a:

- `http://localhost`

## 5. Ruta B (alternativa): instal·lació nativa

1. Instal·la dependències PHP:

```bash
composer install
```

2. Genera la clau:

```bash
php artisan key:generate
```

3. Instal·la dependències frontend:

```bash
npm install
```

4. Executa migracions i seeders:

```bash
php artisan migrate --seed
```

5. Arrenca backend:

```bash
php artisan serve
```

6. En una altra terminal, arrenca frontend:

```bash
npm run dev
```

7. Obre l'aplicació a:

- `http://127.0.0.1:8000`

## 6. Verificació ràpida

Després d'arrencar amb qualsevol ruta:

1. La pàgina principal carrega sense error 500.
2. Pots registrar un usuari i iniciar sessió.
3. Les dades inicials (lligues/equips/partits) apareixen si hi ha seeders.
4. Les rutes d'admin retornen 403 per usuaris sense permisos.

### 6.1. Creació d'usuari admin local

Opció recomanada (si existeix seeder d'admin):

```bash
php artisan db:seed --class=AdminUserSeeder
```

Amb Sail:

```bash
./vendor/bin/sail artisan db:seed --class=AdminUserSeeder
```

Opció alternativa (manual amb Tinker):

```bash
php artisan tinker
```

```php
\App\Models\User::create([
	'name' => 'Admin Local',
	'email' => 'admin@mergeygol.local',
	'password' => bcrypt('Admin12345!'),
	'is_admin' => true,
]);
```

Amb Sail, canvia `php artisan tinker` per `./vendor/bin/sail artisan tinker`.

## 7. Problemes freqüents a Windows

- Port ocupat:
	- Canvia el port (`php artisan serve --port=8080`) o atura el procés en conflicte.
- Error de connexió a MySQL:
	- Revisa `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`.
- `Class ... not found` o autoload:
	- Executa `composer dump-autoload`.
- Frontend no compila:
	- Reinstal·la mòduls: esborra `node_modules` i torna a fer `npm install`.
- En Sail no arrenca contenidor:
	- Comprova que Docker Desktop i WSL2 estiguin actius.

## 8. Criteri d'ús

- Usa Ruta A (Sail) com a opció per defecte per consistència entre màquines.
- Usa Ruta B (nativa) només si Docker no és viable al teu equip.
