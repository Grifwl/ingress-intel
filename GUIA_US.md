# 📖 Guia d'Ús - Ingress Intel

## 📥 Instal·lació

### Pas 1: Descarregar el projecte
Descarrega l'arxiu `ingress-intel.zip` i descomprimeix-lo al teu ordinador.

### Pas 2: Obrir terminal
Obre el terminal (Linux/Mac) o PowerShell/CMD (Windows) i navega al directori:

```bash
cd /ruta/on/has/descomprimit/ingress-intel
```

### Pas 3: Executar instal·lació
```bash
chmod +x install.sh
./install.sh
```

**Nota Windows**: Si estàs a Windows, necessitaràs WSL2 (Windows Subsystem for Linux) o Git Bash.

### Pas 4: Crear usuari administrador
Durant la instal·lació se't demanarà:
- **Name**: El teu nom (ex: Agent001)
- **Email**: El teu email (ex: agent@resistance.com)
- **Password**: Una contrasenya segura

**Guarda aquestes credencials!**

---

## 🚀 Primer Ús

### Accedir a l'aplicació

1. Obre el navegador
2. Vés a: **http://localhost:8080/admin**
3. Introdueix email i contrasenya
4. Fes clic a "Sign in"

---

## 📝 Com Utilitzar l'Aplicació

### 1️⃣ Afegir Agents

**Navegació**: Menú lateral → "Agents" → Botó "New"

**Camps obligatoris**:
- **Nom d'Agent**: El codename al joc (ex: "ResistanceWarrior")
- **Facció Actual**: Resistència o Il·luminats

**Camps opcionals**:
- Nom real (si el coneixes)
- Nivell (1-16)
- Telegram, Email, Telèfon
- Data del primer contacte
- Notes generals

**Consell**: Comença afegint agents de la teva facció i després els enemics més rellevants.

---

### 2️⃣ Definir Zones

**Navegació**: Menú lateral → "Zones" → Botó "New"

**Exemple de zona**:
- **Nom**: "Igualada Centre"
- **Ciutat**: "Igualada"
- **Província**: "Barcelona"
- **País**: "ES"
- **Latitud/Longitud**: Pots obtenir-les de Google Maps (clic dret → coordenades)

**Assignar agents a la zona**:
Al mateix formulari, a la secció "Agents en aquesta zona":
- Fes clic a "+ Add item"
- Selecciona l'agent
- Tria la freqüència (Diari, Setmanal, Mensual, Ocasional)
- Afegeix notes si cal

---

### 3️⃣ Registrar Portals Sofà

**Navegació**: Menú lateral → "Portals" (si el crees)

Els portals sofà són ubicacions on un agent pot jugar sense moure's.

**Tipus de portals**:
- **Casa**: On viu l'agent
- **Feina**: On treballa
- **Cap de setmana**: Casa d'estiueig, segona residència
- **Vacances**: Lloc habitual de vacances
- **Altres**: Qualsevol altre lloc fix

---

### 4️⃣ Registrar Interaccions

**Navegació**: Menú lateral → "Interaccions" → Botó "New"

**Tipus d'interaccions predefinides**:
1. **Anomalia**: Competició oficial
2. **Operació Conjunta**: Ops coordinades amb la teva facció
3. **Conflicte**: Enfrontaments o situacions problemàtiques
4. **Spoof Detectat**: Ús de GPS fals
5. **First Saturday**: Event mensual
6. **Trobada Social**: Quedades informals
7. **Comportament Sospitós**: Activitat a investigar

**Exemple d'interacció**:
```
Agent: EnemyAgent123
Tipus: Spoof Detectat
Títol: Activitat sospitosa a la zona nord
Data: 28/01/2025
Ubicació: Igualada
Descripció: L'agent ha capturat 15 portals en 5 minuts
              en una zona de 2km de radi
Resultat: Reportat a Niantic
Nivell d'Impacte: 4 - Alt
```

---

### 5️⃣ Registrar Comptes Secundaris (Multis)

**Navegació**: Menú lateral → "Secondary Accounts" (si el crees)

