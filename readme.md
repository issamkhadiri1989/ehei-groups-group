# Makefile - Commandes Docker du projet

Ce projet utilise un **Makefile** pour simplifier l'utilisation de Docker et éviter de taper de longues commandes `docker compose`.

Au lieu d'écrire :

```bash
docker compose up -d
````

vous pouvez simplement écrire :

```bash
make up
```

# Prérequis

* Docker
* Docker Compose
* Make

---



# Commandes disponibles

## Démarrage du projet

### `make start`

Démarre les conteneurs Docker en arrière-plan.

```bash
make start
```

Commande exécutée :

```bash
docker compose up -d --no-recreate --remove-orphans
```

* `--no-recreate` : ne recrée pas les conteneurs existants
* `--remove-orphans` : supprime les conteneurs non utilisés

---

### `make up`

Démarre les conteneurs en arrière-plan.

```bash
make up
```

Commande exécutée :

```bash
docker compose up -d
```

---

## Arrêt des conteneurs

### `make stop`

Arrête les conteneurs sans les supprimer.

```bash
make stop
```

Commande exécutée :

```bash
docker compose stop
```

---

### `make down`

Arrête et supprime les conteneurs.

```bash
make down
```

Commande exécutée :

```bash
docker compose down
```

---

### `make restart`

Redémarre les conteneurs.

```bash
make restart
```

Equivalent à :

```bash
make stop
make start
```

---

## Reconstruction des conteneurs

### `make build`

Reconstruit les images Docker.

```bash
make build
```

Commande exécutée :

```bash
docker compose build
```

---

### `make recreate`

Recrée complètement les conteneurs.

```bash
make recreate
```

Equivalent à :

```bash
make down
docker compose up -d --force-recreate
```

---

## Nettoyage Docker

### `make clear`

Supprime les ressources Docker inutilisées.

```bash
make clear
```

Commandes exécutées :

```bash
docker compose down
docker system prune
docker builder prune
docker image prune
docker container prune
```

⚠️ Cette commande peut supprimer :

* images inutilisées
* conteneurs arrêtés
* cache de build

---

## Gestion globale des conteneurs

### `make stop-all`

Arrête **tous les conteneurs Docker actifs sur la machine**.

```bash
make stop-all
```

Commande exécutée :

```bash
docker stop $(docker ps -aq)
```

⚠️ À utiliser avec précaution.

---

## Accès au conteneur PHP

### `make enter`

Ouvre un terminal dans le conteneur PHP.

```bash
make enter
```

Commande exécutée :

```bash
docker compose exec php bash
```

---

## Création du projet Symfony

### `make create-project`

Crée un nouveau projet **Symfony 8 Skeleton** dans le conteneur.

```bash
make create-project
```

Commande exécutée :

```bash
docker compose exec php composer create-project symfony/skeleton:8.0.* .
```

---

## Installation du projet

### `make install`

Installe les dépendances et configure le projet.

```bash
make install
```

Commandes exécutées :

```bash
docker compose exec php composer install
docker compose exec php chmod -R 777 .
docker compose exec php php bin/console doctrine:database:create
docker compose exec php sh -c "rm .php-cs-fixer.php && cp .php-cs-fixer.dist.php .php-cs-fixer.php"
```

Actions effectuées :

1. Installation des dépendances Composer
2. Mise à jour des permissions
3. Création de la base de données
4. Configuration de PHP CS Fixer

---

## Correction automatique du code

### `make fix`

Lance **PHP CS Fixer** pour corriger automatiquement le style du code.

```bash
make fix
```

Commande exécutée :

```bash
docker compose exec php ./vendor/bin/php-cs-fixer fix src
```

---

# Workflow recommandé

Pour installer le  pour la première fois :

```bash
make build
make up
make install
```

Pour travailler quotidiennement :

```bash
make start
```

Pour arrêter :

```bash
make stop
```

