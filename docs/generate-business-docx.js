/**
 * Génère DOCUMENTATION_METIER.docx à partir d'un script Node.js.
 *
 * Pré-requis :
 *   - Node.js 18+
 *   - npm install docx --save-dev   (ou : npm i -g docx)
 *
 * Lancement :
 *   node docs/generate-business-docx.js
 *
 * Sortie : docs/DOCUMENTATION_METIER.docx
 */

// const fs = require('fs');
// const path = require('path');
// const {
//     Document, Packer, Paragraph, TextRun, HeadingLevel, AlignmentType,
//     Table, TableRow, TableCell, WidthType, ShadingType, BorderStyle,
//     LevelFormat, PageBreak, PageNumber, Footer, Header, TableOfContents,
//     Bookmark, PageOrientation,
// } = require('docx');

import fs from "fs";
import path from "path";
import {
    Document,
    Packer,
    Paragraph,
    TextRun,
    HeadingLevel,
    AlignmentType,
    Table,
    TableRow,
    TableCell,
    WidthType,
    ShadingType,
    BorderStyle,
    LevelFormat,
    PageBreak,
    PageNumber,
    Footer,
    Header,
    TableOfContents,
    Bookmark,
    PageOrientation,
} from "docx";

import { fileURLToPath } from "url";
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

/* ----------------------------------------------------------- */
/* Helpers                                                       */
/* ----------------------------------------------------------- */

const FOUNDATION_NAME = 'Fondation Bénianh';
const PRODUCT = 'ProgramFlow';
const VERSION = '1.0';

const COLORS = {
    brand: '9D174D',
    brandLight: 'F9A8D4',
    slate800: '1E293B',
    slate600: '475569',
    slate400: '94A3B8',
    slate200: 'E2E8F0',
    slate50: 'F8FAFC',
    amber: 'D97706',
    red: 'DC2626',
    emerald: '059669',
};

const border = { style: BorderStyle.SINGLE, size: 4, color: COLORS.slate200 };
const allBorders = { top: border, bottom: border, left: border, right: border };
const cellMargins = { top: 80, bottom: 80, left: 120, right: 120 };

/** Paragraphe simple */
const P = (text, opts = {}) =>
    new Paragraph({
        spacing: { after: 120 },
        ...opts,
        children: Array.isArray(text)
            ? text
            : [new TextRun({ text, ...(opts.run || {}) })],
    });

/** Titre H1 */
const H1 = (text, bookmark) =>
    new Paragraph({
        heading: HeadingLevel.HEADING_1,
        spacing: { before: 480, after: 240 },
        children: bookmark
            ? [new Bookmark({ id: bookmark, children: [new TextRun(text)] })]
            : [new TextRun(text)],
    });

/** Titre H2 */
const H2 = (text) =>
    new Paragraph({
        heading: HeadingLevel.HEADING_2,
        spacing: { before: 320, after: 160 },
        children: [new TextRun(text)],
    });

/** Titre H3 */
const H3 = (text) =>
    new Paragraph({
        heading: HeadingLevel.HEADING_3,
        spacing: { before: 240, after: 120 },
        children: [new TextRun(text)],
    });

/** Bullet list item */
const bullet = (text, level = 0) =>
    new Paragraph({
        numbering: { reference: 'bullets', level },
        spacing: { after: 80 },
        children: typeof text === 'string'
            ? [new TextRun(text)]
            : text,
    });

/** Numbered list item */
const numbered = (text, level = 0) =>
    new Paragraph({
        numbering: { reference: 'numbers', level },
        spacing: { after: 80 },
        children: typeof text === 'string'
            ? [new TextRun(text)]
            : text,
    });

/** Cell helper */
const cell = (text, opts = {}) =>
    new TableCell({
        borders: allBorders,
        margins: cellMargins,
        width: { size: opts.width || 4680, type: WidthType.DXA },
        shading: opts.fill
            ? { fill: opts.fill, type: ShadingType.CLEAR, color: 'auto' }
            : undefined,
        children: [
            new Paragraph({
                children: typeof text === 'string'
                    ? [new TextRun({ text, bold: opts.bold, color: opts.color })]
                    : text,
            }),
        ],
    });

/** Encadré "callout" (paragraphe avec bordure gauche et fond) */
const callout = (label, text, color = 'blue') => {
    const colors = {
        blue:    { fill: 'DBEAFE', border: '2563EB' },
        amber:   { fill: 'FEF3C7', border: 'D97706' },
        red:     { fill: 'FEE2E2', border: 'DC2626' },
        emerald: { fill: 'D1FAE5', border: '059669' },
    };
    const c = colors[color] || colors.blue;
    return new Paragraph({
        spacing: { before: 120, after: 120 },
        shading: { fill: c.fill, type: ShadingType.CLEAR, color: 'auto' },
        border: {
            left: { style: BorderStyle.SINGLE, size: 18, color: c.border, space: 8 },
            top: { style: BorderStyle.SINGLE, size: 4, color: c.fill, space: 8 },
            bottom: { style: BorderStyle.SINGLE, size: 4, color: c.fill, space: 8 },
            right: { style: BorderStyle.SINGLE, size: 4, color: c.fill, space: 8 },
        },
        children: [
            new TextRun({ text: label + ' ', bold: true, color: c.border }),
            new TextRun({ text }),
        ],
    });
};

/* ----------------------------------------------------------- */
/* Page de titre                                                 */
/* ----------------------------------------------------------- */