Quan detectis que un agent té múltiples comptes:

**Camps importants**:
- **Agent Principal**: Qui controla els comptes
- **Codename**: Nom del compte secundari
- **Estat**: Actiu / Inactiu / Banejat / Sospitós
- **Certesa**: Confirmat / Molt Probable / Probable / Sospitós
- **Evidències**: Descriu què t'ha fet sospitar

---

### 6️⃣ Registrar Relacions entre Agents

**Navegació**: Menú lateral → "Relationships" (si el crees)

**Tipus de relacions predefinides**:
- Parella
- Familiar
- Pare/Mare ↔ Fill/a
- Germans
- Amic/ga
- Company de feina
- Veí/na
- Ex-parella

**Exemple**:
```
Agent A: AgentBlue
Agent B: AgentGreen
Tipus: Parella
Certesa: Confirmat
Des de: 15/06/2023
Notes: Els he vist junts en diverses anomalies
```

---

## 🔍 Funcions de Cerca i Filtrat

### Cercar Agents
1. Vés a "Agents"
2. Utilitza la barra de cerca superior
3. Pots cercar per: nom d'agent, nom real, Telegram

### Filtrar
Fes clic a la icona de filtre (embut) per filtrar per:
- Facció (Resistència / Il·luminats)
- Agent Actiu (Sí / No)

### Ordenar
Fes clic a les capçaleres de columna per ordenar les dades.

---

## 💡 Consells d'Ús

### 1. Seguretat
- ⚠️ Aquesta informació és sensible
- No comparteixis l'accés amb ningú
- Canvia la contrasenya regularment
- Fes backups regularment

### 2. Organització
- Comença pels agents més importants
- Afegeix notes detallades
- Actualitza la informació regularment
- Usa noms descriptius per zones

### 3. Intel·ligència
- Registra patrons de comportament
- Anota horaris habituals
- Documenta canvis sobtats
- Connecta relacions entre agents

### 4. Backups
Executa periòdicament:
```bash
docker-compose exec postgres pg_dump -U ingress_user ingress_intel > backup_$(date +%Y%m%d).sql
```

---

## 🆘 Problemes Comuns

### No puc accedir a http://localhost:8080
**Solució**:
```bash
# Verifica que els contenidors estan actius
docker-compose ps

# Si no ho estan, inicia'ls
docker-compose up -d
```

### He oblidat la contrasenya
**Solució**:
```bash
# Crear nou usuari admin
docker-compose exec app php artisan make:filament-user
```

### Vull eliminar totes les dades i començar de nou
**Solució**:
```bash
docker-compose down -v
./install.sh
```

---

## 📊 Exportar Dades

### Exportar a Excel/CSV
1. A qualsevol taula, fes clic al botó d'exportació (si està disponible)
2. O utilitza el backup SQL i importa'l a una eina d'anàlisi

### Backup complet
```bash
# Backup de base de dades
docker-compose exec postgres pg_dump -U ingress_user ingress_intel > backup.sql

# Restaurar backup
docker-compose exec -T postgres psql -U ingress_user ingress_intel < backup.sql
```

---

## 🎓 Tutorials

### Com investigar un agent sospitós

1. **Crear fitxa de l'agent**
   - Nom, facció, nivell

2. **Definir zones on l'has vist**
   - Afegeix totes les zones on actua

3. **Buscar portals sofà**
   - Observa on captura sovint
   - Afegeix ubicacions sospitoses

4. **Registrar interaccions**
   - Cada vegada que detectis activitat sospitosa
   - Afegeix captures de pantalla a les notes

5. **Buscar relacions**
   - Comprova si coneix altres agents
   - Busca patrons de comportament coordinat

6. **Comptes secundaris**
   - Si detectes multis, registra'ls
   - Documenta les evidències

---

## 📞 Suport

Si tens problemes tècnics:
1. Consulta el README.md
2. Revisa els logs: `docker-compose logs -f`
3. Verifica l'estat: `docker-compose ps`

---

**Bona caça, Agent! 🎮**
