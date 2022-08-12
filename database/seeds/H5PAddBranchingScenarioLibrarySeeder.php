<?php

use Illuminate\Database\Seeder;

class H5PAddBranchingScenarioLibrarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $h5pBranchingScenarioLibParams = ['name' => "H5P.BranchingScenario", "major_version" => 1, "minor_version" => 7];
        $h5pBranchingScenarioLib = DB::table('h5p_libraries')->where($h5pBranchingScenarioLibParams)->first();

      if (empty($h5pBranchingScenarioLib)) {
          $h5pBranchingScenarioLibId = DB::table('h5p_libraries')->insertGetId([
                          'name' => 'H5P.BranchingScenario',
                          'title' => 'Branching Scenario',
                          'major_version' => 1,
                          'minor_version' => 7,
                          'patch_version' => 1,
                          'embed_types' => 'iframe',
                          'runnable' => 1,
                          'restricted' => 0,
                          'fullscreen' => 0,
                          'preloaded_js' => 'dist/dist.js',
                          'preloaded_css' => 'styles/branchingScenario.css,styles/branchingQuestion.css,styles/genericScreen.css,styles/libraryScreen.css,styles/libraryScreenOverlay.css',
                          'drop_library_css' => '',
                          'semantics' => $this->getSemantics(),
                          'tutorial_url' => ' ',
                          'has_icon' => 1
          ]);

          // insert dependent libraries
          $this->insertDependentLibraries($h5pBranchingScenarioLibId);

          // insert libraries languages
          $this->insertLibrariesLanguages($h5pBranchingScenarioLibId);
      }
    
    }

    /**
     * Insert Dependent Libraries
     * @param $h5pBranchingScenarioLibId
     */
    private function insertDependentLibraries($h5pBranchingScenarioLibId)
    {
        //Preloaded Dependencies
        $h5pFontAwesomeParams = ['name' => "FontAwesome", "major_version" => 4, "minor_version" => 5];
        $h5pFontAwesomeLib = DB::table('h5p_libraries')->where($h5pFontAwesomeParams)->first();
        $h5pFontAwesomeLibId = $h5pFontAwesomeLib->id;

        // Editor Dependencies
        $h5pEditorShowWhenParams = ['name' => "H5PEditor.ShowWhen", "major_version" => 1, "minor_version" => 0];
        $h5pEditorShowWhenLib = DB::table('h5p_libraries')->where($h5pEditorShowWhenParams)->first();
        $h5pEditorShowWhenLibId = $h5pEditorShowWhenLib->id;

        $h5pEditorBranchingScenarioParams = ['name' => "H5PEditor.BranchingScenario", "major_version" => 1, "minor_version" => 4];
        $h5pEditorBranchingScenarioLib = DB::table('h5p_libraries')->where($h5pEditorBranchingScenarioParams)->first();
        if(empty($h5pEditorBranchingScenarioLib)) {
            $h5pEditorBranchingScenarioLibId = DB::table('h5p_libraries')->insertGetId([
                'name' => 'H5PEditor.BranchingScenario',
                'title' => 'Branching Scenario Editor',
                'major_version' => 1,
                'minor_version' => 4,
                'patch_version' => 0,
                'embed_types' => '',
                'runnable' => 0,
                'restricted' => 0,
                'fullscreen' => 0,
                'preloaded_js' => '',
                'preloaded_css' => 'dist/dist.js',
                'drop_library_css' => '',
                'semantics' => '',
                'tutorial_url' => ' ',
                'has_icon' => 0
            ]);
            $this->insertEditorDependentLibraries($h5pEditorBranchingScenarioLibId);
        } else {
            $h5pEditorBranchingScenarioLibId = $h5pEditorBranchingScenarioLib->id;
        }


        DB::table('h5p_libraries_libraries')->insert([
            'library_id' => $h5pBranchingScenarioLibId,
            'required_library_id' => $h5pFontAwesomeLibId,
            'dependency_type' => 'preloaded'
        ]);

        DB::table('h5p_libraries_libraries')->insert([
            'library_id' => $h5pBranchingScenarioLibId,
            'required_library_id' => $h5pEditorShowWhenLibId,
            'dependency_type' => 'editor'
        ]);

        DB::table('h5p_libraries_libraries')->insert([
            'library_id' => $h5pBranchingScenarioLibId,
            'required_library_id' => $h5pEditorBranchingScenarioLibId,
            'dependency_type' => 'editor'
        ]);

    }

    /**
     * Insert Library Language Semantics
     * @param $h5pBranchingScenarioLibId
     */
    private function insertLibrariesLanguages($h5pBranchingScenarioLibId)
    {
        // af.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pBranchingScenarioLibId,
            'language_code' => 'af',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Vertakking scenario redakteur","fields":[{"label":"Titel"},{"label":"Beginskerm","fields":[{"label":"Beginskerm titel","placeholder":"Kursustitel"},{"label":"Beginskerm ondertitel","placeholder":"Kursusbesonderhede"},{"label":"Beginskermprent"},{"label":"Image alternative text"}]},{"label":"Lys van eindskerms","field":{"label":"Eindskerm","fields":[{"label":"Titel"},{"label":"Teks"},{"label":"Prent"},{"label":"Telling","description":"Die telling sal gestuur word na enige LBS of enige ander verbonde diens wat tellings van H5P vir gebruikers wat die verstek eindscenario bereik ontvang"},{"label":"Inhoud ID"}]}},{"label":"Lys van vertakking-scenario inhoud","entity":"inhoud","field":{"fields":[{},{"label":"Wys inhoudtitel in sig","description":"Indien verkies, sal die gebruiker die inhoudtitel in die boonste balk bo hierdie inhoud sien"},{"label":"Vereis om te voltooi","description":"Versoek kykers om die interaksie te voltooi voordat hulle met die scenario voortgaan. Hierdie instelling sal slegs werk vir interaksies wat voltooi kan word","options":[{"label":"Gebruik gedragsinstellings"},{"label":"Aktiveer"},{"label":"Deaktiveer"}]},{"label":"Volgende inhoud ID (eindskerms word definieer deur negatiewe getalle)"},{"label":"Terugvoer","fields":[{"label":"Terugvoertitel"},{"label":"Terugvoerteks"},{"label":"Terugvoerprent"},{"label":"Telling vir hierdie scenario","description":"Die telling sal gestuur word na enige LBS of enige ander verbonde diens wat tellings van H5P vir gebruikers wat hierdie scenario bereik"}]},{"label":"Gedragsinstellings","description":"Hierdie sal gebruikers toelaat om terug te gaan om die vorige inhoud/vrae in die scenario te sien","options":[{"label":"Gebruik gedragsinstellings"},{"label":"Aktiveer"},{"label":"Deaktiveer"}]}]}},{"label":"Telling opsies","fields":[{"label":"Telling opsies","description":"Kies tipe telling","options":[{"label":"Stel telling vir elke eindscenario staties"},{"label":"Bereken telling van gebruikerantwoorde dinamies"},{"label":"Geen telling"}]},{"label":"Include scores from interactions within the Branching Scenario","description":"If chosen scores from for instance Interactive Videos will be added to the total score obtained. If not chosen only scores specified in the feedback sections of the content items, branching questions and end scenarios will count."}]},{"label":"Gedragsinstellings","fields":[{"label":"Negeer agterwaardse navigasie","description":"Negeer die individuele instelling om agterwaardse navigasie te aktiveer"},{"label":"Oorskryding vereis voltooide inhoud","description":"Stel die individuele instellings oor om te vereis dat die inhoud klaar moet wees voordat jy die \"Gaan voort\" -knoppie aktiveer. Sal geen effek hê as die inhoud nie aandui of dit \'klaar\' is nie, bv. beelde of kursusaanbiedings met net een skyfie."},{"label":"Randomize Branching Questions","description":"Branching questions will be displayed in random order"}]},{"label":"Lokalisasie","fields":[{"label":"Teks vir die knoppies op die beginskerm","default":"Begin die kursus"},{"label":"Teks vir die knoppies op die eindskerm","default":"Herbegin kursus"},{"label":"Teks vir die terugknoppie op elk van die biblioteekskerms en vertakkingsvrae","default":"Terug"},{"label":"Teks vir die knoppie op elk van die biblioteekskerms","default":"Gaan voort"},{"label":"Teks vir die knoppies wat gebruik is om die video te herspeel","default":"Speel video weer"},{"label":"Etiket vir eindskermtelling","default":"Jou telling:"},{"label":"Etiket vir telling by eindskerm","default":"Jou telling:"},{"label":"Aria-etiket vir die volskermknoppie","default":"Volskerm"}]}]}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // ar.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pBranchingScenarioLibId,
            'language_code' => 'ar',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Branching Scenario Editor","fields":[{"label":"العنوان"},{"label":"شاشة البدء","fields":[{"label":"عنوان شاشةالبدء","placeholder":"Title for your course"},{"label":"العنوان الفرعي لشاشة البدء","placeholder":"Details about the course"},{"label":"صورة شاشة البدء"},{"label":"Image alternative text"}]},{"label":"List of end screens","field":{"label":"شاشة النهاية","fields":[{"label":"Title"},{"label":"Text"},{"label":"Image"},{"label":"Score","description":"The score will be sent to any LMS, LRS or any other connected service that receives scores from H5P for users who reach the default end scenario"},{"label":"Content ID"}]}},{"label":"محتوى قائمة سيناريو المتفرعة","entity":"content","field":{"fields":[{},{"label":"Show content title in view","description":"If selected, the user will see the content title in the top bar above this content"},{"label":"Required to be completed","description":"Urges viewers to complete the interaction before proceeding with the scenario. This setting will only work for interactions that can be completed","options":[{"label":"Use behavioural setting"},{"label":"Enabled"},{"label":"Disabled"}]},{"label":"Next Content ID (end screens are defined by negative numbers)"},{"label":"Feedback","fields":[{"label":"Feedback title"},{"label":"Feedback text"},{"label":"Feedback image"},{"label":"Score for this scenario","description":"The score will be sent to any LMS, LRS or any other connected service that receives scores from H5P for users who reach this scenario"}]},{"label":"إعدادات سلوكية","description":"This will allow user to go back and see the previous content/question in the scenario","options":[{"label":"Use behavioural setting"},{"label":"Enabled"},{"label":"Disabled"}]}]}},{"label":"Scoring options","fields":[{"label":"Scoring options","description":"Select type of scoring","options":[{"label":"Statically set score for each end scenario"},{"label":"Dynamically calculate score from user answers"},{"label":"No scoring"}]},{"label":"Include scores from interactions within the Branching Scenario","description":"If chosen scores from for instance Interactive Videos will be added to the total score obtained. If not chosen only scores specified in the feedback sections of the content items, branching questions and end scenarios will count."}]},{"label":"إعدادات سلوكية","fields":[{"label":"Override require content finished","description":"Override the individual settings for requiring the content to be finished before activating the \"Proceed\" button. Will not have any effect if the content doesn\'t indicate if it was \"finished\", e.g. images or course presentations with just one slide."},{"label":"Required to watch","description":"Urges viewers to complete the Video, Interactive Vdeo and Course Presentation before proceeding with the scenario. This can be overridden on a content level"},{"label":"Randomize Branching Questions","description":"Branching questions will be displayed in random order"}]},{"label":"Localization","fields":[{"label":"Text for the button on the start screen","default":"Start the course"},{"label":"Text for the button on the end screen","default":"Restart the course"},{"label":"Text for the back button on each of the library screens and branching questions","default":"Back"},{"label":"Text for the button on each of the library screens","default":"Proceed"},{"label":"Text for the button used to replay a video","default":"Replay the video"},{"label":"Label for score on the end screen","default":"Your score:"},{"label":"Label for score on the end screen","default":"Your score:"},{"label":"Aria label for fullscreen button","default":"Fullscreen"}]}]}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // bg.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pBranchingScenarioLibId,
            'language_code' => 'bg',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Редактор на разделящи се сценарии","fields":[{"label":"Заглавие"},{"label":"Начален екран","fields":[{"label":"Заглавие на начален екран","placeholder":"Заглавие на вашия курс"},{"label":"Подзаглавие на начален екран","placeholder":"Подробности за курса"},{"label":"Изображение на начален екран"},{"label":"Image alternative text"}]},{"label":"Списък на крайните екрани","field":{"label":"Краен екран","fields":[{"label":"Заглавие"},{"label":"Текст"},{"label":"Изображение"},{"label":"Резултат","description":"Резултатът ще бъде изпратен до всяка LMS, LRS или всяка друга свързана услуга, която получава резултати от H5P за потребители, които достигат сценария за крайно неизпълнение"},{"label":"Content ID"}]}},{"label":"Списък със съдрржанието на разклоняващите се сценарии","entity":"Съдържание","field":{"fields":[{},{"label":"Показване на заглавията на съдържанието при преглед","description":"Ако е избрано, потребителят ще види заглавието на съдържанието в горната лента над това съдържание"},{"label":"Required to be completed","description":"Urges viewers to complete the interaction before proceeding with the scenario. This setting will only work for interactions that can be completed","options":[{"label":"Use behavioural setting"},{"label":"Enabled"},{"label":"Disabled"}]},{"label":"Следващо ID Съдържание (крайните екрани се дефинират с отрицателни числа)"},{"label":"Обратна връзка","fields":[{"label":"Наименование на обратната връзка"},{"label":"Текст на обратната връзка"},{"label":"Изображение на обратната връзка"},{"label":"Резултат за този сценарий","description":"Резултатът ще бъде изпратен до всяка LMS, LRS или друга свързана услуга, която получава резултати от H5P за потребители, които достигат този сценарий"}]},{"label":"Navigate back","description":"This will allow user to go back and see the previous content/question in the scenario","options":[{"label":"Use behavioural setting"},{"label":"Enabled"},{"label":"Disabled"}]}]}},{"label":"Опции при точкуването","fields":[{"label":"Опции при точкуването","description":"Избери начин на точкуване","options":[{"label":"Статично зададен резултат за всеки краен сценарий"},{"label":"Динамично изчисляване на резултата от потребителските отговори"},{"label":"Без точки"}]},{"label":"Include scores from interactions within the Branching Scenario","description":"If chosen scores from for instance Interactive Videos will be added to the total score obtained. If not chosen only scores specified in the feedback sections of the content items, branching questions and end scenarios will count."}]},{"label":"Behavioural settings","fields":[{"label":"Override require content finished","description":"Override the individual settings for requiring the content to be finished before activating the \"Proceed\" button. Will not have any effect if the content doesn\'t indicate if it was \"finished\", e.g. images or course presentations with just one slide."},{"label":"Required to watch","description":"Urges viewers to complete the Video, Interactive Vdeo and Course Presentation before proceeding with the scenario. This can be overridden on a content level"},{"label":"Randomize Branching Questions","description":"Branching questions will be displayed in random order"}]},{"label":"Локализация","fields":[{"label":"Текст за бутона в началния екран","default":"Начало на курса"},{"label":"Текст за бутона за крайния екран","default":"Започни отново този курс"},{"label":"Текст за бутона на всеки екран","default":"Продължи"},{"label":"Text for the button used to replay a video","default":"Replay the video"},{"label":"Етикет за резултата на крайния прозорец","default":"Вашият резултат:"},{"label":"Label for score on the end screen","default":"Your score:"},{"label":"Label for score on the end screen","default":"Your score:"},{"label":"Aria label for fullscreen button","default":"Fullscreen"}]}]}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // ca.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pBranchingScenarioLibId,
            'language_code' => 'ca',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Editor d’escenaris amb ramificacions","fields":[{"label":"Títol"},{"label":"Pantalla d’inici","fields":[{"label":"Títol de la pantalla d’inici","placeholder":"Títol del vostre curs"},{"label":"Subtítol de la pantalla d’inici","placeholder":"Detalls sobre el curs"},{"label":"Imatge de la pantalla d’inici"},{"label":"Image alternative text"}]},{"label":"Llista de pantalles finals","field":{"label":"Pantalla final","fields":[{"label":"Títol"},{"label":"Text"},{"label":"Imatge"},{"label":"Puntuació","description":"La puntuació s’enviarà a qualsevol LMS, LRS o qualsevol altre servei connectat que rebi puntuacions de H5P per als usuaris que arriben a l’escenari final predeterminat"},{"label":"ID del contingut"}]}},{"label":"Llista del contingut de l’escenari amb ramificacions","entity":"contingut","field":{"fields":[{},{"label":"Mostra el títol del contingut a la visualització","description":"Si se selecciona aquesta opció, l’usuari veurà el títol de contingut a la barra superior, a sobre d’aquest contingut"},{"label":"Required to be completed","description":"Urges viewers to complete the interaction before proceeding with the scenario. This setting will only work for interactions that can be completed","options":[{"label":"Use behavioural setting"},{"label":"Activat"},{"label":"Desactivat"}]},{"label":"Identificador del contingut següent (les pantalles finals es defineixen amb números negatius)"},{"label":"Retroacció","fields":[{"label":"Títol de la retroacció"},{"label":"Text de retroacció"},{"label":"Imatge de retroacció"},{"label":"Puntuació d’aquest escenari","description":"La puntuació s’enviarà al LMS, LRS o qualsevol altre servei connectat que rebi puntuacions de H5P per als usuaris que arribin a aquest escenari."}]},{"label":"Navigate back","description":"This will allow user to go back and see the previous content/question in the scenario","options":[{"label":"Use behavioural setting"},{"label":"Activat"},{"label":"Desactivat"}]}]}},{"label":"Opcions de puntuació","fields":[{"label":"Opcions de puntuació","description":"Seleccioneu el tipus de puntuació","options":[{"label":"Establiu la puntuació de manera estàtica per a cada escenari"},{"label":"Calculeu dinàmicament la puntuació en funció de les respostes dels usuaris"},{"label":"Sense puntuació"}]},{"label":"Include scores from interactions within the Branching Scenario","description":"If chosen scores from for instance Interactive Videos will be added to the total score obtained. If not chosen only scores specified in the feedback sections of the content items, branching questions and end scenarios will count."}]},{"label":"Opcions de comportament","fields":[{"label":"Override require content finished","description":"Override the individual settings for requiring the content to be finished before activating the \"Proceed\" button. Will not have any effect if the content doesn\'t indicate if it was \"finished\", e.g. images or course presentations with just one slide."},{"label":"Required to watch","description":"Urges viewers to complete the Video, Interactive Vdeo and Course Presentation before proceeding with the scenario. This can be overridden on a content level"},{"label":"Randomize Branching Questions","description":"Branching questions will be displayed in random order"}]},{"label":"Localització","fields":[{"label":"Text del botó a la pantalla inicial","default":"Inicia el curs"},{"label":"Text del botó a la pantalla final","default":"Torna a iniciar el curs"},{"label":"Text del botó de cada una de les pantalles de biblioteca","default":"Continua"},{"label":"Text for the button used to replay a video","default":"Replay the video"},{"label":"Etiqueta per a la puntuació a la pantalla final","default":"Torna a reproduir el vídeo"},{"label":"Label for score on the end screen","default":"Your score:"},{"label":"Label for score on the end screen","default":"Your score:"},{"label":"Aria label for fullscreen button","default":"Fullscreen"}]}]}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // cs.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pBranchingScenarioLibId,
            'language_code' => 'cs',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Editor větvení scénářů","fields":[{"label":"Nadpis"},{"label":"Úvodní obrazovka","fields":[{"label":"Název úvodní obrazovky","placeholder":"Titulek vašeho kurzu"},{"label":"Podtitul úvodní obrazovky","placeholder":"Podrobnosti o kurzu"},{"label":"Obrázek úvodní obrazovky"},{"label":"Image alternative text"}]},{"label":"Seznam koncových obrazovek","field":{"label":"Koncová obrazovka","fields":[{"label":"Nadpis"},{"label":"Text"},{"label":"Obrázek"},{"label":"Skóre","description":"Skóre bude odesláno do jakéhokoli LMS, LRS nebo jakékoli jiné připojené služby, která přijímá skóre od H5P pro uživatele, kteří dosáhnou výchozího koncového scénáře"},{"label":"ID obsahu"}]}},{"label":"Seznam obsahu scénáře větvení","entity":"obsah","field":{"fields":[{},{"label":"Zobrazit název obsahu v zobrazení","description":"Pokud je vybráno, uvidí uživatel v horním pruhu nad tímto obsahem nadpis obsahu"},{"label":"Povinné vyplnění","description":"Vyzývá diváky, aby před pokračováním ve scénáři dokončili interakci. Toto nastavení bude fungovat pouze u interakcí, které lze dokončit","options":[{"label":"Použít nastavení chování"},{"label":"Povoleno"},{"label":"Zakázáno"}]},{"label":"Další ID obsahu (koncové obrazovky jsou definovány zápornými čísly)"},{"label":"Zpětná vazba","fields":[{"label":"Nadpis zpětné vazby"},{"label":"Text zpětné vazby"},{"label":"Obrázek zpětné vazby"},{"label":"Skóre pro tento scénář","description":"Skóre bude odesláno do všech LMS, LRS nebo jakékoli jiné připojené služby, která přijímá skóre od H5P pro uživatele, kteří dosáhnou tohoto scénáře"}]},{"label":"Nastavení chování","description":"To umožní uživateli vrátit se zpět a zobrazit předchozí obsah/otázku ve scénáři","options":[{"label":"Použít nastavení chování"},{"label":"Povoleno"},{"label":"Zakázáno"}]}]}},{"label":"Možnosti bodování","fields":[{"label":"Možnosti bodování","description":"Vybrat typ bodování","options":[{"label":"Staticky stanovené skóre pro každý scénář"},{"label":"Dynamicky vypočítat skóre z odpovědí uživatele"},{"label":"Žádné hodnocení"}]},{"label":"Include scores from interactions within the Branching Scenario","description":"If chosen scores from for instance Interactive Videos will be added to the total score obtained. If not chosen only scores specified in the feedback sections of the content items, branching questions and end scenarios will count."}]},{"label":"Nastavení chování","fields":[{"label":"Přepsat zpětnou navigaci","description":"Přepsat jednotlivá nastavení pro povolení zpětné navigace"},{"label":"Přepsat vyžaduje dokončení obsahu","description":"Před aktivací tlačítka „Pokračovat“ přepsat jednotlivá nastavení vyžadující dokončení obsahu. Nebude mít žádný účinek, pokud obsah neuvádí, zda byl „dokončen“, např. obrázky nebo prezentace kurzů pouze s jedním snímkem."},{"label":"Randomize Branching Questions","description":"Branching questions will be displayed in random order"}]},{"label":"Lokalizace","fields":[{"label":"Text tlačítka na úvodní obrazovce","default":"Zahájit kurz"},{"label":"Text tlačítka na koncové obrazovce","default":"Restartovat kurz"},{"label":"Text pro tlačítko Zpět na každé z obrazovek knihovny a větvící otázky","default":"Pokračovat"},{"label":"Text tlačítka na každé obrazovce knihovny","default":"Pokračovat"},{"label":"Text tlačítka použitého k přehrání videa","default":"Přehrát video"},{"label":"Popisek pro skóre na závěrečné obrazovce","default":"Vaše skóre:"},{"label":"Popisek pro skóre na závěrečné obrazovce","default":"Vaše skóre:"},{"label":"Aria label pro tlačítko na celou obrazovku","default":"Celá obrazovka"}]}]}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // cy.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pBranchingScenarioLibId,
            'language_code' => 'cy',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Golygydd Senario Ganghennog","fields":[{"label":"Teitl"},{"label":"Sgrin gychwyn","fields":[{"label":"Teitl y sgrin gychwyn","placeholder":"Teitl ar gyfer eich cwrs"},{"label":"Is-deitl y sgrin gychwyn","placeholder":"Manylion y cwrs"},{"label":"Delwedd y sgrin gychwyn"},{"label":"Image alternative text"}]},{"label":"Rhestr sgriniau diweddu","field":{"label":"Sgrin ddiweddu","fields":[{"label":"Teitl"},{"label":"Testun"},{"label":"Delwedd"},{"label":"Sgôr","description":"Caiff y sgôr ei anfon i unrhyw \'LMS\', \'LRS\' neu unrhyw wasanaeth gysylltiedig sydd yn derbyn sgoriau oddi wrth H5P am ddefnyddwyr sydd yn cyrraedd y senario ddiweddu diofyn"},{"label":"ID Cynnwys"}]}},{"label":"Rhestr cynnwys senario ganghennog","entity":"cynnwys","field":{"fields":[{},{"label":"Dangos teitl y cynnwys yn yr olygfa","description":"Os wedi\'i ddewis, bydd y defnyddiwr yn gweld teitl y cynnwys yn y bar ar y brig uwchben y cynnwys hwn"},{"label":"Required to be completed","description":"Urges viewers to complete the interaction before proceeding with the scenario. This setting will only work for interactions that can be completed","options":[{"label":"Use behavioural setting"},{"label":"Enabled"},{"label":"Disabled"}]},{"label":"ID Cynnwys Nesaf (caiff sgriniau diweddu eu hadnabod gan rifau negatif)"},{"label":"Adborth","fields":[{"label":"Teitl adborth"},{"label":"Testun adborth"},{"label":"Delwedd adborth"},{"label":"Sgôr am y senario hon","description":"Caiff y sgôr ei anfon i unrhyw \'LMS\', \'LRS\' neu unrhyw wasanaeth gysylltiedig sydd yn derbyn sgoriau oddi wrth H5P am ddefnyddwyr sydd yn cyrraedd y senario hon"}]},{"label":"Navigate back","description":"This will allow user to go back and see the previous content/question in the scenario","options":[{"label":"Use behavioural setting"},{"label":"Enabled"},{"label":"Disabled"}]}]}},{"label":"Opsiynau Sgorio","fields":[{"label":"Opsiynau sgorio","description":"Dewiswch math y sgorio","options":[{"label":"Gosodwch (yn statig) y sgôr ar gyfer pob senario diweddu"},{"label":"Cyfrifwch y sgôr (yn ddeinamig) o atebion defnyddwyr"},{"label":"Dim sgorio"}]},{"label":"Include scores from interactions within the Branching Scenario","description":"If chosen scores from for instance Interactive Videos will be added to the total score obtained. If not chosen only scores specified in the feedback sections of the content items, branching questions and end scenarios will count."}]},{"label":"Behavioural settings","fields":[{"label":"Override require content finished","description":"Override the individual settings for requiring the content to be finished before activating the \"Proceed\" button. Will not have any effect if the content doesn\'t indicate if it was \"finished\", e.g. images or course presentations with just one slide."},{"label":"Required to watch","description":"Urges viewers to complete the Video, Interactive Vdeo and Course Presentation before proceeding with the scenario. This can be overridden on a content level"},{"label":"Randomize Branching Questions","description":"Branching questions will be displayed in random order"}]},{"label":"Lleoleiddio","fields":[{"label":"testun ar gyfer y botwm ar y sgrin gychwyn","default":"Dechreuwch y cwrs"},{"label":"Testun ar gyfer y botwm ar y sgrin ddiweddu","default":"Ailddechreuwch y cwrs"},{"label":"Testun ar gyfer y botwm ar bob un o\'r sgriniau llyfrgell","default":"Bwrw ymlaen"},{"label":"Text for the button used to replay a video","default":"Replay the video"},{"label":"Label ar gyfer y sgôr ar y sgrin diweddu","default":"Eich sgôr:"},{"label":"Label for score on the end screen","default":"Your score:"},{"label":"Label for score on the end screen","default":"Your score:"},{"label":"Aria label for fullscreen button","default":"Fullscreen"}]}]}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // da.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pBranchingScenarioLibId,
            'language_code' => 'da',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Branching Scenario Editor","fields":[{"label":"Title"},{"label":"Start screen","fields":[{"label":"Start screen title","placeholder":"Title for your course"},{"label":"Start screen subtitle","placeholder":"Details about the course"},{"label":"Start screen image"},{"label":"Image alternative text"}]},{"label":"List of end screens","field":{"label":"End screen","fields":[{"label":"Title"},{"label":"Text"},{"label":"Image"},{"label":"Score","description":"The score will be sent to any LMS, LRS or any other connected service that receives scores from H5P for users who reach the default end scenario"},{"label":"Content ID"}]}},{"label":"List of branching scenario content","entity":"content","field":{"fields":[{},{"label":"Show content title in view","description":"If selected, the user will see the content title in the top bar above this content"},{"label":"Required to be completed","description":"Urges viewers to complete the interaction before proceeding with the scenario. This setting will only work for interactions that can be completed","options":[{"label":"Use behavioural setting"},{"label":"Enabled"},{"label":"Disabled"}]},{"label":"Next Content ID (end screens are defined by negative numbers)"},{"label":"Feedback","fields":[{"label":"Feedback title"},{"label":"Feedback text"},{"label":"Feedback image"},{"label":"Score for this scenario","description":"The score will be sent to any LMS, LRS or any other connected service that receives scores from H5P for users who reach this scenario"}]},{"label":"Behavioural settings","description":"This will allow user to go back and see the previous content/question in the scenario","options":[{"label":"Use behavioural setting"},{"label":"Enabled"},{"label":"Disabled"}]}]}},{"label":"Scoring options","fields":[{"label":"Scoring options","description":"Select type of scoring","options":[{"label":"Statically set score for each end scenario"},{"label":"Dynamically calculate score from user answers"},{"label":"No scoring"}]},{"label":"Include scores from interactions within the Branching Scenario","description":"If chosen scores from for instance Interactive Videos will be added to the total score obtained. If not chosen only scores specified in the feedback sections of the content items, branching questions and end scenarios will count."}]},{"label":"Behavioural settings","fields":[{"label":"Override backwards navigation","description":"Override the individual settings for enabling backwards navigation"},{"label":"Override require content finished","description":"Override the individual settings for requiring the content to be finished before activating the \"Proceed\" button. Will not have any effect if the content doesn\'t indicate if it was \"finished\", e.g. images or course presentations with just one slide."},{"label":"Randomize Branching Questions","description":"Branching questions will be displayed in random order"}]},{"label":"Localization","fields":[{"label":"Text for the button on the start screen","default":"Start"},{"label":"Text for the button on the end screen","default":"Start forfra"},{"label":"Text for the back button on each of the library screens and branching questions","default":"Tilbage"},{"label":"Text for the button on each of the library screens","default":"Fortsæt"},{"label":"Text for the disbled button on the library screens","default":"Svar på spørgsmålet for at fortsætte"},{"label":"Text for the button used to replay a video","default":"Se Videoen igen"},{"label":"Label for score on the end screen","default":"Dine point:"},{"label":"Aria label for fullscreen button","default":"Fuld skærm"}]}]}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // de.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pBranchingScenarioLibId,
            'language_code' => 'de',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Editor für Branching Scenario","fields":[{"label":"Titel"},{"label":"Startbildschirm","fields":[{"label":"Titel des Startbildschirms","placeholder":"Titel für den Kurs"},{"label":"Untertitel des Startbildschirms","placeholder":"Details zum Kurs"},{"label":"Kursbild"},{"label":"Alternativtext für das Bild"}]},{"label":"Liste der Endbildschirme","field":{"label":"Endbildschirm","fields":[{"label":"Titel"},{"label":"Text"},{"label":"Bild"},{"label":"Punkte","description":"Wenn Lernende den Standard-Endbildschirm erreichen, werden die Punkte an ein LMS, einen LRS oder einen anderen Dienste geschickt, der Ergebnisse von H5P empfängt"},{"label":"Inhalts-ID"}]}},{"label":"Liste der Branching-Scenario-Inhalte","entity":"Inhalt","field":{"fields":[{},{"label":"Titel anzeigen","description":"Wenn diese Einstellung angewählt wird, sehen die Lernenden den Inhaltstitel in der Titelzeile über dem Inhalt"},{"label":"Muss abgeschlossen werden","description":"Zwingt Lernende, die Interaktion zu beenden, bevor mit dem Szenario fortgefahren werden kann. Diese Einstellung funktioniert nur, wenn Interaktionen abgeschlossen werden können.","options":[{"label":"Verhaltenseinstellung verwenden"},{"label":"Aktiviert"},{"label":"Deaktiviert"}]},{"label":"Nächste Inhalts-ID (Endbildschirme werden durch negative Zahlen definiert)"},{"label":"Rückmeldung","fields":[{"label":"Titel der Rückmeldung"},{"label":"Text der Rückmeldung"},{"label":"Bild der Rückmeldung"},{"label":"Punkte für dieses Szenario","description":"Wenn Lernende dieses Szenario erreichen, werden die Punkte an ein LMS, einen LRS oder einen anderen Dienste geschickt, der Ergebnisse von H5P empfängt"}]},{"label":"Interaktionseinstellungen","description":"Erlaubt es Nutzern, zurückzugehen und den vorherigen Inhalt / Frage im Szenario anzuschauen","options":[{"label":"Verhaltenseinstellung verwenden"},{"label":"Aktiviert"},{"label":"Deaktiviert"}]}]}},{"label":"Optionen für die Bepunktung","fields":[{"label":"Optionen für die Bepunktung","description":"Wähle die Art der Bepunktung","options":[{"label":"Stelle die Punktzahl für jedes Endszenario fest ein"},{"label":"Berechne die Punktzahl für die Antworten der Lernenden dynamisch"},{"label":"Keine Bepunktung"}]},{"label":"Verwende die Punkte der Interaktionen innerhalb des Branching Scenario","description":"Wenn diese Option gewählt ist, werden Punkte aus Inhalten wie Interactive Video zur Gesamtpunktzahl hinzugezählt. Wenn die Option nicht angewählt ist, werden nur die Punkte gezählt, die bei den Rückmeldungen zu Inhalten, bei verzweigenden Fragen und bei den Endszenarios vergeben wurden."}]},{"label":"Interaktionseinstellungen","fields":[{"label":"Rückwärtsnavigation überschreiben","description":"Erlaube den Nutzern zurückzugehen und den vorherigen Inhalt/die vorherige Frage zu sehen"},{"label":"Inhaltsabschluss überschreiben","description":"Überschreibt die individuellen Einstellungen, die vorgeben, dass der Inhalt abgeschlossen sein muss bevor der „Weiter“-Button klickbar wird. Hat keinen Effekt, wenn der Inhalt nicht mitteilt, dass er „abgeschlossen“ wurde, wie z.B. bei Bildern oder Kurspräsentationen mit nur einer Folie."},{"label":"Randomize Branching Questions","description":"Branching questions will be displayed in random order"}]},{"label":"Bezeichnungen und Beschriftungen","fields":[{"label":"Text des Buttons auf dem Startbildschirm","default":"Kurs starten"},{"label":"Text des Buttons auf dem Endbildschirm","default":"Kurs neu starten"},{"label":"Text des \"Zurück\"-Buttons auf jedem Inhaltsbildschirm und jeder Verzweigung","default":"Zurück"},{"label":"Text des Buttons auf jedem Inhaltsbildschirm","default":"Weiter"},{"label":"Text für den Button, der das Video erneut abspielen lässt","default":"Video wiederholen"},{"label":"Beschriftung der Punkte auf dem Endbildschirm","default":"Deine Punktzahl:"},{"label":"Label for score on the end screen","default":"Deine Punkte:"},{"label":"Aria-Beschriftung des Vollbild-Buttons","default":"Vollbild"}]}]}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // el.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pBranchingScenarioLibId,
            'language_code' => 'el',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Επεξεργαστής Branching Scenario","fields":[{"label":"Τίτλος"},{"label":"Οθόνη έναρξης","fields":[{"label":"Τίτλος οθόνης έναρξης","placeholder":"Τίτλος"},{"label":"Υπότιτλος οθόνης έναρξης","placeholder":"Λεπτομέρειες"},{"label":"Εικόνα οθόνης έναρξης"},{"label":"Εναλλακτικό κείμενο εικόνας"}]},{"label":"Οθόνες λήξης","field":{"label":"Οθόνη λήξης","fields":[{"label":"Τίτλος"},{"label":"Κείμενο"},{"label":"Εικόνα"},{"label":"Βαθμολογία","description":"Η βαθμολογία αποστέλλεται σε κάθε LMS, LRS ή οποιοδήποτε άλλο σχετικό service"},{"label":"ID αντικειμένου"}]}},{"label":"Αντικείμενα branching scenario","entity":"αντικειμενου","field":{"fields":[{},{"label":"Προβολή τίτλου αντικειμένου","description":"Αν επιλεγεί, ο χρήστης θα βλέπει τον τίτλο του αντικειμένου στην μπάρα πάνω από το αντικείμενο"},{"label":"Required to be completed","description":"Urges viewers to complete the interaction before proceeding with the scenario. This setting will only work for interactions that can be completed","options":[{"label":"Use behavioural setting"},{"label":"Enabled"},{"label":"Disabled"}]},{"label":"ID επόμενου αντικειμένου (χρησιμοποιήστε αρνητικό αριθμό για την οθόνη λήξης)"},{"label":"Ανατροφοδότηση","fields":[{"label":"Τίτλος ανατροφοδότησης"},{"label":"Κείμενο ανατροφοδότησης"},{"label":"Εικόνα ανατροφοδότησης"},{"label":"Βαθμολογία για αυτό το σενάριο","description":"Η βαθμολογία αποστέλλεται σε κάθε LMS, LRS ή οποιοδήποτε άλλο σχετικό service"}]},{"label":"Navigate back","description":"This will allow user to go back and see the previous content/question in the scenario","options":[{"label":"Use behavioural setting"},{"label":"Enabled"},{"label":"Disabled"}]}]}},{"label":"Επιλογές βαθμολόγησης","fields":[{"label":"Επιλογές βαθμολόγησης","description":"Επιλέξτε τον τύπο της βαθμολόγησης","options":[{"label":"Στατική βαθμολόγηση για κάθε σενάριο"},{"label":"Δυναμική βαθμολόγηση από τις απαντήσεις των χρηστών"},{"label":"Χωρίς βαθμολόγηση"}]},{"label":"Include scores from interactions within the Branching Scenario","description":"If chosen scores from for instance Interactive Videos will be added to the total score obtained. If not chosen only scores specified in the feedback sections of the content items, branching questions and end scenarios will count."}]},{"label":"Behavioural settings","fields":[{"label":"Override require content finished","description":"Override the individual settings for requiring the content to be finished before activating the \"Proceed\" button. Will not have any effect if the content doesn\'t indicate if it was \"finished\", e.g. images or course presentations with just one slide."},{"label":"Required to watch","description":"Urges viewers to complete the Video, Interactive Vdeo and Course Presentation before proceeding with the scenario. This can be overridden on a content level"},{"label":"Randomize Branching Questions","description":"Branching questions will be displayed in random order"}]},{"label":"Προσαρμογή","fields":[{"label":"Κείμενο για το κουμπί της οθόνης έναρξης","default":"Ξεκίνησε"},{"label":"Κείμενο για το κουμπί της οθόνης λήξης","default":"Ξεκίνησε ξανά"},{"label":"Κείμενο για το κουμπί των ενδιάμεσων οθονών","default":"Συνέχισε"},{"label":"Text for the button used to replay a video","default":"Replay the video"},{"label":"Ετικέτα βαθμολογίας στην οθόνη λήξης","default":"Η βαθμολογία σου:"},{"label":"Label for score on the end screen","default":"Your score:"},{"label":"Label for score on the end screen","default":"Your score:"},{"label":"Aria label for fullscreen button","default":"Fullscreen"}]}]}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // es-mx.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pBranchingScenarioLibId,
            'language_code' => 'es-mx',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Editor Branching Scenario (Escenario Ramificado)","fields":[{"label":"Título"},{"label":"Pantalla inicial","fields":[{"label":"Título de la pantalla inicial","placeholder":"Título para su curso"},{"label":"Subtítulo de la pantalla inicial","placeholder":"Detalles sobre el curso"},{"label":"Imagen de la pantalla inicial"},{"label":"Texto alterno de imagen"}]},{"label":"Lista de pantallas finales","field":{"label":"Pantalla final","fields":[{"label":"Título"},{"label":"Texto"},{"label":"Imagen"},{"label":"Puntaje","description":"El puntaje será enviada a cualquier LMS, LRS o cualquier otro servicio que reciba puntajes de H5P sobre usuarios que alcancen el escenario final por defecto"},{"label":"Identificador de contenido"}]}},{"label":"Lista del contenido escenario ramificado","entity":"contenido","field":{"fields":[{},{"label":"Mostrar título del contenido en vista","description":"Si está seleccionado, el usuario verá el título del contenido en la barra superior arriba de este contenido"},{"label":"Obligatorio que sea completado","description":"Urge a los espectadores a que completen la interacción antes de proceder con el escenario. Esta configuración solamente funcionará para interacciones que puedan ser completadas","options":[{"label":"Usar configuraciones del comportamiento"},{"label":"Habilitado"},{"label":"Deshabilitado"}]},{"label":"Siguiente identificador de contenido (las pantallas finales están definidas con números negativos)"},{"label":"Retroalimentación","fields":[{"label":"Título de la retroalimentación"},{"label":"Texto de retroalimentación"},{"label":"Imagen de retroalimentación"},{"label":"Puntaje para este escenario","description":"El puntaje será enviada a cualquier LMS, LRS o cualquier otro servicio que reciba puntajes de H5P sobre usuarios que alcancen este escenario"}]},{"label":"Configuraciones del comportamiento","description":"Esto le permitirá al usuario regresar y ver la pregunta / el contenido anterior en el escenario","options":[{"label":"Usar configuraciones del comportamiento"},{"label":"Habilitado"},{"label":"Deshabilitado"}]}]}},{"label":"Opciones de puntaje","fields":[{"label":"Opciones de puntaje","description":"Seleccionar tipo de puntaje","options":[{"label":"Establecer el puntaje estáticamente para cada escenario final"},{"label":"Calcular el puntaje dinámicamente desde las respuestas de los usuarios"},{"label":"Sin puntaje"}]},{"label":"Incluir puntajes de interacciones dentro del Escenario Ramificado","description":"Si se elige, puntajes de instancia de Videos Interactivos serán añadidos al puntaje total obtenido. Si no se elige, solamente los puntajes especificados en las secciones de retroalimentación de los elementos de contenido, preguntas ramificadas y escenarios finales contarán."}]},{"label":"Configuraciones del comportamiento","fields":[{"label":"Anular navegación en reversa","description":"Anular las configuraciones individuales para habilitar navegación en reversa"},{"label":"Anular requerir contenido terminado","description":"Anular las configuraciones individuales para requerir que el contenido esté terminado antes de activar el botón de \"Proceder\". No tendrá efecto alguno si el contenido no indica que fue \"terminado\", por ejemplo imágenes o presentaciones del curso con una sola diapositiva."},{"label":"Randomize Branching Questions","description":"Branching questions will be displayed in random order"}]},{"label":"Localización","fields":[{"label":"Texto para el botón en la pantalla inicial","default":"Comenzar el curso"},{"label":"Texto para el botón de la pantalla final","default":"Reiniciar el curso"},{"label":"Texto para el botón Retroceder en cada una de las pantallas de la biblioteca y preguntas ramificadas","default":"Regresar"},{"label":"Texto para el botón en cada pantalla de biblioteca","default":"Proceder"},{"label":"Texto para el botón usado para reproducir un video","default":"Reproducir el video"},{"label":"Etiqueta para puntaje en la pantalla final","default":"Su puntaje:"},{"label":"Etiqueta para puntaje en la pantalla final","default":"Su puntaje:"},{"label":"Etiqueta Aria para botón de pantalla completa","default":"Pantalla Completa"}]}]}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // es.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pBranchingScenarioLibId,
            'language_code' => 'es',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Editor Branching Scenario (Escenario Ramificado)","fields":[{"label":"Título"},{"label":"Pantalla inicial","fields":[{"label":"Título de la pantalla inicial","placeholder":"Título para tu curso"},{"label":"Subtítulo de la pantalla inicial","placeholder":"Detalles sobre el curso"},{"label":"Imagen de la pantalla inicial"},{"label":"Texto alterno de imagen"}]},{"label":"Lista de pantallas finales","field":{"label":"Pantalla final","fields":[{"label":"Título"},{"label":"Texto"},{"label":"Imagen"},{"label":"Puntaje","description":"El puntaje será enviada a cualquier LMS, LRS o cualquier otro servicio que reciba puntajes de H5P sobre usuarios que alcancen el escenario final por defecto"},{"label":"Identificador de contenido"}]}},{"label":"Lista del contenido escenario ramificado","entity":"contenido","field":{"fields":[{},{"label":"Mostrar título del contenido en vista","description":"Si está seleccionado, el usuario verá el título del contenido en la barra superior arriba de este contenido"},{"label":"Obligatorio que sea completado","description":"Urge a los espectadores a que completen la interacción antes de proceder con el escenario. Esta configuración solamente funcionará para interacciones que puedan ser completadas","options":[{"label":"Usar configuraciones del comportamiento"},{"label":"Habilitado"},{"label":"Deshabilitado"}]},{"label":"Siguiente identificador de contenido (las pantallas finales se definen con números negativos)"},{"label":"Retroalimentación","fields":[{"label":"Título de la retroalimentación"},{"label":"Texto de retroalimentación"},{"label":"Imagen de retroalimentación"},{"label":"Puntuación de este escenario","description":"El puntaje será enviada a cualquier LMS, LRS o cualquier otro servicio que reciba puntajes de H5P sobre usuarios que alcancen este escenario"}]},{"label":"Configuraciones del comportamiento","description":"Esto le permitirá al usuario regresar y ver la pregunta / el contenido anterior en el escenario","options":[{"label":"Usar configuraciones del comportamiento"},{"label":"Habilitado"},{"label":"Deshabilitado"}]}]}},{"label":"Opciones de puntaje","fields":[{"label":"Opciones de puntaje","description":"Seleccionar tipo de puntaje","options":[{"label":"Establecer el puntaje estáticamente para cada escenario final"},{"label":"Calcular el puntaje dinámicamente desde las respuestas de los usuarios"},{"label":"Sin puntaje"}]},{"label":"Incluir puntajes de interacciones dentro de Escenario Ramificado","description":"Si se elige, puntajes de instancia de Videos Interactivos serán añadidos al puntaje total obtenido. Si no se elige, solamente los puntajes especificados en las secciones de retroalimentación de los elementos de contenido, preguntas ramificadas y escenarios finales contarán."}]},{"label":"Configuraciones del comportamiento","fields":[{"label":"Anular navegación en reversa","description":"Anular las configuraciones individuales para habilitar navegación en reversa"},{"label":"Anular requerir contenido terminado","description":"Anular las configuraciones individuales para requerir que el contenido esté terminado antes de activar el botón para \"Proceder\". No tendrá efecto si el contenido no indica si fue \"terminada\"; por ejemplo imágenes o presentaciones d curso con una sola página."},{"label":"Randomize Branching Questions","description":"Branching questions will be displayed in random order"}]},{"label":"Localización","fields":[{"label":"Texto para el botón de la pantalla inicial","default":"Comienza el curso"},{"label":"Texto para el botón de la pantalla final","default":"Reinicia el curso"},{"label":"Texto para el botón Retroceder en cada una de las pantallas de la biblioteca y preguntas ramificadas","default":"Regresar"},{"label":"Texto para el botón en cada pantalla de biblioteca","default":"Proceder"},{"label":"Texto para el botón usado para reproducir un video","default":"Reproducir el video"},{"label":"Etiqueta para puntaje en la pantalla final","default":"Su puntaje:"},{"label":"Etiqueta para puntaje en la pantalla final","default":"Su puntaje:"},{"label":"Etiqueta Aria para botón de pantalla completa","default":"Pantalla Completa"}]}]}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // et.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pBranchingScenarioLibId,
            'language_code' => 'et',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Hargneva stsenaariumi redaktor","fields":[{"label":"Pealkiri"},{"label":"Algusvaate","fields":[{"label":"Algusvaate tiitel","placeholder":"Sinu kursuse tiitel"},{"label":"Algusvaate subtiitel","placeholder":"Kursuse detailid"},{"label":"Algusvaate pilt"},{"label":"Image alternative text"}]},{"label":"Lõppvaadete loetelu","field":{"label":"Lõppvaade","fields":[{"label":"Pealkiri"},{"label":"Tekst"},{"label":"Pilt"},{"label":"Punktisumma","description":"Punktisumma saadetakse LMS, LRS või mõnele teisele ühendatud teenusele, mis kogub H5P keskkonnas vaikelõpustsenaariumini jõudnud kasutajate tulemusi"},{"label":"Sisu ID"}]}},{"label":"Hargneva stsenaariumisisu loetelu","entity":"sisu","field":{"fields":[{},{"label":"Näita sisu pealkirja vaates","description":"Kui märgitud, siis kasutaja näeb sisu tiitlit selle sisu ülaosas"},{"label":"Required to be completed","description":"Urges viewers to complete the interaction before proceeding with the scenario. This setting will only work for interactions that can be completed","options":[{"label":"Use behavioural setting"},{"label":"Enabled"},{"label":"Disabled"}]},{"label":"Järgmine sisu ID (lõppvaadete arvud on negatiivsed)"},{"label":"Tagasiside","fields":[{"label":"Tagasiside pealkiri"},{"label":"Tagasiside tekst"},{"label":"Tagasiside pilt"},{"label":"Selle stsenaariumi punktisumma","description":"Punktisumma saadetakse LMS, LRS või mõnele teisele ühendatud teenusele, mis kogub H5P keskkonnas selle stsenaariumini jõudnud kasutajate tulemusi"}]},{"label":"Navigate back","description":"This will allow user to go back and see the previous content/question in the scenario","options":[{"label":"Use behavioural setting"},{"label":"Lubatud"},{"label":"Keelatud"}]}]}},{"label":"Punktiarvestuse valikud","fields":[{"label":"Punktiarvestuse valikud","description":"Vali punktiarvestuse tüüp","options":[{"label":"Määra iga stsenaariumi jaoks staatiline punktisumma"},{"label":"Arvuta punktisumma dünaamiliselt kasutaja vastuste alusel"},{"label":"Punktiarvestust ei ole"}]},{"label":"Include scores from interactions within the Branching Scenario","description":"If chosen scores from for instance Interactive Videos will be added to the total score obtained. If not chosen only scores specified in the feedback sections of the content items, branching questions and end scenarios will count."}]},{"label":"Käitumisseaded","fields":[{"label":"Override require content finished","description":"Override the individual settings for requiring the content to be finished before activating the \"Proceed\" button. Will not have any effect if the content doesn\'t indicate if it was \"finished\", e.g. images or course presentations with just one slide."},{"label":"Required to watch","description":"Urges viewers to complete the Video, Interactive Vdeo and Course Presentation before proceeding with the scenario. This can be overridden on a content level"},{"label":"Randomize Branching Questions","description":"Branching questions will be displayed in random order"}]},{"label":"Kohandamine","fields":[{"label":"Algusvaate nupu tekst","default":"Alusta kursust"},{"label":"Lõppvaate nupu tekst","default":"Taasalusta kursust"},{"label":"Igal kogumikkuval oleva nupu tekst","default":"Jätka"},{"label":"Text for the button used to replay a video","default":"Replay the video"},{"label":"Lõppvaatel näidatava punktisumma silt","default":"Mängi videot uuesti"},{"label":"Label for score on the end screen","default":"Your score:"},{"label":"Label for score on the end screen","default":"Your score:"},{"label":"Aria label for fullscreen button","default":"Täisekraan"}]}]}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // eu.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pBranchingScenarioLibId,
            'language_code' => 'eu',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Branchig Scenario Editorea (Egoera Adarkatuak)","fields":[{"label":"Izenburua"},{"label":"Hasierako pantaila","fields":[{"label":"Hasierako pantailaren izenburua","placeholder":"Zure ikastaroaren izenburua"},{"label":"Hasierako pantailaren azpititulua","placeholder":"Ikastaroaren xehetasunak"},{"label":"Hasierako pantailaren irudia"},{"label":"Irudiaren ordezko testua"}]},{"label":"Amaierako pantailen zerrenda","field":{"label":"Amaierako pantaila","fields":[{"label":"Izenburua"},{"label":"Testua"},{"label":"Irudia"},{"label":"Kalifikazioa","description":"Lehenetsitako eszenatokiaren amaierara heltzen diren erabiltzaileen kalifikazioa LMS, LRS edo H5Ptik kalifikazioak jasotzen dituen konektatuta dagoen beste edozein zerbitzura bidaliko da"},{"label":"Edukiaren IDa"}]}},{"label":"Egoera adarkatuen edukien zerrenda","entity":"edukia","field":{"fields":[{},{"label":"Erakutsi edukiaren izenburua ikuspegian","description":"Aukeratuz gero, erabiltzaileak edukiaren izenburua ikusiko du eduki honen gaineko barran"},{"label":"Osaketa derrigorrezkoa da","description":"Ikusleek eszenatokiarekin jarraitu aurretik interakzioa osatzera derrigortzen ditu. Ezarpen honek osatu daitezkeen interakzioekin baino ez du funtzionatzen","options":[{"label":"Erabili portaeraren ezarpena"},{"label":"Gaituta"},{"label":"Desgaituta"}]},{"label":"Hurrengo Edukiaren IDa (amaierako pantailek zenbaki negatiboen bitartez identifikatzen dira)"},{"label":"Feedbacka","fields":[{"label":"Feedbackaren izenburua"},{"label":"Feedbackaren testua"},{"label":"Feedbackaren irudia"},{"label":"Eskenatoki honen kalifikazioa","description":"Eszenatoki honen amaierara heltzen diren erabiltzaileen kalifikazioa LMS, LRS edo H5Ptik kalifikazioak jasotzen dituen konektatuta dagoen beste edozein zerbitzura bidaliko da"}]},{"label":"Portaeraren ezarpenak","description":"Honek erabiltzaileari atzera egin eta eszenatokiko aurreko eduki/galderak ikustea ahalbidetzen dio","options":[{"label":"Erabili portaeraren ezarpena"},{"label":"Gaituta"},{"label":"Desgaituta"}]}]}},{"label":"Kalifikazioaren aukerak","fields":[{"label":"Kalifikazioaren aukerak","description":"Aukeratu kalifikazio-mota","options":[{"label":"Estatikoki zehaztu eszenatoki bakoitzaren amaierako kalifikazioa"},{"label":"Dinamikoki kalkulatu kalifikazioa erabiltzaileen erantzunen arabera"},{"label":"Kalifikaziorik ez"}]},{"label":"Sartu Eszenatoki Adarkatuaren interakzioen puntuazioak","description":"Aukeratuz gero, Bideo Interaktiboetako instantzien puntuazioak lortutako guztirako puntuaziora gehituko dira. Ez bada aukeratzen, soilik eduki-elementuen feedback-ataletan, galdera adarkatuen eta eszenatoki-amaieratan zehaztutako puntuazioak hartuko dira kontuan."}]},{"label":"Portaeraren ezarpenak","fields":[{"label":"Baliogabetu atzerakako nabigazioa","description":"Baliogabetu atzerakako nabigazioa gaitzeko banakako ezarpenak"},{"label":"Baliogabetu edukia amaitzea behartzea","description":"Baliogabetu \"Jarraitu\" botoia sakatu aurretik edukia amaitzeko banakako ezarpenak. Ez du edukian efekturik izango edukiak ez badu adierazten \"amaituta\" dagoela, esaterako irudietan edo diapositiba bakarreko ikastaro-aurkezpenetan."},{"label":"Randomize Branching Questions","description":"Branching questions will be displayed in random order"}]},{"label":"Kokapena","fields":[{"label":"Hasiera-pantailako botoiaren testua","default":"Hasi ikastaroa"},{"label":"Amaiera-pantailako botoiaren testua","default":"Berriz hasi ikastaroa"},{"label":"Liburutegi-pantaila bakoitzean atzera botoiaren eta adarkatutako galderen testua","default":"Atzera"},{"label":"Liburutegi-pantaila bakoitzeko botoiaren testua","default":"Ekin"},{"label":"Bideo bat berriro erreproduzitzeko erabilitako botoiaren testua","default":"Berriz erreproduzitu bideoa"},{"label":"Puntuazioaren etiketa pantaila amaieran","default":"Zure puntuazioa:"},{"label":"Puntuazioaren etiketa pantaila amaieran","default":"Zure puntuazioa:"},{"label":"Aria etiketa pantaila osoko botoiarentzat","default":"Pantaila osoa"}]}]}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // fa.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pBranchingScenarioLibId,
            'language_code' => 'fa',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"ویرایشگر سناریو منشعب","fields":[{"label":"عنوان"},{"label":"صفحه شروع","fields":[{"label":"عنوان صفحه شروع","placeholder":"عنوان درس شما"},{"label":"زیرعنوان صفحه شروع","placeholder":"جزئیات درباره درس"},{"label":"تصویر صفحه شروع"},{"label":"Image alternative text"}]},{"label":"لیست صفحات پایان","field":{"label":"صفحه پایان","fields":[{"label":"عنوان"},{"label":"متن"},{"label":"تصویر"},{"label":"نمره","description":"نمره به هر LMS، LRS یا هر خدمات متصل دیگری که نمرات را برای کاربرانی که به سناریوی پیش‌فرض پایانی می‌رسند، از H5P دریافت می‌کند، ارسال خواهد شد"},{"label":"شناسه محتوا"}]}},{"label":"لیست محتوای سناریو منشعب","entity":"محتوا","field":{"fields":[{},{"label":"نمایش عنوان محتوا در نما","description":"در صورت انتخاب، کاربر عنوان محتوا را در نوار بالای محتوا خواهد دید"},{"label":"لازم است تکمیل شود","description":"از مشاهده‌کنندگان می‌خواهد که تعامل را پیش از ادامه سناریو تکمیل کنند. این تنظیم فقط برای تعامل‌هایی کار خواهد کرد که قابل تکمیل شدن باشند","options":[{"label":"استفاده از تنظیم عملکرد"},{"label":"فعال"},{"label":"غیرفعال"}]},{"label":"شناسه محتوای بعدی (صفحات پایان با شماره منفی تعریف می‌شوند)"},{"label":"بازخورد","fields":[{"label":"عنوان بازخورد"},{"label":"متن بازخورد"},{"label":"تصویر بازخورد"},{"label":"نمره این سناریو","description":"نمره به هر LMS، LRS یا هر خدمات متصل دیگری که نمرات کاربرانی که به این سناریو می‌رسند را از H5P دریافت می‌کند، ارسال خواهد شد"}]},{"label":"تنظیمات عملکرد","description":"این به کاربر اجازه می‌دهد که به عقب برگردد و محتوا/سؤال قبلی را در سناریو ببیند","options":[{"label":"استفاده از تنظیمات عملکرد"},{"label":"فعال"},{"label":"غیرفعال"}]}]}},{"label":"گزینه‌های نمره‌دهی","fields":[{"label":"گزینه‌های نمره‌دهی","description":"انتخاب نوع نمره‌دهی","options":[{"label":"تنظیم ثابت نمره برای هر سناریو پایانی"},{"label":"محاسبه پویای نمره برای پاسخ‌های کاربر"},{"label":"بدون نمره‌دهی"}]},{"label":"شامل نمره تعامل‌ها در سناریو منشعب","description":"در صورت انتخاب، به عنوان مثال نمرات ویدئوهای تعاملی به نمره کل افزوده خواهد شد. در صورت عدم انتخاب، فقط نمرات مشخص شده در بخش‌های بازخورد برای موارد محتوا، سؤالات منشعب و سناریو‌های پایانی حساب خواهد شد."}]},{"label":"تنظیمات عملکرد","fields":[{"label":"حذف و جایگزین کردن بازگشت به عقب","description":"حذف و جایگزین کردن تنظیمات شخصی برای فعال‌سازی بازگشت به عقب"},{"label":"حذف و جایگزین کردن لزوم به پایان رسیدن محتوا","description":"حذف و جایگزین کردن تنظیمات شخصی برای لزوم به پایان رسیدن محتوا پیش از فعال‌سازی دکمه ادامه. اگر محتوا به پایان رسیدن را نشان ندهد، اثری نخواهد داشت، برای مثال تصاویر یا ارائه‌های درسی شامل فقط یک اسلاید."},{"label":"Randomize Branching Questions","description":"Branching questions will be displayed in random order"}]},{"label":"بومی‌سازی","fields":[{"label":"متن دکمه روی صفحه شروع","default":"شروع درس"},{"label":"متن دکمه روی صفحه پایان","default":"شروع مجدد درس"},{"label":"متن دکمه قبلی روی هر یک از صفحات کتابخانه و سؤالات منشعب","default":"قبلی"},{"label":"متن دکمه روی هر یک از صفحات کتابخانه","default":"ادامه"},{"label":"متن دکمه مورد استفاده برای بازپخش یک ویدئو","default":"بازپخش ویدئو"},{"label":"برچسب نمره روی صفحه پایان","default":"نمره شما:"},{"label":"برچسب نمره روی صفحه پایان","default":"نمره شما:"},{"label":"برچسب آریا برای دکمه تمام‌صفحه","default":"تمام‌صفحه"}]}]}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // fi.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pBranchingScenarioLibId,
            'language_code' => 'fi',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Haarautuva skenaario","fields":[{"label":"Otsikko"},{"label":"Aloitusruutu","fields":[{"label":"Aloitusruudun otsikko","placeholder":"Kurssisi otsikko"},{"label":"Aloitusruudun teksti","placeholder":"Kurssin kuvaus"},{"label":"Aloitusruudun kuva"},{"label":"Image alternative text"}]},{"label":"Lista haaranpäätteistä","field":{"label":"Haaranpääte","fields":[{"label":"Otsikko"},{"label":"Teksti"},{"label":"Kuva"},{"label":"Pisteet","description":"Pisteet lähetetään LSM, LRS tai muille yhdistetyille palveluille (jotka pystyvät ottamaan arvosanatiedon vastaan H5P:n Haarautuvalta skenaariolta) Pisteet lähetetään silloin, kun käyttäjä saavuttaa haaranpäätteen."},{"label":"Aineiston ID"}]}},{"label":"Lista Haarautuvan skenaarion sisällöstä","entity":"sisältö","field":{"fields":[{},{"label":"Näytä sisältöotsikko","description":"Jos valittuna, käyttäjät näkevät tämän sisältöotsikon yläpalkissa aineiston yläpuolella"},{"label":"Suoritus vaaditaan","description":"Kehottaa katsojia suorittamaan loppuun vuorovaikutuksen ennen skenaariossa jatkamista. Asetus toimii vain niiden vuorovaikutusten kanssa, jotka voi suorittaa loppuun","options":[{"label":"Käytä käyttäytymisasetuksia"},{"label":"Käytössä"},{"label":"Pois käytöstä"}]},{"label":"Seuraava sisältö-ID (haaranpäätteet määritetään negatiivisilla numeroilla)"},{"label":"Palaute","fields":[{"label":"Palautteen otsikko"},{"label":"Palautteen teksti"},{"label":"Palautteen kuva"},{"label":"Tämän skenaarion pisteet","description":"Pisteet lähetetään LSM, LRS tai muille yhdistetyille palveluille (jotka pystyvät ottamaan arvosanatiedon vastaan H5P:n Haarautuvalta skenaariolta) käyttäjille, jotka saavuttavat tämän haarakohdan"}]},{"label":"Käyttäytymisasetukset","description":"Tämä sallii käyttäjän palaamisen taaksepäin ja skenaarion edellisen sisällön/kysymyksen näkemisen","options":[{"label":"Käytä käyttäytymisasetuksia"},{"label":"Käytössä"},{"label":"Pois käytöstä"}]}]}},{"label":"Pisteytyksen asetukset","fields":[{"label":"Pisteytyksen asetukset","description":"Valitse pisteytystyyppi","options":[{"label":"Määritän itse haaranpäätteiden pisteytyksen"},{"label":"Automaattinen pisteytys käyttäjän vastausten perusteella"},{"label":"Ei pisteytystä"}]},{"label":"Ota mukaan pisteet Haarautuvan skenaarion vuorovaikutteisista osioista","description":"Jos valittuna, niin esimerkiksi Interaktiivisten videoiden pisteet lisätään kokonaispistemäärään. Jos ei valittuna, kokonaispistemäärään lasketaan vain sisältökohteiden, haarautuvien kysymysten ja haaranpäätteiden palauteosioissa määritetyt pisteet."}]},{"label":"Käyttäytymisasetukset","fields":[{"label":"Yliaja taaksepäin navigointi","description":"Yliaja yksittäiset asetukset taaksepäin navigoinnin sallimiseksi"},{"label":"Yliaja sisällön loppuun pääsemisen vaatimus","description":"Yliaja yksittäiset asetukset sisällön loppuun pääsemisen vaatimisesta ennen \"Jatka\" -painikkeen aktivoitumista. Tällä ei ole vaikutusta, jos sisällössä ei määritellä milloin se on \"valmis\", esim. kuvat tai yhden sivun diaesitys."},{"label":"Randomize Branching Questions","description":"Branching questions will be displayed in random order"}]},{"label":"Lokalisointi","fields":[{"label":"Aloitusruudun painikkeen teksti","default":"Aloita kurssi"},{"label":"Lopetusruudun painikkeen teksti","default":"Aloita uudelleen alusta"},{"label":"Aineistotyyppien ja haarautuvien kysymysten paluupainikkeen teksti","default":"Paluu"},{"label":"Painikkeen, jolla jatketaan aineistotyypistä toiseen, teksti","default":"Jatka"},{"label":"Painikkeen, jolla toistetaan video uudelleen, teksti","default":"Toista video uudelleen"},{"label":"Loppunäkymän pisteiden teksti","default":"Pisteesi:"},{"label":"Loppunäkymän pisteiden teksti","default":"Pisteesi:"},{"label":"Koko näytön painikkeen teksti","default":"Koko näyttö"}]}]}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // fr.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pBranchingScenarioLibId,
            'language_code' => 'fr',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Éditeur de scénario","fields":[{"label":"Titre"},{"label":"Écran de départ","fields":[{"label":"Titre de l\'écran de départ","placeholder":"Titre de votre cours"},{"label":"Sous-titre de l\'écran de départ","placeholder":"Informations sur votre cours"},{"label":"Image de l\'écran de départ"},{"label":"Texte alternatif pour l\'image"}]},{"label":"Liste des écrans de fin","field":{"label":"Écran de fin","fields":[{"label":"Titre"},{"label":"Texte"},{"label":"Image"},{"label":"Score","description":"Le score sera envoyé à tout LMS, LRS ou tout autre service qui reçoit des résultats depuis H5P pour des utilisateurs qui atteignent le scénario de fin par défaut"},{"label":"Identifiant du contenu"}]}},{"label":"Liste de contenus de scénario de branchement","entity":"contenu","field":{"fields":[{},{"label":"Voir le titre du contenu dans la vue","description":"Si sélectionné, l\'utilisateur verra le titre du contenu dans la barre supérieure en haut de ce contenu"},{"label":"Requis pour terminer","description":"Requiert l\'accomplissement de l\'activité avant de poursuivre le scénario. Cette préférence ne fonctionne que pour l\'activité qui demandent une réponse de la part de l\'utilisateur.","options":[{"label":"Utiliser les paramètres de comportement"},{"label":"Activée"},{"label":"Désactivée"}]},{"label":"Identifiant de contenu suivant (les écrans de fin sont définis par des nombres négatifs)"},{"label":"Feedback","fields":[{"label":"Titre du feedback"},{"label":"Texte du feedback"},{"label":"Image du feedback"},{"label":"Score pour ce scénario","description":"Le score sera envoyé à tout LMS, LRS ou tout autre service qui reçoit des résultats depuis H5P pour des utilisateurs qui atteignent ce scénario"}]},{"label":"Paramètres de comportement","description":"Ce paramètre permettra aux utilisateurs de revenir en arrière pour parcourir le contenu ou les questions du scénario","options":[{"label":"Utiliser les paramètres de comportement"},{"label":"Activée"},{"label":"Désactivée"}]}]}},{"label":"Options de notation","fields":[{"label":"Options de notation","description":"Sélectionnez le type de notation","options":[{"label":"Définir de façon statique le score pour chaque scénario de fin"},{"label":"Calculer dynamiquement le score à partir des réponses de l\'utilisateur"},{"label":"Pas de notation"}]},{"label":"Inclure la notation des interactions à l’intérieur du scénario à embranchements","description":"La notation d’autres activités  (de vidéos interactives, par exemple) sera ajoutée à la notation finale obtenue. Si cette option n’est pas sélectionnée, les notations spécifiées dans les sections de feedbacks des éléments de contenus, des questions de branchement et des scénarios finaux seront comptabilisées."}]},{"label":"Options comportementales","fields":[{"label":"Désactiver la navigation vers l’arrière","description":"Contourner les options comportementales pour activer la navigation vers l’arrière"},{"label":"Contourner la nécessité de terminer le contenu","description":"Contourner les options personnelles exigeant la complétion du contenu avant d’activer le bouton « Continuer ». N’aura aucun effet si le contenu n’indique pas de complétion (des images ou une présentation de cours comprenant une seule diapo, par exemple)."},{"label":"Randomize Branching Questions","description":"Branching questions will be displayed in random order"}]},{"label":"Localisation","fields":[{"label":"Texte pour le bouton de l\'écran de départ","default":"Commencer le cours"},{"label":"Texte pour le bouton de l\'écran de fin","default":"Recommencer le cours"},{"label":"Texte pour le bouton de chaque écran de la bibliothèque","default":"Revenir en arrière"},{"label":"Texte du bouton pour chaque écran de la bibliothèque","default":"Continuer"},{"label":"Libellé du bouton pour jouer la vidéo de nouveau","default":"Jouer la vidéo de nouveau"},{"label":"Libellé de la notation dans l’écran de fin","default":"Votre note:"},{"label":"Étiquette de la notation de l\'écran de fin","default":"Votre note:"},{"label":"Étiquette Aria pour le bouton de plein écran","default":"Plein écran"}]}]}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // gl.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pBranchingScenarioLibId,
            'language_code' => 'gl',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Editor de escenarios ramificados","fields":[{"label":"Título"},{"label":"Pantalla de inicio","fields":[{"label":"Título da pantalla de inicio","placeholder":"Título para o teu curso"},{"label":"Subtítulo da pantalla de inicio","placeholder":"Detalles sobre o curso"},{"label":"Imaxe da pantalla de inicio"},{"label":"Texto alternativo da imaxe"}]},{"label":"Lista de pantallas finais","field":{"label":"Pantalla final","fields":[{"label":"Título"},{"label":"Texto"},{"label":"Imaxe"},{"label":"Puntuación","description":"A puntuación enviarase a calquera LMS, LRS ou a calquera outro servizo que reciba puntuación do H5P para os usuarios que cheguen á pantalla final por defecto"},{"label":"ID do contido"}]}},{"label":"Lista do contido do escenario ramificado","entity":"contido","field":{"fields":[{},{"label":"Amosar o título do contido na vista","description":"Se se selecciona, o usuario verá o título do contido na barra de contido na parte superior"},{"label":"Completado obrigatorio","description":"Pídelle ao usuario que complete a interacción antes de seguir co escenario. Esta característica só funciona en interaccións que poden completarse","options":[{"label":"Usar configuración de comportamento"},{"label":"Activado"},{"label":"Desactivado"}]},{"label":"Seguinte ID de contido (as pantallas finais defínense con números negativos)"},{"label":"Retroalimentación","fields":[{"label":"Título da retroalimentación"},{"label":"Texto da retroalimentación"},{"label":"Imaxe da retroalimentación"},{"label":"Puntación para este escenario","description":"Enviarase a puntuación a calquera LMS, LRS ou calquera dispositivo conectado que reciba puntuacións de H5P para os usuarios que cheguen a este escenario"}]},{"label":"Configuración de comportamento","description":"Permiten que o usuario volva atrás e vexa a pregunta ou contido anterior do escenario","options":[{"label":"Usar a configuración de comportamento"},{"label":"Activado"},{"label":"Desactivado"}]}]}},{"label":"Opcións de puntuación","fields":[{"label":"Opcións de puntuación","description":"Selecciona o tipo de puntuación","options":[{"label":"Aplicar puntuación estática para cada pantalla final"},{"label":"Calcular a puntuación dinamicamente a partir das respostas do usuario"},{"label":"Sen puntuación"}]},{"label":"Incluir as puntuacións das interaccións dentro do Escenario Ramificado","description":"Se se activa, engadiranse as puntuacións do Vídeo Interactivo á puntuación total obtida. En caso contrario, só contarán as puntuacións especificadas nas seccións de retroalimentación dos elementos de contido, preguntas ramificadas e escenarios finais."}]},{"label":"Configuración de comportamento","fields":[{"label":"Anular a navegación cara atrás","description":"Anular a configuración individual que permite navegar cara atrás"},{"label":"Anular requirimento de rematado de contido","description":"Anular elementos individuais da configuración que requiren que se complete o contido antes de activar o botón \"Adiante\". Non terá efecto se o contido non indica se foi completado ou non; p. ex. imaxes ou presentacións de curso cunha soa diapositiva."},{"label":"Randomize Branching Questions","description":"Branching questions will be displayed in random order"}]},{"label":"Localización","fields":[{"label":"Texto para o botón na pantalla inicial","default":"Empezar o curso"},{"label":"Texto para o botón na pantalla final","default":"Reiniciar o curso"},{"label":"Texto para o botón atrás en cada pantalla da biblioteca e pregunta ramificada","default":"Atrás"},{"label":"Texto para o botón en cada pantalla da biblioteca","default":"Adiante"},{"label":"Texto para o botón usado para volver a reproducir o vídeo","default":"Volver a reproducir o vídeo"},{"label":"Etiqueta para a puntuación na pantalla final","default":"A túa puntuación:"},{"label":"Etiqueta para a puntuación na pantalla final","default":"A túa puntuación:"},{"label":"Etiqueta Aria para botón de pantalla completa","default":"Pantalla completa"}]}]}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // it.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pBranchingScenarioLibId,
            'language_code' => 'it',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Editor di Branching Scenario","fields":[{"label":"Titolo"},{"label":"Schermata di inizio","fields":[{"label":"Titolo della schermata di inizio","placeholder":"Titolo del corso"},{"label":"Sottotitolo della schermata di inizio","placeholder":"Dettagli del corso"},{"label":"Immagine della schermata di inizio"},{"label":"Image alternative text"}]},{"label":"Lista delle schermate finali","field":{"label":"Schermata finale","fields":[{"label":"Titolo"},{"label":"Testo"},{"label":"Immagine"},{"label":"Punteggio","description":"Il punteggio sarà inviato a ogni LMS, LRS o a ogni altro servizio collegato che riceva punteggi da H5P per utenti che raggiungano lo scenario finale predefinito"},{"label":"ID del contenuto"}]}},{"label":"Lista dei contenuti del Branching Scenario","entity":"contenuto","field":{"fields":[{},{"label":"Mostra il titolo del contenuto nella visualizzazione","description":"Se selezionato, l\'utente vedrà il titolo del contenuto nella barra sopra questo contenuto"},{"label":"Required to be completed","description":"Urges viewers to complete the interaction before proceeding with the scenario. This setting will only work for interactions that can be completed","options":[{"label":"Use behavioural setting"},{"label":"Enabled"},{"label":"Disabled"}]},{"label":"ID del prossimo contenuto (le schermate finali sono definite da numeri negativi)"},{"label":"Feedback","fields":[{"label":"Titolo del feedback"},{"label":"Testo del feedback"},{"label":"Immagine del feedback"},{"label":"Punteggio per questo scenario","description":"Il punteggio sarà inviato a ogni LMS, LRS o a ogni altro servizio collegato che riceva punteggi da H5P per utenti che raggiungano lo scenario finale predefinito"}]},{"label":"Navigate back","description":"This will allow user to go back and see the previous content/question in the scenario","options":[{"label":"Use behavioural setting"},{"label":"Enabled"},{"label":"Disabled"}]}]}},{"label":"Opzioni di punteggio","fields":[{"label":"Opzioni di punteggio","description":"Scegli il tipo di punteggio","options":[{"label":"Imposta statisticamente un punteggio per ogni finale di scenario"},{"label":"Calcola dinamicamente il punteggio in base alle risposte dell\'utente"},{"label":"Nessun punteggio"}]},{"label":"Include scores from interactions within the Branching Scenario","description":"If chosen scores from for instance Interactive Videos will be added to the total score obtained. If not chosen only scores specified in the feedback sections of the content items, branching questions and end scenarios will count."}]},{"label":"Impostazioni esecuzione","fields":[{"label":"Override require content finished","description":"Override the individual settings for requiring the content to be finished before activating the \"Proceed\" button. Will not have any effect if the content doesn\'t indicate if it was \"finished\", e.g. images or course presentations with just one slide."},{"label":"Required to watch","description":"Urges viewers to complete the Video, Interactive Vdeo and Course Presentation before proceeding with the scenario. This can be overridden on a content level"},{"label":"Randomize Branching Questions","description":"Branching questions will be displayed in random order"}]},{"label":"Localizzazione","fields":[{"label":"Testo per il pulsante sulla schermata di inizio","default":"Inizia il corso"},{"label":"Testo per il pulsante sulla schermata finale","default":"Riavvia il corso"},{"label":"Testo per il pulsante su ognuna delle schermate della libreria","default":"Procedi"},{"label":"Text for the button used to replay a video","default":"Replay the video"},{"label":"Etichetta per il punteggio sulla schermata finale","default":"Il tuo punteggio:"},{"label":"Label for score on the end screen","default":"Your score:"},{"label":"Label for score on the end screen","default":"Your score:"},{"label":"Aria label for fullscreen button","default":"Fullscreen"}]}]}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // km.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pBranchingScenarioLibId,
            'language_code' => 'km',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Branching Scenario Editor","fields":[{"label":"Title"},{"label":"Start screen","fields":[{"label":"Start screen title","placeholder":"Title for your course"},{"label":"Start screen subtitle","placeholder":"Details about the course"},{"label":"Start screen image"},{"label":"Image alternative text"}]},{"label":"List of end screens","field":{"label":"End screen","fields":[{"label":"Title"},{"label":"Text"},{"label":"Image"},{"label":"Score","description":"The score will be sent to any LMS, LRS or any other connected service that receives scores from H5P for users who reach the default end scenario"},{"label":"Content ID"}]}},{"label":"List of branching scenario content","entity":"content","field":{"fields":[{},{"label":"Show content title in view","description":"If selected, the user will see the content title in the top bar above this content"},{"label":"Required to be completed","description":"Urges viewers to complete the interaction before proceeding with the scenario. This setting will only work for interactions that can be completed","options":[{"label":"Use behavioural setting"},{"label":"Enabled"},{"label":"Disabled"}]},{"label":"Next Content ID (end screens are defined by negative numbers)"},{"label":"Feedback","fields":[{"label":"Feedback title"},{"label":"Feedback text"},{"label":"Feedback image"},{"label":"Score for this scenario","description":"The score will be sent to any LMS, LRS or any other connected service that receives scores from H5P for users who reach this scenario"}]},{"label":"Navigate back","description":"This will allow user to go back and see the previous content/question in the scenario","options":[{"label":"Use behavioural setting"},{"label":"Enabled"},{"label":"Disabled"}]}]}},{"label":"Scoring options","fields":[{"label":"Scoring options","description":"Select type of scoring","options":[{"label":"Statically set score for each end scenario"},{"label":"Dynamically calculate score from user answers"},{"label":"No scoring"}]},{"label":"Include scores from interactions within the Branching Scenario","description":"If chosen scores from for instance Interactive Videos will be added to the total score obtained. If not chosen only scores specified in the feedback sections of the content items, branching questions and end scenarios will count."}]},{"label":"Behavioural settings","fields":[{"label":"Override require content finished","description":"Override the individual settings for requiring the content to be finished before activating the \"Proceed\" button. Will not have any effect if the content doesn\'t indicate if it was \"finished\", e.g. images or course presentations with just one slide."},{"label":"Required to watch","description":"Urges viewers to complete the Video, Interactive Vdeo and Course Presentation before proceeding with the scenario. This can be overridden on a content level"},{"label":"Randomize Branching Questions","description":"Branching questions will be displayed in random order"}]},{"label":"Localization","fields":[{"label":"Text for the button on the start screen","default":"ចាប់ផ្តើមវគ្គសិក្សា"},{"label":"Text for the button on the end screen","default":"ផ្តើមវគ្គសិក្សាជាថ្មី"},{"label":"Text for the button on each of the library screens","default":"ទៅ"},{"label":"Text for the button used to replay a video","default":"Replay the video"},{"label":"Label for score on the end screen","default":"ពិន្ទុរបស់អ្នក:"},{"label":"Label for score on the end screen","default":"Your score:"},{"label":"Label for score on the end screen","default":"Your score:"},{"label":"Aria label for fullscreen button","default":"Fullscreen"}]}]}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // ko.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pBranchingScenarioLibId,
            'language_code' => 'ko',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"학습 가지 시나리오 편집기","fields":[{"label":"제목"},{"label":"화면 시작","fields":[{"label":"화면 제목 시작","placeholder":"코스 제목"},{"label":"화면 부제목 시작","placeholder":"코스 세부 내용"},{"label":"스크린 이미지 시작"},{"label":"이미지 대안 텍스트"}]},{"label":"마지막 화면 리스트","field":{"label":"화면 끝내기","fields":[{"label":"제목"},{"label":"텍스트"},{"label":"이미지"},{"label":"점수","description":"기본 종료 시나리오에 도달한 사용자를 위해 H5P로부터 점수를 받는 모든 LMS, LRS 또는 기타 연결된 서비스에 점수를 전송함."},{"label":"콘텐츠 ID"}]}},{"label":"학습 가지 시나리오 콘텐츠 리스트","entity":"콘텐츠","field":{"fields":[{},{"label":"보기에 콘텐츠 제목 표시","description":"선택한 경우, 사용자는 이 내용 위의 상단 막대에 콘텐츠 제목을 볼 수 있다."},{"label":"완료 필수","description":"사용자가 시나리오를 진행하기 전에 상호 작용을 완료해야 합니다. 이 설정은 완료할 수 있는 상호 작용에만 적용됩니다.","options":[{"label":"동작 설정 사용"},{"label":"활성화"},{"label":"비활성화"}]},{"label":"다음 콘텐츠 ID (끝 화면은 음수로 정의됨)"},{"label":"피드백","fields":[{"label":"피드백 제목"},{"label":"피드백 텍스트"},{"label":"피드백 이미지"},{"label":"이 시나리오에 대한 점수","description":"이 시나리오를 완성하는 모든 사용자에 대해 H5P로부터 점수를 받는 모든 LMS, LRS 또는 기타 연결된 서비스에 점수를 전송함."}]},{"label":"뒤로 돌아가기","description":"이를 통해 사용자는 시나리오에서 이전 내용/질문을 다시 볼 수 있습니다.","options":[{"label":"행동설정 사용"},{"label":"활성화"},{"label":"비활성화"}]}]}},{"label":"채점 기준","fields":[{"label":"채점 기준","description":"채점 유형 선택","options":[{"label":"각 엔드 시나리오에 대해 정적으로 점수를 설정"},{"label":"사용자 답변에서 동적으로 점수 계산"},{"label":"점수 없음"}]},{"label":"분기 시나리오 내의 상호 작용 점수 포함","description":"선택되면 예를 들어 대화형 비디오에서 선택한 점수가 획득한 총 점수에 추가됩니다. 선택되지 않으면 컨텐츠 항목, 분기 질문 및 종료 시나리오의 피드백 섹션에 지정된 점수만 반영됩니다."}]},{"label":"행동설정","fields":[{"label":"콘텐츠 완료 필수 재정의","description":"\"Proceed\" (진행) 버튼 활성화 전에 콘텐츠 완료 필수에 대한 개별 설정을 다시 합니다. \"finished\" (완료)되었음을 표시하지 않는 콘텐츠(예&gt;이미지 혹은 한 장의 슬라이드만 있는 코스 프레젠테이션)이면 영향을 받지 않습니다."},{"label":"시청 필수","description":"시나리오를 진행하기 전에 시청자에게 비디오, 상호작용형 비디오 및 코스 프레젠테이션을 완료하도록 요청합니다. 내용 수준에서 재정의할 수 있습니다."},{"label":"Randomize Branching Questions","description":"Branching questions will be displayed in random order"}]},{"label":"맞춤 설정","fields":[{"label":"시작 화면의 버튼에 대한 텍스트","default":"코스 시작"},{"label":"끝 화면의 버튼에 대한 텍스트","default":"코스 재시작"},{"label":"각 라이브러리 화면의 버튼에 대한 텍스트","default":"진행"},{"label":"비디오 재시청 버튼에 대한 텍스트","default":"비디오 재시청"},{"label":"끝 화면에 점수 표시 라벨","default":"점수:"},{"label":"최종 스크린에서 점수에 대한 라벨","default":"점수:"},{"label":"최종 스크린에서 점수에 대한 라벨","default":"점수:"},{"label":"전체 화면 라벨","default":"전체화면"}]}]}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // lv.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pBranchingScenarioLibId,
            'language_code' => 'lv',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Zarošanās scenārija redaktors","fields":[{"label":"Nosaukums"},{"label":"Sākuma ekrāns","fields":[{"label":"Sākuma ekrāna nosaukums","placeholder":"Jūsu kursa nosaukums"},{"label":"Sākuma ekrāna apakšvirsraksts","placeholder":"Detalizēta informācija par kursu"},{"label":"Sākuma ekrāna attēls"},{"label":"Attēla alternatīvais teksts"}]},{"label":"Beigu ekrānu saraksts","field":{"label":"Beigu ekrāns","fields":[{"label":"Nosaukums"},{"label":"Teksts"},{"label":"Attēls"},{"label":"Rezultāts","description":"Rezultāts tiks nosūtīts uz jebkuru LMS, LRS vai jebkuru citu savienotu pakalpojumu, kas saņem rezultātus no H5P lietotājiem, kuri sasniedz scenārija noklusējuma beigas"},{"label":"Satura ID"}]}},{"label":"Zarošanās scenārija satura saraksts","entity":"saturs","field":{"fields":[{},{"label":"Rādīt satura nosaukumu skatā","description":"Ja atlasīts, lietotājs redzēs satura nosaukumu augšējā joslā virs šī satura"},{"label":"Obligāti jāaizpilda","description":"Mudina skatītājus pabeigt mijiedarbību, pirms turpināt scenāriju. Šis iestatījums darbosies tikai tām mijiedarbībām, kuras var pabeigt","options":[{"label":"Izmantot uzvedības iestatījumus"},{"label":"Iespējots"},{"label":"Atspējots"}]},{"label":"Nākamā Satura ID (beigu ekrānus nosaka negatīvi skaitļi)"},{"label":"Atgriezeniskā saite","fields":[{"label":"Atgriezeniskās saites nosaukums"},{"label":"Atgriezeniskās saites teksts"},{"label":"Atgriezeniskās saites attēls"},{"label":"Šī scenārija rezultāts","description":"Rezultāts tiks nosūtīts uz jebkuru LMS, LRS vai jebkuru citu savienotu pakalpojumu, kas saņem rezultātus no H5P lietotājiem, kuri sasniegs šo scenāriju"}]},{"label":"Uzvedības iestatījumi","description":"Ļaus lietotājam atgriezties un redzēt scenārija iepriekšējo saturu/jautājumu","options":[{"label":"Izmantot uzvedības iestatījumus"},{"label":"Iespējots"},{"label":"Atspējots"}]}]}},{"label":"Punktu skaitīšanas iestatījumi","fields":[{"label":"Punktu skaitīšanas iestatījumi","description":"Izvēlieties punktu skaitīšanas veidu","options":[{"label":"Statiski iestatiet punktu skaitu katram beigu scenārijam"},{"label":"Dinamiski aprēķināt punktu skaitu no lietotāja atbildēm"},{"label":"Nav punktu"}]},{"label":"Iekļaut mijiedarbību rezultātus zarošanās scenārija punktu skaitā","description":"Ja izvēlēts, punkti, piemēram, no interaktīvajiem video, tiks pievienoti iegūtajam kopvērtējumam. Ja nav izvēlēts, tiks ņemti vērā tikai zarojošo jautājumu un scenārija beigu ekrānu atgriezeniskās saites sadaļās norādītie punkti."}]},{"label":"Uzvedības iestatījumi","fields":[{"label":"Atspējot navigāciju atpakaļ","description":"Ignorēt individuālos navigācijas atpakaļ iestatījumus"},{"label":"Ignorēt pabeigšanai obligātā satura iestatījumus","description":"Ignorēt atsevišķus iestatījumus, kas nosaka obligātu satura apguvi pirms pogas \"Turpināt\" aktivizēšanas. Neietekmēs saturu, kas nenorāda, ka ir \"pabeigts\", piemēram, attēli vai kursa prezentācija ar tikai vienu slaidu."},{"label":"Randomize Branching Questions","description":"Branching questions will be displayed in random order"}]},{"label":"Lokalizācija","fields":[{"label":"Sākuma ekrāna pogas teksts","default":"Sākt kursu"},{"label":"Beigu ekrāna pogas teksts","default":"Atsākt kursu"},{"label":"Pogas \"Atpakaļ\" teksts katram bibliotēkas ekrānam un zarošanās jautājumam","default":"Atpakaļ"},{"label":"Teksts pogai katrā bibliotēkas ekrānā","default":"Turpināt"},{"label":"Teksts pogai, ko izmanto, lai atkārtotu videoklipu","default":"Atkārtot videoklipu"},{"label":"Rezultāta etiķete beigu ekrānā","default":"Tavs rezultāts:"},{"label":"Rezultāta etiķete beigu ekrānā","default":"Tavs rezultāts:"},{"label":"Lauka etiķete pilnekrāna pogai","default":"Pilnekrāna režīms"}]}]}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // nb.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pBranchingScenarioLibId,
            'language_code' => 'nb',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Branching Scenario Editor","fields":[{"label":"Title"},{"label":"Start screen","fields":[{"label":"Start screen title","placeholder":"Title for your course"},{"label":"Start screen subtitle","placeholder":"Details about the course"},{"label":"Start screen image"},{"label":"Image alternative text"}]},{"label":"List of end screens","field":{"label":"End screen","fields":[{"label":"Title"},{"label":"Text"},{"label":"Image"},{"label":"Score","description":"The score will be sent to any LMS, LRS or any other connected service that receives scores from H5P for users who reach the default end scenario"},{"label":"Content ID"}]}},{"label":"List of branching scenario content","entity":"content","field":{"fields":[{},{"label":"Show content title in view","description":"If selected, the user will see the content title in the top bar above this content"},{"label":"Required to be completed","description":"Urges viewers to complete the interaction before proceeding with the scenario. This setting will only work for interactions that can be completed","options":[{"label":"Use behavioural setting"},{"label":"Enabled"},{"label":"Disabled"}]},{"label":"Next Content ID (end screens are defined by negative numbers)"},{"label":"Feedback","fields":[{"label":"Feedback title"},{"label":"Feedback text"},{"label":"Feedback image"},{"label":"Score for this scenario","description":"The score will be sent to any LMS, LRS or any other connected service that receives scores from H5P for users who reach this scenario"}]},{"label":"Navigate back","description":"This will allow user to go back and see the previous content/question in the scenario","options":[{"label":"Use behavioural setting"},{"label":"Enabled"},{"label":"Disabled"}]}]}},{"label":"Scoring options","fields":[{"label":"Scoring options","description":"Select type of scoring","options":[{"label":"Statically set score for each end scenario"},{"label":"Dynamically calculate score from user answers"},{"label":"No scoring"}]},{"label":"Include scores from interactions within the Branching Scenario","description":"If chosen scores from for instance Interactive Videos will be added to the total score obtained. If not chosen only scores specified in the feedback sections of the content items, branching questions and end scenarios will count."}]},{"label":"Behavioural settings","fields":[{"label":"Override require content finished","description":"Override the individual settings for requiring the content to be finished before activating the \"Proceed\" button. Will not have any effect if the content doesn\'t indicate if it was \"finished\", e.g. images or course presentations with just one slide."},{"label":"Required to watch","description":"Urges viewers to complete the Video, Interactive Vdeo and Course Presentation before proceeding with the scenario. This can be overridden on a content level"},{"label":"Randomize Branching Questions","description":"Branching questions will be displayed in random order"}]},{"label":"Localization","fields":[{"label":"Text for the button on the start screen","default":"Start kurset"},{"label":"Text for the button on the end screen","default":"Restart kurset"},{"label":"Text for the button on each of the library screens","default":"Gå videre"},{"label":"Text for the button used to replay a video","default":"Replay the video"},{"label":"Label for score on the end screen","default":"Ditt resultat:"},{"label":"Label for score on the end screen","default":"Your score:"},{"label":"Label for score on the end screen","default":"Your score:"},{"label":"Aria label for fullscreen button","default":"Fullscreen"}]}]}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // nl.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pBranchingScenarioLibId,
            'language_code' => 'nl',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Vertakkkingsscenario Editor","fields":[{"label":"Titel"},{"label":"Startscherm","fields":[{"label":"Titel van startscherm","placeholder":"Titel van je cursus"},{"label":"Ondertitel van startscherm","placeholder":"Details over de cursus"},{"label":"Afbeelding startscherm"},{"label":"Alternatieve tekst voor afbeelding"}]},{"label":"Lijst van eindschermen","field":{"label":"Eindscherm","fields":[{"label":"Titel"},{"label":"Tekst"},{"label":"Afbeelding"},{"label":"Score","description":"De score wordt verzonden naar een LMS, LRS of een andere aangesloten dienst die scores van H5P ontvangt voor gebruikers die het standaard eindscenario bereiken"},{"label":"ID van de inhoud"}]}},{"label":"Lijst van de inhoud van de vertakkingsscenario\'s","entity":"inhoud","field":{"fields":[{},{"label":"Toon titel van inhoud in weergave","description":"Indien geselecteerd, ziet de gebruiker de titel van de inhoud in de balk boven deze inhoud"},{"label":"Vereist voor voltooiing","description":"Dwingt kijkers de interactie te voltooien alvorens door te gaan met het scenario. Deze instelling werkt alleen bij interacties die kunnen worden voltooid","options":[{"label":"Gebruik gedragsinstelling"},{"label":"Ingeschakeld"},{"label":"Uitgeschakeld"}]},{"label":"Volgende ID van de inhoud (eindschermen worden gedefinieerd door negatieve getallen)"},{"label":"Feedback","fields":[{"label":"Titel van feedbacktekst"},{"label":"Feedbacktekst"},{"label":"Feedback afbeelding"},{"label":"Score voor dit scenario","description":"De score wordt verzonden naar een LMS, LRS of een andere aangesloten dienst die scores van H5P ontvangt voor gebruikers die dit scenario bereiken"}]},{"label":"Gedragsinstellingen","description":"Dit staat de gebruiker toe terug te keren en de vorige inhoud/vraag in het scenario te bekijken","options":[{"label":"Gebruik gedragsinstelling"},{"label":"Ingeschakeld"},{"label":"Uitgeschakeld"}]}]}},{"label":"Score-opties","fields":[{"label":"Score-opties","description":"Selecteer het scoringstype","options":[{"label":"Statistisch vastgestelde score voor elk eindscenario"},{"label":"Dynamisch berekende score op basis van de antwoorden van de gebruikers"},{"label":"Geen score"}]},{"label":"Neem de scores op van interacties binnen het Vertakkingsscenario","description":"Indien gekozen worden scores van bijvoorbeeld interactieve video\'s opgeteld bij de totaal behaalde score. Indien niet gekozen, tellen alleen scores mee die vermeld staan in de feedbacksecties van de inhoudelijke items, vertakkingsvragen en eindscenario\'s."}]},{"label":"Gedragsinstellingen","fields":[{"label":"Overschrijf achterwaartse navigatie","description":"Overschrijf de individuele instellingen om achterwaarts navigeren in te schakelen"},{"label":"Overschrijf vereiste dat inhoud voltooid moet zijn","description":"Overschrijft de individuele instelling die vereist dat inhoud voltooid moet zijn voordat de \"Verder gaan\"-knop geactiveerd wordt. Heeft geen effect als de inhoud niet \"voltooid\" kan worden, bijv. afbeeldingen of cursuspresentaties met slechts één dia."},{"label":"Randomize Branching Questions","description":"Branching questions will be displayed in random order"}]},{"label":"Lokalisatie","fields":[{"label":"Tekst voor de knop op het startscherm","default":"Start de cursus"},{"label":"Tekst voor de knop op het eindscherm","default":"Herstart de cursus"},{"label":"Tekst voor de \"Terug\"-knop op elk bibliotheekscherm en bij vertakkingsvragen","default":"Terug"},{"label":"Tekst voor de knop op elk bibliotheekscherm","default":"Doorgaan"},{"label":"Tekst van de knop voor opnieuw afspelen video","default":"Speel de video opnieuw af"},{"label":"Label voor score op het eindscherm","default":"Jouw score:"},{"label":"Label voor score op het eindscherm","default":"Jouw score:"},{"label":"Aria label voor volledig scherm knop","default":"Volledig scherm"}]}]}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // nn.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pBranchingScenarioLibId,
            'language_code' => 'nn',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Branching Scenario Editor","fields":[{"label":"Title"},{"label":"Start screen","fields":[{"label":"Start screen title","placeholder":"Title for your course"},{"label":"Start screen subtitle","placeholder":"Details about the course"},{"label":"Start screen image"},{"label":"Image alternative text"}]},{"label":"List of end screens","field":{"label":"End screen","fields":[{"label":"Title"},{"label":"Text"},{"label":"Image"},{"label":"Score","description":"The score will be sent to any LMS, LRS or any other connected service that receives scores from H5P for users who reach the default end scenario"},{"label":"Content ID"}]}},{"label":"List of branching scenario content","entity":"content","field":{"fields":[{},{"label":"Show content title in view","description":"If selected, the user will see the content title in the top bar above this content"},{"label":"Required to be completed","description":"Urges viewers to complete the interaction before proceeding with the scenario. This setting will only work for interactions that can be completed","options":[{"label":"Use behavioural setting"},{"label":"Enabled"},{"label":"Disabled"}]},{"label":"Next Content ID (end screens are defined by negative numbers)"},{"label":"Feedback","fields":[{"label":"Feedback title"},{"label":"Feedback text"},{"label":"Feedback image"},{"label":"Score for this scenario","description":"The score will be sent to any LMS, LRS or any other connected service that receives scores from H5P for users who reach this scenario"}]},{"label":"Navigate back","description":"This will allow user to go back and see the previous content/question in the scenario","options":[{"label":"Use behavioural setting"},{"label":"Enabled"},{"label":"Disabled"}]}]}},{"label":"Scoring options","fields":[{"label":"Scoring options","description":"Select type of scoring","options":[{"label":"Statically set score for each end scenario"},{"label":"Dynamically calculate score from user answers"},{"label":"No scoring"}]},{"label":"Include scores from interactions within the Branching Scenario","description":"If chosen scores from for instance Interactive Videos will be added to the total score obtained. If not chosen only scores specified in the feedback sections of the content items, branching questions and end scenarios will count."}]},{"label":"Behavioural settings","fields":[{"label":"Override require content finished","description":"Override the individual settings for requiring the content to be finished before activating the \"Proceed\" button. Will not have any effect if the content doesn\'t indicate if it was \"finished\", e.g. images or course presentations with just one slide."},{"label":"Required to watch","description":"Urges viewers to complete the Video, Interactive Vdeo and Course Presentation before proceeding with the scenario. This can be overridden on a content level"},{"label":"Randomize Branching Questions","description":"Branching questions will be displayed in random order"}]},{"label":"Localization","fields":[{"label":"Text for the button on the start screen","default":"Start kurset"},{"label":"Text for the button on the end screen","default":"Start på nytt"},{"label":"Text for the button on each of the library screens","default":"Gå vidare"},{"label":"Text for the button used to replay a video","default":"Replay the video"},{"label":"Label for score on the end screen","default":"Resultatet ditt:"},{"label":"Label for score on the end screen","default":"Your score:"},{"label":"Label for score on the end screen","default":"Your score:"},{"label":"Aria label for fullscreen button","default":"Fullscreen"}]}]}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // pt-br.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pBranchingScenarioLibId,
            'language_code' => 'pt-br',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Editor do Cenário de Ramificação","fields":[{"label":"Título"},{"label":"Tela inicial","fields":[{"label":"Título da tela inicial","placeholder":"Título para o seu curso"},{"label":"Subtítulo da tela inicial","placeholder":"Detalhes sobre o curso"},{"label":"Imagem da tela inicial"},{"label":"Texto alternativo da imagem"}]},{"label":"Lista das telas finais","field":{"label":"Tela Final","fields":[{"label":"Título"},{"label":"Texto"},{"label":"Imagem"},{"label":"Pontuação","description":"A pontuação será enviada para qualquer LMS, LRS ou qualquer outro serviço conectado que receba pontuação do H5P para usuários que atinjam o cenário final padrão"},{"label":"ID do Conteúdo"}]}},{"label":"Lista dos conteúdos dos cenários de ramificação","entity":"conteúdo","field":{"fields":[{},{"label":"Mostrar o título do conteúdo visualizado","description":"Se selecionado, o usuário verá o título do conteúdo na barra superior acima deste conteúdo"},{"label":"Necessário para ser completado","description":"Incentiva a completar a interação antes de prosseguir com o cenário. Este cenário só funcionará para interações que possam ser completadas","options":[{"label":"Usar as Configurações Comportamentais"},{"label":"Ativo"},{"label":"Inativo"}]},{"label":"Próximo ID de conteúdo (as telas finais são definidas por números negativos)"},{"label":"Feedback","fields":[{"label":"Título do feedback"},{"label":"Texto do feedback"},{"label":"Imagem do feedback"},{"label":"Pontuação para este cenário","description":"A pontuação será enviada para qualquer LMS, LRS ou qualquer outro serviço conectado que receba pontuação do H5P para usuários que atinjam este cenário"}]},{"label":"Configurações comportamentais","description":"Isto permitirá que o usuário volte atrás e veja o conteúdo/questão anterior no cenário","options":[{"label":"Usar as Configurações Comportamentais"},{"label":"Ativo"},{"label":"Inativo"}]}]}},{"label":"Opções de pontuação","fields":[{"label":"Opções de pontuação","description":"Selecione o tipo de pontuação","options":[{"label":"Pontuação definida estaticamente para cada cenário final"},{"label":"Calcular dinamicamente a pontuação das respostas do usuário"},{"label":"Sem pontuação"}]},{"label":"Incluir pontuações de interações dentro do Cenário de Ramificação","description":"Se forem escolhidas pontuações de, por exemplo, Vídeos Interativos, serão adicionadas à pontuação total obtida. Se não forem escolhidas, apenas pontuações especificadas nas seções de feedback dos itens de conteúdo, perguntas de ramificação e cenários finais contarão."}]},{"label":"Configurações comportamentais","fields":[{"label":"Anular a navegação para trás","description":"Substituir as configurações individuais para permitir a navegação para trás"},{"label":"Substituir requer conteúdo concluído","description":"Substitua as configurações individuais para exigir que o conteúdo seja terminado antes de ativar o botão \"Prosseguir\". Não terá qualquer efeito se o conteúdo não indicar se foi \"terminado\", por exemplo, imagens ou apresentações do curso com apenas um slide."},{"label":"Randomize Branching Questions","description":"Branching questions will be displayed in random order"}]},{"label":"Localização","fields":[{"label":"Texto para o botão na Tela Inicial","default":"Comece o curso"},{"label":"Texto para o botão na Tela Final","default":"Reiniciar o curso"},{"label":"Texto para o botão de retorno em cada uma das telas da biblioteca e perguntas de ramificação","default":"Voltar"},{"label":"Texto para o botão em cada uma das bibliotecas de tela","default":"Continuar"},{"label":"Texto para o botão usado para reproduzir um vídeo","default":"Reproduzir o vídeo"},{"label":"Rótulo para pontuação na tela final","default":"Sua pontuação:"},{"label":"Rótulo para pontuação na tela final","default":"Sua pontuação:"},{"label":"Rótulo Aria para botão de tela cheia","default":"Tela Cheia"}]}]}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // ru.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pBranchingScenarioLibId,
            'language_code' => 'ru',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Редактор Сценария Ветвления","fields":[{"label":"Название"},{"label":"Начальный экран","fields":[{"label":"Название стартовом экрана","placeholder":"Название для Вашего курса"},{"label":"Подзаголовок начального экрана","placeholder":"Подробности о курсе"},{"label":"Изображение на стартовом экране"},{"label":"Image alternative text"}]},{"label":"Список заключительных экранов","field":{"label":"Заключительный экран","fields":[{"label":"Заголовок"},{"label":"Текст"},{"label":"Изображение"},{"label":"Оценка","description":"Оценка будет отправлена в любую LMS, LRS или любую другую подключенную службу, которая получает оценки от H5P для пользователей, достигших конечного сценария по умолчанию"},{"label":"Идентификатор контента"}]}},{"label":"Список содержимого сценария ветвления","entity":"контент","field":{"fields":[{},{"label":"Показать заголовок контента в просмотре","description":"Если выбрано, пользователь увидит заголовок контента в верхней панели над этим контентом."},{"label":"Required to be completed","description":"Urges viewers to complete the interaction before proceeding with the scenario. This setting will only work for interactions that can be completed","options":[{"label":"Use behavioural setting"},{"label":"Enabled"},{"label":"Disabled"}]},{"label":"ID следующего контента (конечные экраны определяются отрицательными числами)"},{"label":"Обратная связь","fields":[{"label":"Заголовок обратной связи"},{"label":"Текст обратной связи"},{"label":"Изображение обратной связи"},{"label":"Оценка по этому сценарию","description":"Оценка будет отправлена в любую LMS, LRS или любую другую подключенную службу, которая получает оценки от H5P для пользователей, которые достигли этого сценария."}]},{"label":"Navigate back","description":"This will allow user to go back and see the previous content/question in the scenario","options":[{"label":"Use behavioural setting"},{"label":"Enabled"},{"label":"Disabled"}]}]}},{"label":"Варианты оценки","fields":[{"label":"Варианты оценки","description":"Выберите тип оценки","options":[{"label":"Статически установленная оценка для каждого конечного сценария"},{"label":"Динамически рассчитывать оценку по ответам пользователя"},{"label":"Без оценки"}]},{"label":"Include scores from interactions within the Branching Scenario","description":"If chosen scores from for instance Interactive Videos will be added to the total score obtained. If not chosen only scores specified in the feedback sections of the content items, branching questions and end scenarios will count."}]},{"label":"Настройки поведения","fields":[{"label":"Override require content finished","description":"Override the individual settings for requiring the content to be finished before activating the \"Proceed\" button. Will not have any effect if the content doesn\'t indicate if it was \"finished\", e.g. images or course presentations with just one slide."},{"label":"Required to watch","description":"Urges viewers to complete the Video, Interactive Vdeo and Course Presentation before proceeding with the scenario. This can be overridden on a content level"},{"label":"Randomize Branching Questions","description":"Branching questions will be displayed in random order"}]},{"label":"Локализация","fields":[{"label":"Текст для кнопки на стартовом экране","default":"Начать курс"},{"label":"Текст для кнопки на конечном экране","default":"Перезапустить курс"},{"label":"Текст для кнопки на каждом из экранов библиотеки","default":"Продолжить"},{"label":"Text for the button used to replay a video","default":"Replay the video"},{"label":"Надпись для оценки на конечном экране","default":"Повторить видео"},{"label":"Label for score on the end screen","default":"Your score:"},{"label":"Label for score on the end screen","default":"Your score:"},{"label":"Aria label for fullscreen button","default":"Fullscreen"}]}]}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // sk.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pBranchingScenarioLibId,
            'language_code' => 'sk',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Editor vetveného scenára","fields":[{"label":"Názov"},{"label":"Úvodná obrazovka","fields":[{"label":"Nadpis na úvodnej obrazovke","placeholder":"Nadpis scenára"},{"label":"Podnadpis scenára","placeholder":"Detaily o scenári"},{"label":"Obrázok úvodnej obrazovky"},{"label":"Image alternative text"}]},{"label":"Zoznam záverečných obrazoviek","field":{"label":"Záverečná obrazovka","fields":[{"label":"Názov"},{"label":"Text"},{"label":"Obrázok"},{"label":"Hodnotenie","description":"Hodnotenie sa odošle každému LMS, LRS alebo akejkoľvek inej pripojenej službe, ktorá prijíma hodnotenie od H5P pre používateľov, ktorí ukončia scenár."},{"label":"Obsahové ID"}]}},{"label":"Zoznam obsahov vetveného scenára","entity":"obsah","field":{"fields":[{},{"label":"Zobraziť nadpis obsahu v scenári","description":"Ak je vybrané, používateľ uvidí názov obsahu v hornom paneli nad obsahom scenára"},{"label":"Required to be completed","description":"Urges viewers to complete the interaction before proceeding with the scenario. This setting will only work for interactions that can be completed","options":[{"label":"Use behavioural setting"},{"label":"Enabled"},{"label":"Disabled"}]},{"label":"Nasledujúce ID obsahu (koncové obrazovky sú definované zápornými číslami)"},{"label":"Spätná väzba","fields":[{"label":"Nadpis spätnej väzby"},{"label":"Text spätnej väzby"},{"label":"Obrázok spätnej väzby"},{"label":"Hodnotenie vetveného scenára","description":"Hodnotenie sa odošle každému LMS, LRS alebo akejkoľvek inej pripojenej službe, ktorá prijíma hodnotenie od H5P pre používateľov, ktorí ukončia scenár."}]},{"label":"Navigate back","description":"This will allow user to go back and see the previous content/question in the scenario","options":[{"label":"Use behavioural setting"},{"label":"Enabled"},{"label":"Disabled"}]}]}},{"label":"Možnosti hodnotenia","fields":[{"label":"Možnosti hodnotenia","description":"Vyber typ hodnotenia","options":[{"label":"Staticky stanovené skóre pre každý konečný scenár"},{"label":"Dynamicky vypočítané skóre z odpovedí používateľov"},{"label":"Bez hodnotenia"}]},{"label":"Include scores from interactions within the Branching Scenario","description":"If chosen scores from for instance Interactive Videos will be added to the total score obtained. If not chosen only scores specified in the feedback sections of the content items, branching questions and end scenarios will count."}]},{"label":"Behavioural settings","fields":[{"label":"Override require content finished","description":"Override the individual settings for requiring the content to be finished before activating the \"Proceed\" button. Will not have any effect if the content doesn\'t indicate if it was \"finished\", e.g. images or course presentations with just one slide."},{"label":"Required to watch","description":"Urges viewers to complete the Video, Interactive Vdeo and Course Presentation before proceeding with the scenario. This can be overridden on a content level"},{"label":"Randomize Branching Questions","description":"Branching questions will be displayed in random order"}]},{"label":"Lokalizácia","fields":[{"label":"Text pre tlačidlo na úvodnej obrazovke","default":"Začať kurz"},{"label":"Text pre tlačidlo na záverečnej obrazovke","default":"Reštartovať kurz"},{"label":"Text pre tlačidlo na každej obrazovke scenára","default":"Pokračovať"},{"label":"Text for the button used to replay a video","default":"Replay the video"},{"label":"Nadpis pre hodnotenie na záverečnej obrazovke","default":"Vaše hodnotenie:"},{"label":"Label for score on the end screen","default":"Your score:"},{"label":"Label for score on the end screen","default":"Your score:"},{"label":"Aria label for fullscreen button","default":"Fullscreen"}]}]}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // sl.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pBranchingScenarioLibId,
            'language_code' => 'sl',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Urejevalnik scenarijev","fields":[{"label":"Naslov"},{"label":"Začetni zaslon","fields":[{"label":"Naslov začetnega zaslona","placeholder":"Naslov učne enote"},{"label":"Podnaslov začetnega zaslona","placeholder":"Podrobnosti o učni enoti"},{"label":"Slika začetnega zaslona"},{"label":"Image alternative text"}]},{"label":"Seznam končnih zaslonov","field":{"label":"Končni zaslon","fields":[{"label":"Naslov"},{"label":"Besedilo"},{"label":"Slika"},{"label":"Točke","description":"Rezultat udeležencev je lahko poslan drugi povezani storitvi (npr. LMS, LRS)."},{"label":"ID vsebine"}]}},{"label":"Seznam vsebine","entity":"vsebina","field":{"fields":[{},{"label":"Prikaži naslov vsebine ob ogledu","description":"Udeleženci vidijo naslov v zgornjem delu okna nad vsebino."},{"label":"Required to be completed","description":"Urges viewers to complete the interaction before proceeding with the scenario. This setting will only work for interactions that can be completed","options":[{"label":"Use behavioural setting"},{"label":"Enabled"},{"label":"Disabled"}]},{"label":"Vsebina z naslednjo ID (končni zasloni so definirani z negativnimi vrednostmi)"},{"label":"Povratna informacija","fields":[{"label":"Naslov povratne informacije"},{"label":"Vsebina povratne informacije"},{"label":"Slika"},{"label":"Rezultat izbranega scenarija","description":"Rezultat udeležencev je lahko poslan drugi povezani storitvi (npr. LMS, LRS)."}]},{"label":"Navigate back","description":"This will allow user to go back and see the previous content/question in the scenario","options":[{"label":"Use behavioural setting"},{"label":"Enabled"},{"label":"Disabled"}]}]}},{"label":"Nastavitve točkovanja","fields":[{"label":"Nastavitve točkovanja","description":"Izbira načina točkovanja.","options":[{"label":"Statična nastavitev točk za vsak zaključek scenarija"},{"label":"Dinamična določitev točk na podlagi odgovorov udeležencev"},{"label":"Brez točkovanja"}]},{"label":"Include scores from interactions within the Branching Scenario","description":"If chosen scores from for instance Interactive Videos will be added to the total score obtained. If not chosen only scores specified in the feedback sections of the content items, branching questions and end scenarios will count."}]},{"label":"Behavioural settings","fields":[{"label":"Override require content finished","description":"Override the individual settings for requiring the content to be finished before activating the \"Proceed\" button. Will not have any effect if the content doesn\'t indicate if it was \"finished\", e.g. images or course presentations with just one slide."},{"label":"Required to watch","description":"Urges viewers to complete the Video, Interactive Vdeo and Course Presentation before proceeding with the scenario. This can be overridden on a content level"},{"label":"Randomize Branching Questions","description":"Branching questions will be displayed in random order"}]},{"label":"Določitev","fields":[{"label":"Besedilo gumba za začetek aktivnosti na začetnem zaslonu","default":"Začetek"},{"label":"Besedilo gumba za ponoven začetek aktivnosti na končnem zaslonu","default":"Začni znova"},{"label":"Besedilo gumba za nadaljevanje na vmesnih zaslonih","default":"Nadaljuj"},{"label":"Text for the button used to replay a video","default":"Replay the video"},{"label":"Besedilo za prikaz rezultatov na končnem zaslonu","default":"Rezultat:"},{"label":"Label for score on the end screen","default":"Your score:"},{"label":"Label for score on the end screen","default":"Your score:"},{"label":"Aria label for fullscreen button","default":"Fullscreen"}]}]}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // sma.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pBranchingScenarioLibId,
            'language_code' => 'sma',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Branching Scenario Editor","fields":[{"label":"Title"},{"label":"Start screen","fields":[{"label":"Start screen title","placeholder":"Title for your course"},{"label":"Start screen subtitle","placeholder":"Details about the course"},{"label":"Start screen image"},{"label":"Image alternative text"}]},{"label":"List of end screens","field":{"label":"End screen","fields":[{"label":"Title"},{"label":"Text"},{"label":"Image"},{"label":"Score","description":"The score will be sent to any LMS, LRS or any other connected service that receives scores from H5P for users who reach the default end scenario"},{"label":"Content ID"}]}},{"label":"List of branching scenario content","entity":"content","field":{"fields":[{},{"label":"Show content title in view","description":"If selected, the user will see the content title in the top bar above this content"},{"label":"Required to be completed","description":"Urges viewers to complete the interaction before proceeding with the scenario. This setting will only work for interactions that can be completed","options":[{"label":"Use behavioural setting"},{"label":"Enabled"},{"label":"Disabled"}]},{"label":"Next Content ID (end screens are defined by negative numbers)"},{"label":"Feedback","fields":[{"label":"Feedback title"},{"label":"Feedback text"},{"label":"Feedback image"},{"label":"Score for this scenario","description":"The score will be sent to any LMS, LRS or any other connected service that receives scores from H5P for users who reach this scenario"}]},{"label":"Navigate back","description":"This will allow user to go back and see the previous content/question in the scenario","options":[{"label":"Use behavioural setting"},{"label":"Enabled"},{"label":"Disabled"}]}]}},{"label":"Scoring options","fields":[{"label":"Scoring options","description":"Select type of scoring","options":[{"label":"Statically set score for each end scenario"},{"label":"Dynamically calculate score from user answers"},{"label":"No scoring"}]},{"label":"Include scores from interactions within the Branching Scenario","description":"If chosen scores from for instance Interactive Videos will be added to the total score obtained. If not chosen only scores specified in the feedback sections of the content items, branching questions and end scenarios will count."}]},{"label":"Behavioural settings","fields":[{"label":"Override require content finished","description":"Override the individual settings for requiring the content to be finished before activating the \"Proceed\" button. Will not have any effect if the content doesn\'t indicate if it was \"finished\", e.g. images or course presentations with just one slide."},{"label":"Required to watch","description":"Urges viewers to complete the Video, Interactive Vdeo and Course Presentation before proceeding with the scenario. This can be overridden on a content level"},{"label":"Randomize Branching Questions","description":"Branching questions will be displayed in random order"}]},{"label":"Localization","fields":[{"label":"Text for the button on the start screen","default":"Start the course"},{"label":"Text for the button on the end screen","default":"Restart the course"},{"label":"Text for the button on each of the library screens","default":"Proceed"},{"label":"Text for the button used to replay a video","default":"Replay the video"},{"label":"Label for score on the end screen","default":"Your score:"},{"label":"Label for score on the end screen","default":"Your score:"},{"label":"Label for score on the end screen","default":"Your score:"},{"label":"Aria label for fullscreen button","default":"Fullscreen"}]}]}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // sme.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pBranchingScenarioLibId,
            'language_code' => 'sme',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Branching Scenario Editor","fields":[{"label":"Title"},{"label":"Start screen","fields":[{"label":"Start screen title","placeholder":"Title for your course"},{"label":"Start screen subtitle","placeholder":"Details about the course"},{"label":"Start screen image"},{"label":"Image alternative text"}]},{"label":"List of end screens","field":{"label":"End screen","fields":[{"label":"Title"},{"label":"Text"},{"label":"Image"},{"label":"Score","description":"The score will be sent to any LMS, LRS or any other connected service that receives scores from H5P for users who reach the default end scenario"},{"label":"Content ID"}]}},{"label":"List of branching scenario content","entity":"content","field":{"fields":[{},{"label":"Show content title in view","description":"If selected, the user will see the content title in the top bar above this content"},{"label":"Required to be completed","description":"Urges viewers to complete the interaction before proceeding with the scenario. This setting will only work for interactions that can be completed","options":[{"label":"Use behavioural setting"},{"label":"Enabled"},{"label":"Disabled"}]},{"label":"Next Content ID (end screens are defined by negative numbers)"},{"label":"Feedback","fields":[{"label":"Feedback title"},{"label":"Feedback text"},{"label":"Feedback image"},{"label":"Score for this scenario","description":"The score will be sent to any LMS, LRS or any other connected service that receives scores from H5P for users who reach this scenario"}]},{"label":"Navigate back","description":"This will allow user to go back and see the previous content/question in the scenario","options":[{"label":"Use behavioural setting"},{"label":"Enabled"},{"label":"Disabled"}]}]}},{"label":"Scoring options","fields":[{"label":"Scoring options","description":"Select type of scoring","options":[{"label":"Statically set score for each end scenario"},{"label":"Dynamically calculate score from user answers"},{"label":"No scoring"}]},{"label":"Include scores from interactions within the Branching Scenario","description":"If chosen scores from for instance Interactive Videos will be added to the total score obtained. If not chosen only scores specified in the feedback sections of the content items, branching questions and end scenarios will count."}]},{"label":"Behavioural settings","fields":[{"label":"Override require content finished","description":"Override the individual settings for requiring the content to be finished before activating the \"Proceed\" button. Will not have any effect if the content doesn\'t indicate if it was \"finished\", e.g. images or course presentations with just one slide."},{"label":"Required to watch","description":"Urges viewers to complete the Video, Interactive Vdeo and Course Presentation before proceeding with the scenario. This can be overridden on a content level"},{"label":"Randomize Branching Questions","description":"Branching questions will be displayed in random order"}]},{"label":"Localization","fields":[{"label":"Text for the button on the start screen","default":"Start the course"},{"label":"Text for the button on the end screen","default":"Restart the course"},{"label":"Text for the button on each of the library screens","default":"Proceed"},{"label":"Text for the button used to replay a video","default":"Replay the video"},{"label":"Label for score on the end screen","default":"Your score:"},{"label":"Label for score on the end screen","default":"Your score:"},{"label":"Label for score on the end screen","default":"Your score:"},{"label":"Aria label for fullscreen button","default":"Fullscreen"}]}]}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // smj.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pBranchingScenarioLibId,
            'language_code' => 'smj',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Branching Scenario Editor","fields":[{"label":"Title"},{"label":"Start screen","fields":[{"label":"Start screen title","placeholder":"Title for your course"},{"label":"Start screen subtitle","placeholder":"Details about the course"},{"label":"Start screen image"},{"label":"Image alternative text"}]},{"label":"List of end screens","field":{"label":"End screen","fields":[{"label":"Title"},{"label":"Text"},{"label":"Image"},{"label":"Score","description":"The score will be sent to any LMS, LRS or any other connected service that receives scores from H5P for users who reach the default end scenario"},{"label":"Content ID"}]}},{"label":"List of branching scenario content","entity":"content","field":{"fields":[{},{"label":"Show content title in view","description":"If selected, the user will see the content title in the top bar above this content"},{"label":"Required to be completed","description":"Urges viewers to complete the interaction before proceeding with the scenario. This setting will only work for interactions that can be completed","options":[{"label":"Use behavioural setting"},{"label":"Enabled"},{"label":"Disabled"}]},{"label":"Next Content ID (end screens are defined by negative numbers)"},{"label":"Feedback","fields":[{"label":"Feedback title"},{"label":"Feedback text"},{"label":"Feedback image"},{"label":"Score for this scenario","description":"The score will be sent to any LMS, LRS or any other connected service that receives scores from H5P for users who reach this scenario"}]},{"label":"Navigate back","description":"This will allow user to go back and see the previous content/question in the scenario","options":[{"label":"Use behavioural setting"},{"label":"Enabled"},{"label":"Disabled"}]}]}},{"label":"Scoring options","fields":[{"label":"Scoring options","description":"Select type of scoring","options":[{"label":"Statically set score for each end scenario"},{"label":"Dynamically calculate score from user answers"},{"label":"No scoring"}]},{"label":"Include scores from interactions within the Branching Scenario","description":"If chosen scores from for instance Interactive Videos will be added to the total score obtained. If not chosen only scores specified in the feedback sections of the content items, branching questions and end scenarios will count."}]},{"label":"Behavioural settings","fields":[{"label":"Override require content finished","description":"Override the individual settings for requiring the content to be finished before activating the \"Proceed\" button. Will not have any effect if the content doesn\'t indicate if it was \"finished\", e.g. images or course presentations with just one slide."},{"label":"Required to watch","description":"Urges viewers to complete the Video, Interactive Vdeo and Course Presentation before proceeding with the scenario. This can be overridden on a content level"},{"label":"Randomize Branching Questions","description":"Branching questions will be displayed in random order"}]},{"label":"Localization","fields":[{"label":"Text for the button on the start screen","default":"Start the course"},{"label":"Text for the button on the end screen","default":"Restart the course"},{"label":"Text for the button on each of the library screens","default":"Proceed"},{"label":"Text for the button used to replay a video","default":"Replay the video"},{"label":"Label for score on the end screen","default":"Your score:"},{"label":"Label for score on the end screen","default":"Your score:"},{"label":"Label for score on the end screen","default":"Your score:"},{"label":"Aria label for fullscreen button","default":"Fullscreen"}]}]}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // sr.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pBranchingScenarioLibId,
            'language_code' => 'sr',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Уређивач сценариа за гранање","fields":[{"label":"Наслов"},{"label":"Почетни екран","fields":[{"label":"Наслов почетног екрана","placeholder":"Наслов вашег курса"},{"label":"Поднаслови почетног екрана","placeholder":"Детаљи о курсу"},{"label":"Start screen image"},{"label":"Image alternative text"}]},{"label":"Листа завршних екрана","field":{"label":"Крајњи екран","fields":[{"label":"Наслов"},{"label":"Текст"},{"label":"Слика"},{"label":"Резултат","description":"Резултат ће бити послат било којој ЛМС, ЛРС или било којој другој повезаној услузи која прима оцене од Х5П за кориснике који достигну задати крајњи сценарио"},{"label":"Садржај ID"}]}},{"label":"Листа садржаја сценарија гранања","entity":"садржај","field":{"fields":[{},{"label":"Прикажи наслов садржаја у приказу","description":"Ако је изабрано, корисник ће видети наслов садржаја у горњој траци изнад овог садржаја"},{"label":"Обавезно да се попуни","description":"Подстиче гледаоце да доврше интеракцију пре него што наставе са сценаријем. Ово подешавање ће радити само за интеракције које се могу довршити","options":[{"label":"Користите поставке понашања"},{"label":"Омогућено"},{"label":"Онемогућено"}]},{"label":"Следећи Садржај ИД (крајњи екрани су дефинисани негативним бројевима)"},{"label":"Повратна информација","fields":[{"label":"Наслов повратне информације"},{"label":"Текст повратне информације"},{"label":"Слика повратне информације"},{"label":"Оцена за овај сценарио","description":"Резултат ће бити послат било којем ЛМС-у, ЛРС-у или било којој другој повезаној услузи која прима оцене од Х5П за кориснике који достигну овај сценарио"}]},{"label":"Поставке понашања","description":"Ово ће омогућити кориснику да се врати и види претходни садржај / питање у сценарију","options":[{"label":"Користите поставке понашања"},{"label":"Омогућено"},{"label":"Онемогућено"}]}]}},{"label":"Могућности бодовања","fields":[{"label":"Могућности бодовања","description":"Изаберите врсту бодовања","options":[{"label":"Статички постављен резултат за сваки крајњи сценарио"},{"label":"Динамички израчунајте резултат на основу корисничких одговора"},{"label":"Нема бодовања"}]},{"label":"Укључите резултате из интеракција у оквиру сценарија гранања","description":"Ако се одаберу резултати из, на пример, Интерактивних видео записа, додаће се укупном резултату. Ако нису изабрани, рачунаће се само бодови наведени у одељцима за повратне информације ставки садржаја, питања о гранању и крајњи сценарији."}]},{"label":"Поставке понашања","fields":[{"label":"Замени навигацију уназад","description":"Замените појединачна подешавања за омогућавање навигације уназад"},{"label":"Override require content finished","description":"Override the individual settings for requiring the content to be finished before activating the \"Proceed\" button. Will not have any effect if the content doesn\'t indicate if it was \"finished\", e.g. images or course presentations with just one slide."},{"label":"Randomize Branching Questions","description":"Branching questions will be displayed in random order"}]},{"label":"Локализација","fields":[{"label":"Текст дугмета на почетном екрану","default":"Започните курс"},{"label":"Текст дугмета на завршном екрану","default":"Поново покрените курс"},{"label":"Текст за дугме за повратак на сваком од екрана библиотеке и разграната питања","default":"Назад"},{"label":"Текст дугмета на сваком од екрана библиотеке","default":"Наставите"},{"label":"Текст дугмета за репродукцију видео записа","default":"Поново репродукујте видео"},{"label":"Ознака за резултат на крајњем екрану","default":"Ваш резултат:"},{"label":"Ознака за резултат на крајњем екрану","default":"Ваш резултат:"},{"label":"Ознака дугмета за Цео екран","default":"Цео екран"}]}]}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // sv.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pBranchingScenarioLibId,
            'language_code' => 'sv',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Redigerare för Branching Scenario","fields":[{"label":"Titel"},{"label":"Startskärm","fields":[{"label":"Titel på startskärm","placeholder":"Titel för din kurs"},{"label":"Undertitel för startskärm","placeholder":"Information om kursen"},{"label":"Bild på startskärm"},{"label":"Image alternative text"}]},{"label":"Lista med slutskärmar","field":{"label":"Slutskärm","fields":[{"label":"Titel"},{"label":"Text"},{"label":"Bild"},{"label":"Poäng","description":"Poängen kommer att skickas till något LMS, LRS eller annan kopplad tjänst som tar emot poäng från H5P för användare som når det förvalda slut-scenariot"},{"label":"Innehålls-ID"}]}},{"label":"Lista över innehåll i branching scenario","entity":"innehåll","field":{"fields":[{},{"label":"Visa innehållstitel i vy","description":"Om valt, så kommer användaren att se innehållets titel i toppraden ovanför detta innehåll"},{"label":"Måste genomföras","description":"Uppmanar deltagaren att genomföra interaktionen före denne kan fortsätta med scenariot. Denna inställning fungerar endast för interaktioner som kan genomföras","options":[{"label":"Använd beteende-inställning"},{"label":"Aktiverad"},{"label":"Inaktiverad"}]},{"label":"Nästa innehålls-ID (slutskärmar definieras av negativa nummer)"},{"label":"Feedback","fields":[{"label":"Feedback-titel"},{"label":"Feedback-text"},{"label":"Feedback-bild"},{"label":"Poäng för detta scenario","description":"Poängen kommer att skickas till något LMS, LRS eller annan tjänst som tar emot poäng från H5P för användare som slutför detta scenario"}]},{"label":"Beteende-inställningar","description":"Detta låter användaren gå tillbaka och se föregående innehåll/fråga i scenariot","options":[{"label":"Använd beteende-inställning"},{"label":"Aktiverad"},{"label":"Inaktiverad"}]}]}},{"label":"Inställningar för poängsättning","fields":[{"label":"Inställningar för poängsättning","description":"Välj typ av poängsättning","options":[{"label":"Sätt poäng för varje slutscenario statiskt"},{"label":"Kalkylera poäng från användares svar dynamiskt"},{"label":"Ingen poängsättning"}]},{"label":"Inkludera poäng från interaktioner i Branching Scenario","description":"Om valt, så kommer poäng från exempelvis Interactive Video att läggas till i den totalt uppnådda poängen. Om inte valt, så kommer endast poäng som är specificerade i feedback-sektionerna i innehållsposter, branching-frågor samt slutscenarion att räknas."}]},{"label":"Beteende-inställningar","fields":[{"label":"Ignorera bakåt-navigation","description":"Ignorera de individuella inställningarna för att tillåta bakåt-navigation"},{"label":"Ignorera att innehåll måste avslutas","description":"Ignorera de individuella inställningarna för att kräva att innehållet måste avslutas före \"Framåt\"-knappen aktiveras. Detta kommer inte ha någon effekt om innehållet inte indikerar att det var \"avslutat\", exempelvis bilder eller Course Presentations som bara har en sida."},{"label":"Randomize Branching Questions","description":"Branching questions will be displayed in random order"}]},{"label":"Lokalisering","fields":[{"label":"Text för knappen på startskärmen","default":"Starta kursen"},{"label":"Text för knappen på slutskärmen","default":"Starta om kursen"},{"label":"Text för tillbaka-knappen på varje biblioteksskärm och branching-fråga","default":"Tillbaka"},{"label":"Text för knappen på varje biblioteksskärm","default":"Fortsätt"},{"label":"Etikett för knappen som används för att starta om en video","default":"Starta om video"},{"label":"Etikett för poäng på slutskärmen","default":"Din poäng:"},{"label":"Etikett för poäng på slutskärmen","default":"Din poäng:"},{"label":"Aria-etikett för helskärms-knappen","default":"Helskärm"}]}]}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // tr.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pBranchingScenarioLibId,
            'language_code' => 'tr',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Dallanma Senaryo Editörü","fields":[{"label":"Başlık"},{"label":"Başlangıç ​​ekranı","fields":[{"label":"Başlangıç ​​ekranı başlığı","placeholder":"Kursunuzun başlığı"},{"label":"Başlat ekranı altyazısı","placeholder":"Kurs hakkında detaylar"},{"label":"Başlangıç ​​ekranı görüntüsü"},{"label":"Image alternative text"}]},{"label":"Bitiş ekranlarının listesi","field":{"label":"Bitiş ekranı","fields":[{"label":"Başlık"},{"label":"Metin"},{"label":"Resim"},{"label":"Puan","description":"Puan, varsayılan bitiş senaryosuna ulaşan kullanıcılar için H5P\'den puan alan herhangi bir LMS (Ör: EBA), LRS veya diğer bağlı hizmete gönderilecektir."},{"label":"İçerik Kimliği"}]}},{"label":"Dallanma senaryosu içeriğinin listesi","entity":"içerik","field":{"fields":[{},{"label":"İçerik başlığını görünümde göster","description":"Seçilirse, kullanıcı bu içeriğin üstündeki üst çubukta içerik başlığını görecektir."},{"label":"Tamamlanması gerekli","description":"İzleyicileri senaryoya devam etmeden önce etkileşimi tamamlamaya teşvik eder. Bu ayar yalnızca tamamlanabilen etkileşimler için çalışır","options":[{"label":"Davranış ayarını kullan"},{"label":"Etkinleştirilmiş"},{"label":"Engelli"}]},{"label":"Sonraki İçerik Kimliği (bitiş ekranları negatif sayılarla tanımlanır)"},{"label":"Geri bildirim","fields":[{"label":"Geri bildirim başlığı"},{"label":"Geri bildirim metni"},{"label":"Geri bildirim resmi"},{"label":"Bu senaryo için puan","description":"Puan, bu senaryoya ulaşan kullanıcılar için H5P\'den puan alan herhangi bir LMS (Ör: EBA), LRS veya diğer bağlı hizmete gönderilecektir."}]},{"label":"Etkinlik Ayarları","description":"Bu, kullanıcının geri dönüp senaryodaki önceki içeriği/soruyu görmesine izin verecektir.","options":[{"label":"Davranış ayarını kullan"},{"label":"Etkinleştirilmiş"},{"label":"Engelli"}]}]}},{"label":"Puanlama seçenekleri","fields":[{"label":"Puanlama seçenekleri","description":"Puanlama türünü seçin","options":[{"label":"Her bitiş senaryosu için statik olarak belirlenen puan"},{"label":"Kullanıcı yanıtlarından puanı dinamik olarak hesaplayın"},{"label":"Puanlama yok"}]},{"label":"Dallanma Senaryosu içindeki etkileşimlerden alınan puanları dahil et","description":"Örneğin Etkileşimli Videolardan seçilen puanlar, elde edilen toplam puana eklenecektir. Yalnızca içerik öğelerinin geri bildirim bölümlerinde belirtilen puanlar seçilmezse, dallanma soruları ve bitiş senaryoları sayılacaktır."}]},{"label":"Etkinlik Ayarları","fields":[{"label":"Geri gezinmeyi geçersiz kıl","description":"Geriye doğru gezinmeyi etkinleştirmek için bireysel ayarları geçersiz kılar"},{"label":"İçerğinin tamamlanması gerekir ayarını geçersiz kıl","description":"\"Devam\" düğmesini etkinleştirmeden önce içeriğin tamamlanmasını gerektiren bireysel ayarları geçersiz kılar. İçerik \"bitti\" olarak belirtilmiyorsa hiçbir etkisi olmayacak. Ör: görüntü ve sunum tek slayttan oluşuyorsa."},{"label":"Randomize Branching Questions","description":"Branching questions will be displayed in random order"}]},{"label":"Bölge ve dil seçenekleri","fields":[{"label":"Başlangıç ​​ekranındaki düğme için metin","default":"Kursa başla"},{"label":"Bitiş ekranındaki düğme için metin","default":"Kursu yeniden başlat"},{"label":"Her bir kitaplık ekranındaki geri düğmesi ve dallanma soruları için metin","default":"Geri"},{"label":"Kütüphane ekranlarının her birinde düğme için metin","default":"İlerle"},{"label":"Bir videoyu tekrar oynatmak için kullanılan düğmenin metni","default":"Videoyu tekrar oynat"},{"label":"Bitiş ekranında puan etiketi","default":"Puanın:"},{"label":"Bitiş ekranında puan etiketi","default":"Puanın:"},{"label":"Tam ekran düğmesi için alan etiketi","default":"Tam ekran"}]}]}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // zh-cn.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pBranchingScenarioLibId,
            'language_code' => 'zh-cn',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Branching Scenario Editor","fields":[{"label":"Title"},{"label":"Start screen","fields":[{"label":"Start screen title","placeholder":"Title for your course"},{"label":"Start screen subtitle","placeholder":"Details about the course"},{"label":"Start screen image"},{"label":"Image alternative text"}]},{"label":"List of end screens","field":{"label":"End screen","fields":[{"label":"Title"},{"label":"Text"},{"label":"Image"},{"label":"Score","description":"The score will be sent to any LMS, LRS or any other connected service that receives scores from H5P for users who reach the default end scenario"},{"label":"Content ID"}]}},{"label":"List of branching scenario content","entity":"content","field":{"fields":[{},{"label":"Show content title in view","description":"If selected, the user will see the content title in the top bar above this content"},{"label":"Required to be completed","description":"Urges viewers to complete the interaction before proceeding with the scenario. This setting will only work for interactions that can be completed","options":[{"label":"Use behavioural setting"},{"label":"Enabled"},{"label":"Disabled"}]},{"label":"Next Content ID (end screens are defined by negative numbers)"},{"label":"Feedback","fields":[{"label":"Feedback title"},{"label":"Feedback text"},{"label":"Feedback image"},{"label":"Score for this scenario","description":"The score will be sent to any LMS, LRS or any other connected service that receives scores from H5P for users who reach this scenario"}]},{"label":"Behavioural settings","description":"This will allow user to go back and see the previous content/question in the scenario","options":[{"label":"Use behavioural setting"},{"label":"Enabled"},{"label":"Disabled"}]}]}},{"label":"Scoring options","fields":[{"label":"Scoring options","description":"Select type of scoring","options":[{"label":"Statically set score for each end scenario"},{"label":"Dynamically calculate score from user answers"},{"label":"No scoring"}]},{"label":"Include scores from interactions within the Branching Scenario","description":"If chosen scores from for instance Interactive Videos will be added to the total score obtained. If not chosen only scores specified in the feedback sections of the content items, branching questions and end scenarios will count."}]},{"label":"Behavioural settings","fields":[{"label":"Override backwards navigation","description":"Override the individual settings for enabling backwards navigation"},{"label":"Override require content finished","description":"Override the individual settings for requiring the content to be finished before activating the \"Proceed\" button. Will not have any effect if the content doesn\'t indicate if it was \"finished\", e.g. images or course presentations with just one slide."},{"label":"Randomize Branching Questions","description":"Branching questions will be displayed in random order"}]},{"label":"Localization","fields":[{"label":"Text for the button on the start screen","default":"Start the course"},{"label":"Text for the button on the end screen","default":"Restart the course"},{"label":"Text for the back button on each of the library screens and branching questions","default":"Back"},{"label":"Text for the button on each of the library screens","default":"Proceed"},{"label":"Text for the button used to replay a video","default":"Replay the video"},{"label":"Label for score on the end screen","default":"Your score:"},{"label":"Label for score on the end screen","default":"Your score:"},{"label":"Aria label for fullscreen button","default":"Fullscreen"}]}]}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);

    }

    private function getSemantics() {
      return '[
  {
    "name": "branchingScenario",
    "type": "group",
    "widget": "branchingScenario",
    "label": "Branching Scenario Editor",
    "importance": "high",
    "fields": [
      {
        "name": "title",
        "label": "Title",
        "type": "text",
        "importance": "high"
      },
      {
        "name": "startScreen",
        "label": "Start screen",
        "type": "group",
        "importance": "low",
        "fields": [
          {
            "name": "startScreenTitle",
            "label": "Course Title",
            "placeholder": "Title for your course",
            "type": "text",
            "importance": "medium",
            "optional": true,
            "widget": "html",
            "enterMode": "p",
            "tags": [
              "strong",
              "em",
              "del",
              "code",
              "a"
            ]
          },
          {
            "name": "startScreenSubtitle",
            "label": "Course Details",
            "placeholder": "Details about the course",
            "type": "text",
            "importance": "medium",
            "optional": true,
            "widget": "html",
            "enterMode": "p",
            "tags": [
              "strong",
              "em",
              "del",
              "code",
              "a"
            ]
          },
          {
            "name": "startScreenImage",
            "label": "Course image",
            "type": "image",
            "optional": true,
            "importance": "medium"
          },
          {
            "name": "startScreenAltText",
            "label": "Image alternative text",
            "type": "text",
            "optional": true,
            "importance": "medium"
          }
        ]
      },
      {
        "name": "endScreens",
        "label": "List of end screens",
        "type": "list",
        "importance": "medium",
        "field": {
          "name": "endScreen",
          "label": "End screen",
          "type": "group",
          "importance": "low",
          "fields": [
            {
              "name": "endScreenTitle",
              "label": "Title",
              "type": "text",
              "importance": "medium",
              "optional": true,
              "widget": "html",
              "enterMode": "p",
              "tags": [
                "strong",
                "em",
                "del",
                "code",
                "a"
              ]
            },
            {
              "name": "endScreenSubtitle",
              "label": "Text",
              "type": "text",
              "importance": "medium",
              "optional": true,
              "widget": "html",
              "enterMode": "p",
              "tags": [
                "strong",
                "em",
                "del",
                "code",
                "a"
              ]
            },
            {
              "name": "endScreenImage",
              "label": "Image",
              "type": "image",
              "importance": "medium",
              "optional": true
            },
            {
              "name": "endScreenScore",
              "label": "Score",
              "description": "The score will be sent to any LMS, LRS or any other connected service that receives scores from H5P for users who reach the default end scenario",
              "type": "number",
              "default": 0,
              "optional": true
            },
            {
              "name": "contentId",
              "label": "Content ID",
              "type": "number",
              "max": 0,
              "widget": "none"
            }
          ]
        }
      },
      {
        "name": "content",
        "label": "List of branching scenario content",
        "importance": "high",
        "type": "list",
        "min": 1,
        "entity": "content",
        "field": {
          "name": "content",
          "type": "group",
          "fields": [
            {
              "name": "type",
              "type": "library",
              "importance": "high",
              "options": [
                "H5P.BranchingQuestion 1.0",
                "H5P.CoursePresentation 1.24",
                "H5P.AdvancedText 1.1",
                "H5P.Image 1.1",
                "H5P.ImageHotspots 1.10",
                "H5P.InteractiveVideo 1.24",
                "H5P.Video 1.6"
              ]
            },
            {
              "name": "showContentTitle",
              "label": "Show content title in view",
              "description": "If selected, the user will see the content title in the top bar above this content",
              "type": "boolean",
              "importance": "high"
            },
            {
              "name": "forceContentFinished",
              "type": "select",
              "label": "Override require content finished",
              "description": "Override the individual settings for requiring the content to be finished before activating the \"Proceed\" button. Will not have any effect if the content doesn\'t indicate if it was \"finished\", e.g. images or course presentations with just one slide.",
              "optional": "true",
              "options": [
                {
                  "value": "useBehavioural",
                  "label": "Use behavioural setting"
                },
                {
                  "value": "enabled",
                  "label": "Enabled"
                },
                {
                  "value": "disabled",
                  "label": "Disabled"
                }
              ],
              "default": "useBehavioural"
            },
            {
              "name": "nextContentId",
              "label": "Next Content ID (end screens are defined by negative numbers)",
              "type": "number",
              "importance": "high",
              "widget": "none"
            },
            {
              "name": "feedback",
              "type": "group",
              "label": "Feedback",
              "expanded": true,
              "fields": [
                {
                  "name": "title",
                  "type": "text",
                  "label": "Feedback title",
                  "widget": "html",
                  "enterMode": "p",
                  "tags": [
                    "strong",
                    "em",
                    "del",
                    "code",
                    "a"
                  ]
                },
                {
                  "name": "subtitle",
                  "type": "text",
                  "label": "Feedback text",
                  "optional": true,
                  "widget": "html",
                  "enterMode": "p",
                  "tags": [
                    "strong",
                    "em",
                    "del",
                    "code",
                    "a"
                  ]
                },
                {
                  "name": "image",
                  "type": "image",
                  "label": "Feedback image",
                  "optional": true
                },
                {
                  "name": "endScreenScore",
                  "type": "number",
                  "label": "Score for this scenario",
                  "description": "The score will be sent to any LMS, LRS or any other connected service that receives scores from H5P for users who reach this scenario",
                  "optional": true
                }
              ]
            },
            {
              "name": "contentBehaviour",
              "label": "Navigate back",
              "type": "select",
              "description": "This will allow user to go back and see the previous content/question in the scenario",
              "options": [
                {
                  "value": "useBehavioural",
                  "label": "Use behavioural setting"
                },
                {
                  "value": "enabled",
                  "label": "Enabled"
                },
                {
                  "value": "disabled",
                  "label": "Disabled"
                }
              ],
              "default": "useBehavioural"
            }
          ]
        }
      },
      {
        "name": "scoringOptionGroup",
        "type": "group",
        "label": "Scoring options",
        "importance": "low",
        "fields": [
          {
            "name": "scoringOption",
            "label": "Scoring options",
            "type": "select",
            "description": "Select type of scoring",
            "options": [
              {
                "value": "static-end-score",
                "label": "Statically set score for each end scenario"
              },
              {
                "value": "dynamic-score",
                "label": "Dynamically calculate score from user answers"
              },
              {
                "value": "no-score",
                "label": "No scoring"
              }
            ],
            "default": "no-score"
          },
          {
            "name": "includeInteractionsScores",
            "label": "Include scores from interactions within the Branching Scenario",
            "description": "If chosen scores from for instance Interactive Videos will be added to the total score obtained. If not chosen only scores specified in the feedback sections of the content items, branching questions and end scenarios will count.",
            "type": "boolean",
            "default": true,
            "optional": true,
            "widget": "showWhen",
            "showWhen": {
              "rules": [
                {
                  "field": "scoringOption",
                  "equals": "dynamic-score"
                }
              ]
            }
          }
        ]
      },
      {
        "name": "behaviour",
        "type": "group",
        "label": "Behavioural settings",
        "importance": "low",
        "fields": [
          {
            "name": "enableBackwardsNavigation",
            "label": "Navigate back",
            "description": "This will allow user to go back and see the previous content/question in the scenario. This can be overridden on a content level",
            "type": "boolean",
            "optional": true,
            "default": false
          },
          {
            "name": "forceContentFinished",
            "label": "Required to watch",
            "description": "Urges viewers to complete the Video, Interactive Vdeo and Course Presentation before proceeding with the scenario. This can be overridden on a content level",
            "type": "boolean",
            "optional": true,
            "default": false
          },
          {
            "name": "randomizeBranchingQuestions",
            "label": "Randomize Branching Questions",
            "description": "Branching questions will be displayed in random order",
            "type": "boolean",
            "optional": true,
            "default": false
          }
        ]
      },
      {
        "name": "l10n",
        "type": "group",
        "label": "Localization",
        "importance": "low",
        "common": true,
        "fields": [
          {
            "name": "startScreenButtonText",
            "label": "Text for the button on the start screen",
            "type": "text",
            "importance": "low",
            "default": "Start the course"
          },
          {
            "name": "endScreenButtonText",
            "label": "Text for the button on the end screen",
            "type": "text",
            "importance": "low",
            "default": "Restart the course"
          },
          {
            "name": "backButtonText",
            "label": "Text for the back button on each of the library screens and branching questions",
            "type": "text",
            "importance": "low",
            "default": "Back"
          },
          {
            "name": "proceedButtonText",
            "label": "Text for the button on each of the library screens",
            "type": "text",
            "importance": "low",
            "default": "Proceed"
          },
          {
            "name": "disableProceedButtonText",
            "label": "Text for the disbled button on the library screens",
            "type": "text",
            "importance": "low",
            "default": "Require to complete the current module"
          },
          {
            "name": "replayButtonText",
            "label": "Text for the button used to replay a video",
            "type": "text",
            "importance": "low",
            "default": "Replay the video"
          },
          {
            "name": "scoreText",
            "label": "Label for score on the end screen",
            "type": "text",
            "importance": "low",
            "default": "Your score:"
          },
          {
            "name": "fullscreenAria",
            "label": "Aria label for fullscreen button",
            "type": "text",
            "importance": "low",
            "default": "Fullscreen"
          }
        ]
      }
    ]
  }
]';

  }

    /**
     * Insert Editor Dependent Libraries
     * @param $h5pEditorBranchingScenarioLibId
     */
    private function insertEditorDependentLibraries($h5pEditorBranchingScenarioLibId)
    {
        $h5pMaterialDesignIconsParams = ['name' => "H5P.MaterialDesignIcons", "major_version" => 1, "minor_version" => 0];
        $h5pMaterialDesignIconsLib = DB::table('h5p_libraries')->where($h5pMaterialDesignIconsParams)->first();
        $h5pMaterialDesignIconsLibId = $h5pMaterialDesignIconsLib->id;

        $h5pBranchingQuestionParams = ['name' => "H5P.BranchingQuestion", "major_version" => 1, "minor_version" => 0];
        $h5pBranchingQuestionLib = DB::table('h5p_libraries')->where($h5pBranchingQuestionParams)->first();
        $h5pBranchingQuestionLibId = $h5pBranchingQuestionLib->id;

        $h5pBranchingScenarioParams = ['name' => "H5P.BranchingScenario", "major_version" => 1, "minor_version" => 7];
        $h5pBranchingScenarioLib = DB::table('h5p_libraries')->where($h5pBranchingScenarioParams)->first();
        $h5pBranchingScenarioLibId = $h5pBranchingScenarioLib->id;

        $h5pCoursePresentationParams = ['name' => "H5P.CoursePresentation", "major_version" => 1, "minor_version" => 24];
        $h5pCoursePresentationLib = DB::table('h5p_libraries')->where($h5pCoursePresentationParams)->first();
        $h5pCoursePresentationLibId = $h5pCoursePresentationLib->id;

        $h5pAdvancedTextParams = ['name' => "H5P.AdvancedText", "major_version" => 1, "minor_version" => 1];
        $h5pAdvancedTextLib = DB::table('h5p_libraries')->where($h5pAdvancedTextParams)->first();
        $h5pAdvancedTextLibId = $h5pAdvancedTextLib->id;

        $h5pImageParams = ['name' => "H5P.Image", "major_version" => 1, "minor_version" => 1];
        $h5pImageLib = DB::table('h5p_libraries')->where($h5pImageParams)->first();
        $h5pImageLibId = $h5pImageLib->id;

        $h5pImageHotspotsParams = ['name' => "H5P.ImageHotspots", "major_version" => 1, "minor_version" => 10];
        $h5pImageHotspotsLib = DB::table('h5p_libraries')->where($h5pImageHotspotsParams)->first();
        $h5pImageHotspotsLibId = $h5pImageHotspotsLib->id;

        $h5pInteractiveVideoParams = ['name' => "H5P.InteractiveVideo", "major_version" => 1, "minor_version" => 24];
        $h5pInteractiveVideoLib = DB::table('h5p_libraries')->where($h5pInteractiveVideoParams)->first();
        $h5pInteractiveVideoLibId = $h5pInteractiveVideoLib->id;

        $h5pVideoParams = ['name' => "H5P.Video", "major_version" => 1, "minor_version" => 6];
        $h5pVideoLib = DB::table('h5p_libraries')->where($h5pVideoParams)->first();
        $h5pVideoLibId = $h5pVideoLib->id;


        DB::table('h5p_libraries_libraries')->insert([
            'library_id' => $h5pEditorBranchingScenarioLibId,
            'required_library_id' => $h5pMaterialDesignIconsLibId,
            'dependency_type' => 'preloaded'
        ]);

        DB::table('h5p_libraries_libraries')->insert([
            'library_id' => $h5pEditorBranchingScenarioLibId,
            'required_library_id' => $h5pBranchingQuestionLibId,
            'dependency_type' => 'preloaded'
        ]);

        DB::table('h5p_libraries_libraries')->insert([
            'library_id' => $h5pEditorBranchingScenarioLibId,
            'required_library_id' => $h5pBranchingScenarioLibId,
            'dependency_type' => 'preloaded'
        ]);

        DB::table('h5p_libraries_libraries')->insert([
            'library_id' => $h5pEditorBranchingScenarioLibId,
            'required_library_id' => $h5pCoursePresentationLibId,
            'dependency_type' => 'preloaded'
        ]);

        DB::table('h5p_libraries_libraries')->insert([
            'library_id' => $h5pEditorBranchingScenarioLibId,
            'required_library_id' => $h5pAdvancedTextLibId,
            'dependency_type' => 'preloaded'
        ]);

        DB::table('h5p_libraries_libraries')->insert([
            'library_id' => $h5pEditorBranchingScenarioLibId,
            'required_library_id' => $h5pImageLibId,
            'dependency_type' => 'preloaded'
        ]);

        DB::table('h5p_libraries_libraries')->insert([
            'library_id' => $h5pEditorBranchingScenarioLibId,
            'required_library_id' => $h5pImageHotspotsLibId,
            'dependency_type' => 'preloaded'
        ]);

        DB::table('h5p_libraries_libraries')->insert([
            'library_id' => $h5pEditorBranchingScenarioLibId,
            'required_library_id' => $h5pInteractiveVideoLibId,
            'dependency_type' => 'preloaded'
        ]);

        DB::table('h5p_libraries_libraries')->insert([
            'library_id' => $h5pEditorBranchingScenarioLibId,
            'required_library_id' => $h5pVideoLibId,
            'dependency_type' => 'preloaded'
        ]);

    }


}