const titlePage = [
    new Paragraph({ spacing: { before: 2400 }, children: [] }),
    new Paragraph({
        alignment: AlignmentType.CENTER,
        spacing: { after: 360 },
        children: [new TextRun({ text: PRODUCT, bold: true, size: 72, color: COLORS.brand })],
    }),
    new Paragraph({
        alignment: AlignmentType.CENTER,
        spacing: { after: 240 },
        children: [new TextRun({ text: 'Documentation Métier', size: 48, color: COLORS.slate800 })],
    }),
    new Paragraph({
        alignment: AlignmentType.CENTER,
        spacing: { after: 1200 },
        children: [new TextRun({ text: `Plateforme de gestion des programmes — ${FOUNDATION_NAME}`, italics: true, size: 28, color: COLORS.slate600 })],
    }),
    new Paragraph({
        alignment: AlignmentType.CENTER,
        children: [new TextRun({ text: 'Destiné aux non-techniciens : direction, équipes opérationnelles, partenaires', size: 22, color: COLORS.slate600 })],
    }),
    new Paragraph({
        alignment: AlignmentType.CENTER,
        spacing: { before: 2400 },
        children: [new TextRun({ text: `Version ${VERSION}`, size: 22, color: COLORS.slate400 })],
    }),
    new Paragraph({
        alignment: AlignmentType.CENTER,
        children: [new TextRun({ text: `© ${new Date().getFullYear()} ${FOUNDATION_NAME}`, size: 20, color: COLORS.slate400 })],
    }),
    new Paragraph({ children: [new PageBreak()] }),
];

/* ----------------------------------------------------------- */
/* Sommaire (TOC)                                                */
/* ----------------------------------------------------------- */

const toc = [
    new Paragraph({
        alignment: AlignmentType.CENTER,
        children: [new TextRun({ text: 'Table des matières', bold: true, size: 36, color: COLORS.brand })],
        spacing: { after: 360 },
    }),
    new TableOfContents('Sommaire', { hyperlink: true, headingStyleRange: '1-3' }),
    new Paragraph({ children: [new PageBreak()] }),
];

/* ----------------------------------------------------------- */
/* Contenu                                                       */
/* ----------------------------------------------------------- */

const content = [];

// 1. Introduction
content.push(H1('1. Introduction', 'introduction'));

content.push(H2('1.1 Présentation du projet'));
content.push(P(
    'ProgramFlow est l\'application web professionnelle développée pour la Fondation Bénianh afin de centraliser, gérer et automatiser l\'ensemble des activités de ses programmes d\'accompagnement, en commençant par le programme phare Leadership Féminin.',
));
content.push(P(
    'L\'application remplace les outils fragmentés (tableurs Excel, formulaires Google, échanges email) par une plateforme unique qui couvre le cycle complet d\'un programme : de sa création par l\'administrateur jusqu\'à son archivage, en passant par la gestion des candidatures, l\'évaluation par jury, la sélection finale et le suivi des participantes.',
));

content.push(H2('1.2 Pourquoi cette plateforme ?'));
content.push(P([new TextRun({ text: 'Avant ProgramFlow, gérer un programme impliquait :', bold: true })]));
content.push(bullet('Des candidatures reçues par email avec pièces jointes dispersées'));
content.push(bullet('Des notations manuelles dans des tableurs partagés'));
content.push(bullet('Aucun suivi structuré des présences ou des sessions'));
content.push(bullet('Une communication peu traçable avec les candidates'));
content.push(bullet('Aucune visibilité agrégée pour la direction et les partenaires'));

content.push(P([new TextRun({ text: 'Avec ProgramFlow, tout est centralisé, traçable et automatisé :', bold: true })]));
content.push(bullet('Une candidature = un dossier numérique unique avec sa référence'));
content.push(bullet('L\'évaluation suit une grille pondérée objective'));
content.push(bullet('Les emails partent automatiquement à chaque étape'));
content.push(bullet('Le reporting est en temps réel'));
content.push(bullet('L\'historique est consultable pour toutes les anciennes éditions'));

content.push(H2('1.3 Pour qui ?'));
content.push(new Table({
    width: { size: 9360, type: WidthType.DXA },
    columnWidths: [3200, 6160],
    rows: [
        new TableRow({ tableHeader: true, children: [
            cell('Public', { width: 3200, fill: COLORS.brandLight, bold: true }),
            cell('Bénéfice principal', { width: 6160, fill: COLORS.brandLight, bold: true }),
        ] }),
        new TableRow({ children: [cell('Direction de la fondation', { width: 3200 }), cell('Vue d\'ensemble, reporting, gouvernance', { width: 6160 })] }),
        new TableRow({ children: [cell('Équipe opérationnelle', { width: 3200 }), cell('Outils pour orchestrer les programmes', { width: 6160 })] }),
        new TableRow({ children: [cell('Partenaires', { width: 3200 }), cell('Transparence sur les programmes soutenus', { width: 6160 })] }),
        new TableRow({ children: [cell('Candidates', { width: 3200 }), cell('Parcours fluide et professionnel', { width: 6160 })] }),
        new TableRow({ children: [cell('Membres du jury', { width: 3200 }), cell('Outil structuré et impartial', { width: 6160 })] }),
    ],
}));

// 2. Vision
content.push(H1('2. Vision et objectifs', 'vision'));
content.push(H2('2.1 Vision long terme'));
content.push(P('ProgramFlow est conçue pour évoluer en produit SaaS utilisable par plusieurs fondations et organisations. Son architecture multi-programme et multi-rôle est pensée dès le départ pour ce passage à l\'échelle.'));

