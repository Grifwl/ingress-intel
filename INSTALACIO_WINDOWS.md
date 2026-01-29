# 🪟 Guia d'Instal·lació per Windows

## 📋 Resum Ràpid

1. Instal·lar Docker Desktop
2. Descomprimir el projecte
3. Configurar Git
4. Executar l'instal·lador
5. Accedir a l'aplicació

---

## Pas 1: Instal·lar Docker Desktop

### 1.1 Obre PowerShell com a Administrador

- Prem `Win + X`
- Selecciona "Windows PowerShell (Administrador)" o "Terminal (Administrador)"

### 1.2 Executa la comanda d'instal·lació

```powershell
winget install Docker.DockerDesktop
```

### 1.3 Reinicia l'ordinador

**Això és important!** Docker necessita un reinici per funcionar correctament.

### 1.4 Obre Docker Desktop

- Busca "Docker Desktop" al menú d'inici
- Obre'l
- Accepta els termes i condicions
- Espera que aparegui "Docker Desktop is running" (icona verda a baix)

**Si et demana activar WSL 2:**
- Accepta i segueix les instruccions
- Potser necessitaràs reiniciar un altre cop

---

## Pas 2: Preparar el Projecte

### 2.1 Crear el directori de repos

Obre PowerShell (ja no cal que sigui com a administrador):

```powershell
# Crear el directori si no existeix
mkdir C:\Users\Joan\Documents\repos
```

### 2.2 Descomprimir el projecte

Opció A - **Manual** (més fàcil):
1. Descarrega `ingress-intel.zip`
2. Clic dret → "Extreure tot..."
3. Tria la ubicació: `C:\Users\Joan\Documents\repos`
4. Hauria de quedar: `C:\Users\Joan\Documents\repos\ingress-intel`

Opció B - **PowerShell**:
```powershell
# Navega al directori de descàrregues (ajusta si és diferent)
cd C:\Users\Joan\Downloads

# Descomprimeix el ZIP
Expand-Archive -Path ingress-intel.zip -DestinationPath C:\Users\Joan\Documents\repos
```

### 2.3 Navega al directori del projecte

```powershell
cd C:\Users\Joan\Documents\repos\ingress-intel
```

---

## Pas 3: Configurar Git

### 3.1 Inicialitzar el repositori

```powershell
# Estant a C:\Users\Joan\Documents\repos\ingress-intel
git init
```

### 3.2 Afegir els fitxers

```powershell
git add .
```

### 3.3 Fer el primer commit

```powershell
git commit -m "Initial commit: Ingress Intel project setup"
```

**Si et dona error de configuració de Git:**
```powershell
# Configura el teu nom i email (només cal fer-ho un cop)
git config --global user.name "El Teu Nom"
git config --global user.email "el.teu.email@example.com"

# Després torna a fer el commit
git commit -m "Initial commit: Ingress Intel project setup"
```

---

## Pas 4: Executar la Instal·lació

### 4.1 Assegura't que Docker Desktop està executant-se

Mira la icona de Docker Desktop a la safata del sistema:
- ✅ **Verda**: Tot correcte, continua
- ❌ **Vermella** o no apareix: Obre Docker Desktop i espera que estigui "running"

### 4.2 Executa l'script d'instal·lació

```powershell
# Estant a C:\Users\Joan\Documents\repos\ingress-intel
.\install.ps1
```

**Si et dona error de "scripts desactivats":**

```powershell
# Permet l'execució d'scripts (només cal fer-ho un cop)
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser

# Torna a executar l'instal·lador
.\install.ps1
```

### 4.3 Crear l'usuari administrador

Durant la instal·lació, se't demanarà:

```
Name: Joan
Email: joan@ingress.local
Password: *********
```

**IMPORTANT**: Guarda aquestes credencials en un lloc segur!

### 4.4 Espera que acabi

L'instal·lació pot trigar entre 5-15 minuts, depenent de la teva connexió a internet.

---

## Pas 5: Accedir a l'Aplicació

### 5.1 Obre el navegador

Ves a: **http://localhost:8080/admin**

### 5.2 Inicia sessió

- Email: El que has introduït abans
- Password: La contrasenya que has creat

### 5.3 Comença a utilitzar l'aplicació! 🎉

---

## 🛠️ Comandes Útils per Windows

### Gestió bàsica

```powershell
# Anar al directori del projecte
cd C:\Users\Joan\Documents\repos\ingress-intel

# Iniciar l'aplicació
docker-compose up -d

# Aturar l'aplicació
docker-compose down

# Veure logs en temps real
docker-compose logs -f

# Veure l'estat dels contenidors
docker-compose ps
```

### Gestió de la base de dades

```powershell
# Crear un backup
docker-compose exec postgres pg_dump -U ingress_user ingress_intel > backup.sql

# Restaurar un backup
Get-Content backup.sql | docker-compose exec -T postgres psql -U ingress_user ingress_intel

# Accedir a la consola de PostgreSQL
docker-compose exec postgres psql -U ingress_user -d ingress_intel
```

### Crear un nou usuari administrador

```powershell
docker-compose exec app php artisan make:filament-user
```

---

## 🐛 Problemes Comuns i Solucions

### Error: "docker-compose: command not found"

**Solució**:
```powershell
# Verifica que Docker Desktop està executant-se
docker --version

# Si funciona, prova:
docker compose up -d
# (sense guió, és la nova versió)
```

### Error: "Permission denied"

**Solució**:
- Assegura't que Docker Desktop està executant-se
- Reinicia Docker Desktop
- Obre PowerShell com a Administrador

### No puc accedir a http://localhost:8080

**Solució**:
```powershell
# Verifica que els contenidors estan actius
docker-compose ps

# Si no ho estan:
docker-compose up -d

# Comprova els logs per errors
docker-compose logs nginx
docker-compose logs app
```

### WSL 2 installation is incomplete

**Solució**:
1. Obre PowerShell com a Administrador
2. Executa:
```powershell
wsl --install
```
3. Reinicia l'ordinador
4. Torna a obrir Docker Desktop

---

## 📝 Resum de les Ubicacions

| Element | Ubicació |
|---------|----------|
| Projecte | `C:\Users\Joan\Documents\repos\ingress-intel` |
| Codi Laravel | `C:\Users\Joan\Documents\repos\ingress-intel\laravel` |
| Backups | `C:\Users\Joan\Documents\repos\ingress-intel` |
| Aplicació web | http://localhost:8080 |
| Admin panel | http://localhost:8080/admin |

---

## 🎯 Checklist d'Instal·lació

- [ ] Docker Desktop instal·lat
- [ ] Ordinador reiniciat
- [ ] Docker Desktop executant-se (icona verda)
- [ ] Projecte descomprimit a `C:\Users\Joan\Documents\repos\ingress-intel`
- [ ] Git inicialitzat (`git init`)
- [ ] Primer commit fet
- [ ] Script `install.ps1` executat amb èxit
- [ ] Usuari administrador creat
- [ ] Aplicació accessible a http://localhost:8080/admin
- [ ] Puc iniciar sessió

---

## 🚀 Següents Passos

Un cop tot estigui instal·lat:

1. Consulta **GUIA_US.md** per aprendre a utilitzar l'aplicació
2. Llegeix **README.md** per funcionalitats avançades
3. Comença afegint els teus primers agents!

---

**Bon joc, Agent! 🎮**
