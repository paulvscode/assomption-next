<?php
declare(strict_types=1);

/*
 * Content model of the site.
 *
 * Every editable page is described here: its public address, who may edit
 * it, and the boxes ("fields") shown in the admin, grouped in sections.
 * The admin forms and the validation are generated from this file, so adding
 * a field here is all it takes to make it editable.
 *
 * Field types:
 *   text, textarea, rich (formatted text), lines (one bullet per line),
 *   image, file, gallery (several images), docs (list of downloadable files),
 *   table, repeater (list of blocks with sub-fields), toggle, select,
 *   pagelink (choose a page of the site), color, url, email, tel, date, datetime.
 * Option 'loc' => true means the field exists in French and in English.
 */

function fld(string $type, string $name, string $label, array $opts = []): array
{
    return ['type' => $type, 'name' => $name, 'label' => $label] + $opts;
}

function loc(string $type, string $name, string $label, array $opts = []): array
{
    return fld($type, $name, $label, ['loc' => true] + $opts);
}

function docs_field(string $name, string $label, array $opts = []): array
{
    return fld('repeater', $name, $label, $opts + [
        'item_label' => 'Document',
        'add_label' => 'Ajouter un document',
        'fields' => [
            loc('text', 'label', 'Nom affiché sur le site', ['placeholder' => 'Ex : Règlement intérieur 2025-2026']),
            fld('file', 'file', 'Fichier', ['help' => "Sans fichier, la ligne s'affiche avec la mention « À venir »."]),
        ],
    ]);
}

function members_field(string $name = 'members', string $label = 'Membres du bureau'): array
{
    return fld('repeater', $name, $label, [
        'item_label' => 'Membre',
        'add_label' => 'Ajouter un membre',
        'compact' => true,
        'fields' => [
            loc('text', 'role', 'Fonction', ['placeholder' => 'Ex : Présidente']),
            fld('text', 'name', 'Nom', ['placeholder' => 'Ex : Marie Dupont']),
        ],
    ]);
}

function summary_field(): array
{
    return loc('lines', 'summary', "L'essentiel en bref (facultatif)", ['help' => "2 à 4 points clés, un par ligne. Ils s'affichent dans un encadré en haut de la page, pour les visiteurs pressés."]);
}

function title_field(string $help = ''): array
{
    return loc('text', 'title', 'Titre de la page', ['required' => true, 'help' => $help ?: 'Le grand titre affiché en haut de la page.']);
}

/** Sections of the site menu, used to group pages in the admin like on the site. */
const MENU_GROUPS = [
    'general' => 'Accueil & réglages',
    'ecole' => "L'école",
    'programmes' => 'Accompagnement & inclusion',
    'pratique' => 'Infos pratiques & inscriptions',
    'apel' => 'APEL',
    'ogec' => 'OGEC',
    'legal' => 'Pages légales',
];

