# Environnement Docker pour Magento 2.4.2

## 📋 Prérequis

- Docker version 28.5.1+ ✅
- Docker Compose version v2.40.2+ ✅

## 🏗️ Architecture

L'environnement Docker comprend les services suivants :

| Service | Version | Port | Description |
|---------|---------|------|-------------|
| **Nginx** | 1.21-alpine | 80, 443 | Serveur web |
| **PHP-FPM** | 7.4 | 9000 | Moteur PHP avec toutes les extensions Magento |
| **MySQL** | 8.0 | 3306 | Base de données |
| **Elasticsearch** | 7.9.3 | 9200, 9300 | Moteur de recherche (requis pour Magento 2.4.x) |
| **Redis** | 6-alpine | 6379 | Cache et sessions |
| **MailHog** | latest | 1025, 8025 | Serveur SMTP de test |

## 🚀 Installation et démarrage

### 1. Construire et démarrer les conteneurs

```bash
docker-compose up -d --build
```

### 2. Vérifier que tous les services sont démarrés

```bash
docker-compose ps
```

Tous les services doivent avoir le statut "Up".

### 3. Installer les dépendances Composer (si nécessaire)

```bash
docker-compose exec php composer install
```

### 4. Configuration de Magento

Si c'est une nouvelle installation, créez le fichier `app/etc/env.php` :

```bash
docker-compose exec php bin/magento setup:install \
  --base-url=http://localhost/ \
  --db-host=mysql \
  --db-name=magento \
  --db-user=magento \
  --db-password=magento \
  --admin-firstname=Admin \
  --admin-lastname=User \
  --admin-email=admin@example.com \
  --admin-user=admin \
  --admin-password=Admin123! \
  --language=fr_FR \
  --currency=EUR \
  --timezone=Europe/Paris \
  --use-rewrites=1 \
  --search-engine=elasticsearch7 \
  --elasticsearch-host=elasticsearch \
  --elasticsearch-port=9200 \
  --cache-backend=redis \
  --cache-backend-redis-server=redis \
  --cache-backend-redis-db=0 \
  --page-cache=redis \
  --page-cache-redis-server=redis \
  --page-cache-redis-db=1 \
  --session-save=redis \
  --session-save-redis-host=redis \
  --session-save-redis-db=2
```

Si vous avez déjà un fichier `app/etc/env.php`, mettez à jour les configurations :

```php
// Database
'db' => [
    'connection' => [
        'default' => [
            'host' => 'mysql',
            'dbname' => 'magento',
            'username' => 'magento',
            'password' => 'magento',
        ]
    ]
],

// Elasticsearch
'system' => [
    'default' => [
        'catalog' => [
            'search' => [
                'elasticsearch7_server_hostname' => 'elasticsearch',
                'elasticsearch7_server_port' => '9200',
            ]
        ]
    ]
],

// Redis Cache
'cache' => [
    'frontend' => [
        'default' => [
            'backend' => 'Cm_Cache_Backend_Redis',
            'backend_options' => [
                'server' => 'redis',
                'port' => '6379',
                'database' => '0',
            ]
        ],
        'page_cache' => [
            'backend' => 'Cm_Cache_Backend_Redis',
            'backend_options' => [
                'server' => 'redis',
                'port' => '6379',
                'database' => '1',
            ]
        ]
    ]
],

// Redis Sessions
'session' => [
    'save' => 'redis',
    'redis' => [
        'host' => 'redis',
        'port' => '6379',
        'database' => '2',
    ]
]
```

### 5. Définir les permissions

```bash
docker-compose exec php chown -R www-data:www-data var generated pub/static pub/media app/etc
docker-compose exec php chmod -R 777 var generated pub/static pub/media app/etc
```

### 6. Compiler et déployer

```bash
# Mode développeur
docker-compose exec php bin/magento deploy:mode:set developer

# Compilation
docker-compose exec php bin/magento setup:upgrade
docker-compose exec php bin/magento setup:di:compile
docker-compose exec php bin/magento setup:static-content:deploy -f fr_FR

# Vider les caches
docker-compose exec php bin/magento cache:flush
```

## 🌐 Accès aux services

