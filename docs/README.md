# Documentation ProgramFlow

Ce dossier contient toute la documentation du projet, technique et métier.

## Documentation technique (déjà existante)

- [ARCHITECTURE.md](ARCHITECTURE.md) — Architecture, couches, design patterns
- [DATABASE_SCHEMA.md](DATABASE_SCHEMA.md) — Schéma complet de la base de données
- [INSTALLATION.md](INSTALLATION.md) — Guide d'installation pas-à-pas
- [USER_GUIDE.md](USER_GUIDE.md) — Guide utilisateur condensé
- [TECHNICAL.md](TECHNICAL.md) — Notes techniques avancées + pistes SaaS

## Documentation utilisateur publique (HTML, dans l'app)

Une documentation complète et illustrée est disponible **directement dans l'application** à l'URL :

```
http://programflow.test/aide
```

Cette documentation publique :
- Est accessible sans authentification
- Propose un guide par rôle (admin, organisateur, jury, candidate, partenaire)
- Contient une FAQ et un glossaire
- Utilise les vraies couleurs et composants de l'application
- Inclut des aperçus d'écran annotés pour chaque parcours

Le lien "Centre d'aide" est ajouté en bas de la sidebar de tous les espaces authentifiés
et dans le menu supérieur des pages publiques.

## Documentation métier (Word .docx)

Pour les **non-techniciens** (direction, comité, partenaires, équipes opérationnelles),
un document Word complet est généré localement.

### Fichier source (immédiatement lisible)

- [DOCUMENTATION_METIER.md](DOCUMENTATION_METIER.md) — version Markdown complète

Ce fichier est lisible directement dans tout éditeur Markdown
(VS Code, Typora, GitHub, etc.) ou peut être ouvert dans Word
qui supporte l'import Markdown depuis Office 365.

### Génération du fichier .docx professionnel

Pour produire la version Word formatée avec table des matières,
en-têtes, tableaux et mise en page professionnelle :

```bash
# Depuis la racine du projet
npm install --save-dev docx
node docs/generate-business-docx.js
```

Le script `generate-business-docx.js` génère `DOCUMENTATION_METIER.docx` (~30-50 pages)
avec :
- Une page de titre
- Une table des matières cliquable (à actualiser dans Word avec F9)
- 11 sections numérotées
- 6 tableaux structurés (matrice des rôles, statuts, glossaire, etc.)
- Encadrés "callout" colorés pour mettre en avant les règles métier importantes
- Pagination en pied de page
- Format A4, polices Calibri

Ouvrez ensuite le `.docx` dans Microsoft Word ou LibreOffice. Pour rafraîchir la
table des matières, faites un clic droit dessus et choisissez "Mettre à jour les champs".

### Alternative : conversion via Pandoc

Si vous avez [Pandoc](https://pandoc.org/) installé, vous pouvez également convertir
le Markdown en Word d'un seul coup :

```bash
pandoc docs/DOCUMENTATION_METIER.md -o docs/DOCUMENTATION_METIER.docx --toc --toc-depth=2
```

Cette méthode est plus rapide mais offre moins de contrôle sur la mise en forme.

## Structure recommandée

Pour partager la documentation avec les différents publics :

| Public | Document à partager |
|--------|---------------------|
| Direction, partenaires, comité de pilotage | `DOCUMENTATION_METIER.docx` (Word) |
| Équipes opérationnelles | Lien vers `/aide` dans l'app |
| Développeurs | `ARCHITECTURE.md`, `TECHNICAL.md`, `DATABASE_SCHEMA.md` |
| Administrateurs système | `INSTALLATION.md`, `TECHNICAL.md` |
| Utilisateurs finaux | Lien vers `/aide` (par rôle) |

## Mise à jour de la documentation

- **Doc HTML publique** : éditer les fichiers Blade dans `resources/views/help/`
- **Doc métier Markdown** : éditer `DOCUMENTATION_METIER.md`
- **Doc métier Word** : éditer `generate-business-docx.js` puis re-générer

Toujours mettre à jour le Markdown ET le script Node ensemble pour qu'ils restent
cohérents.