function page_defs(): array
{
    static $defs = null;
    if ($defs !== null) {
        return $defs;
    }
    $simpleBody = fn (string $help = '') => [
        'title' => 'Contenu',
        'fields' => [
            title_field(),
            summary_field(),
            loc('rich', 'body', 'Texte de la page', ['help' => $help]),
            fld('image', 'coverImage', 'Image (facultatif)', ['help' => 'Affichée sous le texte.']),
        ],
    ];

    $defs = [
        'settings' => [
            'label' => 'Réglages du site',
            'group' => 'general',
            'owner' => 'admin',
            'path' => null,
            'icon' => 'settings',
            'intro' => "Le logo, la couleur et les coordonnées de l'école. Ces informations apparaissent sur toutes les pages (en-tête, pied de page, page contact).",
            'sections' => [
                ['title' => 'Identité', 'fields' => [
                    fld('text', 'schoolName', "Nom de l'école", ['required' => true]),
                    fld('image', 'logo', 'Logo', ['help' => "Affiché en haut de chaque page. Idéalement un PNG à fond transparent, plus large que haut (ex. 300 × 100 pixels)."]),
                    fld('color', 'accentColor', "Couleur d'accent", ['help' => 'Couleur des petits losanges décoratifs et des flèches. Par défaut : #8BB1C5.']),
                    loc('textarea', 'footerText', 'Phrase du pied de page', ['rows' => 2]),
                    loc('text', 'labelText', 'Label mis en avant', ['help' => "Affiché comme un badge sur la page d'accueil et la page de présentation. Laissez vide pour ne rien afficher.", 'placeholder' => "Label Ouverture à l'International — niveau 2"]),
                    loc('textarea', 'description', 'Description du site pour Google', ['rows' => 2, 'max' => 160, 'help' => "Une ou deux phrases qui apparaissent sous le nom du site dans les résultats Google (160 caractères maximum)."]),
                ]],
                ['title' => 'Coordonnées', 'help' => "Utilisées sur la page d'accueil, la page « Localisation & contact » et les mentions légales.", 'fields' => [
                    fld('textarea', 'address', 'Adresse', ['rows' => 2, 'required' => true]),
                    fld('tel', 'phone', 'Téléphone'),
                    fld('email', 'email', 'Adresse e-mail'),
                    loc('textarea', 'hours', "Horaires d'ouverture", ['rows' => 2]),
                ]],
                ['title' => 'Liens utiles', 'help' => 'Affichés dans le pied de page : Class Dojo, réseaux sociaux, diocèse…', 'fields' => [
                    fld('repeater', 'links', 'Liens', [
                        'item_label' => 'Lien',
                        'add_label' => 'Ajouter un lien',
                        'compact' => true,
                        'fields' => [
                            loc('text', 'label', 'Texte du lien', ['placeholder' => 'Ex : Class Dojo']),
                            fld('url', 'url', 'Adresse', ['placeholder' => 'https://…']),
                        ],
                    ]),
                ]],
            ],
        ],

        'landing' => [
            'label' => "Page d'accueil",
            'group' => 'general',
            'owner' => 'ecole',
            'path' => '',
            'icon' => 'home',
            'intro' => "La première page que voient les visiteurs. Vous pouvez y afficher une annonce, changer la grande photo et les textes, et choisir les liens rapides.",
            'sections' => [
                ['title' => 'Annonce en haut de page', 'help' => "Un bandeau coloré en haut de la page d'accueil, pour une information importante (fermeture, réunion, inscriptions…).", 'fields' => [
                    fld('toggle', 'bannerEnabled', "Afficher l'annonce", ['on' => 'Annonce visible sur le site', 'off' => 'Annonce masquée']),
                    loc('text', 'bannerText', "Texte de l'annonce", ['max' => 200, 'placeholder' => "Ex : L'école sera fermée le vendredi 15 novembre."]),
                    fld('select', 'bannerStyle', 'Couleur du bandeau', ['options' => ['info' => 'Jaune — information', 'urgent' => 'Rouge — urgent', 'calm' => 'Bleu — rappel'], 'default' => 'info']),
                ]],
                ['title' => 'Grande image et texte de bienvenue', 'fields' => [
                    fld('image', 'heroImage', 'Grande photo de fond', ['help' => 'Une photo horizontale de bonne qualité (au moins 1600 pixels de large). Elle est un peu assombrie pour que le texte reste lisible.']),
                    loc('text', 'tagline', 'Petite ligne au-dessus du titre', ['placeholder' => 'Le Havre · École catholique']),
                    loc('text', 'h1', 'Titre principal', ['required' => true]),
                    loc('text', 'h2', 'Sous-titre'),
                    loc('textarea', 'statement', "Paragraphe d'introduction", ['rows' => 4]),
                    loc('text', 'cta', 'Texte du bouton « En savoir plus »'),
                    loc('text', 'ctaContact', 'Texte du bouton « Nous contacter »'),
                ]],
                ['title' => 'Accès rapide parents', 'help' => "Les boutons affichés juste sous la grande photo, vers les informations que les parents cherchent le plus.", 'fields' => [
                    loc('text', 'parentLinksTitle', 'Titre'),
                    fld('repeater', 'parentLinks', 'Boutons', [
                        'item_label' => 'Bouton',
                        'add_label' => 'Ajouter un bouton',
                        'fields' => [
                            loc('text', 'title', 'Texte du bouton', ['required' => true]),
                            fld('pagelink', 'page', 'Page de destination'),
                            fld('select', 'icon', 'Pictogramme', ['options' => [
                                'enrol' => 'Inscription', 'meal' => 'Repas', 'calendar' => 'Calendrier', 'bag' => 'Cartable',
                                'euro' => 'Euro', 'phone' => 'Téléphone', 'doc' => 'Document', 'info' => 'Information',
                            ], 'default' => 'info']),
                        ],
                    ]),
                ]],
                ['title' => 'Actualités et agenda', 'fields' => [
                    fld('toggle', 'newsEnabled', 'Afficher les 3 dernières actualités', ['on' => 'Section visible', 'off' => 'Section masquée']),
                    fld('toggle', 'eventsEnabled', 'Afficher les prochains événements', ['on' => 'Événements visibles', 'off' => 'Événements masqués', 'help' => "Les 3 prochains événements de l'APEL."]),
                    fld('toggle', 'holidayEnabled', 'Afficher la prochaine date du calendrier', ['on' => 'Date visible', 'off' => 'Date masquée', 'help' => "Calculée automatiquement à partir du « Calendrier des vacances » : rien à mettre à jour ici."]),
                ]],
                ['title' => 'Le mot de la direction', 'help' => "Un court message d'accueil signé, avec une photo. Il n'apparaît que si le texte est rempli et l'option activée.", 'fields' => [
                    fld('toggle', 'welcomeEnabled', 'Afficher le mot de la direction', ['on' => 'Visible sur le site', 'off' => 'Masqué']),
                    loc('text', 'welcomeTitle', 'Titre', ['placeholder' => 'Le mot de la directrice']),
                    loc('rich', 'welcomeText', 'Message'),
                    fld('text', 'welcomeName', 'Nom', ['placeholder' => 'Ex : Isabelle Queval']),
                    loc('text', 'welcomeRole', 'Fonction', ['placeholder' => "Chef d'établissement"]),
                    fld('image', 'welcomePhoto', 'Photo'),
                ]],
                ['title' => 'Liens rapides', 'help' => 'Les cartes avec photo qui mènent vers les pages importantes.', 'fields' => [
                    loc('text', 'linksTitle', 'Titre de la section'),
                    fld('repeater', 'quickLinks', 'Cartes', [
                        'item_label' => 'Carte',
                        'add_label' => 'Ajouter une carte',
                        'fields' => [
                            loc('text', 'title', 'Texte de la carte', ['required' => true]),
                            fld('pagelink', 'page', 'Page de destination'),
                            fld('image', 'image', 'Photo'),
                        ],
                    ]),
                ]],
                ['title' => 'Bloc contact', 'help' => "Les coordonnées elles-mêmes se modifient dans « Réglages du site ».", 'fields' => [
                    loc('text', 'contactTitle', 'Titre du bloc contact'),
                ]],
            ],
        ],

        // ── Notre école ────────────────────────────────────────────────────
        'schoolPresentation' => [
            'label' => "Présentation de l'école",
            'group' => 'ecole',
            'owner' => 'ecole',
            'path' => 'notre-ecole/presentation',
            'sections' => [
                ['title' => 'Contenu', 'fields' => [
                    title_field(),
                    loc('rich', 'body', 'Texte de présentation'),
                ]],
                ['title' => 'Chiffres clés', 'help' => "Une bande de 3 ou 4 chiffres marquants, affichée en haut de la page.", 'fields' => [
                    fld('repeater', 'stats', 'Chiffres', [
                        'item_label' => 'Chiffre',
                        'add_label' => 'Ajouter un chiffre',
                        'compact' => true,
                        'fields' => [
                            loc('text', 'value', 'Chiffre ou mot court', ['placeholder' => 'Ex : 300']),
                            loc('text', 'label', 'Légende', ['placeholder' => 'Ex : élèves accueillis']),
                        ],
                    ]),
                ]],
                ['title' => 'Nos valeurs', 'fields' => [
                    loc('text', 'valuesTitle', 'Phrase d’introduction des valeurs'),
                    loc('lines', 'values', 'Valeurs', ['help' => 'Une valeur par ligne.']),
                ]],
                ['title' => 'Photos', 'fields' => [
                    fld('gallery', 'images', 'Photos (4 conseillées)', ['help' => 'Affichées en carrés sous le texte.', 'max_items' => 8]),
                ]],
            ],
        ],
        'educationalProject' => [
            'label' => 'Projet éducatif',
            'group' => 'ecole',
            'owner' => 'ecole',
            'path' => 'notre-ecole/projet-educatif',
            'sections' => [
                ['title' => 'Contenu', 'fields' => [
                    title_field(),
                    loc('rich', 'intro', 'Introduction (facultatif)'),
                    fld('repeater', 'cards', 'Encadrés', [
                        'item_label' => 'Encadré',
                        'add_label' => 'Ajouter un encadré',
                        'fields' => [
                            loc('text', 'title', "Titre de l'encadré"),
                            loc('lines', 'items', 'Points', ['help' => 'Un point par ligne.']),
                        ],
                    ]),
                ]],
            ],
        ],
        'schoolProject' => [
            'label' => "Projet d'établissement",
            'group' => 'ecole',
            'owner' => 'ecole',
            'path' => 'notre-ecole/projet-etablissement',
            'sections' => [
                ['title' => 'Contenu', 'fields' => [
                    title_field(),
                    loc('textarea', 'intro', 'Introduction', ['rows' => 3]),
                    fld('repeater', 'cards', 'Axes du projet', [
                        'item_label' => 'Axe',
                        'add_label' => 'Ajouter un axe',
                        'fields' => [
                            loc('text', 'badge', 'Étiquette', ['placeholder' => 'Ex : 2025 - 2026']),
                            loc('text', 'title', 'Titre'),
                            loc('textarea', 'description', 'Description', ['rows' => 4]),
                            loc('rich', 'body', 'Actions menées'),
                            fld('image', 'image', 'Photo'),
                        ],
                    ]),
                    fld('file', 'document', 'Document complet à télécharger (facultatif)'),
                ]],
            ],
        ],
        'pastoralMission' => [
            'label' => 'Mission pastorale',
            'group' => 'ecole',
            'owner' => 'ecole',
            'path' => 'notre-ecole/mission-pastorale',
            'sections' => [
                ['title' => 'Contenu', 'fields' => [
                    title_field(),
                    summary_field(),
                    loc('textarea', 'statement', 'Phrase mise en avant', ['rows' => 3, 'help' => 'Affichée en grand et en italique sous le titre.']),
                    fld('repeater', 'blocks', 'Paragraphes avec photo', [
                        'item_label' => 'Paragraphe',
                        'add_label' => 'Ajouter un paragraphe',
                        'fields' => [
                            loc('rich', 'text', 'Texte'),
                            fld('image', 'image', 'Photo à côté du texte'),
                        ],
                    ]),
                    loc('rich', 'highlight', 'Encadré gris', ['help' => 'Le texte affiché dans le grand encadré en bas de page.']),
                    fld('image', 'bottomImage', 'Grande photo en bas de page'),
                ]],
            ],
        ],
        'professorsPage' => [
            'label' => 'Équipe enseignante',
            'group' => 'ecole',
            'owner' => 'ecole',
            'path' => 'notre-ecole/equipe-enseignante',
            'intro' => "À mettre à jour à chaque rentrée : année scolaire, enseignants et personnel.",
            'sections' => [
                ['title' => 'En-tête', 'fields' => [
                    loc('textarea', 'title', 'Titre de la page', ['rows' => 2, 'required' => true]),
                    loc('text', 'subtitle', 'Année scolaire', ['placeholder' => 'Année scolaire 2025 / 2026']),
                ]],
                ['title' => 'Enseignants', 'fields' => [
                    fld('repeater', 'groups', 'Groupes', [
                        'item_label' => 'Groupe',
                        'add_label' => 'Ajouter un groupe',
                        'fields' => [
                            loc('text', 'title', 'Nom du groupe', ['placeholder' => 'Ex : Enseignantes de maternelle']),
                            loc('lines', 'members', 'Personnes', ['help' => 'Une personne par ligne, par exemple : Sophie Patin — PS/MS']),
                        ],
                    ]),
                ]],
                ['title' => 'Autres membres de l’équipe', 'fields' => [
                    loc('text', 'supportTitle', 'Titre'),
                    loc('lines', 'supportMembers', 'Personnes', ['help' => 'Une ligne par fonction.']),
                ]],
                ['title' => "Chef d'établissement et photo", 'fields' => [
                    loc('text', 'principalLabel', 'Intitulé'),
                    loc('text', 'principal', 'Texte'),
                    fld('image', 'teamPhoto', "Photo de l'équipe"),
                ]],
            ],
        ],
        'englishProgram' => [
            'label' => 'Programme anglais',
            'group' => 'ecole',
            'owner' => 'ecole',
            'path' => 'notre-ecole/programme-anglais',
            'sections' => [$simpleBody()],
        ],

        // ── Programmes spéciaux ────────────────────────────────────────────
        'ashPage' => [
            'label' => 'Dispositif ASH',
            'group' => 'programmes',
            'owner' => 'ecole',
            'path' => 'programmes-speciaux/ash',
            'sections' => [$simpleBody()],
        ],
        'ulisPage' => [
            'label' => 'ULIS',
            'group' => 'programmes',
            'owner' => 'ecole',
            'path' => 'programmes-speciaux/ulis',
            'sections' => [$simpleBody()],
        ],
        'englishClassPage' => [
            'label' => 'La classe anglophone',
            'group' => 'programmes',
            'owner' => 'ecole',
            'path' => 'programmes-speciaux/classe-anglophone',
            'sections' => [
                ['title' => 'Contenu', 'fields' => [
                    title_field(),
                    summary_field(),
                    loc('text', 'subtitle', 'Phrase d’introduction'),
                    fld('repeater', 'cases', "Cas d'admission", [
                        'item_label' => 'Cas',
                        'add_label' => 'Ajouter un cas',
                        'fields' => [
                            loc('textarea', 'description', 'Description', ['rows' => 2]),
                            loc('textarea', 'condition', 'Condition', ['rows' => 2]),
                        ],
                    ]),
                    loc('rich', 'text', 'Fonctionnement'),
                    loc('text', 'activitiesTitle', 'Titre de la liste des activités'),
                    loc('lines', 'activities', 'Activités', ['help' => 'Une activité par ligne.']),
                    loc('textarea', 'finalWord', 'Phrase de conclusion', ['rows' => 2]),
                ]],
            ],
        ],

        // ── Informations pratiques ─────────────────────────────────────────
        'locationContact' => [
            'label' => 'Localisation & contact',
            'group' => 'pratique',
            'owner' => 'ecole',
            'path' => 'informations-pratiques/localisation-contact',
            'intro' => "L'adresse, le téléphone, l'e-mail et les horaires viennent des « Réglages du site ».",
            'sections' => [
                ['title' => 'Contenu', 'fields' => [
                    title_field(),
                    fld('toggle', 'showMap', 'Afficher la carte', ['on' => 'Carte visible', 'off' => 'Carte masquée']),
                    fld('text', 'mapAddress', 'Adresse utilisée pour la carte', ['help' => "Ce que l'on taperait dans un GPS. Laissez vide pour utiliser l'adresse des réglages."]),
                    loc('rich', 'additionalInfo', 'Informations complémentaires (facultatif)'),
                ]],
            ],
        ],
        'cafeteria' => [
            'label' => 'Restauration scolaire',
            'group' => 'pratique',
            'owner' => 'ecole',
            'path' => 'informations-pratiques/restauration',
            'sections' => [
                ['title' => 'Contenu', 'fields' => [
                    title_field(),
                    loc('rich', 'body', 'Texte'),
                    loc('text', 'linkLabel', 'Texte du bouton des menus'),
                    fld('url', 'linkUrl', 'Adresse du site des menus', ['placeholder' => 'https://…']),
                    fld('file', 'menuFile', 'Menu à télécharger (facultatif)'),
                ]],
            ],
        ],
        'applicationForm' => [
            'label' => "Formulaire d'inscription",
            'group' => 'pratique',
            'owner' => 'ecole',
            'path' => 'informations-pratiques/inscription',
            'sections' => [
                ['title' => 'Fiche d’inscription', 'fields' => [
                    title_field(),
                    loc('text', 'downloadLabel', 'Phrase au-dessus du bouton'),
                    fld('file', 'formFile', "Fiche d'inscription (PDF)", ['help' => "Tant qu'aucun fichier n'est ajouté, le site affiche « Document à venir »."]),
                ]],
                ['title' => 'Pièces à fournir', 'fields' => [
                    loc('text', 'list1Title', 'Titre de la liste'),
                    loc('lines', 'list1', 'Documents à transmettre', ['help' => 'Un document par ligne.']),
                ]],
                ['title' => 'Questions fréquentes', 'help' => "Les questions que les familles posent le plus souvent au secrétariat.", 'fields' => [
                    loc('text', 'faqTitle', 'Titre'),
                    fld('repeater', 'faq', 'Questions', [
                        'item_label' => 'Question',
                        'add_label' => 'Ajouter une question',
                        'fields' => [
                            loc('text', 'question', 'Question'),
                            loc('textarea', 'answer', 'Réponse', ['rows' => 3]),
                        ],
                    ]),
                ]],
                ['title' => 'Autres documents', 'fields' => [
                    loc('text', 'list2Title', 'Titre'),
                    docs_field('otherDocs', 'Documents à consulter'),
                ]],
            ],
        ],
        'fees' => [
            'label' => 'Frais de scolarité',
            'group' => 'pratique',
            'owner' => 'ecole',
            'path' => 'informations-pratiques/frais',
            'sections' => [
                ['title' => 'Contenu', 'fields' => [
                    title_field(),
                    loc('rich', 'intro', 'Texte (facultatif)'),
                    docs_field('documents', 'Documents'),
                ]],
            ],
        ],
        'backToSchool' => [
            'label' => 'Rentrée scolaire',
            'group' => 'pratique',
            'owner' => 'ecole',
            'path' => 'informations-pratiques/rentree',
            'intro' => 'À mettre à jour avant chaque rentrée : dates, horaires et listes de fournitures.',
            'sections' => [
                ['title' => 'Informations générales', 'fields' => [
                    title_field(),
                    loc('rich', 'intro', 'Texte'),
                ]],
                ['title' => 'Horaires', 'fields' => [
                    loc('text', 'table1Title', 'Titre du premier tableau'),
                    loc('table', 'table1', 'Premier tableau'),
                    loc('text', 'table2Title', 'Titre du second tableau'),
                    loc('table', 'table2', 'Second tableau'),
                ]],
                ['title' => 'Note de rentrée', 'fields' => [
                    loc('textarea', 'noteText', 'Texte', ['rows' => 2]),
                    fld('file', 'noteFile', 'Note de rentrée (PDF)'),
                ]],
                ['title' => 'Listes de fournitures', 'fields' => [
                    loc('text', 'listsTitle', 'Titre'),
                    docs_field('supplyLists', 'Listes'),
                ]],
            ],
        ],
        'schoolBreaks' => [
            'label' => 'Calendrier des vacances',
            'group' => 'pratique',
            'owner' => 'ecole',
            'path' => 'informations-pratiques/vacances',
            'sections' => [
                ['title' => 'Contenu', 'fields' => [
                    title_field(),
                    loc('rich', 'intro', 'Texte (facultatif)'),
                    fld('repeater', 'periods', 'Dates du calendrier', [
                        'item_label' => 'Date',
                        'add_label' => 'Ajouter une période',
                        'help' => "Les dates s'écrivent automatiquement en toutes lettres, en français et en anglais. La prochaine date s'affiche aussi sur la page d'accueil.",
                        'fields' => [
                            loc('text', 'name', 'Nom', ['placeholder' => 'Ex : Vacances de la Toussaint']),
                            fld('date', 'start', 'Date (ou début)', ['required' => true]),
                            fld('date', 'end', 'Fin (facultatif)', ['help' => 'Jour de reprise des cours, pour des vacances.']),
                        ],
                    ]),
                    fld('file', 'calendarFile', 'Calendrier à télécharger (facultatif)'),
                ]],
            ],
        ],
        'otherDocuments' => [
            'label' => 'Autres documents',
            'group' => 'pratique',
            'owner' => 'ecole',
            'path' => 'informations-pratiques/documents',
            'sections' => [
                ['title' => 'Contenu', 'fields' => [
                    title_field(),
                    loc('text', 'intro', 'Phrase d’introduction'),
                    docs_field('documents', 'Documents'),
                ]],
            ],
        ],

        // ── APEL / OGEC ────────────────────────────────────────────────────
        'apelPresentation' => [
            'label' => "Présentation de l'APEL",
            'group' => 'apel',
            'owner' => 'apel',
            'path' => 'apel/presentation',
            'sections' => [
                ['title' => 'Présentation', 'fields' => [
                    title_field(),
                    summary_field(),
                    loc('rich', 'body', 'Texte de présentation'),
                ]],
                ['title' => 'Le bureau', 'fields' => [
                    loc('text', 'compositionTitle', 'Titre'),
                    members_field(),
                ]],
                ['title' => 'Contact et inscription', 'fields' => [
                    loc('text', 'contactTitle', 'Titre'),
                    loc('lines', 'contactItems', 'Moyens de contact', ['help' => 'Un moyen de contact par ligne.']),
                    loc('textarea', 'signupText', 'Texte au-dessus du bouton', ['rows' => 2]),
                    loc('text', 'signupLabel', 'Texte du bouton'),
                    fld('url', 'signupUrl', 'Lien du bouton', ['placeholder' => 'https://…']),
                    loc('text', 'closing', 'Phrase de fin'),
                ]],
            ],
        ],
        'ogecPresentation' => [
            'label' => "Présentation de l'OGEC",
            'group' => 'ogec',
            'owner' => 'ogec',
            'path' => 'ogec/presentation',
            'sections' => [
                ['title' => 'Présentation', 'fields' => [
                    title_field(),
                    summary_field(),
                    loc('text', 'headline', 'Sous-titre'),
                    loc('rich', 'body', 'Texte de présentation'),
                ]],
                ['title' => "Domaines d'action", 'fields' => [
                    loc('text', 'domainsTitle', 'Titre'),
                    fld('repeater', 'domains', 'Domaines', [
                        'item_label' => 'Domaine',
                        'add_label' => 'Ajouter un domaine',
                        'fields' => [
                            loc('text', 'name', 'Nom'),
                            loc('textarea', 'description', 'Description', ['rows' => 2]),
                        ],
                    ]),
                    loc('textarea', 'closingText', 'Texte de conclusion', ['rows' => 3]),
                ]],
                ['title' => 'Le bureau', 'fields' => [
                    loc('text', 'compositionTitle', 'Titre'),
                    members_field(),
                ]],
            ],
        ],

        // ── Legal ──────────────────────────────────────────────────────────
        'legal' => [
            'label' => 'Mentions légales',
            'group' => 'legal',
            'owner' => 'admin',
            'path' => 'mentions-legales',
            'sections' => [['title' => 'Contenu', 'fields' => [title_field(), loc('rich', 'body', 'Texte')]]],
        ],
        'privacy' => [
            'label' => 'Politique de confidentialité',
            'group' => 'legal',
            'owner' => 'admin',
            'path' => 'confidentialite',
            'sections' => [['title' => 'Contenu', 'fields' => [title_field(), loc('rich', 'body', 'Texte')]]],
        ],
    ];
    return $defs;
}

