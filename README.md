# 🎮 Ingress Intel

Sistema de gestió d'intel·ligència per a jugadors d'Ingress. Permet fer un seguiment d'agents de la Resistència i dels Il·luminats, les seves ubicacions, interaccions, relacions i més.

## 📋 Requisits

- Docker
- Docker Compose
- Git (opcional)

## 🚀 Instal·lació Ràpida

### 1. Clonar o descarregar el projecte

```bash
# Si tens git:
git clone <url-del-repositori>
cd ingress-intel

# O descomprimeix l'arxiu ZIP i navega al directori
```

### 2. Executar l'script d'instal·lació

```bash
chmod +x install.sh
./install.sh
```

L'script farà:
- ✅ Construir les imatges Docker
- ✅ Crear la base de dades PostgreSQL
- ✅ Instal·lar Laravel
- ✅ Instal·lar Filament Admin Panel
- ✅ Executar migracions
- ✅ Crear dades inicials
- ✅ Demanar-te crear un usuari administrador

### 3. Accedir a l'aplicació

Un cop completada la instal·lació:

- **Aplicació web**: http://localhost:8080
- **Panel d'Administració**: http://localhost:8080/admin

Inicia sessió amb l'usuari que has creat durant la instal·lació.

## 📊 Estructura de la Base de Dades

### Taules Principals

#### 🧑‍💼 **Agents**
Informació bàsica dels agents (tant Resistència com Il·luminats):
- Nom d'agent (codename)
- Nom real (opcional)
- Facció actual
- Nivell (1-16)
- Contacte (Telegram, email, telèfon)
- Estat (actiu/inactiu)
- Notes

#### 🔄 **Canvis de Facció**
Historial de canvis de facció d'un agent:
- Agent
- Facció origen → facció destí
- Data del canvi
- Motiu
- Notes

#### 📍 **Zones de Joc**
Zones habituals on juga un agent:
- Nom de la zona
- Coordenades GPS (centre i polígon)
- Ciutat/Província/País
- Freqüència de joc (diari, setmanal, mensual, ocasional)

#### 🏠 **Portals Sofà**
Portals als que té accés sense moure's de casa/feina:
- Nom del portal
- Coordenades GPS
- Tipus (casa, feina, cap de setmana, vacances, altres)
- Confirmació (confirmat o suposició)

#### 💬 **Interaccions**
Registre d'interaccions amb agents:
- Agent implicat
- Tipus d'interacció (anomalia, conflicte, operació conjunta, etc.)
- Data i hora
- Ubicació
- Descripció i resultat
- Nivell d'impacte (1-5)
- Altres agents involucrats

#### 👤 **Comptes Secundaris**
Comptes secundaris (multis) detectats:
- Agent principal
- Nom del compte secundari
- Facció
- Estat (actiu, inactiu, banejat, sospitós)
- Certesa (confirmat, molt probable, probable, sospitós)
- Evidències

#### 👥 **Relacions entre Agents**
Relacions familiars, d'amistat, veïnatge, etc.:
- Agent A → Agent B
- Tipus de relació (parella, familiar, amic, company de feina, veí, etc.)
- Certesa
- Des de quan es coneixen
- Notes

## 🎯 Funcionalitats

### Panel d'Administració (Filament)

El panel d'administració ofereix:

1. **Gestió d'Agents**
   - Crear, editar, eliminar agents
   - Filtrar per facció i estat
   - Cercar per nom o Telegram
   - Vista completa amb tota la informació

2. **Zones i Portals**
   - Gestió de zones de joc
   - Registre de portals sofà
   - Mapa visual (pròximament)

3. **Interaccions**
   - Registre d'events i interaccions
   - Tipus configurables
   - Historial complet per agent

4. **Relacions**
   - Xarxa de relacions entre agents
   - Tipus de relacions personalitzables
   - Vista de connexions

5. **Intel·ligència**
   - Comptes secundaris
   - Canvis de facció
   - Comportaments sospitosos

## 🛠️ Comandes Útils

### Gestió de contenidors