content.push(H2('2.2 Principes fondateurs'));
content.push(numbered([new TextRun({ text: 'Centralisation : ', bold: true }), new TextRun('toutes les données d\'un programme au même endroit')]));
content.push(numbered([new TextRun({ text: 'Automatisation : ', bold: true }), new TextRun('les tâches répétitives sont déclenchées par le système (emails, attributions, calculs)')]));
content.push(numbered([new TextRun({ text: 'Traçabilité : ', bold: true }), new TextRun('chaque action est horodatée, chaque transition est journalisée')]));
content.push(numbered([new TextRun({ text: 'Confidentialité : ', bold: true }), new TextRun('les données sensibles (santé, identité) sont strictement protégées')]));
content.push(numbered([new TextRun({ text: 'Accessibilité : ', bold: true }), new TextRun('utilisable par des non-techniciens, sans formation lourde')]));

content.push(H2('2.3 Objectifs mesurables'));
content.push(bullet('Réduire de 80 % le temps consacré aux tâches administratives répétitives'));
content.push(bullet('Garantir 100 % de traçabilité des décisions'));
content.push(bullet('Permettre la consultation de toute édition antérieure en moins de 30 secondes'));
content.push(bullet('Offrir aux candidates un parcours en moins de 30 minutes de remplissage'));

// 3. Glossaire
content.push(new Paragraph({ children: [new PageBreak()] }));
content.push(H1('3. Glossaire essentiel', 'glossaire'));

const glossaryTerms = [
    ['Candidate', 'Personne qui postule à un programme'],
    ['Candidature', 'Dossier rempli pour postuler. Possède un statut unique à tout moment'],
    ['Programme', 'Initiative de la fondation (formation, mentorat, financement) avec un début et une fin'],
    ['Statut', 'État actuel d\'une candidature ou d\'un programme (8 statuts possibles)'],
    ['Jury', 'Personne désignée pour évaluer les candidatures d\'un programme'],
    ['Organisateur', 'Responsable opérationnel d\'un ou plusieurs programmes'],
    ['Administrateur', 'Gestionnaire de la plateforme, configure utilisateurs et programmes'],
    ['Partenaire', 'Organisation soutenant un ou plusieurs programmes'],
    ['Référence', 'Identifiant unique d\'une candidature (ex: PF-2026-A1B2C3)'],
    ['Critère d\'évaluation', 'Axe de notation avec un poids (ex: « Qualité du projet » — poids 3)'],
    ['Score pondéré', 'Note finale calculée par Σ(note × poids) / Σ(poids)'],
    ['Champ standard', 'Question fixe identique pour tous les programmes'],
    ['Champ dynamique', 'Question spécifique à un programme, configurée par l\'admin'],
    ['Form Builder', 'Interface permettant de configurer les champs dynamiques'],
    ['Présélection', 'Filtre des meilleurs profils avant la décision finale'],
    ['Session', 'Activité planifiée d\'un programme actif (formation, atelier)'],
    ['Rapport d\'activité', 'Document narratif et médias documentant une activité'],
];
content.push(new Table({
    width: { size: 9360, type: WidthType.DXA },
    columnWidths: [2800, 6560],
    rows: [
        new TableRow({ tableHeader: true, children: [
            cell('Terme', { width: 2800, fill: COLORS.brandLight, bold: true }),
            cell('Définition', { width: 6560, fill: COLORS.brandLight, bold: true }),
        ] }),
        ...glossaryTerms.map(([term, def]) => new TableRow({
            children: [
                cell(term, { width: 2800, bold: true }),
                cell(def, { width: 6560 }),
            ],
        })),
    ],
}));

// 4. Acteurs
content.push(new Paragraph({ children: [new PageBreak()] }));
content.push(H1('4. Les acteurs du système', 'acteurs'));
content.push(P('ProgramFlow distingue cinq rôles, chacun avec des droits et des écrans dédiés.'));

const actors = [
    {
        name: '4.1 Administrateur',
        who: 'Membre de la direction ou responsable IT de la fondation.',
        role: 'Chef d\'orchestre du système. Premier à intervenir.',
        rights: 'Tous (« super-utilisateur »).',
        resp: [
            'Créer et désactiver les comptes utilisateurs',
            'Créer, configurer, modifier et archiver les programmes',
            'Gérer le référentiel des partenaires',
            'Consulter le reporting global de la fondation',
            'Personnaliser les formulaires de candidature via le Form Builder',
        ],
    },
    {
        name: '4.2 Organisateur',
        who: 'Coordinateur ou chargé de projet d\'un programme.',
        role: 'Opère le programme au quotidien.',
        rights: 'Limité aux programmes auxquels il est associé.',
        resp: [
            'Suivre l\'arrivée des candidatures',
            'Clôturer la phase de candidature et déclencher l\'évaluation',
            'Prendre les décisions finales (acceptée / refusée / liste d\'attente)',
            'Planifier les sessions (formations, ateliers) et marquer les présences',
            'Rédiger les rapports d\'activité',
        ],
    },
    {
        name: '4.3 Membre du jury',
        who: 'Expert externe ou interne désigné pour évaluer.',
        role: 'Note les candidatures selon une grille pondérée.',
        rights: 'Voit uniquement les programmes auxquels il est associé.',
        resp: [
            'Consulter les dossiers complets des candidates',
            'Noter chaque critère de la grille',
            'Soumettre l\'évaluation (action définitive)',
        ],
    },
    {
        name: '4.4 Candidate',
        who: 'Femme intéressée par un programme de la fondation.',
        role: 'Postule et suit son dossier.',
        rights: 'Uniquement sur ses propres candidatures.',
        resp: [
            'Créer son compte et vérifier son email',
            'Remplir le formulaire de candidature en 3 étapes',
            'Soumettre sa candidature avant la date de clôture',
            'Suivre l\'évolution du statut sur son dashboard',
            'Recevoir les emails à chaque transition de statut',
        ],
    },
    {
        name: '4.5 Partenaire',
        who: 'Représentant d\'une organisation soutenant un programme.',
        role: 'Observateur en lecture seule.',
        rights: 'Consulter les programmes auxquels son organisation contribue. Pas d\'accès aux dossiers individuels (RGPD).',
        resp: [
            'Suivre les statistiques agrégées',
            'Demander un reporting détaillé à l\'administrateur si besoin',
        ],
    },
];

