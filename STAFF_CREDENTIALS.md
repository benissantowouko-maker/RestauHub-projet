# 🔐 RestauHub — Identifiants du Personnel Interne

> ⚠️  **CONFIDENTIEL** — Ne jamais partager, committer sans chiffrement, ni exposer publiquement.
> Ce fichier est listé dans `.gitignore` si vous utilisez Git en production.

---

## 🌐 URL du Portail Staff (interne, non lié sur le site public)

```
http://localhost/RestauHub/public/pro
```

---

## 👤 Comptes Administrateurs

| Nom             | Email                    | Mot de passe | Rôle  |
|-----------------|--------------------------|--------------|-------|
| Super Admin     | admin@cafeparis.com      | password     | admin |

---

## 🍳 Comptes Cuisine (KDS)

| Nom             | Email                    | Mot de passe | Rôle  |
|-----------------|--------------------------|--------------|-------|
| Chef Cuisine    | cuisine@cafeparis.com    | password     | staff |

---

## 🚴 Comptes Livreurs

| Nom             | Email                    | Mot de passe | Rôle    |
|-----------------|--------------------------|--------------|---------|
| Livreur 1       | livreur1@cafeparis.com   | password     | livreur |

---

## 📱 Installation PWA (application mobile)

### Android (Chrome)
1. Ouvrir `http://<votre-serveur>/pro` dans Chrome
2. Appuyer sur les 3 points (⋮) > **"Ajouter à l'écran d'accueil"**
3. Confirmer — l'icône **RH Staff** apparaît sur l'écran

### iOS (Safari)
1. Ouvrir `http://<votre-serveur>/pro` dans Safari
2. Appuyer sur **Partager** (📤) > **"Sur l'écran d'accueil"**
3. Confirmer — l'icône **RH Staff** apparaît sur l'écran

### URL production (à remplacer)
```
https://votre-domaine.com/pro
```

---

## 🔒 Bonnes pratiques

- Changer les mots de passe de production immédiatement après le déploiement
- Utiliser des mots de passe forts (16+ caractères, majuscules, chiffres, symboles)
- Activer la 2FA si disponible dans les paramètres
- Ne jamais partager les identifiants par SMS ou email non chiffré
- Révoquer l'accès d'un employé immédiatement en cas de départ (Admin > Paramètres > Utilisateurs > Désactiver)

---

## 🛠️ Gestion des comptes

Pour créer un nouveau compte staff, aller dans :
**Admin > Paramètres > Onglet "Utilisateurs" > Nouveau compte**

Pour désactiver un compte sans le supprimer :
**Admin > Paramètres > Onglet "Utilisateurs" > Désactiver**

---

*Généré le {{ date('d/m/Y') }} — RestauHub v1.0*