```bash
# Iniciar tots els serveis
docker-compose up -d

# Aturar tots els serveis
docker-compose down

# Veure logs en temps real
docker-compose logs -f

# Reiniciar un servei específic
docker-compose restart app

# Accedir a la shell de Laravel
docker-compose exec app sh
```

### Comandes Laravel

```bash
# Executar migracions
docker-compose exec app php artisan migrate

# Executar seeders
docker-compose exec app php artisan db:seed

# Crear un nou usuari admin
docker-compose exec app php artisan make:filament-user

# Netejar cache
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan view:clear

# Accedir a Tinker (consola interactiva)
docker-compose exec app php artisan tinker
```

### Base de dades

```bash
# Accedir a PostgreSQL
docker-compose exec postgres psql -U ingress_user -d ingress_intel

# Backup de la base de dades
docker-compose exec postgres pg_dump -U ingress_user ingress_intel > backup.sql

# Restaurar backup
docker-compose exec -T postgres psql -U ingress_user ingress_intel < backup.sql
```

## 🌍 Desplegament a Producció

### Opcions gratuïtes recomanades:

1. **Railway.app** (Recomanat)
   - Suport natiu per Docker
   - PostgreSQL inclòs
   - Domini gratuït

2. **Render.com**
   - Plans gratuïts disponibles
   - Base de dades PostgreSQL
   - Fàcil desplegament

3. **Fly.io**
   - Capa gratuïta generosa
   - Bon rendiment
   - Documentació excellent

### Configuració per producció:

1. Canvia les credencials a `.env`:
```env
APP_ENV=production
APP_DEBUG=false
DB_PASSWORD=<contrasenya-segura>
```

2. Optimitza l'aplicació:
```bash
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache
```

## 📁 Estructura del Projecte

```
ingress-intel/
├── app/
│   ├── Filament/
│   │   └── Resources/      # Recursos Filament (UI)
│   └── Models/             # Models Eloquent
├── database/
│   ├── migrations/         # Migracions de BD
│   └── seeders/           # Dades inicials
├── docker-compose.yml     # Configuració Docker
├── Dockerfile            # Imatge PHP/Laravel
├── nginx/
│   └── nginx.conf        # Configuració Nginx
├── install.sh           # Script d'instal·lació
└── README.md           # Aquest fitxer
```

## 🔒 Seguretat

⚠️ **IMPORTANT per entorns de producció:**

1. Canvia totes les contrasenyes per defecte
2. Activa HTTPS
3. Configura firewalls adequats
4. Fes backups regulars
5. No exposis ports de PostgreSQL a internet
6. Revisa els permisos dels fitxers

## 🐛 Resolució de Problemes

### El contenidor no s'inicia

```bash
# Veure logs d'errors
docker-compose logs app

# Reconstruir des de zero
docker-compose down -v
docker-compose build --no-cache
./install.sh
```

### Error de connexió a la base de dades

```bash
# Verificar que PostgreSQL està actiu
docker-compose ps

# Reiniciar PostgreSQL
docker-compose restart postgres

# Verificar configuració .env
cat laravel/.env | grep DB_
```

### Problemes de permisos

```bash
# Arreglar permisos de directoris
sudo chown -R $USER:$USER laravel
chmod -R 755 laravel/storage laravel/bootstrap/cache
```

## 📝 Notes Addicionals

- Les dades es guarden en volums Docker (persistents)
- El port 8080 s'utilitza per evitar conflictes amb altres serveis
- PostgreSQL està accessible només dins la xarxa Docker
- Redis s'utilitza per sessions i cache

## 🤝 Suport

Si tens problemes:

1. Revisa els logs: `docker-compose logs -f`
2. Verifica l'estat dels contenidors: `docker-compose ps`
3. Comprova la documentació de Laravel: https://laravel.com/docs
4. Comprova la documentació de Filament: https://filamentphp.com/docs

## 📜 Llicència

Aquest projecte és privat i està destinat només a ús personal.

---

**Fet amb ❤️ per a la comunitat Ingress**