actors.forEach(actor => {
    content.push(H2(actor.name));
    content.push(P([new TextRun({ text: 'Qui ? ', bold: true }), new TextRun(actor.who)]));
    content.push(P([new TextRun({ text: 'Rôle : ', bold: true }), new TextRun(actor.role)]));
    content.push(P([new TextRun({ text: 'Droits : ', bold: true }), new TextRun(actor.rights)]));
    content.push(P([new TextRun({ text: 'Responsabilités principales :', bold: true })]));
    actor.resp.forEach(r => content.push(bullet(r)));
});

// 5. Cycle de vie d'un programme
content.push(new Paragraph({ children: [new PageBreak()] }));
content.push(H1('5. Le cycle de vie d\'un programme', 'cycle'));
content.push(P('Un programme passe par 8 statuts qui structurent l\'activité de tous les acteurs.'));

content.push(H2('5.1 Tableau des statuts'));
const programStatuses = [
    ['1', 'Brouillon (draft)', 'Programme en cours de création, invisible au public', 'Administrateur'],
    ['2', 'Publié (published)', 'Visible mais candidatures pas encore ouvertes', 'Administrateur'],
    ['3', 'Candidatures ouvertes (open)', 'Les candidates peuvent postuler', 'Candidates'],
    ['4', 'Évaluation jury (review)', 'Les jurys notent les candidatures', 'Jurys'],
    ['5', 'Sélection finale (selection)', 'Classement et décisions individuelles', 'Organisateur'],
    ['6', 'En cours (active)', 'Programme se déroule : sessions, présences', 'Organisateur'],
    ['7', 'Terminé (completed)', 'Programme achevé, données figées', '—'],
    ['8', 'Archivé (archived)', 'Lecture seule, historique consultable', 'Administrateur'],
];
content.push(new Table({
    width: { size: 9360, type: WidthType.DXA },
    columnWidths: [600, 2400, 4360, 2000],
    rows: [
        new TableRow({ tableHeader: true, children: [
            cell('#', { width: 600, fill: COLORS.brandLight, bold: true }),
            cell('Statut', { width: 2400, fill: COLORS.brandLight, bold: true }),
            cell('Signification', { width: 4360, fill: COLORS.brandLight, bold: true }),
            cell('Acteur principal', { width: 2000, fill: COLORS.brandLight, bold: true }),
        ] }),
        ...programStatuses.map(row => new TableRow({
            children: [
                cell(row[0], { width: 600, bold: true }),
                cell(row[1], { width: 2400 }),
                cell(row[2], { width: 4360 }),
                cell(row[3], { width: 2000 }),
            ],
        })),
    ],
}));

content.push(H2('5.2 Transitions standard'));
content.push(P([new TextRun({ text: 'draft → published → open → review → selection → active → completed → archived', font: 'Consolas', bold: true })]));

// 6. Processus métier
content.push(new Paragraph({ children: [new PageBreak()] }));
content.push(H1('6. Processus métier détaillés', 'processus'));

// 6.1 Création de programme
content.push(H2('6.1 Processus n°1 — Création d\'un programme'));
content.push(P([new TextRun({ text: 'Déclencheur : ', bold: true }), new TextRun('Décision de la fondation de lancer une nouvelle édition.')]));
content.push(P([new TextRun({ text: 'Acteur : ', bold: true }), new TextRun('Administrateur.')]));
content.push(P([new TextRun({ text: 'Étapes :', bold: true })]));
content.push(numbered('L\'admin se connecte et accède à Programmes → Nouveau programme'));
content.push(numbered('Il saisit : titre, description courte, description complète, objectifs, éligibilité, places, dates clés, image de couverture'));
content.push(numbered('À l\'enregistrement, le système crée automatiquement 50+ champs de formulaire standard répartis en 9 sections fixes et 4 critères d\'évaluation pondérés par défaut'));
content.push(numbered('L\'admin associe ensuite les organisateurs, les membres du jury et les partenaires'));
content.push(numbered('Optionnel : personnalisation du formulaire via le Form Builder'));
content.push(P([new TextRun({ text: 'Résultat : ', bold: true }), new TextRun('Programme prêt à être publié et à recevoir des candidatures.')]));
content.push(P([new TextRun({ text: 'Durée typique : ', bold: true }), new TextRun('15-30 minutes pour la première création.')]));

// 6.2 Candidatures
content.push(H2('6.2 Processus n°2 — Période de candidatures'));
content.push(P([new TextRun({ text: 'Déclencheur : ', bold: true }), new TextRun('Date d\'ouverture des candidatures atteinte.')]));
content.push(P([new TextRun({ text: 'Acteurs : ', bold: true }), new TextRun('Candidates (acteur principal), Organisateur (suivi).')]));
content.push(H3('Étapes côté candidate'));
content.push(numbered('Découverte du programme via la page publique d\'accueil'));
content.push(numbered('Création de compte : prénom, nom, email, mot de passe'));
content.push(numbered('Vérification de l\'email : clic sur le lien reçu'));
content.push(numbered('Connexion à son espace personnel'));
content.push(numbered('Sur la fiche du programme, clic sur "Postuler" → création d\'un brouillon avec référence unique (PF-2026-A1B2C3)'));
content.push(numbered('Remplissage du formulaire en 3 étapes'));
content.push(numbered('Enregistrements multiples en brouillon possible — revenir plus tard'));
content.push(numbered('Soumission finale : statut Brouillon → Soumise + email de confirmation'));
content.push(callout('Règle métier importante.',
    'Une seule candidature par programme et par personne. Si la candidate clique deux fois sur "Postuler", elle est redirigée vers son dossier existant — pas de doublon possible.',
    'amber'));