function page_def(string $key): ?array
{
    return page_defs()[$key] ?? null;
}

/** Fields of a page, flattened (sections removed). */
function page_fields(array $def): array
{
    $out = [];
    foreach ($def['sections'] as $s) {
        foreach ($s['fields'] as $f) {
            $out[] = $f;
        }
    }
    return $out;
}

// ── News & events ──────────────────────────────────────────────────────────

const ENTRY_SECTIONS = [
    'ecole' => ['label' => "École", 'news_path' => 'actualites'],
    'apel' => ['label' => 'APEL', 'news_path' => 'apel/actualites'],
    'ogec' => ['label' => 'OGEC', 'news_path' => 'ogec/actualites'],
];

function entry_fields(string $type): array
{
    $common = [
        'body' => loc('rich', 'body', 'Texte complet', ['help' => "Le texte affiché quand on ouvre l'article."]),
        'image' => fld('image', 'coverImage', 'Image principale', ['help' => 'Affichée sur la carte et en haut de l’article. Une photo horizontale rend mieux.']),
        'gallery' => fld('gallery', 'gallery', 'Autres photos (facultatif)', ['max_items' => 24]),
        'docs' => docs_field('documents', 'Documents joints (facultatif)'),
    ];
    if ($type === 'event') {
        return [
            ['title' => "L'essentiel", 'fields' => [
                loc('text', 'title', "Nom de l'événement", ['required' => true, 'placeholder' => 'Ex : Kermesse de fin d’année']),
                fld('datetime', 'start', 'Début', ['required' => true]),
                fld('datetime', 'end', 'Fin (facultatif)'),
                loc('text', 'location', 'Lieu', ['placeholder' => "Ex : Cour de l'école"]),
                loc('textarea', 'excerpt', 'Résumé court', ['rows' => 2, 'max' => 300, 'help' => 'Une ou deux phrases affichées dans la liste des événements.']),
            ]],
            ['title' => 'Description et photos', 'fields' => [$common['image'], $common['body'], $common['gallery'], $common['docs']]],
        ];
    }
    return [
        ['title' => "L'essentiel", 'fields' => [
            loc('text', 'title', 'Titre', ['required' => true, 'placeholder' => 'Ex : Retour sur la classe de découverte']),
            fld('date', 'date', 'Date affichée', ['required' => true, 'help' => "Les actualités sont classées de la plus récente à la plus ancienne selon cette date."]),
            loc('textarea', 'excerpt', 'Résumé court', ['rows' => 2, 'max' => 300, 'help' => "Une ou deux phrases affichées sur la carte de l'actualité."]),
            $common['image'],
        ]],
        ['title' => 'Article', 'fields' => [$common['body'], $common['gallery'], $common['docs']]],
    ];
}

