## WalletWave

Cette API est construite avec **Laravel** et permet l'authentification des utilisateurs, la gestion des rôles et des transactions financières. Elle permet aux utilisateurs de s'inscrire, se connecter, et effectuer des transactions telles que des transferts d'argent, des dépôts et des retraits. L'API utilise **Laravel Sanctum** pour l'authentification basée sur des tokens.

## Installation

### Étapes pour installer le projet

1. Clonez le repository dans votre répertoire local :

    ```bash
    git clone https://github.com/kaoutarlabjioui/WalletWave.git

    ```

2. Allez dans le répertoire du projet :

    ```bash
    cd WalletWave
    ```

3. Installez les dépendances via Composer :

    ```bash
    composer install
    ```

4. Créez une copie du fichier `.env.example` et renommez-le en `.env` :

    ```bash
    cp .env.example .env
    ```

5. Générez la clé d'application :

    ```bash
    php artisan key:generate
    ```

6. Configurez la base de données dans le fichier `.env`. Assurez-vous de bien indiquer les bonnes informations pour votre base de données (par exemple, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).

7. Exécutez les migrations pour configurer la base de données :

    ```bash
    php artisan migrate
    ```

8. Démarrez le serveur de développement :
    ```bash
    php artisan serve
    ```

> Votre API devrait maintenant être accessible à `http://127.0.0.1:8000`.

## Points de terminaison API

### 1. Points de terminaison d'authentification des utilisateurs

#### inscription d'un nouvel utilisateur

Permet de créer un nouveau compte utilisateur dans le système.
**Endpoint**: POST /api/register
Permet d'enregistrer un nouvel utilisateur avec les informations suivantes :

-   `name` (obligatoire)
-   `email` (obligatoire, doit être unique)
-   `password` (obligatoire, minimum 8 caractères)
-   `role` (obligatoire)

Exemple de requête:

```bash
jsonCopier{
  "name": "Jean Dupont",
  "email": "jean.dupont@exemple.fr",
  "password": "motdepasse123",
  "role": "utilisateur"
}
```

Réponse réussie (Code 201 Created):

```bash
jsonCopier{
  "status": "success",
  "message": "Utilisateur créé avec succès",
  "data": {
    "user": {
      "id": 1,
      "name": "Jean Dupont",
      "email": "jean.dupont@exemple.fr",
      "role": "utilisateur",
      "created_at": "2025-03-17T12:00:00.000000Z",
      "updated_at": "2025-03-17T12:00:00.000000Z"
    },
    "wallet":{
     "id": 1,
     "serial": "MPLKIUJHN1",
     "balance": 0,
     "user_id": 1
    },
    "token": "1|abcdefghijklmnopqrstuvwxyz123456"
  }
}
```

#### Connexion de l'utilisateur

**Endpoint**: `POST /api/login`  
Permet à un utilisateur de se connecter avec les informations suivantes :

-   `email` (obligatoire)
-   `password` (obligatoire)

```bash
{
  "email": "jean.dupont@exemple.fr",
  "password": "motdepasse123"
}
```

```bash
{
  "status": "success",
  "message": "Connexion réussie",
  "data": {
    "user": {
      "id": 1,
      "name": "Jean Dupont",
      "email": "jean.dupont@exemple.fr",
      "role": "utilisateur",
      "created_at": "2025-03-17T12:00:00.000000Z",
      "updated_at": "2025-03-17T12:00:00.000000Z"
    },
    "token": "1|abcdefghijklmnopqrstuvwxyz123456"
  }
}
```

#### Déconnexion de l'utilisateur

**Endpoint**: `POST /api/logout`  
Permet à un utilisateur de se déconnecter (nécessite un token d'authentification valide via `auth:sanctum`).