content.push(callout('Modification après soumission.',
    'Tant que la date de clôture n\'est pas dépassée, la candidate peut encore modifier son dossier déjà soumis. Les changements sont enregistrés sans nouvelle soumission.',
    'blue'));

// 6.3 Évaluation
content.push(H2('6.3 Processus n°3 — Clôture et lancement de l\'évaluation'));
content.push(P([new TextRun({ text: 'Déclencheur : ', bold: true }), new TextRun('Soit la date de clôture est atteinte (automatique), soit l\'organisateur clique sur "Démarrer l\'évaluation" (anticipé).')]));
content.push(P([new TextRun({ text: 'Effets automatiques :', bold: true })]));
content.push(bullet('Le statut du programme passe à "review"'));
content.push(bullet('Toutes les candidatures soumises deviennent "en évaluation"'));
content.push(bullet('Pour chaque (candidature × jury), une évaluation est créée automatiquement en statut "À traiter"'));
content.push(callout('Note importante.',
    'L\'attribution est automatique. Chaque jury membre du programme évalue toutes les candidatures soumises. Pas d\'attribution manuelle par l\'organisateur.',
    'emerald'));

content.push(H2('6.4 Processus n°4 — Évaluation par le jury'));
content.push(numbered('Le jury se connecte à son espace'));
content.push(numbered('Il voit sur son dashboard le compteur "À évaluer"'));
content.push(numbered('Il clique sur "Évaluer" → écran 2 colonnes (dossier à gauche, grille à droite)'));
content.push(numbered('Pour chaque critère : note (0-20) + commentaire optionnel'));
content.push(numbered('Commentaire global en bas'));
content.push(numbered('Soumission → action irréversible'));

content.push(H3('Calcul automatique du score pondéré'));
content.push(P([new TextRun({ text: 'Score pondéré = Σ(note × poids du critère) / Σ(poids)', font: 'Consolas', bold: true })]));
content.push(P('Exemple : si un jury attribue 15 (poids 2), 17 (poids 3), 16 (poids 3), 18 (poids 2), son score pondéré est (30+51+48+36) / 10 = 16,5 / 20.'));

// 6.5 Sélection
content.push(H2('6.5 Processus n°5 — Sélection finale'));
content.push(numbered('Accès à la page Sélection du programme'));
content.push(numbered('Classement automatique pondéré décroissant'));
content.push(numbered('Saisie du nombre de places → "Pré-sélectionner top N"'));
content.push(numbered('Décision finale individuelle (acceptée / refusée / liste d\'attente) avec commentaire envoyé par email'));
content.push(numbered('Verrouillage : statut programme → "active"'));
content.push(P([new TextRun({ text: 'Exports disponibles : ', bold: true }), new TextRun('Excel (tableau complet avec scores), PDF (classement formaté).')]));

// 6.6 Programme actif
content.push(H2('6.6 Processus n°6 — Programme actif'));
content.push(numbered('Planification des sessions (titre, type, date, lieu, facilitateur)'));
content.push(numbered('Le jour J : marquage des présences (présent / absent / excusé / retard)'));
content.push(numbered('Rédaction du compte rendu de chaque session'));
content.push(numbered('Création et assignation de tâches aux participantes'));

// 6.7 Rapports
content.push(H2('6.7 Processus n°7 — Rapports d\'activité'));
content.push(numbered('Accès au module Rapports d\'activité d\'un programme'));
content.push(numbered('Saisie : titre, description, date, contenu détaillé, session liée optionnelle'));
content.push(numbered('Téléversement : fichier principal (PDF), galerie d\'images, vidéos'));
content.push(numbered('Choix du statut : brouillon ou publié'));

// 6.8 Archivage
content.push(H2('6.8 Processus n°8 — Archivage'));
content.push(numbered('Accès à la fiche du programme'));
content.push(numbered('Clic sur "Archiver ce programme"'));
content.push(numbered('Le statut passe à "Archivé" : données consultables en lecture seule, plus aucune modification possible'));

// 7. Confidentialité
content.push(new Paragraph({ children: [new PageBreak()] }));
content.push(H1('7. Gestion documentaire et confidentialité', 'documents'));

content.push(H2('7.1 Types de documents stockés'));
content.push(new Table({
    width: { size: 9360, type: WidthType.DXA },
    columnWidths: [3000, 4360, 2000],
    rows: [
        new TableRow({ tableHeader: true, children: [
            cell('Type', { width: 3000, fill: COLORS.brandLight, bold: true }),
            cell('Exemples', { width: 4360, fill: COLORS.brandLight, bold: true }),
            cell('Disque', { width: 2000, fill: COLORS.brandLight, bold: true }),
        ] }),
        ...[
            ['Avatars', 'Photos de profil', 'Public'],
            ['Logos partenaires', 'Images d\'organisations', 'Public'],
            ['Images de couverture programmes', 'Bannières', 'Public'],
            ['CV des candidates', 'PDF, DOCX', 'Privé (protégé)'],
            ['Pièces d\'identité', 'CNI, passeport scannés', 'Privé (protégé)'],
            ['Vidéos de motivation', 'MP4, MOV', 'Privé (protégé)'],
            ['Galeries de rapports', 'JPG, PNG', 'Public'],
        ].map(row => new TableRow({
            children: [
                cell(row[0], { width: 3000, bold: true }),
                cell(row[1], { width: 4360 }),
                cell(row[2], { width: 2000 }),
            ],
        })),
    ],
}));

