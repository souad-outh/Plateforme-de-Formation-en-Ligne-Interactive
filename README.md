# Plateforme de Formation en Ligne Interactive

[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)
[![Laravel Version](https://img.shields.io/badge/Laravel-10.x-orange.svg)](https://laravel.com)

Plateforme d'e-learning interactive avec génération automatique de quiz par IA et surveillance d'examens par reconnaissance faciale.

## Fonctionnalités Clés

- 🎓 **Gestion des cours** : Création, modification et organisation de contenus pédagogiques
- 🤖 **Génération automatique** de quiz et exercices par IA
- 📸 **Reconnaissance faciale** pour sécuriser les examens
- 📊 **Tableaux de bord** analytiques pour étudiants et enseignants
- 🔐 **Système d'authentification** sécurisé avec rôles (étudiant, enseignant, admin)

## Technologies Utilisées

- **Backend** : Laravel 10
- **Frontend** : Blade, Tailwind CSS, Alpine.js
- **Base de données** : MySQL
- **IA** : Python (pour la génération de quiz - intégration via API)
- **Reconnaissance faciale** : Face-API.js ou OpenCV
- **Déploiement** : Docker (optionnel)

## Prérequis

- PHP 8.1+
- Composer 2.0+
- MySQL 5.7+
- Serveur web (Apache/Nginx)

## Installation

1. **Cloner le dépôt** :
   ```bash
   git clone https://github.com/souad-outh/Plateforme-de-Formation-en-Ligne-Interactive.git
   cd Plateforme-de-Formation-en-Ligne-Interactive
   ```

2. **Installer les dépendances** :
   ```bash
   composer install
   npm install
   ```

3. **Configurer l'environnement** :
   - Copier `.env.example` vers `.env`
   - Configurer les variables d'environnement (DB, Mail, etc.)
   - Générer la clé d'application :
     ```bash
     php artisan key:generate
     ```

4. **Migrer la base de données** :
   ```bash
   php artisan migrate --seed
   ```

5. **Compiler les assets** :
   ```bash
   npm run build
   ```

6. **Lancer le serveur** :
   ```bash
   php artisan serve
   ```

## Structure du Projet

```
app/
├── Http/
│   ├── Controllers/      # Contrôleurs
│   ├── Middleware/       # Middlewares
│   └── Requests/         # Form requests
resources/
├── views/               # Templates Blade
├── js/                  # JavaScript
└── css/                 # Styles
routes/
├── web.php              # Routes web
└── api.php              # Routes API
database/
├── migrations/          # Migrations
└── seeders/             # Seeders
config/                  # Fichiers de configuration
public/                  # Assets compilés
```

## Déploiement

Pour déployer en production :

1. Configurer `.env` pour l'environnement de production
2. Optimiser l'application :
   ```bash
   php artisan optimize
   ```
3. Mettre en place la planification des tâches (cron) :
   ```bash
   * * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
   ```

## Contribution

Les contributions sont les bienvenues ! Voici comment participer :

1. Forker le projet
2. Créer une branche (`git checkout -b feature/AmazingFeature`)
3. Commiter vos changements (`git commit -m 'Add some AmazingFeature'`)
4. Pousser vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrir une Pull Request

## License

Distribué sous licence MIT. Voir `LICENSE` pour plus d'informations.

## Contact

Souad Outharout - [outharoutsouad@gmail.com](mailto:outharoutsouad@gmail.com)  
Lien du projet : [https://github.com/souad-outh/Plateforme-de-Formation-en-Ligne-Interactive](https://github.com/souad-outh/Plateforme-de-Formation-en-Ligne-Interactive)