// ── Public site menu ───────────────────────────────────────────────────────

function site_menu(): array
{
    $l = fn (string $label, string $path) => ['label' => $label, 'path' => $path];
    return [
        ['label' => 'ourSchool', 'groups' => [
            ['heading' => null, 'children' => [
                $l('schoolPresentation', 'notre-ecole/presentation'),
                $l('educationalProject', 'notre-ecole/projet-educatif'),
                $l('schoolProject', 'notre-ecole/projet-etablissement'),
                $l('pastoralMission', 'notre-ecole/mission-pastorale'),
                $l('professors', 'notre-ecole/equipe-enseignante'),
                $l('englishProgram', 'notre-ecole/programme-anglais'),
            ]],
            ['heading' => 'support', 'children' => [
                $l('ash', 'programmes-speciaux/ash'),
                $l('ulis', 'programmes-speciaux/ulis'),
                $l('englishClass', 'programmes-speciaux/classe-anglophone'),
            ]],
        ]],
        ['label' => 'practicalInfo', 'groups' => [
            ['heading' => null, 'children' => [
                $l('locationContact', 'informations-pratiques/localisation-contact'),
                $l('application', 'informations-pratiques/inscription'),
                $l('fees', 'informations-pratiques/frais'),
                $l('cafeteria', 'informations-pratiques/restauration'),
                $l('backToSchool', 'informations-pratiques/rentree'),
                $l('schoolBreaks', 'informations-pratiques/vacances'),
                $l('documents', 'informations-pratiques/documents'),
            ]],
        ]],
        ['label' => 'schoolLife', 'groups' => [
            ['heading' => null, 'children' => [
                $l('schoolNews', 'actualites'),
            ]],
            ['heading' => 'apel', 'children' => [
                $l('apelPresentation', 'apel/presentation'),
                $l('apelNews', 'apel/actualites'),
                $l('events', 'apel/evenements'),
            ]],
            ['heading' => 'ogec', 'children' => [
                $l('ogecPresentation', 'ogec/presentation'),
                $l('ogecNews', 'ogec/actualites'),
            ]],
        ]],
    ];
}

/** Choices for "pagelink" fields: every public page of the site. */
function linkable_pages(): array
{
    $out = [];
    foreach (page_defs() as $def) {
        if ($def['path'] !== null && $def['path'] !== '') {
            $out[$def['path']] = $def['label'];
        }
    }
    $out['actualites'] = "Actualités de l'école";
    $out['apel/actualites'] = 'Actualités APEL';
    $out['apel/evenements'] = 'Événements APEL';
    $out['ogec/actualites'] = 'Actualités OGEC';
    asort($out, SORT_LOCALE_STRING);
    return $out;
}
