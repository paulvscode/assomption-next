<?php
declare(strict_types=1);

/*
 * Initial content, written to the database the first time the site runs.
 * It reproduces the text that was hardcoded in the previous Next.js pages,
 * so the new site looks the same on day one. After that, everything is
 * edited from the admin and this file is never read again.
 */

function L($fr, $en = null): array
{
    return ['fr' => $fr, 'en' => $en ?? $fr];
}

/** Paragraphs → HTML. */
function P(string ...$paras): string
{
    return implode('', array_map(fn ($p) => '<p>' . htmlspecialchars($p, ENT_NOQUOTES) . '</p>', $paras));
}

/** Bullet list → HTML. */
function UL(array $items, string $intro = ''): string
{
    $html = $intro !== '' ? '<p><strong>' . htmlspecialchars($intro, ENT_NOQUOTES) . '</strong></p>' : '';
    return $html . '<ul>' . implode('', array_map(fn ($i) => '<li>' . htmlspecialchars($i, ENT_NOQUOTES) . '</li>', $items)) . '</ul>';
}

function lines(array $items): string
{
    return implode("\n", $items);
}

function docs(array $fr, array $en): array
{
    return array_map(fn ($a, $b) => ['label' => L($a, $b), 'file' => ''], $fr, $en);
}

