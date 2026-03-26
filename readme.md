# 🏃‍♂️ IFOSUP Running Club - Wavre
> **Plateforme web dynamique de gestion pour club d'athlétisme.**

## 📝 Présentation du Projet
Ce projet a été réalisé dans le cadre de ma formation en développement Web. Il s'agit d'un outil complet permettant de gérer la communication d'un club de course à pied (News, Résultats, Calendrier) tout en intégrant des fonctionnalités modernes comme une IA et un mini-jeu.

---

## 🚀 Fonctionnalités Majeures

### 👤 Espace Membre & Sécurité
* **Authentification complète** : Inscription (`inscription.php`, `Register.php`) et Connexion (`login.php`) sécurisées.
* **Gestion de Session** : Déconnexion propre (`logout.php`) et protection des pages sensibles.
* **RGPD & Confidentialité** : Pages dédiées aux cookies (`cookies.php`), à la confidentialité (`privacy.php`) et aux conditions d'utilisation (`terms.php`).

### 📊 Gestion Administrative (Système CRUD)
* **Dashboard Admin** : Un panneau de contrôle centralisé (`admin_panel.php`).
* **Gestion de Contenu** : Interfaces dédiées pour administrer les actualités (`adminnews.php`) et les résultats sportifs (`adminresults.php`).
* **Actions Dynamiques** : Formulaires d'ajout (`ajouter.php`) et de modification (`modifier.php`) de données.

### 🤖 Innovation & Divertissement
* **Assistant IA Gemini** : Chatbot intelligent (`chat_ia.php` et `ia_handler.php`) pour conseiller les coureurs sur leurs entraînements.
* **Expérience Gaming** : Intégration d'un module de jeu (`game.php`) avec sauvegarde des scores (`save_score.php`).
* **Calendrier Interactif** : Consultation des courses à venir (`calendrier.php`).

---

## 🛠️ Stack Technique
* **Backend** : PHP 8.x (Architecture modulaire).
* **Base de Données** : MySQL / MariaDB (Accès sécurisé via **PDO**).
* **Frontend** : HTML5, CSS3, JavaScript (ES6+ / Fetch API).
* **Configuration** : Fichiers de config (`config.php`) et gestion des erreurs (`error.php`).
* **Serveur** : Configuration via `.htaccess`.

---
## 💾 Procédure d'Installation

Pour faire fonctionner ce projet sur votre machine locale (WampServer, Laragon ou XAMPP) :

1. **Déploiement** : 
   Copiez le dossier `projet-progressif` dans votre répertoire racine (ex: `www/` ou `htdocs/`).

2. **Base de Données** : 
   - Créez une base de données MySQL nommée `examen` (ou celle définie dans `connexion.php`).
   - Importez le script SQL pour générer les tables `courreur`, `news` et `utilisateurs`.

3. **Configuration** :
   - Modifiez les identifiants de connexion dans `connexion.php` ou/et `config.php`.
   - Insérez votre clé API Google Gemini dans le fichier `ia_handler.php`.

4. **Lancement** : 
   Accédez au projet via `http://localhost/projet-progressif/index.php`.

---
## 📂 Structure du Projet (Arborescence)
```text
/projet-progressif
│
├── 📁 css/                 # Feuilles de style globaux (reset, print)
├── 📁 immages/             # Logos et ressources graphiques
├── 📁 Musique/             # Fichiers audio pour l'ambiance du site
│
├── index.php               # Page d'accueil principale
├── News.php / Results.php  # Pages publiques (News et Résultats)
├── Contact.php             # Formulaire de contact
│
├── admin_panel.php         # Tableau de bord Administration
├── adminnews.php           # Gestion des news (Admin)
├── adminresults.php        # Gestion des résultats (Admin)
├── ajouter.php             # Script d'ajout de données
├── modifier.php            # Script de modification de données
│
├── chat_ia.php             # Interface de discussion avec l'IA
├── ia_handler.php          # Traitement API Gemini (Backend)
│
├── game.php                # Interface du mini-jeu
├── save_score.php          # Sauvegarde des scores en base de données
│
├── Register.php            # Formulaire d'inscription
├── login.php / logout.php  # Connexion et Déconnexion
│
├── connexion.php           # Initialisation PDO
├── config.php              # Fichier de configuration de la db
│
├── .htaccess               # Configuration serveur
├── robots.txt              # Gestion de l'indexation
└── readme.md               # Documentation du projet (ce fichier)


