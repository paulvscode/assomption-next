<?php
declare(strict_types=1);

/*
 * Migration 2: new fields (parents quick access, key figures, summaries, FAQ,
 * structured holiday dates, useful links…). Only fields that do not exist yet
 * are added, so nothing already edited in the admin is overwritten.
 */

function seed_v2(PDO $pdo): void
{
    $T = fn ($fr, $en) => ['fr' => $fr, 'en' => $en];
    $lines = fn (array $a) => implode("\n", $a);

    $add = [];
    $add['settings'] = [
        'labelText' => $T("Label Ouverture à l'International — niveau 2", "“Ouverture à l'International” label — level 2"),
        'links' => [['label' => $T('Class Dojo', 'Class Dojo'), 'url' => 'https://www.classdojo.com']],
    ];
    $add['landing'] = [
        'parentLinksTitle' => $T('Accès rapide parents', 'Quick access for parents'),
        'parentLinks' => [
            ['title' => $T('Inscriptions', 'Enrolment'), 'page' => 'informations-pratiques/inscription', 'icon' => 'enrol'],
            ['title' => $T('Menus de la cantine', 'School meals'), 'page' => 'informations-pratiques/restauration', 'icon' => 'meal'],
            ['title' => $T('Vacances scolaires', 'School holidays'), 'page' => 'informations-pratiques/vacances', 'icon' => 'calendar'],
            ['title' => $T('Rentrée & fournitures', 'Back to school & supplies'), 'page' => 'informations-pratiques/rentree', 'icon' => 'bag'],
            ['title' => $T('Frais de scolarité', 'School fees'), 'page' => 'informations-pratiques/frais', 'icon' => 'euro'],
            ['title' => $T('Nous contacter', 'Contact us'), 'page' => 'informations-pratiques/localisation-contact', 'icon' => 'phone'],
        ],
        'eventsEnabled' => true,
        'holidayEnabled' => true,
        'welcomeEnabled' => false,
        'welcomeTitle' => $T('Le mot de la directrice', 'A word from the head teacher'),
        'welcomeText' => $T('', ''),
        'welcomeName' => 'Isabelle Queval',
        'welcomeRole' => $T("Chef d'établissement", 'Head teacher'),
        'welcomePhoto' => '',
    ];
    $add['schoolPresentation'] = [
        'stats' => [
            ['value' => $T('300', '300'), 'label' => $T('élèves, de la petite section au CM2', 'pupils, from nursery to Year 6')],
            ['value' => $T('Niveau 2', 'Level 2'), 'label' => $T("du Label Ouverture à l'International", "of the “Ouverture à l'International” label")],
            ['value' => $T('Dès la PS', 'From age 3'), 'label' => $T("de l'anglais pour tous les élèves", 'English lessons for every pupil')],
            ['value' => $T('ULIS & ASH', 'ULIS & ASH'), 'label' => $T('pour accompagner chaque enfant', 'to support every child')],
        ],
    ];
    $add['ashPage'] = ['summary' => $T(
        $lines(["Un enseignant spécialisé aide les élèves en difficulté, en petits groupes.", "Il intervient à la demande des enseignants, dans la classe ou en dehors.", "Il fait le lien entre l'élève, sa famille et l'enseignant."]),
        $lines(['A specialist teacher supports pupils who are struggling, in small groups.', "They step in at the class teacher's request, inside or outside the classroom.", 'They link the pupil, the family and the class teacher.'])
    )];
    $add['ulisPage'] = ['summary' => $T(
        $lines(['Un dispositif pour 14 élèves de 6 à 11 ans en situation de handicap, orientés par la MDPH.', 'Chaque élève est inscrit dans une classe de son âge et y suit une partie des cours.', 'Une coordonnatrice spécialisée et des AESH les accompagnent.']),
        $lines(['A unit for 14 pupils aged 6 to 11 with disabilities, referred by the MDPH.', 'Each pupil is enrolled in a class of their age and attends part of its lessons.', 'A specialist coordinator and teaching assistants (AESH) support them.'])
    )];
    $add['englishProgram'] = ['summary' => $T(
        $lines(["De l'anglais pour tous les élèves dès la petite section, avec une intervenante dédiée.", "Des cours en demi-classe : petits groupes et beaucoup d'oral.", "L'écrit est abordé progressivement jusqu'au CM2."]),
        $lines(['English for every pupil from nursery onwards, with a dedicated teacher.', 'Half-class lessons: small groups and plenty of speaking.', 'Reading and writing are introduced gradually up to Year 6.'])
    )];
    $add['englishClassPage'] = ['summary' => $T(
        $lines(['Pour les enfants anglophones, ayant vécu à l’étranger ou se préparant à partir.', 'Des groupes de 3 à 10 élèves, 45 minutes par jour, entièrement en anglais.', 'Les élèves restent inscrits dans une classe de leur âge.']),
        $lines(['For English-speaking children, or children who have lived abroad or are about to move.', 'Groups of 3 to 10 pupils, 45 minutes a day, entirely in English.', 'Pupils stay enrolled in a class of their age.'])
    )];
    $add['pastoralMission'] = ['summary' => $T(
        $lines(["Une école catholique, ouverte à toutes les familles qui acceptent son projet pastoral.", "Un temps d'éveil religieux en classe (« Cadeaux de Dieu », puis « Anne et Léo »).", "Une catéchèse à l'école, sur inscription, à partir du CE2."]),
        $lines(['A Catholic school, open to every family that accepts its pastoral project.', "Religious awakening in class ('Cadeaux de Dieu', then 'Anne et Léo').", 'Catechism at school, on registration, from CE2 (Year 3).'])
    )];
    $add['apelPresentation'] = ['summary' => $T(
        $lines(["L'association des parents d'élèves de l'école, animée par des bénévoles.", "Elle représente les parents et organise les événements de l'année.", 'Contact : apel.assomption76@gmail.com']),
        $lines(["The school's parents' association, run by volunteers.", 'It represents parents and organises events throughout the year.', 'Contact: apel.assomption76@gmail.com'])
    )];
    $add['ogecPresentation'] = ['summary' => $T(
        $lines(["Des bénévoles qui assurent la gestion de l'école : personnel, budget et bâtiments.", "Ils se réunissent 5 à 6 fois par an.", 'Leur objectif : une contribution des familles qui reste raisonnable.']),
        $lines(['Volunteers who manage the school: staff, budget and buildings.', 'They meet 5 to 6 times a year.', 'Their aim: keeping family contributions reasonable.'])
    )];
    $add['applicationForm'] = [
        'faqTitle' => $T('Questions fréquentes', 'Frequently asked questions'),
        'faq' => [
            ['question' => $T("À partir de quel âge puis-je inscrire mon enfant ?", 'From what age can my child be enrolled?'),
             'answer' => $T("L'école accueille les enfants de la toute petite section (TPS) au CM2. Pour connaître les places disponibles, contactez le secrétariat.", 'The school welcomes children from the youngest nursery class (TPS) to CM2 (Year 6). Please contact the school office to check available places.')],
            ['question' => $T('Faut-il être catholique pour inscrire son enfant ?', 'Do we need to be Catholic to enrol our child?'),
             'answer' => $T("Non. L'école accueille tout enfant dont la famille connaît et accepte le projet pastoral présenté à l'inscription.", 'No. The school welcomes every child whose family knows and accepts the pastoral project presented at enrolment.')],
            ['question' => $T('Quels documents dois-je fournir ?', 'Which documents do I need to provide?'),
             'answer' => $T("Le carnet de santé (pages des vaccinations), le livret de famille et un chèque d'acompte de 75 € à l'ordre d'OGEC Assomption, déduit de la première facture.", "The child's health record (vaccination pages), the family record book, and a €75 deposit cheque payable to OGEC Assomption, deducted from the first invoice.")],
            ['question' => $T("Peut-on visiter l'école avant d'inscrire son enfant ?", 'Can we visit the school before enrolling?'),
             'answer' => $T('Contactez le secrétariat par téléphone ou par e-mail pour convenir d’un rendez-vous.', 'Please contact the school office by phone or email to arrange an appointment.')],
            ['question' => $T('Mon enfant parle anglais : existe-t-il une classe adaptée ?', 'My child speaks English: is there a suitable class?'),
             'answer' => $T("Oui : la classe anglophone d'Immersion Linguistique et Culturelle accueille les enfants anglophones, ayant vécu à l'étranger ou se préparant à partir.", 'Yes: the English-language Immersion Class welcomes English-speaking children, or children who have lived abroad or are preparing to move.')],
        ],
    ];

    $get = $pdo->prepare('SELECT data FROM pages WHERE key = ?');
    $put = $pdo->prepare('UPDATE pages SET data = ? WHERE key = ?');
    foreach ($add as $key => $fields) {
        $get->execute([$key]);
        $data = json_decode((string) $get->fetchColumn(), true) ?: [];
        foreach ($fields as $k => $v) {
            if (!array_key_exists($k, $data)) {
                $data[$k] = $v;
            }
        }
        $put->execute([json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), $key]);
    }

    // Holiday calendar: free-text table → real dates (written out automatically in FR and EN).
    $get->execute(['schoolBreaks']);
    $data = json_decode((string) $get->fetchColumn(), true) ?: [];
    if (!array_key_exists('periods', $data)) {
        $fr = $data['table']['fr'] ?? [];
        $en = $data['table']['en'] ?? [];
        $periods = [];
        foreach (array_slice($fr, 1) as $i => $row) {
            $dates = parse_french_dates((string) ($row[1] ?? ''));
            if (!$dates) {
                continue;
            }
            // "TOUSSAINT" → "Toussaint"; mixed-case names are kept as they are.
            $nice = fn (string $x) => mb_strtoupper($x) === $x ? mb_convert_case(mb_strtolower($x), MB_CASE_TITLE) : $x;
            $periods[] = [
                'name' => ['fr' => $nice((string) $row[0]), 'en' => $nice((string) ($en[$i + 1][0] ?? $row[0]))],
                'start' => $dates[0],
                'end' => $dates[1] ?? '',
            ];
        }
        $data['periods'] = $periods;
        unset($data['table']);
        $put->execute([json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), 'schoolBreaks']);
    }
}

/** "Du vendredi 18 octobre 2024 au lundi 4 novembre 2024" → ['2024-10-18', '2024-11-04'] */
function parse_french_dates(string $text): array
{
    $months = ['janvier' => 1, 'février' => 2, 'fevrier' => 2, 'mars' => 3, 'avril' => 4, 'mai' => 5, 'juin' => 6, 'juillet' => 7, 'août' => 8, 'aout' => 8, 'septembre' => 9, 'octobre' => 10, 'novembre' => 11, 'décembre' => 12, 'decembre' => 12];
    preg_match_all('/(\d{1,2})(?:er)?\s+(' . implode('|', array_keys($months)) . ')\s+(\d{4})/iu', $text, $m, PREG_SET_ORDER);
    return array_map(fn ($x) => sprintf('%04d-%02d-%02d', (int) $x[3], $months[mb_strtolower($x[2])], (int) $x[1]), $m);
}