- **Site web** : http://localhost
- **Admin Magento** : http://localhost/admin
- **MailHog (emails)** : http://localhost:8025
- **Elasticsearch** : http://localhost:9200

## 🛠️ Commandes utiles

### Accéder au conteneur PHP

```bash
docker-compose exec php bash
```

### Voir les logs

```bash
# Tous les services
docker-compose logs -f

# Service spécifique
docker-compose logs -f php
docker-compose logs -f nginx
docker-compose logs -f mysql
```

### Arrêter les conteneurs

```bash
docker-compose stop
```

### Redémarrer les conteneurs

```bash
docker-compose restart
```

### Supprimer les conteneurs (conserve les volumes)

```bash
docker-compose down
```

### Supprimer les conteneurs ET les volumes (⚠️ supprime la base de données)

```bash
docker-compose down -v
```

### Vider les caches Magento

```bash
docker-compose exec php bin/magento cache:clean
docker-compose exec php bin/magento cache:flush
```

### Réindexer

```bash
docker-compose exec php bin/magento indexer:reindex
```

### Importer une base de données

```bash
docker-compose exec -T mysql mysql -umagento -pmagento magento < backup.sql
```

### Exporter une base de données

```bash
docker-compose exec mysql mysqldump -umagento -pmagento magento > backup.sql
```

## 📦 Extensions PHP installées

Toutes les extensions requises par Magento 2.4.2 sont installées :

- bcmath
- ctype
- curl
- dom
- fileinfo
- gd
- hash
- iconv
- intl
- json
- libxml
- mbstring
- openssl
- pcre
- pdo_mysql
- simplexml
- soap
- sockets
- sodium
- tokenizer
- xmlwriter
- xsl
- zip
- zlib

## 🔧 Configuration PHP

Les paramètres PHP sont optimisés pour Magento dans `docker/php/php.ini` :

- `memory_limit = 2G`
- `max_execution_time = 18000`
- `upload_max_filesize = 64M`
- OPcache activé
- MailHog configuré pour les emails

## 🗄️ Configuration MySQL

MySQL 8.0 est configuré avec :

- `innodb_buffer_pool_size = 1G`
- `max_allowed_packet = 64M`
- Charset UTF-8mb4
- Slow query log activé

## 🔍 Elasticsearch

Elasticsearch 7.9.3 est configuré en mode single-node avec :

- Sécurité désactivée (développement local)
- Mémoire Java : 512MB

## 🐛 Dépannage

### Les conteneurs ne démarrent pas

```bash
# Vérifier les logs
docker-compose logs

# Reconstruire les images
docker-compose down
docker-compose up -d --build
```

### Erreur de permissions

```bash
docker-compose exec php chown -R www-data:www-data /var/www/html
docker-compose exec php chmod -R 777 var generated pub/static pub/media app/etc
```

### Elasticsearch ne démarre pas

Augmentez la limite de mémoire virtuelle sur votre système hôte :

```bash
sudo sysctl -w vm.max_map_count=262144
```

Pour rendre ce changement permanent :

```bash
echo "vm.max_map_count=262144" | sudo tee -a /etc/sysctl.conf
```

### Le site est lent

- Vérifiez que le mode développeur est activé
- Désactivez les modules non utilisés
- Augmentez les ressources Docker (CPU/RAM)

## 📝 Notes importantes

1. **Mode développeur** : Par défaut, le site est en mode développeur pour faciliter le développement
2. **MailHog** : Tous les emails sont capturés par MailHog (accessible sur http://localhost:8025)
3. **Volumes** : Les données MySQL, Elasticsearch et Redis sont persistées dans des volumes Docker
4. **Performance** : Utilisez `:cached` pour les volumes sous macOS pour améliorer les performances

## 🔐 Credentials par défaut

- **MySQL** :
  - Host: `mysql` (ou `localhost:3306` depuis l'hôte)
  - Database: `magento`
  - User: `magento`
  - Password: `magento`
  - Root password: `root`

- **Magento Admin** (après installation) :
  - URL: http://localhost/admin
  - User: `admin`
  - Password: `Admin123!`

## 📚 Ressources

- [Documentation Magento 2.4.2](https://experienceleague.adobe.com/docs/commerce-operations/installation-guide/system-requirements.html)
- [Magento DevDocs](https://devdocs.magento.com/)
