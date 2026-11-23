# MergeYGol App MVP

Aquest directori conté un MVP funcional de MergeYGol amb estructura Laravel:

- Jornada i llistat de partits.
- Detall de partit amb timeline.
- Prediccio 1X2 amb un vot per usuari i partit.
- Comentaris per usuaris autenticats.
- Registre d'esdeveniments per admin.

## 1. Integracio en un projecte Laravel

Si encara no tens base Laravel creada, fes:

1. `composer create-project laravel/laravel .`
2. Copia/sobrescriu els fitxers d'aquest directori dins el projecte.

Si ja tens Laravel creat, aplica directament aquests fitxers.

## 2. Canvis obligatoris addicionals

### 2.1. Registrar middleware admin

A `bootstrap/app.php`, dins del bloc de middleware, afegeix l'alias:

- `admin` => `App\Http\Middleware\EnsureAdmin::class`

### 2.2. Afegir is_admin al model User

A `app/Models/User.php`, inclou `is_admin` a `$fillable` o gestiona-ho amb `$guarded` segons convencio del projecte.

## 3. Arrencada

1. Configura `.env` amb MySQL.
2. Executa migracions i seeders:
   - `php artisan migrate --seed`
3. Inicia servidor:
   - `php artisan serve`
4. Inicia frontend:
   - `npm install`
   - `npm run dev`

## 4. Credencial admin de demo

- Email: admin@mergeygol.local
- Password: Admin12345!

## 5. Rutes principals

- `/` home jornada
- `/matches/{match}` detall partit
- `POST /matches/{match}/predict` vot 1X2 (auth)
- `POST /matches/{match}/comments` comentaris (auth)
- `POST /admin/matches/{match}/events` esdeveniments (auth + admin)