content.push(H2('7.2 Confidentialité des données santé'));
content.push(P('La section "Santé & sécurité" du formulaire contient des informations particulièrement sensibles : maladies chroniques, allergies, traitements, groupe sanguin, contact médical.'));
content.push(P('Ces données :'));
content.push(bullet('Sont marquées « Confidentiel » dans l\'interface'));
content.push(bullet('Apparaissent dans un encadré distinctif'));
content.push(bullet('Ne sont accessibles qu\'à l\'admin, l\'organisateur et le jury du programme'));
content.push(bullet('Ne sont JAMAIS visibles par les partenaires'));
content.push(bullet('Servent exclusivement à la sécurité en cas d\'urgence pendant le programme'));

content.push(H2('7.3 RGPD et droit à l\'oubli'));
content.push(bullet('Conservation des données pendant la durée du programme + 5 ans (sauf demande contraire)'));
content.push(bullet('Droit à l\'oubli : la candidate peut demander la suppression de son compte via son espace'));
content.push(bullet('Anonymisation possible des programmes archivés sur demande'));

// 8. Notifications
content.push(H1('8. Notifications et communications', 'notifications'));
content.push(H2('8.1 Emails automatiques'));
content.push(new Table({
    width: { size: 9360, type: WidthType.DXA },
    columnWidths: [3500, 2800, 3060],
    rows: [
        new TableRow({ tableHeader: true, children: [
            cell('Événement', { width: 3500, fill: COLORS.brandLight, bold: true }),
            cell('Destinataire', { width: 2800, fill: COLORS.brandLight, bold: true }),
            cell('Contenu', { width: 3060, fill: COLORS.brandLight, bold: true }),
        ] }),
        ...[
            ['Inscription', 'Candidate', 'Email de vérification'],
            ['Soumission de candidature', 'Candidate', 'Confirmation avec référence'],
            ['Décision (acceptée/refusée/liste d\'attente)', 'Candidate', 'Statut + commentaire'],
            ['Réinitialisation de mot de passe', 'Tout utilisateur', 'Lien de réinitialisation (60 min)'],
            ['Attribution manuelle (cas exceptionnel)', 'Membre du jury', 'Notification d\'attribution'],
        ].map(row => new TableRow({
            children: [
                cell(row[0], { width: 3500 }),
                cell(row[1], { width: 2800 }),
                cell(row[2], { width: 3060 }),
            ],
        })),
    ],
}));

// 9. Cas d'usage
content.push(new Paragraph({ children: [new PageBreak()] }));
content.push(H1('9. Cas d\'usage concrets', 'cas-usage'));
content.push(H2('9.1 Scénario complet : Leadership Féminin 2026'));
content.push(P('Voici comment se déroule un programme du début à la fin sur ProgramFlow.'));

content.push(new Table({
    width: { size: 9360, type: WidthType.DXA },
    columnWidths: [1000, 2200, 6160],
    rows: [
        new TableRow({ tableHeader: true, children: [
            cell('Jour', { width: 1000, fill: COLORS.brandLight, bold: true }),
            cell('Acteur', { width: 2200, fill: COLORS.brandLight, bold: true }),
            cell('Action', { width: 6160, fill: COLORS.brandLight, bold: true }),
        ] }),
        ...[
            ['J1', 'Admin', 'Crée le programme en statut Brouillon'],
            ['J1', 'Admin', 'Configure dates, places, objectifs'],
            ['J2', 'Admin', 'Associe 3 organisateurs, 5 jurys, 4 partenaires'],
            ['J2', 'Admin', 'Personnalise via Form Builder'],
            ['J3', 'Admin', 'Statut → Publié, puis → Candidatures ouvertes'],
            ['J3-J30', 'Candidates (~150)', 'Inscriptions, candidatures, soumissions'],
            ['J30', 'Système', 'Date de clôture atteinte, candidatures fermées automatiquement'],
            ['J31', 'Organisateur', 'Clique "Démarrer l\'évaluation"'],
            ['J31', 'Système', 'Crée 750 évaluations (150 × 5 jurys)'],
            ['J31-J45', 'Jurys', 'Évaluent leurs candidatures attribuées'],
            ['J46', 'Organisateur', 'Pré-sélectionne top 30'],
            ['J46-J48', 'Organisateur', 'Prend décisions finales individuelles'],
            ['J48', 'Système', 'Envoie 150 emails (30 acceptées, 100 refusées, 20 liste d\'attente)'],
            ['J48', 'Organisateur', 'Verrouille la sélection → Programme actif'],
            ['J50-M6', 'Organisateur', 'Planifie 12 sessions, marque présences, rédige rapports'],
            ['M6', 'Admin', 'Statut → Terminé'],
            ['M12', 'Admin', 'Archive le programme'],
        ].map(row => new TableRow({
            children: [
                cell(row[0], { width: 1000, bold: true }),
                cell(row[1], { width: 2200 }),
                cell(row[2], { width: 6160 }),
            ],
        })),
    ],
}));

content.push(H2('9.2 Cas particulier : un désistement'));
content.push(numbered('Une candidate acceptée se désiste après la sélection'));
content.push(numbered('L\'organisateur change son statut en "Retirée"'));
content.push(numbered('Sur la liste d\'attente, il identifie la prochaine candidate à reclasser'));
content.push(numbered('Il change son statut de "Liste d\'attente" → "Acceptée"'));
content.push(numbered('Un email de bonne nouvelle est envoyé automatiquement'));

