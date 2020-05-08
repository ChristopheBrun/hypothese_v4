Module : USER
=============

### Installation

```
'modules' => [
    'user' => [
        'class' => 'app\modules\user\UserModule',
        //
        'rememberConfirmationTokenFor' => 259200, // 72h = 72 * 3600 secondes
        'password_resetAfterEmailChange' => false,
    ],
],
```

### Configuration

### Extensions requises

- execut/yii2-widget-bootstraptreeview

@see https://github.com/execut/yii2-widget-bootstraptreeview


### Dépendances

- hlib

### Développement

**Personnalisation des vues du module**

Pour surcharger une vue, mettre en place un thème dans la configuration de l'application
@see http://www.yiiframework.com/doc-2.0/guide-output-theming.html

```
'components' => [
    'view' => [
        'theme' => [
            'pathMap' => [
                '@app/modules/foo/views' => '@app/views/foo',
            ],
        ],
    ],
],
```

### Description

Un utilisateur est identifié par son email & authentifié avec son mot de passe.

Un utilisateur peut avoir plusieurs profils.

NB : Un seul profil géré pour le moment >> Rendre le nombre de profils configurable + maj gestion du profil    

### Workflow

**Demande re ré-initialisation du mot de passe**

- Cliquer sur le lien "nouveau mot de passe" provoque l'affichage d'un formulaire permettant de saisir son email
- Un mail avec un lien de ré-initialisation est envoyé à cette adresse (sans changer le mot de passe actuel). Validité du lien limitée (jeton)
- Cliquer sur ce lien dans le mail amène sur la page de ré-initialisation du mot de passe

