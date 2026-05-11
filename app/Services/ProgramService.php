<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\ProgramStatus;
use App\Models\Program;
use App\Models\User;
use App\Repositories\Contracts\ProgramRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ProgramService
{
    public function __construct(private ProgramRepositoryInterface $programs)
    {
    }

    /**
     * Crée un programme avec ses champs standards et critères par défaut.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data, User $creator): Program
    {
        return DB::transaction(function () use ($data, $creator) {
            $data['created_by'] = $creator->id;
            $data['status']     = $data['status'] ?? ProgramStatus::Draft->value;

            /** @var Program $program */
            $program = $this->programs->create($data);

            $this->seedStandardFields($program);
            $this->seedDefaultCriteria($program);

            return $program;
        });
    }

    public function update(Program $program, array $data): Program
    {
        return $this->programs->update($program, $data);
    }

    public function changeStatus(Program $program, ProgramStatus $status): Program
    {
        return $this->programs->update($program, ['status' => $status->value]);
    }

    public function archive(Program $program): Program
    {
        return $this->changeStatus($program, ProgramStatus::Archived);
    }

    /**
     * Sections standard FIXES qui sont générées pour chaque programme.
     *
     * Toutes ces sections sont obligatoires et identiques pour tous les
     * programmes. La section "dynamic" (spécifique au programme) est à
     * configurer séparément via le Form Builder admin.
     *
     * Sections :
     *   - identity        : Identité du candidat
     *   - address         : Coordonnées
     *   - id_document     : Pièce d'identité
     *   - academic        : Parcours académique
     *   - experience      : Expérience & engagement
     *   - health          : Santé & sécurité (confidentiel)
     *   - parents         : Parent / tuteur principal
     *   - emergency       : Contact d'urgence
     *   - dynamic         : Section programme (vide à la création)
     *   - declaration     : Déclaration & engagement final
     */
    public function seedStandardFields(Program $program): void
    {
        $sections = $this->standardFieldsBlueprint();
        $position = 0;

        foreach ($sections as $sectionKey => $fields) {
            foreach ($fields as $field) {
                $program->applicationFields()->firstOrCreate(
                    ['program_id' => $program->id, 'key' => $field['key']],
                    array_merge($field, [
                        'section'      => $sectionKey,
                        'order_column' => $position++,
                    ]),
                );
            }
        }
    }

    /**
     * Définition centralisée et versionnable des champs standard.
     *
     * @return array<string, array<int, array<string, mixed>>>
     */
    protected function standardFieldsBlueprint(): array
    {
        $civilOptions = [
            ['label' => 'Célibataire', 'value' => 'celibataire'],
            ['label' => 'Marié(e)',    'value' => 'marie'],
            ['label' => 'Divorcé(e)',  'value' => 'divorce'],
            ['label' => 'Veuf(ve)',    'value' => 'veuf'],
        ];

        $sexeOptions = [
            ['label' => 'Féminin',     'value' => 'F'],
            ['label' => 'Masculin',    'value' => 'M'],
            ['label' => 'Autre',       'value' => 'O'],
        ];

        $idOptions = [
            ['label' => 'CNI',         'value' => 'cni'],
            ['label' => 'Passeport',   'value' => 'passeport'],
            ['label' => 'Attestation', 'value' => 'attestation'],
            ['label' => 'Autre',       'value' => 'autre'],
        ];

        $bacOptions = [
            ['label' => 'A1', 'value' => 'A1'], ['label' => 'A2', 'value' => 'A2'],
            ['label' => 'C',  'value' => 'C'],  ['label' => 'D',  'value' => 'D'],
            ['label' => 'E',  'value' => 'E'],  ['label' => 'F',  'value' => 'F'],
            ['label' => 'G',  'value' => 'G'],  ['label' => 'Autre', 'value' => 'autre'],
        ];

        $yesNo = [
            ['label' => 'Oui', 'value' => 'oui'],
            ['label' => 'Non', 'value' => 'non'],
        ];

        return [
            // ÉTAPE 1 - Section 1.1 : Identité
            'identity' => [
                ['key' => 'nom',                 'label' => 'Nom',                 'type' => 'text',     'is_required' => true],
                ['key' => 'prenoms',             'label' => 'Prénom(s)',           'type' => 'text',     'is_required' => true],
                ['key' => 'date_naissance',      'label' => 'Date de naissance',   'type' => 'date',     'is_required' => true],
                ['key' => 'lieu_naissance',      'label' => 'Lieu de naissance',   'type' => 'text',     'is_required' => true],
                ['key' => 'nationalite',         'label' => 'Nationalité',         'type' => 'text',     'is_required' => true],
                ['key' => 'sexe',                'label' => 'Sexe',                'type' => 'radio',    'is_required' => true, 'options' => $sexeOptions],
                ['key' => 'situation_matrimoniale', 'label' => 'Situation matrimoniale', 'type' => 'select', 'is_required' => true, 'options' => $civilOptions],
            ],

            // ÉTAPE 1 - Section 1.2 : Coordonnées
            'address' => [
                ['key' => 'adresse_complete',    'label' => 'Adresse complète',    'type' => 'textarea', 'is_required' => true],
                ['key' => 'ville',               'label' => 'Ville',               'type' => 'text',     'is_required' => true],
                ['key' => 'commune_quartier',    'label' => 'Commune / Quartier',  'type' => 'text',     'is_required' => false],
                ['key' => 'telephone_principal', 'label' => 'Téléphone principal', 'type' => 'tel',      'is_required' => true],
                ['key' => 'telephone_secondaire','label' => 'Téléphone secondaire','type' => 'tel',      'is_required' => false],
                ['key' => 'email_personnel',     'label' => 'Email personnel',     'type' => 'email',    'is_required' => true],
            ],

            // ÉTAPE 1 - Section 1.3 : Pièce d'identité
            'id_document' => [
                ['key' => 'piece_type',          'label' => 'Type de pièce d\'identité', 'type' => 'select', 'is_required' => true, 'options' => $idOptions],
                ['key' => 'piece_numero',        'label' => 'Numéro de la pièce',  'type' => 'text',     'is_required' => true],
                ['key' => 'piece_date_expiration','label' => 'Date d\'expiration',  'type' => 'date',     'is_required' => false],
                ['key' => 'piece_scan',          'label' => 'Copie scannée de la pièce', 'type' => 'file', 'is_required' => true,
                    'validation_rules' => ['mimes:pdf,jpg,jpeg,png', 'max:5120'],
                    'help_text' => 'PDF ou image, max 5 Mo.'],
            ],

            // ÉTAPE 1 - Section 1.4 : Parcours académique
            'academic' => [
                ['key' => 'dernier_diplome',     'label' => 'Dernier diplôme obtenu', 'type' => 'text', 'is_required' => true],
                ['key' => 'serie_bac',           'label' => 'Série du Baccalauréat','type' => 'select', 'is_required' => false, 'options' => $bacOptions],
                ['key' => 'annee_bac',           'label' => 'Année d\'obtention du Bac', 'type' => 'number', 'is_required' => false],
                ['key' => 'etablissement_bac',   'label' => 'Établissement du Bac',  'type' => 'text', 'is_required' => false],
                ['key' => 'niveau_etude_actuel', 'label' => 'Niveau d\'étude actuel','type' => 'text', 'is_required' => false],
                ['key' => 'universite_institut', 'label' => 'Université / Institut fréquenté', 'type' => 'text', 'is_required' => false],
                ['key' => 'domaine_etude',       'label' => 'Domaine d\'étude',     'type' => 'text', 'is_required' => false],
                ['key' => 'autres_formations',   'label' => 'Autres formations / certifications', 'type' => 'textarea', 'is_required' => false],
            ],

            // ÉTAPE 1 - Section 1.5 : Expérience & engagement
            'experience' => [
                ['key' => 'experiences_pro',     'label' => 'Expériences professionnelles', 'type' => 'textarea', 'is_required' => false],
                ['key' => 'associations_clubs',  'label' => 'Associations / Clubs / Organisations d\'appartenance', 'type' => 'textarea', 'is_required' => false],
                ['key' => 'competences_particulieres','label' => 'Compétences particulières', 'type' => 'textarea', 'is_required' => false],
                ['key' => 'cv_upload',           'label' => 'CV (PDF)',             'type' => 'file', 'is_required' => true,
                    'validation_rules' => ['mimes:pdf,doc,docx', 'max:10240'],
                    'help_text' => 'PDF ou DOCX, max 10 Mo.'],
            ],

            // ÉTAPE 1 - Section 1.6 : Santé & sécurité (CONFIDENTIEL)
            'health' => [
                ['key' => 'maladie_chronique',   'label' => 'Souffrez-vous d\'une maladie chronique ?', 'type' => 'radio', 'is_required' => true, 'options' => $yesNo],
                ['key' => 'maladie_chronique_precision', 'label' => 'Si oui, précisez',  'type' => 'textarea', 'is_required' => false],
                ['key' => 'allergies',           'label' => 'Avez-vous des allergies (alimentaires, médicamenteuses, autres) ?', 'type' => 'radio', 'is_required' => true, 'options' => $yesNo],
                ['key' => 'allergies_precision', 'label' => 'Si oui, précisez',  'type' => 'textarea', 'is_required' => false],
                ['key' => 'traitement_medical',  'label' => 'Êtes-vous sous traitement médical régulier ?', 'type' => 'radio', 'is_required' => true, 'options' => $yesNo],
                ['key' => 'traitement_medical_precision', 'label' => 'Si oui, précisez', 'type' => 'textarea', 'is_required' => false],
                ['key' => 'contre_indications',  'label' => 'Avez-vous une contre-indication médicale à certaines activités ?', 'type' => 'textarea', 'is_required' => false],
                ['key' => 'restrictions_alimentaires', 'label' => 'Restrictions alimentaires', 'type' => 'textarea', 'is_required' => false],
                ['key' => 'situations_particulieres', 'label' => 'Situations particulières que nous devons connaître pour votre sécurité', 'type' => 'textarea', 'is_required' => false],
                ['key' => 'groupe_sanguin',      'label' => 'Groupe sanguin (facultatif mais recommandé)', 'type' => 'text', 'is_required' => false],
                ['key' => 'contact_medical',     'label' => 'Contact médical habituel (médecin de famille)', 'type' => 'text', 'is_required' => false],
            ],

            // ÉTAPE 2 - Section 2.1 : Parent / Tuteur principal
            'parents' => [
                ['key' => 'parent_nom_prenom',   'label' => 'Nom et prénom du parent / tuteur', 'type' => 'text', 'is_required' => true],
                ['key' => 'parent_lien',         'label' => 'Lien avec le candidat',  'type' => 'text', 'is_required' => true],
                ['key' => 'parent_profession',   'label' => 'Profession',            'type' => 'text', 'is_required' => false],
                ['key' => 'parent_telephone',    'label' => 'Téléphone',             'type' => 'tel',  'is_required' => true],
                ['key' => 'parent_email',        'label' => 'Email',                 'type' => 'email','is_required' => false],
                ['key' => 'parent_adresse',      'label' => 'Adresse',               'type' => 'textarea', 'is_required' => false],
            ],

            // ÉTAPE 2 - Section 2.2 : Contact d'urgence
            'emergency' => [
                ['key' => 'urgence_nom_prenom',  'label' => 'Nom et prénom du contact', 'type' => 'text', 'is_required' => true],
                ['key' => 'urgence_lien',        'label' => 'Lien avec le candidat',  'type' => 'text', 'is_required' => true],
                ['key' => 'urgence_telephone',   'label' => 'Téléphone principal',   'type' => 'tel',  'is_required' => true],
                ['key' => 'urgence_telephone_2', 'label' => 'Téléphone secondaire',  'type' => 'tel',  'is_required' => false],
                ['key' => 'urgence_adresse',     'label' => 'Adresse',               'type' => 'textarea', 'is_required' => false],
            ],

            // ÉTAPE 3 : section dynamique propre au programme — vide à la création.
            // (Géré par le Form Builder admin via ApplicationFieldController)
            'dynamic' => [
                // Champ d'exemple. L'admin peut le supprimer / remplacer.
                ['key' => 'lettre_motivation',   'label' => 'Lettre de motivation',  'type' => 'textarea', 'is_required' => true,
                    'help_text' => 'Présentez en quelques paragraphes pourquoi vous candidatez à ce programme.'],
            ],

            // Section finale : déclaration & engagement
            'declaration' => [
                ['key' => 'declaration_exactitude', 'label' => 'Je certifie que les informations fournies sont exactes', 'type' => 'checkbox', 'is_required' => true,
                    'options' => [['label' => 'Je certifie', 'value' => '1']]],
                ['key' => 'declaration_reglement',  'label' => 'Je m\'engage à respecter le règlement intérieur du programme', 'type' => 'checkbox', 'is_required' => true,
                    'options' => [['label' => 'Je m\'engage', 'value' => '1']]],
                ['key' => 'declaration_donnees',    'label' => 'J\'autorise l\'utilisation de mes données dans le cadre administratif du programme', 'type' => 'checkbox', 'is_required' => true,
                    'options' => [['label' => 'J\'autorise', 'value' => '1']]],
                ['key' => 'declaration_urgence',    'label' => 'J\'autorise l\'organisation à prendre les mesures nécessaires en cas d\'urgence médicale', 'type' => 'checkbox', 'is_required' => true,
                    'options' => [['label' => 'J\'autorise', 'value' => '1']]],
            ],
        ];
    }

    protected function seedDefaultCriteria(Program $program): void
    {
        $defaults = [
            ['label' => 'Pertinence du parcours',   'weight' => 2, 'max_score' => 20],
            ['label' => 'Qualité du projet',        'weight' => 3, 'max_score' => 20],
            ['label' => 'Impact attendu',           'weight' => 3, 'max_score' => 20],
            ['label' => 'Motivation et engagement', 'weight' => 2, 'max_score' => 20],
        ];

        foreach ($defaults as $i => $c) {
            $program->evaluationCriteria()->firstOrCreate(
                ['program_id' => $program->id, 'label' => $c['label']],
                array_merge($c, ['order_column' => $i]),
            );
        }
    }

    public function dashboardStats(): array
    {
        return $this->programs->statsForDashboard();
    }
}