function seed_content(PDO $pdo): void
{
    $email = 'dir.ec.assomption.lehavre@srec-hn.com';
    $pages = [];

    $pages['settings'] = [
        'schoolName' => "École de l'Assomption",
        'logo' => '',
        'accentColor' => '#8BB1C5',
        'footerText' => L("Une école catholique engagée dans l'excellence éducative.", 'A Catholic school committed to educational excellence.'),
        'description' => L(
            "École maternelle et primaire catholique au cœur du Havre, sous contrat d'association avec l'État. 300 enfants accueillis dans un esprit d'ouverture et de bienveillance.",
            "Catholic nursery and primary school in the heart of Le Havre, under contract with the State. 300 children welcomed in a spirit of openness and kindness."
        ),
        'address' => "32, rue Lord Kitchener\n76600 Le Havre",
        'phone' => '02 35 43 60 68',
        'email' => $email,
        'hours' => L("Lundi, mardi, jeudi, vendredi\n7h45 – 18h00", "Monday, Tuesday, Thursday, Friday\n7:45 am – 6:00 pm"),
    ];

    $pages['landing'] = [
        'bannerEnabled' => false,
        'bannerText' => L('', ''),
        'bannerStyle' => 'info',
        'heroImage' => '',
        'tagline' => L('Le Havre · École catholique', 'Le Havre · Catholic school'),
        'h1' => L("École de l'Assomption"),
        'h2' => L("Une école ouverte à l'autre", 'A school open to others'),
        'statement' => L(
            "Située en plein cœur du Havre, l'école de l'Assomption est une école maternelle et primaire catholique sous contrat d'association avec l'État, ayant obtenu le Label « Ouverture à l'Internationale ». 300 enfants y sont accueillis dans un esprit d'ouverture et de bienveillance.",
            "Located in the heart of Le Havre, the École de l'Assomption is a Catholic nursery and primary school under contract with the State, having received Level 2 of the 'Ouverture à l'Internationale' Label. 300 children are welcomed here in a spirit of openness and kindness."
        ),
        'cta' => L('En savoir plus', 'Learn more'),
        'ctaContact' => L('Nous contacter', 'Contact us'),
        'newsEnabled' => true,
        'linksTitle' => L('Liens rapides', 'Quick links'),
        'quickLinks' => [
            ['title' => L("L'équipe", 'Our team'), 'page' => 'notre-ecole/equipe-enseignante', 'image' => ''],
            ['title' => L('Le projet éducatif', 'Educational project'), 'page' => 'notre-ecole/projet-educatif', 'image' => ''],
            ['title' => L("Le projet d'établissement", 'School project'), 'page' => 'notre-ecole/projet-etablissement', 'image' => ''],
            ['title' => L('Le projet pastoral', 'Pastoral mission'), 'page' => 'notre-ecole/mission-pastorale', 'image' => ''],
            ['title' => L("L'anglais", 'English programme'), 'page' => 'notre-ecole/programme-anglais', 'image' => ''],
            ['title' => L('Les classes spécifiques', 'Specific classes'), 'page' => 'programmes-speciaux/ash', 'image' => ''],
        ],
        'contactTitle' => L('Nous contacter', 'Contact us'),
    ];

    $pages['schoolPresentation'] = [
        'title' => L("Bienvenue à l'école de l'Assomption !", "Welcome to the École de l'Assomption!"),
        'body' => L(
            P(
                "Située en plein centre-ville du Havre, l'Assomption est une école maternelle et primaire catholique sous contrat d'association avec l'État ayant obtenu le niveau 2 du Label Ouverture à l'internationale. Elle accueille environ 300 enfants de la petite section au CM2, dans un esprit d'ouverture et de bienveillance.",
                "À l'école de l'Assomption, les besoins et les difficultés de chaque élève sont prises en compte. Les enseignants effectuent un suivi personnalisé des élèves, et des rencontres régulières avec les parents sont organisées. Les enfants peuvent bénéficier d'un travail de remédiation en petits groupes, effectué par l'enseignant du poste d'adaptation. Notre école compte également une ULIS (Unité Localisée pour l'Inclusion Scolaire).",
                "Forte du niveau 2 du Label « Ouverture à l'Internationale », notre école donne à l'enseignement de l'anglais une place de choix, et ce dès la petite section. En effet, tous les enfants, de la PS au CM2, apprennent l'anglais avec une intervenante attachée à l'école. Ils bénéficient d'un travail en petits groupes afin d'être dans les meilleures conditions pour cet apprentissage. Une classe anglophone d'Immersion Linguistique et Culturelle fonctionne également pour les enfants anglophones, ayant vécu à l'étranger ou se préparant à partir."
            ),
            P(
                "Located in the heart of Le Havre, the Assomption is a Catholic nursery and primary school under contract with the State, having received Level 2 of the Label Ouverture à l'internationale. It welcomes around 300 children from nursery to Year 6, in a spirit of openness and kindness.",
                "At the École de l'Assomption, each pupil's needs and difficulties are taken into account. Teachers provide personalised follow-up for pupils, and regular meetings with parents are organised. Children can benefit from small-group remediation carried out by the teacher in the adaptation post. Our school also includes a ULIS unit (Unité Localisée pour l'Inclusion Scolaire).",
                "Proud of its Level 2 Label 'Ouverture à l'Internationale', our school gives English teaching a special place, from nursery onwards. All children, from PS to CM2, learn English with a specialist teacher attached to the school. They benefit from small-group work for the best learning conditions. An English-language class of Linguistic and Cultural Immersion also operates for English-speaking children who have lived abroad or are preparing to leave."
            )
        ),
        'valuesTitle' => L(
            "La communauté éducative de l'école de l'Assomption souhaite faire vivre nos valeurs au quotidien :",
            'The educational community of the École de l\'Assomption wishes to live our values on a daily basis:'
        ),
        'values' => L(
            lines(["Favoriser l'épanouissement de la personne", 'Accueillir les différences', 'Cultiver notre ouverture sur le monde', 'Éduquer à la solidarité et au respect']),
            lines(['Fostering personal development', 'Welcoming differences', 'Cultivating openness to the world', 'Educating towards solidarity and respect'])
        ),
        'images' => [],
    ];

    $pages['educationalProject'] = [
        'title' => L('Projet éducatif', 'Educational project'),
        'intro' => L('', ''),
        'cards' => [
            ['title' => L('Une école qui favorise le développement de la personne', 'A school that fosters personal development'), 'items' => L(
                lines(['Développer les talents, les compétences de chacun.', "Offrir à chaque élève la possibilité d'acquérir des connaissances solides et un raisonnement efficace.", 'Favoriser la relation aux autres.', "Promouvoir le dialogue parents-enseignants pour que chacun, dans son rôle, aide l'enfant à dépasser les difficultés."]),
                lines(['Developing the talents and skills of each person.', 'Offering each pupil the opportunity to acquire solid knowledge and effective reasoning.', 'Fostering relationships with others.', 'Promoting parent-teacher dialogue so that each, in their role, helps the child overcome difficulties.'])
            )],
            ['title' => L('Une école qui accueille les différences', 'A school that welcomes differences'), 'items' => L(
                lines(["Accueillir chaque enfant tel qu'il est.", "Adapter les situations d'apprentissage.", "Favoriser la connaissance de l'autre, percevoir la différence comme une richesse."]),
                lines(['Welcoming each child as they are.', 'Adapting learning situations.', 'Fostering knowledge of others, perceiving difference as a richness.'])
            )],
            ['title' => L('Une école ouverte sur le monde', 'A school open to the world'), 'items' => L(
                lines(["Préparer l'enfant à l'autonomie.", "Favoriser la curiosité au-delà de l'environnement familier.", 'Développer des connaissances et compétences dans la langue anglaise.', 'Sensibiliser et éduquer au monde numérique.']),
                lines(['Preparing children for autonomy.', 'Fostering curiosity beyond the familiar environment.', 'Developing knowledge and skills in the English language.', 'Raising awareness of and educating about the digital world.'])
            )],
            ['title' => L('Une école qui éduque à la solidarité et au respect', 'A school that educates towards solidarity and respect'), 'items' => L(
                lines(['Donner des responsabilités.', "Développer les occasions d'échange entre enfants de classes différentes.", 'Participer à des actions de solidarité.', "Vivre au quotidien les valeurs de l'Évangile."]),
                lines(['Giving responsibilities.', 'Developing opportunities for exchange between children from different classes.', 'Participating in solidarity actions.', 'Living the values of the Gospel daily.'])
            )],
        ],
    ];

    $pages['schoolProject'] = [
        'title' => L("Projet d'établissement", 'School project'),
        'intro' => L(
            "Le projet d'établissement concrétise les choix pédagogiques et éducatifs au travers de projets d'école, de cycle, de classe.",
            'The school project translates educational and pedagogical choices into school, cycle and class projects.'
        ),
        'cards' => [
            [
                'badge' => L('2024 - 2025'),
                'title' => L('Une école qui favorise le développement de la personne', 'A school that fosters personal development'),
                'description' => L("L'équipe éducative souhaite faire de l'école de l'Assomption un lieu accueillant, à l'écoute de chacun.", "The teaching team wishes to make the École de l'Assomption a welcoming place, attentive to everyone."),
                'body' => L(
                    UL(['Rénovation de la classe de CE1.', 'Équipement des classes : changement des bureaux et des chaises de la classe de CM1.', 'Poursuite de l\'utilisation de Class Dojo, lien entre la famille et l\'école.', 'Développement de la communication par la refonte du site internet de l\'école.']),
                    UL(['Renovation of the CE1 classroom.', 'Classroom equipment: replacement of desks and chairs in the CM1 classroom.', 'Continued use of Class Dojo as a link between families and school.', 'Development of communications through a redesigned school website.'])
                ),
                'image' => '',
            ],
            [
                'badge' => L('2024 - 2025'),
                'title' => L('Une école qui accueille les différences', 'A school that welcomes differences'),
                'description' => L(
                    "L'équipe pédagogique souhaite offrir un suivi particulier à chaque enfant. Les enseignants reçoivent régulièrement les parents lors de réunions collectives ou d'entretiens individuels. Les enfants qui rencontrent des difficultés dans les apprentissages peuvent bénéficier d'une prise en charge en petits groupes par l'enseignant du poste d'adaptation (ou poste ASH). Enfin, une douzaine d'élèves sont accueillis en ULIS par une enseignante spécialisée.",
                    'The teaching team wishes to offer personalised support to each child. Teachers regularly meet parents in collective meetings or individual interviews. Children who encounter learning difficulties can receive small-group support from the teacher in the adaptation post (ASH). Around twelve pupils are also welcomed in ULIS by a specialist teacher.'
                ),
                'body' => L(
                    UL(['20mn de lecture par jour proposées aux enfants de CP au 1er trimestre pour bien démarrer en lecture.', 'Aide aux devoirs pour les élèves de primaire proposée par les enseignants.', "Poursuite d'un temps d'APC hebdomadaire dans tous les cycles pour travailler la compréhension de lecture.", "Inclusion des enfants d'ULIS dans les classes « ordinaires »."]),
                    UL(['20 minutes of reading per day for Year 1 children during the first term to get them started in reading.', 'Homework help for primary pupils offered by teachers.', 'Continued weekly APC time across all cycles to work on reading comprehension.', "Inclusion of ULIS children in 'regular' classes."])
                ),
                'image' => '',
            ],
            [
                'badge' => L('2024 - 2025'),
                'title' => L('Une école ouverte sur le monde', 'A school open to the world'),
                'description' => L(
                    "Des classes de découverte sont organisées en cycle 2 et en cycle 3. L'anglais est développé et mis en valeur à l'école de l'Assomption : tous les élèves, de la PS au CM2, participent à des cours d'anglais dispensés par un intervenant en demi-groupes-classes. La Classe anglophone d'Immersion Linguistique et Culturelle prend en charge, par petits groupes, des enfants anglophones ou se préparant à partir vivre en pays anglophone.",
                    "Discovery trips are organised in Cycle 2 and Cycle 3. English is developed at the École de l'Assomption: all pupils, from PS to CM2, take part in English lessons given by a specialist in half-class groups. The English-language class of Linguistic and Cultural Immersion supports English-speaking children or those preparing to live in an English-speaking country, in small groups."
                ),
                'body' => L(
                    UL(['Classe de découverte en Bretagne en juin 2025 pour les CE1 et CE2.', 'Les élèves des classes élémentaires sont pris en charge chaque semaine en demi-groupes par une intervenante en informatique.', 'Participation de la classe de CE1 au projet « Little Planet Fraternity » : échanges en visio avec une école au Kenya et une en Haïti.', "Proposition d'initiation au mandarin sur les temps périscolaires."]),
                    UL(['Discovery trip to Brittany in June 2025 for CE1 and CE2.', 'Primary class pupils receive weekly half-group IT lessons from a specialist.', "Participation of the CE1 class in the 'Little Planet Fraternity' project: video exchanges with a school in Kenya and one in Haiti.", 'Introduction to Mandarin offered as an extra-curricular activity.'])
                ),
                'image' => '',
            ],
            [
                'badge' => L('2024 - 2025'),
                'title' => L('Une école qui éduque à la solidarité et au respect', 'A school that educates towards solidarity and respect'),
                'description' => L(
                    "Notre école souhaite éduquer à la solidarité et au respect, se référant aux valeurs de l'enseignement catholique. Cela se traduit autant que possible dans tous les aspects de la vie quotidienne, ainsi que dans les moments de culture chrétienne et d'éveil religieux animés par les enseignants dans les classes.",
                    'Our school wishes to educate towards solidarity and respect, drawing on the values of Catholic education. This is reflected as much as possible in all aspects of daily life, as well as in moments of Christian culture and religious awakening led by teachers in the classroom.'
                ),
                'body' => L(
                    UL(['Parcours « Cadeaux de Dieu » en maternelle, CP et CE1 ; parcours « Anne et Léo » en CE2, CM1, CM2.', 'Catéchisme à l\'école à partir du CE2 et éveil à la foi de la Grande Section au CE1, sur inscription.', 'Préparation de « temps forts » : Nativité de Marie, interventions du Père Maurin dans les classes, célébrations de Noël et Pâques.'], 'Culture religieuse :')
                    . UL(['Participation à des actions de solidarité en faveur d\'associations : chiens-guides, Resto du cœur, Secours Catholique…', 'Formation des enseignants à la lutte contre le harcèlement.'], 'Solidarité / prévention :'),
                    UL(["'Cadeaux de Dieu' programme in nursery, CP and CE1; 'Anne et Léo' programme in CE2, CM1, CM2.", 'Catechism at school from CE2, and religious awakening from Grande Section to CE1, on registration.', 'Preparation of key moments: Nativity of Mary, visits by Father Maurin to classes, Christmas and Easter celebrations.'], 'Religious culture:')
                    . UL(['Participation in solidarity actions for associations: guide dogs, Resto du cœur, Secours Catholique…', 'Teacher training in anti-bullying.'], 'Solidarity / prevention:')
                ),
                'image' => '',
            ],
        ],
        'document' => '',
    ];

    $pages['pastoralMission'] = [
        'title' => L('Mission pastorale', 'Pastoral mission'),
        'statement' => L(
            "L'école de l'Assomption accueille tout enfant dont la famille connaît et accepte le projet pastoral présenté à l'inscription.",
            "The École de l'Assomption welcomes every child whose family knows and accepts the pastoral project presented at enrolment."
        ),
        'blocks' => [
            ['text' => L(
                P("Notre école est un établissement catholique, sous tutelle diocésaine. Elle respecte donc les orientations données par l'Enseignement catholique et par l'évêque, Mgr Jean-Luc Brunin. Son prêtre référent est le Père Marcel Maurin. Ainsi, elle souhaite faire vivre au quotidien les valeurs de solidarité, de respect, d'écoute, en référence à l'Évangile. Dans les classes, un temps d'éveil religieux est régulièrement proposé.", 'En maternelles, CP et CE1, les enfants découvrent et échangent grâce au parcours « Cadeaux de Dieu ».'),
                P('Our school is a Catholic institution under diocesan authority. It therefore follows the guidelines given by Catholic Education and by the bishop, Mgr Jean-Luc Brunin. Its reference priest is Father Marcel Maurin. The school wishes to live out daily the values of solidarity, respect and listening, in reference to the Gospel. In the classrooms, a time of religious awakening is regularly offered.', "In nursery, CP and CE1, children discover and share through the 'Cadeaux de Dieu' programme.")
            ), 'image' => ''],
            ['text' => L(
                P('Des activités concrètes, parlantes pour les plus jeunes, sont mises en place dans les classes au moyen d\'un chevalet, d\'un CD de chants et contes bibliques et autres supports proposés sur le site du parcours.'),
                P("Concrete, engaging activities for younger children are put in place in the classrooms through an easel, a CD of songs and biblical stories, and other materials available on the programme's website.")
            ), 'image' => ''],
            ['text' => L(
                P('En CE2, CM1, CM2, ce temps est complété par un parcours de culture chrétienne, « Anne et Léo ». Les enfants découvrent la Bible, la vie de Jésus, les nombreuses références religieuses présentes dans notre patrimoine culturel (littérature, peinture, architecture…).'),
                P("In CE2, CM1 and CM2, this time is complemented by a Christian culture programme, 'Anne et Léo'. Children discover the Bible, the life of Jesus, and the many religious references in our cultural heritage (literature, painting, architecture…).")
            ), 'image' => ''],
        ],
        'highlight' => L(
            P(
                "L'année est marquée par deux temps forts : Noël et Pâques. Ces fêtes, expliquées aux enfants, sont l'occasion d'actions de solidarité au bénéfice d'associations caritatives.",
                'Une célébration de Noël rassemble tous les élèves et les familles qui le souhaitent.',
                'Un « bol de riz » est proposé à tous le Vendredi Saint, avant Pâques.',
                "Les temps d'éveil religieux permettent à certains d'approfondir leur Foi, à d'autres d'acquérir des connaissances afin de faire ensuite des choix, à d'autres encore d'effectuer des comparaisons avec une religion différente. Ce sont des moments d'échange, d'écoute, de partage, dans le respect des différences.",
                "Pour les enfants qui souhaitent approfondir la Foi chrétienne, des groupes de catéchèse paroissiale se réunissent à l'école. Ils sont ouverts aux enfants à partir du CE2. Ils permettent la préparation des sacrements tels que le baptême, la première communion."
            ),
            P(
                'The year is marked by two key moments: Christmas and Easter. These celebrations, explained to the children, are an opportunity for solidarity actions benefiting charitable organisations.',
                'A Christmas celebration brings together all pupils and families who wish to attend.',
                "A 'bowl of rice' is offered to all on Good Friday, before Easter.",
                'Moments of religious awakening allow some to deepen their Faith, others to acquire knowledge in order to make informed choices, and others still to make comparisons with a different religion. They are moments of exchange, listening and sharing, in respect for differences.',
                'For children who wish to deepen their Christian Faith, parish catechism groups meet at school. They are open to children from CE2 onwards and allow preparation for sacraments such as baptism and first communion.'
            )
        ),
        'bottomImage' => '',
    ];

    $pages['professorsPage'] = [
        'title' => L(
            "À l'École de l'Assomption, chaque membre de l'équipe pédagogique contribue au projet éducatif.",
            "At the École de l'Assomption, every member of the teaching team contributes to the educational project."
        ),
        'subtitle' => L('Année scolaire 2024 / 2025', 'School year 2024 / 2025'),
        'groups' => [
            ['title' => L('Enseignantes de maternelle', 'Nursery teachers'), 'members' => L(
                lines(['Linda Zeghoubi — TPS/PS, aidée de Mélanie', "Sophie Patin — PS/MS, aidée d'Emmanuelle", 'Lara Delamare — MS/GS, aidée de Béatrice', 'Graziella Ledoux — GS, aidée de Mélanie']),
                lines(['Linda Zeghoubi — TPS/PS, assisted by Mélanie', 'Sophie Patin — PS/MS, assisted by Emmanuelle', 'Lara Delamare — MS/GS, assisted by Béatrice', 'Graziella Ledoux — GS, assisted by Mélanie'])
            )],
            ['title' => L('Enseignants de primaire', 'Primary teachers'), 'members' => L(lines([
                'Guillaume Saint Martin — CP', 'Diana Mahrouchi — CE1', 'Sarah Leray — CE1/CE2', 'Audrey Prigent — CE2', 'Vanessa Héry — CM1', 'Florence Gosselin — CM1/CM2', 'Océane Dubourg — CM2',
            ]))],
            ['title' => L('Enseignants pour les enfants à Besoins Éducatifs Particuliers', 'Teachers for children with Special Educational Needs'), 'members' => L(
                lines(["Fabien Varly-Le Bot — Poste d'adaptation (ASH)", 'Marie Charlotte Dutertre — ULIS']),
                lines(['Fabien Varly-Le Bot — Adaptation post (ASH)', 'Marie Charlotte Dutertre — ULIS'])
            )],
            ['title' => L("Enseignantes pour l'enseignement de l'anglais", 'English teaching staff'), 'members' => L(
                lines(["Olivia Lea-Robinson — Classe anglophone d'Immersion Linguistique et Culturelle", 'Laëtitia Laude — Anglais']),
                lines(['Olivia Lea-Robinson — English-language Immersion Class', 'Laëtitia Laude — English'])
            )],
        ],
        'supportTitle' => L("L'équipe éducative comporte également", 'The educational team also includes'),
        'supportMembers' => L(
            lines(['1 secrétaire-comptable : Sophie Kervella', '4 aides maternelles : Emmanuelle Lemieux, Mélanie Cabioch, Béatrice Fiquet et Mélanie Saint Martin', '1 assistante pédagogique / surveillante : Julie Desfontaine', '4 AESH mutualisées auprès de 8 enfants : Claire Hugonnet, Nathalie Leroux, Delphine Gréboval et Angéline Gouteux', '3 personnes à la cantine : Laura Degraeve, Angélina et Sylvie Robert']),
            lines(['1 secretary-accountant: Sophie Kervella', '4 nursery assistants: Emmanuelle Lemieux, Mélanie Cabioch, Béatrice Fiquet and Mélanie Saint Martin', '1 teaching assistant / supervisor: Julie Desfontaine', '4 AESH shared among 8 children: Claire Hugonnet, Nathalie Leroux, Delphine Gréboval and Angéline Gouteux', '3 cafeteria staff: Laura Degraeve, Angélina and Sylvie Robert'])
        ),
        'principalLabel' => L("Chef d'établissement", 'Head teacher'),
        'principal' => L("Le chef d'établissement de l'École de l'Assomption est Mme Isabelle QUEVAL.", "The head teacher of the École de l'Assomption is Mme Isabelle QUEVAL."),
        'teamPhoto' => '',
    ];

    $pages['englishProgram'] = [
        'title' => L('Programme anglais', 'English programme'),
        'body' => L(
            P(
                "L'anglais est un apprentissage fondamental à l'école de l'Assomption. C'est pourquoi, dès la Petite Section, Laëtitia, intervenante en anglais, prend en charge les enfants. Elle intervient une, puis deux fois par semaine pendant 30, puis 45 minutes.",
                "Les activités s'effectuent par demi-classe. Les groupes étant peu nombreux, les élèves peuvent s'exprimer fréquemment et facilement. Le travail est d'abord centré sur l'oral (imprégnation de la langue, de son accent tonique, de sa syntaxe…) à travers des activités vivantes et variées.",
                "Quand ils sont aptes à le faire, les enfants abordent l'écrit en lien avec différents thèmes. Ils acquièrent donc, en plus d'une aisance à l'oral, de bonnes bases à l'écrit. Ainsi, beaucoup d'élèves choisissent de poursuivre leur scolarité dans une 6e avec option spécifique en anglais.",
                "L'anglais est aussi développé à l'école dans le cadre de la classe anglophone, qui s'adresse à des enfants ayant un profil spécifique."
            ),
            P(
                "English is a fundamental subject at the École de l'Assomption. From nursery onwards, Laëtitia, the English specialist, takes charge of the children. She intervenes once, then twice a week for 30, then 45 minutes.",
                'Activities take place in half-classes. With small group sizes, pupils can express themselves frequently and easily. The work is first centred on oral skills (language immersion, stress patterns, syntax…) through lively and varied activities.',
                'When ready, children approach written English linked to different themes. They thus acquire, alongside oral fluency, solid written foundations. As a result, many pupils choose to continue their schooling in a secondary school with a specific English option.',
                'English is also developed at school through the English-language class, which caters to children with a specific profile.'
            )
        ),
        'coverImage' => '',
    ];

    $pages['ashPage'] = [
        'title' => L('Dispositif ASH', 'ASH programme'),
        'body' => L(
            P(
                "L'enseignant responsable du poste ASH intervient à la demande des enseignants des classes. En cas de difficultés, il analyse la situation scolaire des enfants à partir d'observations, d'évaluations, de bilans et d'entretiens. Il intervient ensuite en prenant en charge les enfants par petits groupes, au sein de leur classe ou non.",
                "Il travaille sur l'acquisition des connaissances, mais aussi la méthodologie, en référence aux programmes de l'école primaire. Il favorise la concertation à l'intérieur de l'école entre l'élève, sa famille et l'enseignant.",
                'Si besoin et selon le cas de chaque enfant, il peut proposer des aides spécialisées extérieures.'
            ),
            P(
                "The teacher responsible for the ASH post intervenes at the request of class teachers. In cases of difficulty, they analyse each child's school situation through observations, assessments, evaluations and interviews. They then provide support by taking children in small groups, within or outside their class.",
                'The focus is on knowledge acquisition as well as methodology, in line with primary school programmes. The ASH teacher promotes collaboration within the school between the pupil, their family and the class teacher.',
                "If needed, and depending on each child's situation, specialised external support may also be proposed."
            )
        ),
        'coverImage' => '',
    ];

    $pages['ulisPage'] = [
        'title' => L('ULIS'),
        'body' => L(
            P(
                "L'Unité Localisée pour l'Inclusion Scolaire est un dispositif collectif d'aide à la scolarisation. Il développe et consolide l'autonomie personnelle, sociale de l'enfant, ainsi que ses apprentissages et l'amélioration de sa communication. Le dispositif peut accueillir 14 élèves, âgés de 6 à 11 ans, en situation de handicap.",
                "Les élèves sont orientés sur décision de la MDPH, puis sont inscrits dans une classe de l'école, en fonction de leur Projet Personnalisé de Scolarisation. Ils participent aux activités proposées de leur classe et leur emploi du temps est aménagé en début d'année en fonction de leurs besoins spécifiques et peut être réaménagé durant l'année, selon les compétences ciblées. Un élève notifié en unité localisée pour l'inclusion scolaire profite d'heures dans sa classe avec son professeur et l'accompagnement de l'AESH-co du dispositif, s'il y a, ou d'un AESH-i ou encore d'un AESH-mut si notifiée.",
                "Il bénéficie d'heures avec la coordonnatrice, au sein du dispositif et/ou dans sa classe. Le professeur et la coordonnatrice travaillent ensemble en co-intervention et/ou en co-enseignement. Cette émulation d'équipe pédagogique permet d'anticiper les adaptations et favorise l'appartenance de l'élève à son groupe classe.",
                "L'unité possède de nombreux matériels pédagogiques et pratique la ludopédagogie. L'espace y est flexible pour adapter l'environnement aux besoins des élèves. L'élève peut ainsi retrouver sa concentration et sa motivation dans ses apprentissages. Afin d'apporter de l'affection et/ou du réconfort aux enfants, des médiateurs animaliers complètent le dispositif en faisant preuve de calme et d'amitié.",
                "Le dispositif ULIS de l'Assomption est basé sur une pédagogie active et positive, afin que chaque enfant puisse s'épanouir à l'école."
            ),
            P(
                "The ULIS (Unité Localisée pour l'Inclusion Scolaire) is a collective support unit for school inclusion. It develops and consolidates each child's personal and social autonomy, as well as their learning and communication. The unit can accommodate 14 pupils aged 6 to 11 with disabilities.",
                "Pupils are referred by the MDPH and enrolled in a class according to their Personal Schooling Project. They participate in their class activities, with a timetable tailored at the start of the year to meet their specific needs — adjustable throughout the year based on targeted skills. Each pupil benefits from time in their class with their teacher and the support of the AESH-co, AESH-i, or AESH-mut as notified.",
                "Pupils also benefit from sessions with the ULIS coordinator, within the unit and/or in the classroom. The teacher and coordinator work together through co-intervention and/or co-teaching. This team approach anticipates adaptations and reinforces the pupil's sense of belonging to their class group.",
                "The unit has a wide range of teaching materials and uses play-based pedagogy. The space is flexible to adapt the environment to pupils' needs, helping children regain concentration and motivation in their learning. Animal mediators also complete the unit, providing affection and comfort through calm and friendly interaction.",
                'The ULIS at the Assomption is based on active and positive pedagogy, so that every child can flourish at school.'
            )
        ),
        'coverImage' => '',
    ];

    $pages['englishClassPage'] = [
        'title' => L("La Classe anglophone d'Immersion Linguistique et Culturelle", 'The English-language Immersion Class'),
        'subtitle' => L("La Classe anglophone d'Immersion Linguistique et Culturelle doit permettre :", 'The English-language Class of Linguistic and Cultural Immersion is designed to:'),
        'cases' => [
            ['description' => L("Aux enfants en partance pour l'étranger d'avoir un contact approfondi avec la langue anglaise.", 'Allow children moving abroad to develop in-depth contact with the English language.'),
             'condition' => L("Les parents présentent à l'école un projet de départ à l'étranger certifié par l'entreprise.", 'Parents present the school with a company-certified relocation project.')],
            ['description' => L("Aux enfants arrivant d'un pays étranger d'être pris en charge en anglais et de maintenir ainsi leur niveau de langue.", 'Support children arriving from an English-speaking country in maintaining their language level.'),
             'condition' => L("Les parents transmettent à l'école le livret scolaire de l'enfant.", "Parents provide the school with the child's school report.")],
            ['description' => L('Aux enfants de père et/ou mère anglophone(s) vivant en France de poursuivre des apprentissages linguistiques en anglais.', 'Enable children with one or both English-speaking parents living in France to continue developing their English language skills.'),
             'condition' => L('La langue anglaise est parlée quotidiennement à la maison.', 'English is spoken daily at home.')],
        ],
        'text' => L(
            P("Les élèves sont regroupés et inscrits dans une classe « ordinaire » qui correspond à leur tranche d'âge. Ils constituent des groupes de 3 à 10 maximum. Conformément à un emploi du temps fixé par l'enseignante en début d'année, et en concertation avec l'enseignant de la classe de référence, chaque groupe est pris en charge 45 minutes par jour."),
            P("Pupils are grouped and enrolled in a 'regular' class corresponding to their age group. Groups consist of 3 to 10 pupils maximum. According to a timetable set by the teacher at the start of the year, and in consultation with the reference class teacher, each group receives 45 minutes of instruction per day.")
        ),
        'activitiesTitle' => L('Les activités se font uniquement en anglais dans les domaines suivants', 'All activities are conducted exclusively in English, covering:'),
        'activities' => L(
            lines(['Compréhension écrite', 'Production écrite', "Compréhension de l'oral", 'Production orale']),
            lines(['Reading comprehension', 'Written production', 'Listening comprehension', 'Oral production'])
        ),
        'finalWord' => L('Elles correspondent aux exigences du Cadre européen commun de référence pour les langues.', 'These correspond to the requirements of the Common European Framework of Reference for Languages.'),
    ];

    $pages['locationContact'] = [
        'title' => L('Localisation & contact', 'Location & contact'),
        'showMap' => true,
        'mapAddress' => '32 rue Lord Kitchener, 76600 Le Havre',
        'additionalInfo' => L('', ''),
    ];

    $pages['cafeteria'] = [
        'title' => L('Restauration scolaire', 'Cafeteria & meals'),
        'body' => L(
            P('Les repas de cantine sont préparés par la société API Restauration.', 'Vous pouvez consulter les menus en cliquant sur le lien :'),
            P('Cafeteria meals are prepared by API Restauration.', 'You can view the menus by clicking the link below:')
        ),
        'linkLabel' => L('Consulter les menus sur c-est-pret.com', 'View menus at c-est-pret.com'),
        'linkUrl' => 'https://www.c-est-pret.com',
        'menuFile' => '',
    ];

    $pages['applicationForm'] = [
        'title' => L("Formulaire d'inscription", 'Application form'),
        'downloadLabel' => L("Téléchargez la fiche d'inscription au format PDF ci-dessous", 'Download the application form in PDF format below'),
        'formFile' => '',
        'list1Title' => L("Voici les documents à transmettre lors de l'inscription :", 'Documents required at enrolment:'),
        'list1' => L(
            lines(['Le carnet de santé de votre enfant (photocopies des pages des vaccinations).', "Le livret de famille (photocopies des pages des parents et de l'enfant).", "Un chèque d'acompte de 75 € (à l'ordre d'OGEC Assomption) qui sera déduit de la 1re facture."]),
            lines(["Your child's health record (photocopies of vaccination pages).", "Family record book (photocopies of parents' and child's pages).", 'A deposit cheque of €75 (payable to OGEC Assomption), deducted from the first invoice.'])
        ),
        'list2Title' => L('Autres documents consultables :', 'Other documents available:'),
        'otherDocs' => docs(['Convention de scolarisation', 'Le projet éducatif', 'Règlement intérieur'], ['Schooling agreement', 'Educational project', 'School rules']),
    ];

    $pages['fees'] = [
        'title' => L('Frais de scolarité', 'Fees'),
        'intro' => L('', ''),
        'documents' => docs(['Frais de scolarité', "Téléchargez ici l'autorisation de prélèvement"], ['School fees', 'Download the direct debit authorisation']),
    ];

    $pages['backToSchool'] = [
        'title' => L('Rentrée scolaire', 'Back to school'),
        'intro' => L(
            P('Notre école fonctionnera sur 4 jours : lundi, mardi, jeudi, vendredi.', 'Horaires de classe : 8h30 – 11h45, 13h30 – 16h30.', 'La rentrée aura lieu le lundi 2 septembre (rentrée échelonnée pour les classes de TPS/PS et PS/MS).'),
            P('Our school operates on 4 days: Monday, Tuesday, Thursday, Friday.', 'School hours: 8:30 am – 11:45 am, 1:30 pm – 4:30 pm.', 'The new school year begins on Monday 2 September (staggered entry for TPS/PS and PS/MS classes).')
        ),
        'table1Title' => L('Horaires de rentrée', 'Entry timetable'),
        'table1' => L(
            [['Heure', 'Classes'], ['8h30', 'MS/GS, CE1, CE2/CM1, CM1/CM2, CM2, ULIS'], ['8h45', 'TPS/PS, PS/MS'], ['9h00', 'GS, CP, CP/CE1, CE2']],
            [['Time', 'Classes'], ['8:30 am', 'MS/GS, CE1, CE2/CM1, CM1/CM2, CM2, ULIS'], ['8:45 am', 'TPS/PS, PS/MS'], ['9:00 am', 'GS, CP, CP/CE1, CE2']]
        ),
        'table2Title' => L('Rentrée échelonnée TPS/PS et PS/MS', 'Staggered entry — TPS/PS and PS/MS'),
        'table2' => L(
            [['Jour', 'TPS/PS', 'PS/MS'], ['Lundi 2 septembre', '1er groupe à 8h45', '1er groupe à 8h45'], ['Mardi 3 septembre', '2e groupe à 8h45', '2e groupe à 8h45'], ['Jeudi 5 septembre', 'Classe entière à 8h30', 'Classe entière à 8h30']],
            [['Day', 'TPS/PS', 'PS/MS'], ['Monday 2 September', '1st group at 8:45 am', '1st group at 8:45 am'], ['Tuesday 3 September', '2nd group at 8:45 am', '2nd group at 8:45 am'], ['Thursday 5 September', 'Full class at 8:30 am', 'Full class at 8:30 am']]
        ),
        'noteText' => L("Vous trouverez d'autres précisions en consultant la Note de rentrée 2024.", 'Further details are available in the 2024 Back-to-School Note.'),
        'noteFile' => '',
        'listsTitle' => L('Listes des fournitures (cliquez pour ouvrir et télécharger) :', 'Supply lists (click to open and download):'),
        'supplyLists' => docs(
            ['Fournitures TPS-PS (Linda Zeghoubi)', 'Fournitures PS Classe de PS-MS (Sophie Patin)', 'Fournitures MS Classe de PS-MS (Sophie Patin)', 'Fournitures MS Classe de MS-GS (Lara Delamare)', 'Fournitures GS Classe de MS-GS (Lara Delamare)', 'Fournitures GS/CP (Graziella Ledoux)', 'Fournitures CP (Guillaume Saint Martin)', 'Fournitures CP Classe de CP-CE1', 'Fournitures CE1 Classe de CE1 (Diana Mahrouchi)', 'Fournitures CE1/CE2 (Sarah Leray)', 'Fournitures CE2 (Audrey Prigent)', 'Fournitures CM1 (Vanessa Héry)', 'Fournitures CM1-CM2 (Florence Gosselin)', 'Fournitures CM2 (Océane Dubourg)'],
            ['Supplies TPS-PS (Linda Zeghoubi)', 'Supplies PS — PS-MS class (Sophie Patin)', 'Supplies MS — PS-MS class (Sophie Patin)', 'Supplies MS — MS-GS class (Lara Delamare)', 'Supplies GS — MS-GS class (Lara Delamare)', 'Supplies GS/CP (Graziella Ledoux)', 'Supplies CP (Guillaume Saint Martin)', 'Supplies CP — CP-CE1 class', 'Supplies CE1 — CE1 class (Diana Mahrouchi)', 'Supplies CE1/CE2 (Sarah Leray)', 'Supplies CE2 (Audrey Prigent)', 'Supplies CM1 (Vanessa Héry)', 'Supplies CM1-CM2 (Florence Gosselin)', 'Supplies CM2 (Océane Dubourg)']
        ),
    ];

    $pages['schoolBreaks'] = [
        'title' => L('Calendrier des vacances', 'School breaks calendar'),
        'intro' => L('', ''),
        'table' => L(
            [['Période', 'Dates'], ['Prérentrée des enseignants', 'Vendredi 30 août 2024'], ['Rentrée scolaire des élèves', 'Lundi 2 septembre 2024'], ['TOUSSAINT', 'Du vendredi 18 octobre 2024 au lundi 4 novembre 2024'], ['NOËL', 'Du vendredi 20 décembre 2024 au lundi 6 janvier 2025'], ['HIVER', 'Du vendredi 7 février 2025 au lundi 24 février 2025'], ['PRINTEMPS', 'Du vendredi 4 avril 2025 au lundi 22 avril 2025'], ['ASCENSION', 'Du mardi 27 mai 2025 au lundi 2 juin 2025'], ['PENTECÔTE', 'Du vendredi 6 juin 2025 au mardi 10 juin 2025'], ['ÉTÉ', 'Vendredi 4 juillet 2025']],
            [['Period', 'Dates'], ["Teachers' pre-term day", 'Friday 30 August 2024'], ["Pupils' first day", 'Monday 2 September 2024'], ['ALL SAINTS', 'Friday 18 October 2024 to Monday 4 November 2024'], ['CHRISTMAS', 'Friday 20 December 2024 to Monday 6 January 2025'], ['WINTER', 'Friday 7 February 2025 to Monday 24 February 2025'], ['SPRING', 'Friday 4 April 2025 to Monday 22 April 2025'], ['ASCENSION', 'Tuesday 27 May 2025 to Monday 2 June 2025'], ['WHITSUN', 'Friday 6 June 2025 to Tuesday 10 June 2025'], ['SUMMER', 'Friday 4 July 2025']]
        ),
        'calendarFile' => '',
    ];

    $pages['otherDocuments'] = [
        'title' => L('Autres documents', 'Other documents'),
        'intro' => L('Vous pouvez consulter ici :', 'You can consult the following documents here:'),
        'documents' => docs(['Le règlement intérieur', 'La convention de scolarisation 2024-2025', "L'assurance scolaire Saint-Christophe"], ['School rules', 'Schooling agreement 2024-2025', 'Saint-Christophe school insurance']),
    ];

    $pages['apelPresentation'] = [
        'title' => L("Présentation de l'APEL", 'APEL presentation'),
        'body' => L(
            P(
                "L'APEL (Association des Parents d'élèves de l'Enseignement Libre) est une association loi 1901. Ses membres sont bénévoles.",
                "Son rôle est de favoriser les rencontres entre les parents, de les accompagner, de les représenter, notamment dans les conseils d'établissement. L'APEL est l'interlocuteur du chef d'établissement.",
                "Les membres de l'APEL se réunissent régulièrement. Ils préparent les manifestations qui ponctuent l'année scolaire. Ils soutiennent les projets éducatifs de l'école. Ils travaillent en lien avec le chef d'établissement."
            ) . "<p>L'APEL a une représentation départementale (UDAPEL) et nationale (UNAPEL). Pour davantage d'informations, rendez-vous sur le site <a href=\"https://www.apel.fr\">www.apel.fr</a>.</p>"
              . P("Lors de l'inscription, la plupart des familles adhèrent à l'association. Elles sont invitées à l'Assemblée Générale annuelle. Elles reçoivent à domicile la revue « Famille et Éducation » qui traite de questions diverses au travers de reportages, conseils de spécialistes, témoignages."),
            P(
                'The APEL (Association des Parents d\'élèves de l\'Enseignement Libre) is a non-profit association. Its members are volunteers.',
                'Its role is to foster connections between parents, support and represent them, particularly in school governing bodies. The APEL is the main point of contact for the head teacher.',
                "APEL members meet regularly. They organise events that punctuate the school year, support the school's educational projects, and work closely with the head teacher."
            ) . '<p>The APEL has a departmental (UDAPEL) and national (UNAPEL) presence. For more information, visit <a href="https://www.apel.fr">www.apel.fr</a>.</p>'
              . P("At enrolment, most families join the association. They are invited to the Annual General Meeting and receive the 'Famille et Éducation' magazine at home, covering various topics through features, expert advice and testimonials.")
        ),
        'compositionTitle' => L("Composition de l'APEL", 'APEL board'),
        'members' => array_map(fn ($m) => ['role' => L($m[0], $m[1]), 'name' => $m[2]], [
            ['Présidente', 'President', 'Laurence Glovert'],
            ['Présidente adjointe', 'Deputy president', 'Charlène Guilbert'],
            ['Trésorière', 'Treasurer', 'Florentine Avenel'],
            ['Trésorière adjointe', 'Deputy treasurer', 'Lisa-Marie Arfelli'],
            ['Secrétaire', 'Secretary', 'Amel Luu'],
            ['Secrétaire adjointe', 'Deputy secretary', 'Stéphanie Peillex'],
        ]),
        'contactTitle' => L('Nous contacter', 'Contact us'),
        'contactItems' => L(
            lines(["Par courrier : la boîte aux lettres de l'APEL est située dans le hall principal, avant la porte d'entrée du secrétariat.", 'Par mail : apel.assomption76@gmail.com']),
            lines(["By post: the APEL letterbox is located in the main hall, before the entrance to the secretary's office.", 'By email: apel.assomption76@gmail.com'])
        ),
        'signupText' => L(
            'Pour être contacté(e) et informé(e) de tous les événements qui jalonnent la vie de vos enfants au sein de l\'école, laissez-nous votre adresse mail :',
            "To be kept informed of all events in your children's school life, leave us your email address:"
        ),
        'signupLabel' => L("S'inscrire à la liste de diffusion", 'Subscribe to the mailing list'),
        'signupUrl' => 'https://eepurl.com/ckpGAL',
        'closing' => L("L'équipe de l'APEL est à votre écoute.", 'The APEL team is here to listen to you.'),
    ];

    $pages['ogecPresentation'] = [
        'title' => L("Présentation de l'OGEC", 'OGEC presentation'),
        'headline' => L("L'OGEC : des bénévoles engagés pour notre projet", 'OGEC: volunteers committed to our project'),
        'body' => L(
            P("L'OGEC (Organisme de Gestion de l'Enseignement Catholique) est composé de bénévoles, parents d'élèves ou anciens parents d'élèves qui se réunissent en Conseils d'Administration 5 à 6 fois par an. Son rôle : donner les moyens à l'établissement de mettre en œuvre son projet pédagogique, éducatif et pastoral. L'établissement étant sous contrat d'association avec l'État, les enseignants sont rémunérés par l'Éducation Nationale et le programme est conforme à ses directives."),
            P("The OGEC (Organisme de Gestion de l'Enseignement Catholique) is composed of volunteers — current or former parents — who meet in Board of Directors sessions 5 to 6 times a year. Its role: to provide the school with the means to implement its pedagogical, educational and pastoral project. As the school is under a state contract, teachers are paid by the Éducation Nationale and the curriculum follows its directives.")
        ),
        'domainsTitle' => L("L'OGEC intervient dans les domaines suivants", 'The OGEC operates in the following areas'),
        'domains' => [
            ['name' => L('Social'), 'description' => L("En tant qu'employeur du personnel non pris en charge par l'État : personnel administratif et éducatif (ASEM, assistante pédagogique).", 'As employer of staff not covered by the State: administrative and educational staff (ASEM, teaching assistant).')],
            ['name' => L('Économique et financier', 'Economic and financial'), 'description' => L('Il perçoit les contributions des familles et des collectivités locales et gère les dépenses.', 'It collects contributions from families and local authorities and manages expenditure.')],
            ['name' => L('Juridique', 'Legal'), 'description' => L("Comme personnalité juridique de l'établissement.", 'As the legal entity of the establishment.')],
            ['name' => L('Gestion immobilière', 'Property management'), 'description' => L("Pour l'entretien courant des locaux mais aussi leur rénovation, entièrement à la charge de l'OGEC.", "For the routine upkeep of the premises as well as renovation, entirely at the OGEC's expense.")],
        ],
        'closingText' => L(
            "L'OGEC assure une gestion rigoureuse et prudente de l'établissement afin de maintenir la contribution des familles à un niveau raisonnable tout en offrant un cadre de travail adapté à nos enfants et au personnel.",
            'The OGEC ensures rigorous and prudent management of the school in order to keep family contributions at a reasonable level while providing a suitable working environment for our children and staff.'
        ),
        'compositionTitle' => L("Composition de l'OGEC de l'école de l'Assomption", "OGEC board — École de l'Assomption"),
        'members' => array_map(fn ($m) => ['role' => L($m[0], $m[1]), 'name' => $m[2]], [
            ['Présidente', 'President', 'Mme Christine Cormerais'],
            ['Vice-président', 'Vice-president', 'M Pierre Avenel'],
            ['Trésorier', 'Treasurer', 'Mme Sophie Corrihons'],
            ['Secrétaire', 'Secretary', 'Mme Clarisse Abo-Dib'],
        ]),
    ];

    $mail = '<a href="mailto:' . $email . '">' . $email . '</a>';
    $pages['legal'] = [
        'title' => L('Mentions légales', 'Legal notice'),
        'body' => L(
            "<h2>Éditeur du site</h2><p>École de l'Assomption<br>École catholique privée sous contrat d'association avec l'État<br>32, rue Lord Kitchener — 76600 Le Havre<br>Téléphone : 02 35 43 60 68<br>E-mail : $mail</p>"
            . "<h2>Directeur de la publication</h2><p>Le chef d'établissement de l'École de l'Assomption.</p>"
            . '<h2>Hébergeur</h2><p>OVH SAS<br>2 rue Kellermann — 59100 Roubaix, France<br><a href="https://www.ovhcloud.com">www.ovhcloud.com</a></p>'
            . "<h2>Propriété intellectuelle</h2><p>L'ensemble des contenus présents sur ce site (textes, images, logos, graphismes) est la propriété exclusive de l'École de l'Assomption, sauf mention contraire. Toute reproduction, même partielle, est interdite sans autorisation écrite préalable.</p>"
            . "<h2>Responsabilité</h2><p>L'École de l'Assomption s'efforce d'assurer l'exactitude et la mise à jour des informations diffusées sur ce site. Elle se réserve le droit de corriger ou de modifier le contenu à tout moment et sans préavis. L'école ne saurait être tenue responsable de l'utilisation faite des informations disponibles sur le site.</p>",
            "<h2>Publisher</h2><p>École de l'Assomption<br>Private Catholic school under contract with the French State<br>32, rue Lord Kitchener — 76600 Le Havre, France<br>Phone: +33 2 35 43 60 68<br>Email: $mail</p>"
            . "<h2>Publication director</h2><p>The head teacher of the École de l'Assomption.</p>"
            . '<h2>Hosting provider</h2><p>OVH SAS<br>2 rue Kellermann — 59100 Roubaix, France<br><a href="https://www.ovhcloud.com">www.ovhcloud.com</a></p>'
            . "<h2>Intellectual property</h2><p>All content on this website (texts, images, logos, graphics) is the exclusive property of École de l'Assomption, unless otherwise stated. Any reproduction, even partial, is prohibited without prior written permission.</p>"
            . "<h2>Liability</h2><p>École de l'Assomption strives to ensure the accuracy and currency of the information published on this site. It reserves the right to correct or modify content at any time without notice. The school cannot be held liable for any use made of the information available on the site.</p>"
        ),
    ];

    $pages['privacy'] = [
        'title' => L('Politique de confidentialité', 'Privacy policy'),
        'body' => L(
            "<h2>Responsable du traitement</h2><p>École de l'Assomption<br>32, rue Lord Kitchener — 76600 Le Havre<br>$mail</p>"
            . "<h2>Données collectées</h2><p>Ce site ne collecte aucune donnée personnelle de ses visiteurs. Il n'utilise ni mesure d'audience, ni pixel de suivi, ni formulaire. Les polices de caractères sont hébergées sur nos propres serveurs : aucune donnée n'est transmise à Google.</p>"
            . "<h2>Cookies</h2><p>Les visiteurs du site ne reçoivent aucun cookie. Seules les personnes chargées de mettre à jour le site reçoivent, lorsqu'elles se connectent à l'administration, un cookie de session strictement nécessaire à leur connexion. Il est supprimé à la déconnexion.</p>"
            . "<h2>Carte</h2><p>La carte de la page « Localisation & contact » est fournie par OpenStreetMap. Elle ne se charge que si vous cliquez sur « Afficher la carte » ; votre navigateur contacte alors les serveurs d'OpenStreetMap.</p>"
            . "<h2>Hébergement</h2><p>Ce site est hébergé en France par OVH SAS (2 rue Kellermann, 59100 Roubaix).</p>"
            . "<h2>Vos droits (RGPD)</h2><p>Conformément au Règlement Général sur la Protection des Données (UE 2016/679), vous disposez d'un droit d'accès, de rectification, d'effacement et d'opposition. Pour exercer ces droits, écrivez-nous à $mail. Vous pouvez également adresser une réclamation à la CNIL (<a href=\"https://www.cnil.fr\">www.cnil.fr</a>).</p>",
            "<h2>Data controller</h2><p>École de l'Assomption<br>32, rue Lord Kitchener — 76600 Le Havre, France<br>$mail</p>"
            . '<h2>Data collected</h2><p>This website does not collect any personal data from its visitors. It uses no analytics, no tracking pixels and no forms. Fonts are served from our own servers: no data is sent to Google.</p>'
            . '<h2>Cookies</h2><p>Visitors receive no cookies. Only the people who update the site receive, when they log in to the admin area, a session cookie that is strictly necessary for their login. It is deleted when they log out.</p>'
            . "<h2>Map</h2><p>The map on the “Location & contact” page is provided by OpenStreetMap. It only loads if you click “Show the map”; your browser then contacts OpenStreetMap's servers.</p>"
            . '<h2>Hosting</h2><p>This website is hosted in France by OVH SAS (2 rue Kellermann, 59100 Roubaix).</p>'
            . "<h2>Your rights (GDPR)</h2><p>Under the General Data Protection Regulation (EU 2016/679), you have the right of access, rectification, erasure and objection. To exercise these rights, write to us at $mail. You may also lodge a complaint with the CNIL (<a href=\"https://www.cnil.fr\">www.cnil.fr</a>).</p>"
        ),
    ];

    $stmt = $pdo->prepare('INSERT OR IGNORE INTO pages (key, data, updated_at) VALUES (?, ?, ?)');
    foreach ($pages as $key => $data) {
        $stmt->execute([$key, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), date('Y-m-d H:i:s')]);
    }
}