content.push(H2('9.3 Cas particulier : un jury supplémentaire ajouté en cours'));
content.push(numbered('L\'admin associe le nouveau jury au programme via la fiche'));
content.push(numbered('Lors de sa prochaine connexion, le jury voit automatiquement toutes les candidatures soumises'));
content.push(numbered('Il peut commencer à évaluer immédiatement (auto-création des évaluations)'));

// 10. FAQ
content.push(new Paragraph({ children: [new PageBreak()] }));
content.push(H1('10. Questions fréquentes', 'faq'));

const faqs = [
    ['Puis-je modifier ma candidature après soumission ?', 'Oui, tant que la période de candidature n\'est pas fermée.'],
    ['Mes informations santé sont-elles vues par tout le monde ?', 'Non. Seuls l\'admin, l\'organisateur et le jury y ont accès, et uniquement pour des raisons de sécurité.'],
    ['Combien de temps prend le remplissage du formulaire ?', 'Environ 20-30 minutes, plus le temps de scanner vos documents.'],
    ['Combien de jurys minimum pour démarrer l\'évaluation ?', 'Au moins 1, mais 2-3 est recommandé pour la robustesse de la notation.'],
    ['Que se passe-t-il si un jury ne soumet pas son évaluation à temps ?', 'Le système n\'oblige pas la complétude. La moyenne se calcule sur les évaluations soumises.'],
    ['Puis-je annuler une décision d\'acceptation par erreur ?', 'Oui, ouvrez la candidature et changez la décision. Un nouvel email sera envoyé.'],
    ['Comment exporter toutes les données d\'un programme ?', 'Les exports Excel et PDF couvrent les candidatures. Pour une sauvegarde complète, demandez un dump de la base.'],
    ['Quelle est la taille maximale d\'un fichier que je peux téléverser ?', '64 Mo par fichier maximum.'],
    ['Quels formats de fichiers sont acceptés ?', 'CV et pièce d\'identité : PDF, DOC, DOCX, JPG, PNG. Vidéos : MP4, WebM, MOV.'],
    ['Une candidate peut-elle postuler deux fois au même programme ?', 'Non. Une seule candidature par programme et par personne.'],
];

faqs.forEach(([q, a]) => {
    content.push(P([new TextRun({ text: 'Q : ', bold: true, color: COLORS.brand }), new TextRun({ text: q, bold: true })]));
    content.push(P([new TextRun({ text: 'R : ', bold: true, color: COLORS.emerald }), new TextRun(a)]));
});

// 11. Annexes
content.push(new Paragraph({ children: [new PageBreak()] }));
content.push(H1('11. Annexes', 'annexes'));

content.push(H2('11.1 Annexe A — Droits par rôle (matrice)'));

const rightsMatrix = [
    ['Action', 'Admin', 'Organisateur', 'Jury', 'Candidate', 'Partenaire'],
    ['Créer un utilisateur', 'Oui', '—', '—', '—', '—'],
    ['Créer un programme', 'Oui', '—', '—', '—', '—'],
    ['Modifier un programme', 'Oui', 'Si associé', '—', '—', '—'],
    ['Archiver un programme', 'Oui', '—', '—', '—', '—'],
    ['Personnaliser le formulaire', 'Oui', 'Si associé', '—', '—', '—'],
    ['Voir les candidatures', 'Oui', 'Si associé', 'Siennes', 'Propre', '—'],
    ['Modifier une candidature', 'Oui', '—', '—', 'Propre (ouverte)', '—'],
    ['Évaluer une candidature', '—', '—', 'Si assignée', '—', '—'],
    ['Prendre une décision finale', 'Oui', 'Si associé', '—', '—', '—'],
    ['Planifier une session', 'Oui', 'Si associé', '—', '—', '—'],
    ['Rédiger un rapport', 'Oui', 'Si associé', '—', '—', '—'],
    ['Reporting global', 'Oui', '—', '—', '—', '—'],
    ['Reporting partenaire', 'Oui', 'Si associé', '—', '—', 'Oui (lecture)'],
];

content.push(new Table({
    width: { size: 9360, type: WidthType.DXA },
    columnWidths: [2860, 1300, 1500, 1100, 1500, 1100],
    rows: rightsMatrix.map((row, idx) => new TableRow({
        tableHeader: idx === 0,
        children: row.map((value, i) => cell(value, {
            width: [2860, 1300, 1500, 1100, 1500, 1100][i],
            fill: idx === 0 ? COLORS.brandLight : undefined,
            bold: idx === 0 || i === 0,
        })),
    })),
}));

content.push(H2('11.2 Annexe B — Sections du formulaire standard'));
content.push(P('Le formulaire est structuré en 9 sections fixes générées automatiquement pour chaque programme :'));
[
    '1.1 Identité (7 champs) : Nom, Prénoms, Date de naissance, Lieu, Nationalité, Sexe, Situation matrimoniale',
    '1.2 Coordonnées (6 champs) : Adresse complète, Ville, Commune, Téléphones, Email',
    '1.3 Pièce d\'identité (4 champs) : Type, Numéro, Expiration, Scan',
    '1.4 Parcours académique (8 champs) : Diplômes, Bac, Université, Domaine, Formations',
    '1.5 Expérience & engagement (4 champs) : Expériences, Associations, Compétences, CV',
    '1.6 Santé & sécurité (11 champs, confidentiels) : Maladies, Allergies, Traitements, etc.',
    '2.1 Parent / tuteur principal (6 champs)',
    '2.2 Contact d\'urgence (5 champs)',
    '3. Spécifique au programme (configurable par l\'admin)',
    'Déclaration finale (4 cases obligatoires)',
].forEach(s => content.push(bullet(s)));

content.push(H2('11.3 Annexe C — Critères d\'évaluation par défaut'));
content.push(new Table({
    width: { size: 9360, type: WidthType.DXA },
    columnWidths: [3000, 1200, 1500, 3660],
    rows: [
        new TableRow({ tableHeader: true, children: [
            cell('Critère', { width: 3000, fill: COLORS.brandLight, bold: true }),
            cell('Poids', { width: 1200, fill: COLORS.brandLight, bold: true }),
            cell('Note max', { width: 1500, fill: COLORS.brandLight, bold: true }),
            cell('Description', { width: 3660, fill: COLORS.brandLight, bold: true }),
        ] }),
        ...[
            ['Pertinence du parcours', '2', '20', 'Cohérence entre formation/expérience et le programme'],
            ['Qualité du projet', '3', '20', 'Clarté, réalisme, ambition du projet'],
            ['Impact attendu', '3', '20', 'Bénéfice social/économique du projet'],
            ['Motivation et engagement', '2', '20', 'Solidité de la lettre, parcours associatif'],
        ].map(row => new TableRow({
            children: [
                cell(row[0], { width: 3000, bold: true }),
                cell(row[1], { width: 1200 }),
                cell(row[2], { width: 1500 }),
                cell(row[3], { width: 3660 }),
            ],
        })),
    ],
}));

// Fin
content.push(new Paragraph({ children: [new PageBreak()] }));
content.push(new Paragraph({
    alignment: AlignmentType.CENTER,
    spacing: { before: 1200 },
    children: [new TextRun({ text: 'Fin du document', italics: true, color: COLORS.slate400, size: 22 })],
}));
content.push(new Paragraph({
    alignment: AlignmentType.CENTER,
    spacing: { before: 240 },
    children: [new TextRun({ text: `${PRODUCT} v${VERSION} — © ${new Date().getFullYear()} ${FOUNDATION_NAME}`, color: COLORS.slate400, size: 18 })],
}));

/* ----------------------------------------------------------- */
/* Construction du document                                       */
/* ----------------------------------------------------------- */

const doc = new Document({
    creator: FOUNDATION_NAME,
    title: 'Documentation Métier — ProgramFlow',
    description: 'Documentation métier complète pour les non-techniciens',
    styles: {
        default: { document: { run: { font: 'Calibri', size: 22 } } },
        paragraphStyles: [
            {
                id: 'Heading1', name: 'Heading 1', basedOn: 'Normal', next: 'Normal', quickFormat: true,
                run: { size: 36, bold: true, color: COLORS.brand, font: 'Calibri' },
                paragraph: { spacing: { before: 480, after: 240 }, outlineLevel: 0 },
            },
            {
                id: 'Heading2', name: 'Heading 2', basedOn: 'Normal', next: 'Normal', quickFormat: true,
                run: { size: 30, bold: true, color: COLORS.slate800, font: 'Calibri' },
                paragraph: { spacing: { before: 320, after: 160 }, outlineLevel: 1 },
            },
            {
                id: 'Heading3', name: 'Heading 3', basedOn: 'Normal', next: 'Normal', quickFormat: true,
                run: { size: 26, bold: true, color: COLORS.slate600, font: 'Calibri' },
                paragraph: { spacing: { before: 240, after: 120 }, outlineLevel: 2 },
            },
        ],
    },
    numbering: {
        config: [
            {
                reference: 'bullets',
                levels: [
                    { level: 0, format: LevelFormat.BULLET, text: '•', alignment: AlignmentType.LEFT,
                      style: { paragraph: { indent: { left: 720, hanging: 360 } } } },
                    { level: 1, format: LevelFormat.BULLET, text: '◦', alignment: AlignmentType.LEFT,
                      style: { paragraph: { indent: { left: 1440, hanging: 360 } } } },
                ],
            },
            {
                reference: 'numbers',
                levels: [
                    { level: 0, format: LevelFormat.DECIMAL, text: '%1.', alignment: AlignmentType.LEFT,
                      style: { paragraph: { indent: { left: 720, hanging: 360 } } } },
                ],
            },
        ],
    },
    sections: [
        {
            properties: {
                page: {
                    size: { width: 11906, height: 16838 }, // A4
                    margin: { top: 1440, right: 1440, bottom: 1440, left: 1440 },
                },
            },
            headers: {
                default: new Header({
                    children: [new Paragraph({
                        alignment: AlignmentType.RIGHT,
                        children: [new TextRun({ text: `${PRODUCT} · Documentation Métier`, color: COLORS.slate400, size: 18, italics: true })],
                    })],
                }),
            },
            footers: {
                default: new Footer({
                    children: [new Paragraph({
                        alignment: AlignmentType.CENTER,
                        children: [
                            new TextRun({ text: 'Page ', color: COLORS.slate400, size: 18 }),
                            new TextRun({ children: [PageNumber.CURRENT], color: COLORS.slate400, size: 18 }),
                            new TextRun({ text: ' sur ', color: COLORS.slate400, size: 18 }),
                            new TextRun({ children: [PageNumber.TOTAL_PAGES], color: COLORS.slate400, size: 18 }),
                        ],
                    })],
                }),
            },
            children: [...titlePage, ...toc, ...content],
        },
    ],
});

const outputPath = path.join(__dirname, 'DOCUMENTATION_METIER.docx');
Packer.toBuffer(doc).then(buffer => {
    fs.writeFileSync(outputPath, buffer);
    console.log('✓ Document généré : ' + outputPath);
    console.log('  Taille : ' + (buffer.length / 1024).toFixed(1) + ' Ko');
}).catch(err => {
    console.error('Erreur lors de la génération :', err);
    process.exit(1);
});
