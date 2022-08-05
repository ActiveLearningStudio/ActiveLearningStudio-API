<?php

use Illuminate\Database\Seeder;

class H5PAddImageHotSpots extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $h5pFibLibParams = ['name' => "H5P.ImageHotspots", "major_version" => 1, "minor_version" => 10];
        $h5pFibLib = DB::table('h5p_libraries')->where($h5pFibLibParams)->first();

      if (empty($h5pFibLib)) {
          $h5pFibLibId = DB::table('h5p_libraries')->insertGetId([
                          'name' => 'H5P.ImageHotspots',
                          'title' => 'Image Hotspots',
                          'major_version' => 1,
                          'minor_version' => 10,
                          'patch_version' => 0,
                          'embed_types' => 'iframe',
                          'runnable' => 1,
                          'restricted' => 0,
                          'fullscreen' => 0,
                          'preloaded_js' => 'scripts/image-hotspots.js,scripts/hotspot.js,scripts/popup.js',
                          'preloaded_css' => 'styles/image-hotspots.css',
                          'drop_library_css' => '',
                          'semantics' => $this->getSemantics(),
                          'tutorial_url' => ' ',
                          'has_icon' => 1
          ]);

          // insert dependent libraries
          $this->insertDependentLibraries($h5pFibLibId);

          // insert libraries languages
          $this->insertLibrariesLanguages($h5pFibLibId);
      }
    }

    /**
     * Insert Dependent Libraries
     * @param $h5pFibLibId
     */
    private function insertDependentLibraries($h5pFibLibId)
    {
        //Preloaded Dependencies
        $h5pFontAwesomeParams = ['name' => "FontAwesome", "major_version" => 4, "minor_version" => 5];
        $h5pFontAwesomeLib = DB::table('h5p_libraries')->where($h5pFontAwesomeParams)->first();
        $h5pFontAwesomeLibId = $h5pFontAwesomeLib->id;


        $h5pTransitionParams = ['name' => "H5P.Transition", "major_version" => 1, "minor_version" => 0];
        $h5pTransitionLib = DB::table('h5p_libraries')->where($h5pTransitionParams)->first();

        if(empty($h5pTransitionLib)) {
          $h5pTransitionLibId = DB::table('h5p_libraries')->insertGetId([
              'name' => 'H5P.Transition',
              'title' => 'Transition',
              'major_version' => 1,
              'minor_version' => 0,
              'patch_version' => 4,
              'embed_types' => '',
              'runnable' => 0,
              'restricted' => 0,
              'fullscreen' => 0,
              'preloaded_js' => 'transition.js',
              'preloaded_css' => '',
              'drop_library_css' => '',
              'semantics' => '',
              'tutorial_url' => ' ',
              'has_icon' => 0
          ]);
      } else {
        $h5pTransitionLibId = $h5pTransitionLib->id;
      }

        // Editor Dependencies
        $h5pEditorImageCoordinateSelectorParams = ['name' => "H5PEditor.ImageCoordinateSelector", "major_version" => 1, "minor_version" => 2];
        $h5pEditorImageCoordinateSelectorLib = DB::table('h5p_libraries')->where($h5pEditorImageCoordinateSelectorParams)->first();
        

        if(empty($h5pEditorImageCoordinateSelectorLib)) {
          $h5pEditorImageCoordinateSelectorLibId = DB::table('h5p_libraries')->insertGetId([
              'name' => 'H5PEditor.ImageCoordinateSelector',
              'title' => 'ImageCoordinateSelector',
              'major_version' => 1,
              'minor_version' => 2,
              'patch_version' => 5,
              'embed_types' => '',
              'runnable' => 0,
              'restricted' => 0,
              'fullscreen' => 0,
              'preloaded_js' => 'scripts/image-coordinate-selector.js',
              'preloaded_css' => 'styles/image-coordinate-selector.css',
              'drop_library_css' => '',
              'semantics' => '',
              'tutorial_url' => ' ',
              'has_icon' => 0
          ]);
      } else {
        $h5pEditorImageCoordinateSelectorLibId = $h5pEditorImageCoordinateSelectorLib->id;
      }

        $h5pEditorColorSelectorParams = ['name' => "H5PEditor.ColorSelector", "major_version" => 1, "minor_version" => 3];
        $h5pEditorColorSelectorLib = DB::table('h5p_libraries')->where($h5pEditorColorSelectorParams)->first();

        if(empty($h5pEditorColorSelectorLib)) {
          $h5pEditorColorSelectorLibId = DB::table('h5p_libraries')->insertGetId([
              'name' => 'H5PEditor.ColorSelector',
              'title' => 'H5P color selector',
              'major_version' => 1,
              'minor_version' => 3,
              'patch_version' => 1,
              'embed_types' => '',
              'runnable' => 0,
              'restricted' => 0,
              'fullscreen' => 0,
              'preloaded_js' => 'scripts/spectrum.js,scripts/color-selector.js',
              'preloaded_css' => 'styles/spectrum.css,styles/color-selector.css',
              'drop_library_css' => '',
              'semantics' => '',
              'tutorial_url' => ' ',
              'has_icon' => 0
          ]);
      } else {
        $h5pEditorColorSelectorLibId = $h5pEditorColorSelectorLib->id;
      }


        $h5pEditorShowWhenParams = ['name' => "H5PEditor.ShowWhen", "major_version" => 1, "minor_version" => 0];
        $h5pEditorShowWhenLib = DB::table('h5p_libraries')->where($h5pEditorShowWhenParams)->first();
        if(empty($h5pEditorShowWhenLib)) {
          $h5pEditorShowWhenLibId = DB::table('h5p_libraries')->insertGetId([
              'name' => 'H5PEditor.ShowWhen',
              'title' => 'H5P ShowWhen',
              'major_version' => 1,
              'minor_version' => 0,
              'patch_version' => 5,
              'embed_types' => '',
              'runnable' => 0,
              'restricted' => 0,
              'fullscreen' => 0,
              'preloaded_js' => 'h5p-show-when.js',
              'preloaded_css' => 'h5p-show-when.css',
              'drop_library_css' => '',
              'semantics' => '',
              'tutorial_url' => ' ',
              'has_icon' => 0
          ]);
      } else {
        $h5pEditorShowWhenLibId = $h5pEditorShowWhenLib->id;
      }



        DB::table('h5p_libraries_libraries')->insert([
            'library_id' => $h5pFibLibId,
            'required_library_id' => $h5pFontAwesomeLibId,
            'dependency_type' => 'preloaded'
        ]);
        DB::table('h5p_libraries_libraries')->insert([
            'library_id' => $h5pFibLibId,
            'required_library_id' => $h5pTransitionLibId,
            'dependency_type' => 'preloaded'
        ]);
    

        DB::table('h5p_libraries_libraries')->insert([
            'library_id' => $h5pFibLibId,
            'required_library_id' => $h5pEditorImageCoordinateSelectorLibId,
            'dependency_type' => 'editor'
        ]);

        DB::table('h5p_libraries_libraries')->insert([
            'library_id' => $h5pFibLibId,
            'required_library_id' => $h5pEditorColorSelectorLibId,
            'dependency_type' => 'editor'
        ]);

        DB::table('h5p_libraries_libraries')->insert([
            'library_id' => $h5pFibLibId,
            'required_library_id' => $h5pEditorShowWhenLibId,
            'dependency_type' => 'editor'
        ]);
    }

    /**
     * Insert Library Language Semantics
     * @param $h5pFibLibId
     */
    private function insertLibrariesLanguages($h5pFibLibId)
    {
        // af.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'af',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Agtergrond prent","description":"Prent wat op agtergrond vertoon word."},{"label":"Alternatiewe teks vir agtergrondprent","description":"Indien die weblaaier nie die teks kan lees nie, sal \'n alternatiewe teks vertoon word. Word ook gebruik deur hulptegnologieë."},{"label":"Warmkol ikoon","options":[{"label":"Voorafingestelde ikoon"},{"label":"Opgelaaide prent"}]},{"label":"Voorafopgelaaide prent","description":"Gebruik \'n voorafopgelaaide prent vir die warmkol.","options":[{"label":"Plus"},{"label":"Minus"},{"label":"Maal"},{"label":"Toets"},{"label":"Vraag"},{"label":"Inligting"},{"label":"Uitroepteken"}]},{"label":"Opgelaaide prent","description":"Gebruik jou eie prent vir die warmkolprent.<br />75px by 75px word aanbeveel vir die prentgrote."},{"label":"Warmkol kleur","description":"Die kleur van die warmkol"},{"entity":"warmkol","label":"Warmkolle","field":{"label":"Warmkol","fields":[{"label":"Warmkol plasing","description":"Klik op die miniatuurbeeld om die warmkol te plaas"},{"label":"Bedek die hele agtergrondprent","description":"Wanneer die gebruiker op die warmkol klik, sal die opblaasbeeld die hele agtergrondbeeld dek"},{"label":"Hoofopskrif","description":"Opsionele opblaasbeeld vir die hoofopskrif"},{"label":"Opblaasbeeld inhoud","field":{"label":"Inhouditem"}}]}},{"label":"Warmkoletiket vir hulptegnologieë","default":"Warmkol #num","description":"Dit sal help om aan te kondig na watter warmkol-element u navigeer het as daar geen hoofopskriftekst vir \'n warmkol is nie. #num kan gebruik word om die warmkol-nommer te vervang."},{"label":"Sluitingsknoppie-etiket vir hulptegnologieë","default":"Sluit"}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // ar.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'ar',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"صورة الخلفية","description":"الصورة التي تظهر على الخلفية."},{"label":"لون نقطة الاتصال","description":"لون نقاط الاتصال"},{"label":"Hotspot Icon","options":[{"label":"Predefined icon"},{"label":"Uploaded image"}]},{"label":"Predefined icon","description":"Using a predefined icon for the hotspot.","options":[{"label":"Plus"},{"label":"Minus"},{"label":"Times"},{"label":"Check"},{"label":"Question"},{"label":"Info"},{"label":"Exclamation"}]},{"label":"Uploaded image","description":"Use your own image for the hotspot icon.<br />75px by 75px is recommended for your image."},{"label":"نقاط الاتصال","description":"لون نقاط الاتصال"},{"entity":"hotspot","label":"نقاط الاتصال","field":{"label":"نقطة اتصال","fields":[{"label":"موضع نقطة الاتصال","description":"انقر على الصورة المصغرة لمكان نقطة الاتصال"},{"label":"تغطية صورة الخلفية بأكملها","description":"عندما ينقر المستخدم على نقطة الاتصال ، ستغطي النافذة المنبثقة صورة الخلفية بأكملها"},{"label":"الترويسة","description":"ترويسة اختيارية للنوافذ المنبثقة"},{"label":"محتوى منبثق","field":{"label":"عنصر المحتوى"}}]}},{"label":"تسمية نقاط الاتصال من اجل التقنيات المساعدة","default":"Hotspot #num","description":"سيساعد في الإعلان عن عنصر نقطة الاتصال التي تم الانتقال إليها إذا لم يكن هناك عنوان محدد لنقطة اتصال. #num يمكن استخدامه لاستبدال رقم نقطة الاتصال."},{"label":"تسمية زر الاغلاق من اجل التقنيات المساعدة","default":"Close"}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // bg.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'bg',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Фоново изображение","description":"Изображение, показвано като фон."},{"label":"Алтернативен текст за фоново изображение","description":"Ако браузърът не може да зареди изображението, вместо него ще се покаже този текст. Използва се и от помощни технологии като четци на екранен текст и др."},{"label":"Hotspot Icon","options":[{"label":"Predefined icon"},{"label":"Качено изображение"}]},{"label":"Predefined icon","description":"Използвайте predefined icon за hotspot.","options":[{"label":"Plus"},{"label":"Minus"},{"label":"Times"},{"label":"Check"},{"label":"Question"},{"label":"Info"},{"label":"Exclamation"}]},{"label":"Качено изображение","description":"Използвайте ваше изображение за hotspot icon.<br />75px by 75px е препоръчителен размер за изображението ви."},{"label":"Цвят на активна точка","description":"Цвят на активните точки"},{"entity":"активна точка","label":"Активни точки","field":{"label":"Актовна точка","fields":[{"label":"Позиция на активна точка","description":"Кликнете върху картинката, за да поставите активна точка"},{"label":"Покрийте цялото фоново изображение","description":"Когато потребителят кликне на активната точка, изскачащото меню ще покрие цялото фоново изображение"},{"label":"Хедър","description":"Незадължително заглавие за изскачащия прозорец"},{"label":"Изскачащо съдържание","field":{"label":"Елемент на съдържанието"}}]}},{"label":"Етикет на активната точка за помощните технологии","default":"Активна точка #num","description":"Ще ви помогне да обявите към кой елемент на активната точка да добавите навигация, ако не е посочено заглавие за точка за достъп. #num може да се използва за замяна на номера на гореща точка."},{"label":"Бутон за приключване за помощните технологии","default":"Приключи"}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // bs.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'bs',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Background image","description":"Image shown on background."},{"label":"Alternative text for background image","description":"If the browser can\'t load the image this text will be displayed instead. Also used by assistive technologies."},{"label":"Hotspot Icon","options":[{"label":"Predefined icon"},{"label":"Uploaded image"}]},{"label":"Predefined icon","description":"Using a predefined icon for the hotspot.","options":[{"label":"Plus"},{"label":"Minus"},{"label":"Times"},{"label":"Check"},{"label":"Question"},{"label":"Info"},{"label":"Exclamation"}]},{"label":"Uploaded image","description":"Use your own image for the hotspot icon.<br />75px by 75px is recommended for your image."},{"label":"Hotspot color","description":"The color of the hotspots"},{"entity":"hotspot","label":"Hotspots","field":{"label":"Hotspot","fields":[{"label":"Hotspot position","description":"Click on the thumbnail image to place the hotspot"},{"label":"Cover entire background image","description":"When the user clicks the hotspot the popup will cover the entire background image"},{"label":"Header","description":"Optional header for the popup"},{"label":"Popup content","field":{"label":"Content Item"}}]}},{"label":"Hotspot label for assistive technologies","default":"Hotspot #num","description":"Will help announce what hotspot element has been navigated to if there is no header specified for a hotspot. #num can be used to replace the hotspot number."},{"label":"Close button label for assistive technologies","default":"Close"}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // ca.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'ca',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Imatge de fons","description":"La imatge que es mostra al fons."},{"label":"Text alternatiu per a la imatge de fons","description":"Aquest text es mostrarà quan el navegador no pugui carregar la imatge. També és utilitzat per tecnologies d\'assistència."},{"label":"Icona de la zona sensible","options":[{"label":"Icona predefinida"},{"label":"Imatge carregada"}]},{"label":"Icona predefinida","description":"Ús d’una icona predefinida per a la zona sensible.","options":[{"label":"Més"},{"label":"Menys"},{"label":"Vegades"},{"label":"Comprova"},{"label":"Pregunta"},{"label":"Informació"},{"label":"Exclamació"}]},{"label":"Imatge carregada","description":"Empreu la vostra pròpia imatge per a la icona de la zona sensible. <br />La mida recomanada per a la vostra imatge és 75px per 75px."},{"label":"Color de la zona sensible","description":"Color de les zones sensibles"},{"entity":"zona sensible","label":"Zones sensibles","field":{"label":"Zona sensible","fields":[{"label":"Posició de la zona sensible","description":"Feu clic a la imatge en miniatura per situar-hi la zona sensible"},{"label":"Imatge que ocupa tot el fons","description":"Quan l’usuari fa clic a la zona sensible, l’element emergent cobreix tota la imatge de fons"},{"label":"Capçalera","description":"Capçalera opcional per a l’element emergent"},{"label":"Contingut emergent","field":{"label":"Element de contingut"}}]}},{"label":"Etiqueta de la zona sensible per a tecnologies d’assistència","default":"Zona sensible #num","description":"Ajudarà a indicar quin element de la zona sensible s’ha seleccionat si la zona sensible no té cap capçalera. El número de zona sensible es pot substituir per #num."},{"label":"Etiqueta del botó «Tanca» per a tecnologies d’assistència","default":"Tanca"}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // cs.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'cs',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Obrázek na pozadí","description":"Obrázek zobrazovaný na pozadí."},{"label":"Alternativní text pro obrázek na pozadí","description":"Pokud prohlížeč nemůže načíst obrázek, zobrazí se místo toho tento text. Používá se také asistenčními technologiemi."},{"label":"Ikona aktivního bodu","options":[{"label":"Předdefinovaná ikona"},{"label":"Nahraný obrázek"}]},{"label":"Předdefinovaná ikona","description":"Použití předdefinované ikony pro aktivní bod.","options":[{"label":"Plus"},{"label":"Mínus"},{"label":"Křížek"},{"label":"Zaškrtnutí"},{"label":"Otazník"},{"label":"Informace"},{"label":"Vykřičník"}]},{"label":"Nahraný obrázek","description":"Použijte svůj vlastní obrázek pro ikonu hotspotu. <br />Pro obrázek se doporučuje 75 x 75 pixelů."},{"label":"Barva aktivního bodu","description":"Barva aktivních bodů"},{"entity":"aktivní bod","label":"Aktivní body","field":{"label":"Aktivní bod","fields":[{"label":"Poloha aktivního bodu","description":"Kliknutím na obrázek miniatury umístíte aktivní bod"},{"label":"Překrýt celý obrázek na pozadí","description":"Když uživatel klepne na aktivní bod, vyskakovací okno překryje celý obrázek na pozadí"},{"label":"Záhlaví","description":"Volitelná hlavička pro vyskakovací okno"},{"label":"Obsah vyskakovacího okna","field":{"label":"Obsah položky"}}]}},{"label":"Popisek aktivního bodu pro podpůrné technologie","default":"Aktivní bod #num","description":"Pomůže oznámit, ke kterému prvku aktivního bodu byl navigován, pokud pro hotspot není zadána žádná záhlaví. #num lze použít k nahrazení čísla aktivního bodu."},{"label":"Popisek tlačítka zavřít pro podpůrné technologie","default":"Zavřít"}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // da.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'da',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Background image","description":"Image shown on background."},{"label":"Alternative text for background image","description":"If the browser can\'t load the image this text will be displayed instead. Also used by assistive technologies."},{"label":"Hotspot Icon","options":[{"label":"Predefined icon"},{"label":"Uploaded image"}]},{"label":"Predefined icon","description":"Using a predefined icon for the hotspot.","options":[{"label":"Plus"},{"label":"Minus"},{"label":"Times"},{"label":"Check"},{"label":"Question"},{"label":"Info"},{"label":"Exclamation"}]},{"label":"Uploaded image","description":"Use your own image for the hotspot icon.<br />75px by 75px is recommended for your image."},{"label":"Hotspot color","description":"The color of the hotspots"},{"entity":"hotspot","label":"Hotspots","field":{"label":"Hotspot","fields":[{"label":"Hotspot position","description":"Click on the thumbnail image to place the hotspot"},{"label":"Cover entire background image","description":"When the user clicks the hotspot the popup will cover the entire background image"},{"label":"Header","description":"Optional header for the popup"},{"label":"Popup content","field":{"label":"Content Item"}}]}},{"label":"Hotspot label for assistive technologies","default":"Hotspot #num","description":"Will help announce what hotspot element has been navigated to if there is no header specified for a hotspot. #num can be used to replace the hotspot number."},{"label":"Close button label for assistive technologies","default":"Luk"}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // de.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'de',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Hintergrundbild","description":"Bild, das im Hintergrund angezeigt wird."},{"label":"Alternativtext für das Hintergrundbild","description":"Wenn der Browser das Bild nicht anzeigen kann, wird dieser Text stattdessen angezeigt. Wird auch zur Barrierefreiheit genutzt."},{"label":"Hotspot-Symbol","options":[{"label":"Mitgeliefertes Symbol"},{"label":"Hochgeladenes Bild"}]},{"label":"Mitgeliefertes Symbol","description":"Wähle eines der mitgelieferten Symbole aus der Liste aus.","options":[{"label":"Plus"},{"label":"Minus"},{"label":"Malzeichen (X)"},{"label":"Haken"},{"label":"Fragezeichen"},{"label":"Infozeichen (i)"},{"label":"Ausrufezeichen"}]},{"label":"Hochgeladenes Bild","description":"Verwende ein eigenes Bild als Hotspot-Symbol.<br />Als Größe ist 75px mal 75px empfehlenswert."},{"label":"Hotspot-Farbe","description":"Die Farbe der Hotspots"},{"entity":"Hotspot","label":"Hotspots","field":{"label":"Hotspot","fields":[{"label":"Hotspot-Position","description":"Klicke auf die gewünschte Stelle im Vorschaubild, um den Hotspot zu platzieren."},{"label":"Überdecke das gesamte Hintergrundbild","description":"Sobald der Lernende auf den Hotspot klickt, wird das erscheinende Fenster den gesamten Hintergrund überdecken."},{"label":"Überschrift","description":"Optionale Überschrift für das Fenster"},{"label":"Fensterinhalt","field":{"label":"Inhaltsobjekt"}}]}},{"label":"Beschriftung von Hotpots für Vorlesewerkzeuge","default":"Hotspot Nummer #num","description":"Wenn ein Vorlesewerkzeug (Barrierefreiheit) verwendet wird, verdeutlicht dieser Text, zu welchem Hotspot navigiert wurde, sofern für einen Hotspot keine Überschrift existiert. #num kann als Platzhalter für die Nummer des Hotspots verwendet werden."},{"label":"Beschriftung des \"Schließen\"-Buttons (Barrierefreiheit)","default":"Schließen"}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // el.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'el',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Εικόνα φόντου","description":"Η εικόνα που θα εμφανίζεται στο φόντο."},{"label":"Εναλλακτικό κείμενο για εικόνα φόντου","description":"Αν ο φυλλομετρητής αδυνατεί να φορτώσει την εικόνα, θα εμφανιστεί αυτό το κείμενο. Χρησιμοποιείται επίσης από υποστηρικτικές τεχνολογίες."},{"label":"Εικονίδιο Hotspot","options":[{"label":"Προεπιλεγμένο εικονίδιο"},{"label":"Εικόνα που μεταφορτώθηκε"}]},{"label":"Προεπιλεγμένο εικονίδιο","description":"Χρησιμοποιήστε ένα προεπιλεγμένο εικονίδιο για το hotspot.","options":[{"label":"Συν"},{"label":"Μείον"},{"label":"Επί"},{"label":"Έλεγχος"},{"label":"Ερώτηση"},{"label":"Πληροφορία"},{"label":"Διευκρίνιση"}]},{"label":"Εικόνα που μεταφορτώθηκε","description":"Χρησιμοποιήστε τη δική σας εικόνα ως εικονίδιο του hotspot.<br />Προτεινόμενο μέγεθος εικόνας 75px επί 75px."},{"label":"Χρώμα Hotspot","description":"Το χρώμα των hotspots"},{"entity":"hotspot","label":"Hotspots","field":{"label":"Hotspot","fields":[{"label":"Θέση του Hotspot","description":"Κάντε κλικ στη μικρογραφία της εικόνας για να τοποθετήσετε το hotspot"},{"label":"Να καλύπτει ολόκληρη την εικόνα φόντου","description":"Όταν ο χρήστης κάνει κλικ πάνω στο hotspot, το αναδυόμενο περιεχόμενο θα καλύπτει ολόκληρη την εικόνα φόντου"},{"label":"Επικεφαλίδα","description":"Eπικεφαλίδα για το αναδυόμενο περιεχόμενο (προαιρετικό)"},{"label":"Αναδυόμενο περιεχόμενο","field":{"label":"Στοιχείο περιεχομένου"}}]}},{"label":"Ετικέτα hotspot για υποστηρικτικές τεχνολογίες","default":"Hotspot #num","description":"Διευκρινίζει το hotspot σε περίπτωση που δεν έχει προσδιοριστεί επικεφαλίδα. Το #num μπορεί να χρησιμοποιηθεί για να αντικαταστήσει τον αριθμό του hotspot."},{"label":"Ετικέτα κουμπιού κλεισίματος για υποστηρικτικές τεχνολογίες","default":"Κλείσιμο"}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // es-mx.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'es-mx',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Imagen de fondo","description":"Imagen mostrada en el fondo."},{"label":"Texto alternativo para imagen de fondo","description":"Si el navegador no puede cargar la imagen, este texto se mostrará en su lugar. También utilizado por las tecnologías de asistencia."},{"label":"Ícono de hotspot","options":[{"label":"Ícono predefinido"},{"label":"Imagen subida"}]},{"label":"Ícono predefinido","description":"Usando un ícono predefinido para el hotspot.","options":[{"label":"Más"},{"label":"Menos"},{"label":"Por"},{"label":"Comprobar"},{"label":"Pregunta"},{"label":"Información"},{"label":"Exclamación"}]},{"label":"Imagen subida","description":"Use su propia imagen para el ícono de hotspot.<br />75px por 75px es lo recomendado para su imagen."},{"label":"Color del hotspot","description":"El color de los hotspots"},{"entity":"punto caliente (hotspot)","label":"Puntos calientes (Hotspots)","field":{"label":"Punto caliente (Hotspot)","fields":[{"label":"Posición del Hotspot","description":"Haga clic en la imagen en miniatura para colocar el hotspot"},{"label":"Cubrir toda la imagen de fondo","description":"Cuando el usuario hace clic en el hotspot, la ventana emergente cubrirá toda la imagen de fondo"},{"label":"Encabezado","description":"Encabezado opcional para la ventana emergente"},{"label":"Contenido emergente","field":{"label":"Elemento del Contenido"}}]}},{"label":"Etiqueta de hotspot para tecnologías de asistencia","default":"Punto caliente (Hotspot) #num","description":"Ayudará a anunciar a qué elemento hotspot se ha navegado si no hay un encabezado especificado para un hotspot. #num se puede usar para reemplazar el número del hotspot."},{"label":"Etiqueta de cierre de botón para tecnologías de asistencia","default":"Cerrar"}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // es.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'es',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Imagen de fondo","description":"Imagen mostrada en el fondo."},{"label":"Texto alternativo para imagen de fondo","description":"Si el navegador no puede cargar la imagen, este texto se mostrará en su lugar. También utilizado por las tecnologías de asistencia."},{"label":"Ícono de hotspot","options":[{"label":"Ícono predefinido"},{"label":"Imagen subida"}]},{"label":"Ícono predefinido","description":"Usando un ícono predefinido para el hotspot.","options":[{"label":"Más"},{"label":"Menos"},{"label":"Por"},{"label":"Comprobar"},{"label":"Pregunta"},{"label":"Información"},{"label":"Exclamación"}]},{"label":"Imagen subida","description":"Use su propia imagen para el ícono de hotspot.<br />75px por 75px es lo recomendado para su imagen."},{"label":"Color del hotspot","description":"El color de los hotspots"},{"entity":"punto caliente (hotspot)","label":"Puntos calientes (Hotspots)","field":{"label":"Punto caliente (Hotspot)","fields":[{"label":"Posición del Hotspot","description":"Haga clic en la imagen en miniatura para colocar el hotspot"},{"label":"Cubrir toda la imagen de fondo","description":"Cuando el usuario hace clic en el hotspot, la ventana emergente cubrirá toda la imagen de fondo"},{"label":"Encabezado","description":"Encabezado opcional para la ventana emergente"},{"label":"Contenido emergente","field":{"label":"Elemento del Contenido"}}]}},{"label":"Etiqueta de hotspot para tecnologías de asistencia","default":"Punto caliente (Hotspot) #num","description":"Ayudará a anunciar a qué elemento hotspot se ha navegado si no hay un encabezado especificado para un hotspot. #num se puede usar para reemplazar el número del hotspot."},{"label":"Etiqueta de cierre de botón para tecnologías de asistencia","default":"Cerrar"}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // et.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'et',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Taustapilt","description":"Taustal näidatav pilt."},{"label":"Taustapildi alternatiivtekst","description":"Seda teksti näidatakse siis, kui brauser ei saa pilti laadida. Samuti kasutatakse teksti assisteeriva tehnoloogia puhul."},{"label":"Kuumkoha ikoon","options":[{"label":"Ettemääratletud ikoon"},{"label":"Üles laetud pilt"}]},{"label":"Ettemääratletud pilt","description":"Kuumkoha jaoks kasutatakse ettemääratletud pilti.","options":[{"label":"Pluss"},{"label":"Miinus"},{"label":"Ajad"},{"label":"Kontrolli"},{"label":"Küsimus"},{"label":"Info"},{"label":"Hüüumärk"}]},{"label":"Üles laetud pilt","description":"Kasuta kuumkoha ikooniks oma pilti.<br />Pildi suuruseks on soovitatud 75px korda 75px."},{"label":"Tulipunkti värv","description":"Tulipunktide värv"},{"entity":"tulipunkt","label":"Tulipunktid","field":{"label":"Tulipunkt","fields":[{"label":"Tulipunkti asukoht","description":"Tulipunkti asetamiseks kliki pisipildil"},{"label":"Kata kogu taustapilt","description":"Kui kasutaja klikib tulipunktil, siis katab hüpikaken kogu taustapildi"},{"label":"Päis","description":"Valikuline hüpikakna pealkiri"},{"label":"Hüpikakna sisu","field":{"label":"Sisuelement"}}]}},{"label":"Tulipunkti silt assisteeriva tehnoloogia jaoks","default":"Tulipunkt #num","description":"Aitab teavitada, missugusele tulipunkti elemendile on navigeeritud, kui tulipunktile ei ole määratud pealkirja. #num saab kasutada tulipunkti arvu asendamiseks."},{"label":"Sulge nupu silt assisteeriva tehnoloogia jaoks","default":"Sulge"}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // eu.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'eu',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Atzeko planoko irudia","description":"Atzeko planoan erakusten den irudia."},{"label":"Atzeko planoko irudiaren ordezko testua","description":"Nabigatzaileak ezin badu irudia kargatu testu hau haren ordez erakutsiko da. Laguntzako teknologiek ere erabiltzen dute."},{"label":"Leku-beroarentzako Ikonoa","options":[{"label":"Lehenetsitako ikonoa"},{"label":"Igotako irudia"}]},{"label":"Lehenetsitako ikonoa","description":"Leku-beroarentzako lehenetsitako ikonoa erabiltzen.","options":[{"label":"Gehitu"},{"label":"Kendu"},{"label":"Biderkatu"},{"label":"Egiaztatu"},{"label":"Galdera"},{"label":"Informazioa"},{"label":"Harridura"}]},{"label":"Igotako irudia","description":"Zure irudi bat erabili ezazu leku-beroaren ikonorako.<br />Zure irudirako 75px x 75px tamaina gomendatzen da."},{"label":"Leku beroen kolorea","description":"Leku beroek duten kolorea"},{"entity":"leku beroa","label":"Leku beroa","field":{"label":"Leku beroa","fields":[{"label":"Leku beroaren kokapena","description":"Egin klik miniaturan leku beroa kokatzeko"},{"label":"Atzeko planoko irudi osoa estaltzen du","description":"Erabiltzaileak leku beroan klik egiten duenean popupak atzeko planoko irudi guztia estaliko du"},{"label":"Goiburua","description":"Popuparen aukerako goiburua"},{"label":"Popuparen edukia","field":{"label":"Eduki-elementua"}}]}},{"label":"Leku beroaren etiketa laguntzako teknologietarako","default":"Leku beroa #num","description":"Lagunduko du adierazten zein leku beroan nabigatu dugun baldin eta bere popupak goibururik ez badu. #num erabili daiteke leku beroaren zenbakiaren ordez."},{"label":"Itxi botoiaren etiketa laguntzako teknologietarako","default":"Itxi"}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
         // fa.json
         DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'fa',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"تصویر پس‌زمینه","description":"تصویر نمایش داده شده در پس‌زمینه."},{"label":"متن جایگزین برای تصویر پس‌زمینه","description":"اگر مرورگر نتواند تصویر را لود کند،‌ این متن به جای آن نمایش داده خواهد شد. همچنین مورد استفاده تکنولوژی‌های کمکی قرار می‌گیرد."},{"label":"شمایل نقطه داغ","options":[{"label":"شمایل از پیش تعریف شده"},{"label":"تصویر بارگذاری شده"}]},{"label":"شمایل از پیش تعریف شده","description":"استفاده از یک شمایل از پیش تعریف شده برای نقطه داغ.","options":[{"label":"به علاوه"},{"label":"منها"},{"label":"ضرب در"},{"label":"بررسی"},{"label":"سؤال"},{"label":"اطلاعات"},{"label":"تعجب"}]},{"label":"تصویر بارگذاری شده","description":"از تصویر خودتان برای شمایل نقطه داغ استفاده کنید. <br /> 75px در 75px برای تصویرتان توصیه می‌شود."},{"label":"رنگ نقطه داغ","description":"رنگ نقطه داغ"},{"entity":"نقطه داغ","label":"نقطه داغ","field":{"label":"نقطه داغ","fields":[{"label":"مکان نقطه داغ","description":"برای تعیین مکان نقطه داغ روی تصویر بندانگشتی کلیک کنید"},{"label":"تمام تصویر پس‌زمینه را بپوشان","description":"وقتی کاربر روی نقطه داغ کلیک می‌کند، پنجره بازشو تمام تصویر پس‌زمینه را می‌پوشاند"},{"label":"سرصفحه","description":"سرصفحه اختیاری برای پنجره بازشونده"},{"label":"محتوای پنجره باز شونده","field":{"label":"نوع محتوا"}}]}},{"label":"برچسب نقطه داغ برای تکنولوژی‌های کمکی","default":"نقطه داغ #num","description":"اگر سرصفحه‌ای برای نقطه داغ مشخص نشده باشد، کمک می‌کند تا عنصر hotspot که پیمایش شده است اعلان شود. برای جایگزینی شماره نقطه داغ می‌توان از ‎#num استفاده کرد."},{"label":"برچسب دکمه بستن برای تکنولوژی‌های کمکی","default":"بستن"}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // fi.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'fi',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Taustakuva","description":"Taustakuva hotspoteille."},{"label":"Vaihtoehtoinen teksti taustakuvalle.","description":"Jos selain ei pysty lataamaan tätä kuvaa tämä teksti näytetään sen sijasta. Myös ruudunlukijat lukevat tämän tiedon."},{"label":"Hotspotin ikoni","options":[{"label":"Vakio kuvake"},{"label":"Lataa oma kuva"}]},{"label":"Valmisikoni","description":"Käyttää sovelluksen omia kuvakkeita.","options":[{"label":"Plus"},{"label":"Miinus"},{"label":"Kertolaskumerkki"},{"label":"Oikein-merkki"},{"label":"Kysymysmerkki"},{"label":"Info-merkki"},{"label":"Huutomerkki"}]},{"label":"Itseladattu kuva","description":"Käytä valitsemaasi kuvaa hotspot-ikonina.<br />75px kertaa 75px pikseliä on suositeltu koko"},{"label":"Hotspot-ikonin väri","description":"Hotspot-kuvakkeiden väri"},{"entity":"hotspot","label":"Hotspotit","field":{"label":"Hotspot","fields":[{"label":"Hotspotin sijainti","description":"Klikkaa pienoiskuvaa määrittääksesi hotspot-kuvakkeen sijainti"},{"label":"Peitä koko taustakuva","description":"Kun käyttäjä klikkaa hotspot-kuvaketta, avautuva pop up-ikkuna peittää koko taustakuvan."},{"label":"Otsikko","description":"Ponnahdusikkunan otsikko (ei pakollinen)"},{"label":"Ponnahdusikkunan sisältö","field":{"label":"Sisältövaihtoehto"}}]}},{"label":"Hotspot selite avustaville teknologioille","default":"Hotspot #num","description":"Auttaa esimerkiksi ruudunlukijasovellusta kertomaan mihin hotspot-ikoniin on navigoitu, jos sille ei ole erikseen määritetty otsikkoa joka luettaisiin ääneen. #num muuttujaa/tekstiä voidaan käyttää korvaamaan hotspot-kuvakkeen numero."},{"label":"Sulje-painikkeen selite avustaville teknologioille","default":"Sulje"}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // fr.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'fr',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Image d\'arrière-plan","description":"Image affichée en arrière-plan."},{"label":"Texte alternatif pour l\'image d\'arrière-plan","description":"Si le navigateur ne peut pas charger l\'image, ce texte sera affiché à la place. Également utilisé par les technologies d\'accessibilité."},{"label":"Hotspot Icon","options":[{"label":"Predefined icon"},{"label":"Uploaded image"}]},{"label":"Predefined icon","description":"Using a predefined icon for the hotspot.","options":[{"label":"Plus"},{"label":"Minus"},{"label":"Times"},{"label":"Check"},{"label":"Question"},{"label":"Info"},{"label":"Exclamation"}]},{"label":"Uploaded image","description":"Use your own image for the hotspot icon.<br />75px by 75px is recommended for your image."},{"label":"Couleur de la puce cliquable","description":"Couleur des puces cliquables (en code hexadécimal)."},{"entity":"Puces cliquables","label":"Puce cliquable","field":{"label":"Puce cliquable","fields":[{"label":"Position de la puce cliquable","description":"Cliquez sur la miniature pour positionner la puce cliquable."},{"label":"Recouvrir toute l\'image d\'arrière-plan","description":"Quand l\'utilisateur cliquera sur la puce cliquable, le popup recouvrira toute l\'image d\'arrière-plan."},{"label":"Titre","description":"Titre facultatif du popup."},{"label":"Contenu du popup","field":{"label":"Type de contenu"}}]}},{"label":"Intitulé de la puce cliquable pour les technologies d\'accessibilité","default":"Puce #num","description":"Permet d\'annoncer à quelle puce l\'utilisateur est parvenu à naviguer s\'il n\'y a pas de titre spécifié pour cette puce. #num peut être utilisé pour remplacer le numéro de la puce."},{"label":"Intitulé du bouton \"Fermer\" pour les technologies d\'accessibilité","default":"Fermer"}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // gl.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'gl',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Imaxe de fondo","description":"Imaxe amosada como fondo."},{"label":"Texto alternativo para a imaxe de fondo","description":"Amosarase este texto se o navegador non pode amosar a imaxe. Usado tamén polas tecnoloxías de asistencia."},{"label":"Icona de punto chave","options":[{"label":"Icona predefinida"},{"label":"Imaxe subida"}]},{"label":"Icona predefinida","description":"Usando unha icona predefinida para o punto chave.","options":[{"label":"Máis"},{"label":"Menos"},{"label":"Veces"},{"label":"Comprobar"},{"label":"Pregunta"},{"label":"Información"},{"label":"Exclamación"}]},{"label":"Imaxe subida","description":"Usa a túa propia imaxe como icona de punto chave.<br />Recoméndase un tamaño de 75px por 75px para a imaxe."},{"label":"Cor do punto chave","description":"A cor dos puntos chave"},{"entity":"punto chave","label":"Puntos chave","field":{"label":"Punto chave","fields":[{"label":"Posición do punto chave","description":"Preme na imaxe en miniatura para colocar o punto chave"},{"label":"Cubrir toda a imaxe de fondo","description":"Cando o usuario preme no punto chave, a pantalla emerxente cubrirá toda a imaxe de fondo"},{"label":"Cabeceira","description":"Cabeceira opcional para a pantalla emerxente"},{"label":"Contido da pantalla emerxente","field":{"label":"Elemento de contido"}}]}},{"label":"Etiqueta de punto chave para tecnoloxías de asistencia","default":"Punto chave #num","description":"Axudará a anunciar a que elemento se navegou se non se especificou unha cabeceira para ese punto chave. Pódese usar #num para substituir o número do punto chave."},{"label":"Etiqueta do botón pechar para tecnoloxías de asistencia","default":"Pechar"}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // he.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'he',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"תמונת רקע","description":"תמונה מוצגת ברקע."},{"label":"טקסט חלופי עבור תמונת רקע","description":"אם הדפדפן לא יכול להעלות את התמונה טקסט זה יוצג במקום. בשימוש גם על ידי טכנולוגיות מסייעות."},{"label":"צלמית נקודה־חמה","options":[{"label":"צלמית מוגדרת מראש"},{"label":"תמונה שהועלתה"}]},{"label":"צלמית מוגדרת מראש","description":"שימוש בצלמית מוגדרת מראש עבור הנקודה-החמה.","options":[{"label":"ועוד"},{"label":"מינוס"},{"label":"כפול"},{"label":"בדיקה"},{"label":"שאלה"},{"label":"מידע"},{"label":"סימן קריאה"}]},{"label":"התמונה שעלתה","description":"השתמשו בתמונה משלכם עבור הסמל של הנקודה-החמה.<br /> גודל התמונה המומלץ הוא 75px על 75px."},{"label":"צבע הנקודה־החמה","description":"צבע הנקודות־החמות"},{"entity":"נקודה־חמה","label":"נקודות־חמות","field":{"label":"נקודה־חמה","fields":[{"label":"מיקום נקודה־חמה","description":"לחצו על התמונה הממוזערת כדי למקם את הנקודה-החמה"},{"label":"יש לכסות את כל תמונת הרקע","description":"כאשר המשתמש לוחץ על הנקודה-החמה החלון הקופץ יכסה את כל תמונת הרקע"},{"label":"כותרת","description":"כותרת אפשרית עבור החלון הקופץ"},{"label":"תוכן החלון הקופץ","field":{"label":"תוכן הפריט"}}]}},{"label":"תווית נקודה-חמה עבור טכנולוגיות מסייעות","default":"נקודה־חמה #num","description":"יעזור להודיע לאיזה רכיב של הנקודה-החמה נווט אם לא הוגדרה כותרת לנקודה-חמה. ניתן להשתמש ב- #num כדי להחליף את מספר הנקודה-החמה."},{"label":"תווית כפתור סגירה עבור טכנולוגיות מסייעות","default":"סגירה"}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // hsb.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'hsb',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"medije","fields":[{"label":"typ","description":"Móžne medije, kiž so nad prašenjom pokazaja."},{"label":"zoomowanje do wobraza móžne njeje"}]},{"label":"wopisowanje nadawka","default":"zapisaj falowace słowa","description":"Wujasnjenje, kak ma so nadawk rozrisać."},{"label":"tekst","entity":"tekst","field":{"label":"tekstowa linka","placeholder":"Oslo je hłowne město *Norwegskeje*.","important":{"description":"<ul><li>Prózdnoty woznamjenjeja so z hwěžku (*) před a po jednotliwym słowje.</li><li>Alternatiwne wotmołwy dźěla so z nakósnej smužku (/).</li><li>Z dwojodypkom (:) před pokiwom móžeš pokiw k tekstej zapodać.</li></ul>","example":"H5P wobsahi hodźa so z *browserom/web-browserom:Něšto, štož wšědnje wužiwaš* wobdźěłać."}}},{"label":"powšitkowny feedback","fields":[{"widgets":[{"label":"standard"}],"label":"postaj swójski feedback za kóždu wolenu ličbu dypkow","description":"Stłóč na tłóčatko \"Dodaj wobłuk\", zo by telko wobłukow dodał/a, kaž trjebaš. Přikład: 0-20% Špatny wuslědk, 21-91% Přerězny wuslědk, 91-100% Wuběrnje!","entity":"wobłuk","field":{"fields":[{"label":"wobłuk dypkow"},{},{"label":"feedback za wěsty wobłuk dypkow","placeholder":"zapisaj feedback"}]}}]},{"label":"tekst za tłóčatko \"pokaž mi rozrisanje\"","default":"pokaž mi rozrisanje"},{"label":"tekst za tłóčatko \"hišće raz\"","default":"hišće raz"},{"label":"tekst za tłóčatko \"přepruwuj\"","default":"přepruwuj"},{"label":"tekst za tłóčatko \"wotpósćel\"","default":"wotpósćel"},{"label":"tekst za powěsć \"njeje wupjelnjene\"","default":"wupjelń prošu wšitke prózdnoty, zo by rozrisanje widźał/a"},{"label":"tekst za powěsć \":ans\' je prawa\"","default":"\':ans\' je prawa"},{"label":"tekst za powěsć \"\':ans\' je wopak\"","default":"\':ans\' je wopak"},{"label":"tekst za powěsć \"prawa wotmołwa\"","default":"prawa wotmołwa"},{"label":"tekst za powěsć \"wopačna wotmołwa\"","default":"wopačna wotmołwa"},{"label":"etiket za podpěracu technologiju při rozrisanju","default":"prawa wotmołwa:"},{"label":"etiket za podpěracu technologiju při zapodaću","description":"Wužiwaj @num a @total, zo by aktualnu ličbu prózdnotow a cyłkownu ličbu prózdnotow změnił/a","default":"prózdne zapodaće @num wot @total"},{"label":"etiket za podpěracu technologiju, kiž pokazuje na pokiw","default":"pokiw je móžny"},{"label":"etiket za symbol \"pokiw\"","default":"pokiw"},{"label":"Zadźerženske mustry.","description":"Z tutymi opcijemi móžeš postajić, kak so nadawk zadźerži.","fields":[{"label":"Zapni \"hišće raz\""},{"label":"zapni tłóčatko \"pokaž mi rozrisanje\""},{"label":"zapni tłóčatko \"přepruwuj\""},{"label":"přepruwuj wotmołwy awtomatisce po zapodaću"},{"label":"dźiwaj na wulke spočatne pismiki","description":"Zapodate słowo dyrbi dokładnje z wotmołwu identiske być."},{"label":"wšitke prózdnoty dyrbja wupjelnjene być, zo by rozrisanje widźeć było"},{"label":"sadź pola za zapisanje teksta na nowe linki"},{"label":"pokaž přepruwowanski dialog, hdyž stłóčiš na \"přepruwuj\"","description":"Tuta móžnosć njeje kompatibelna z móžnosću \"přepruwuj wotmołwy awtomatisce po zapodaću\""},{"label":"pokaž přepruwowanski dialog, hdyž stłóčiš na \"hišće raz\""},{"label":"akceptuj snadne prawopisne zmylki","description":"Hdyž je tole aktiwizowane, akceptuja so tež mjeńše prawopisne zmylki (3-9 znamješkow: 1 prawopisny zmylk, wjace hač 9 znamješkow: 2 prawopisnej zmylkaj)"}]},{"label":"pruwuj přepruwowanski dialog","fields":[{"label":"tekst za nadpismo","default":"Zakónčić?"},{"label":"tekst","default":"Chceš woprawdźe zakónčić?"},{"label":"wopisanje tłóčatka přetorhnyć","default":"přetorhnyć"},{"label":"wopisanje tłóčatka wopodstatnić","default":"zakónčić"}]},{"label":"wospjetuj wopodstatnjenski dialog","fields":[{"label":"tekst nadpisma","default":"Hišće raz?"},{"label":"tekst","default":"Chceš woprawdźe hišće raz spytać?"},{"label":"wopisanje tłóčatka přetorhnyć","default":"přetorhnyć"},{"label":"wopisanje tłóčatka wopodstatnić","default":"wopodstatnić"}]},{"label":"předstajenje dypkow za někoho, kiž wužiwa předčitansku funkciju","default":"Twoje dypki su :num wot :total"},{"label":"wopisanje podpěraceje technologije za tłóčatko \"přepruwuj\"","default":"Přepruwuj wotmołwy. Zapodate słowa hódnoća so jako prawe, wopak abo njewotmołwjene."},{"label":"wopisanje podpěraceje technologije za tłóčatko \"pokaž mi rozrisanje\"","default":"Pokaž mi rozrisanje. Nadawk markěruje so ze swojim prawym rozrisanjom."},{"label":"wopisanje podpěraceje technologije za tłóčatko \"hišće raz\"","default":"Spytaj hišće raz. Wšitke wotmołwy so wušmórnu a nadawk so hišće raz wotnowa započina."},{"label":"wopisanje podpěraceje technologije za započinanje nadawka","default":"kontrolny modus"}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // hu.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'hu',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Background image","description":"Image shown on background."},{"label":"Alternative text for background image","description":"If the browser can\'t load the image this text will be displayed instead. Also used by assistive technologies."},{"label":"Hotspot Icon","options":[{"label":"Predefined icon"},{"label":"Uploaded image"}]},{"label":"Predefined icon","description":"Using a predefined icon for the hotspot.","options":[{"label":"Plus"},{"label":"Minus"},{"label":"Times"},{"label":"Check"},{"label":"Question"},{"label":"Info"},{"label":"Exclamation"}]},{"label":"Uploaded image","description":"Use your own image for the hotspot icon.<br />75px by 75px is recommended for your image."},{"label":"Hotspot color","description":"The color of the hotspots"},{"entity":"hotspot","label":"Hotspots","field":{"label":"Hotspot","fields":[{"label":"Hotspot position","description":"Click on the thumbnail image to place the hotspot"},{"label":"Cover entire background image","description":"When the user clicks the hotspot the popup will cover the entire background image"},{"label":"Header","description":"Optional header for the popup"},{"label":"Popup content","field":{"label":"Content Item"}}]}},{"label":"Hotspot label for assistive technologies","default":"Hotspot #num","description":"Will help announce what hotspot element has been navigated to if there is no header specified for a hotspot. #num can be used to replace the hotspot number."},{"label":"Close button label for assistive technologies","default":"Close"}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // it.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'it',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Immagine di sfondo","description":"Immagine impostata come sfondo"},{"label":"Testo alternativo per l\'immagine di sfondo","description":"Se il browser non riesce a caricarla, al posto dell\'immagine sarà mostrato questo testo (usato anche per tecnologie assistive)"},{"label":"Icona dell\'hotspot","options":[{"label":"Icona predefinita"},{"label":"Immagine caricata"}]},{"label":"Icona predefinita","description":"Usare un\'icona predefinita per l\'hotspot","options":[{"label":"Più"},{"label":"Meno"},{"label":"Volte"},{"label":"Verifica"},{"label":"Domanda"},{"label":"Info"},{"label":"Esclamativo"}]},{"label":"Immagine caricata","description":"Usa una tua immagine come icona dell\'hotspot.<br />La dimensione raccomandata è 75px per 75px"},{"label":"Colore dell\'hotspot","description":"Colore degli hotspot"},{"entity":"hotspot","label":"Hotspot","field":{"label":"Hotspot","fields":[{"label":"Posizione dell\'hotspot","description":"Clicca sulla miniatura dell\'immagine per posizionare l\'hotspot"},{"label":"Copri l\'intera immagine di sfondo","description":"Quando l\'apprendente clicca sull\'hotspot il popup ricoprirà l\'intera immagine di sfondo"},{"label":"Intestazione","description":"Intestazione facoltativa per il popup"},{"label":"Contenuto del popup","field":{"label":"Item del contenuto"}}]}},{"label":"Etichetta dell\'hotspot per tecnologie assistive","default":"Hotspot #num","description":"Aiuterà a evidenziare quale elemento dell\'hotspot è stato navigato se non ne è stata specificata alcuna intestazione. #num può essere utilizzato per sostituire il numero di hotspot"},{"label":"Etichetta del pulsante di chiusura per tecnologie assistive","default":"Chiudi"}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // ja.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'ja',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"背景画像","description":"背景に表示される画像。"},{"label":"Alternative text for background image","description":"If the browser can\'t load the image this text will be displayed instead. Also used by assistive technologies."},{"label":"Hotspot Icon","options":[{"label":"Predefined icon"},{"label":"Uploaded image"}]},{"label":"Predefined icon","description":"Using a predefined icon for the hotspot.","options":[{"label":"Plus"},{"label":"Minus"},{"label":"Times"},{"label":"Check"},{"label":"Question"},{"label":"Info"},{"label":"Exclamation"}]},{"label":"Uploaded image","description":"Use your own image for the hotspot icon.<br />75px by 75px is recommended for your image."},{"label":"Hotspot color","description":"The color of the hotspots"},{"entity":"hotspot","label":"Hotspots","field":{"label":"Hotspot","fields":[{"label":"Hotspot position","description":"Click on the thumbnail image to place the hotspot"},{"label":"Cover entire background image","description":"When the user clicks the hotspot the popup will cover the entire background image"},{"label":"Header","description":"Optional header for the popup"},{"label":"Popup content","field":{"label":"Content Item"}}]}},{"label":"Hotspot label for assistive technologies","default":"Hotspot #num","description":"Will help announce what hotspot element has been navigated to if there is no header specified for a hotspot. #num can be used to replace the hotspot number."},{"label":"Close button label for assistive technologies","default":"Close"}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // km.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'km',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Background image","description":"Image shown on background."},{"label":"Alternative text for background image","description":"If the browser can\'t load the image this text will be displayed instead. Also used by assistive technologies."},{"label":"Hotspot Icon","options":[{"label":"Predefined icon"},{"label":"Uploaded image"}]},{"label":"Predefined icon","description":"Using a predefined icon for the hotspot.","options":[{"label":"Plus"},{"label":"Minus"},{"label":"Times"},{"label":"Check"},{"label":"Question"},{"label":"Info"},{"label":"Exclamation"}]},{"label":"Uploaded image","description":"Use your own image for the hotspot icon.<br />75px by 75px is recommended for your image."},{"label":"Hotspot color","description":"The color of the hotspots"},{"entity":"hotspot","label":"Hotspots","field":{"label":"Hotspot","fields":[{"label":"Hotspot position","description":"Click on the thumbnail image to place the hotspot"},{"label":"Cover entire background image","description":"When the user clicks the hotspot the popup will cover the entire background image"},{"label":"Header","description":"Optional header for the popup"},{"label":"Popup content","field":{"label":"Content Item"}}]}},{"label":"Hotspot label for assistive technologies","default":"ចំណុចដៅ #num","description":"Will help announce what hotspot element has been navigated to if there is no header specified for a hotspot. #num can be used to replace the hotspot number."},{"label":"Close button label for assistive technologies","default":"បិទ"}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // ko.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'ko',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"배경 이미지","description":"배경에 나타나는 이미지"},{"label":"배경 이미지에 대한 대체 텍스트","description":"브라우저가 이미지를 로드할 수 없는 경우 이 텍스트가 대신 표시될 것이다. 보조 기술에도 사용됨."},{"label":"핫스팟 아이콘","options":[{"label":"미리 정의된 아이콘"},{"label":"이미지 업로드"}]},{"label":"미리 정의된 아이콘","description":"핫스팟에 미리 정의된 아이콘 사용하기","options":[{"label":"플러스"},{"label":"마이너스"},{"label":"곱하기"},{"label":"확인"},{"label":"질문"},{"label":"정보"},{"label":"느낌표"}]},{"label":"이미지 업로드","description":"핫스팟 아이콘에 사용자 자신의 이미지를 사용하십시오.<br />이미지 사이즈는 7픽셀x7픽셀로 추천합니다."},{"label":"핫스팟 색상","description":"핫스팟 색상"},{"entity":"핫스팟","label":"핫스팟","field":{"label":"핫스팟","fields":[{"label":"핫스팟 위치","description":"썸네일 이미지를 클릭하여 핫스팟을 배치"},{"label":"전체 배경 이미지 덮기","description":"사용자가 핫스팟을 클릭하면 팝업이 전체 배경 이미지를 덮습니다."},{"label":"머릿말","description":"(선택사항)팝업에 대한 머릿말"},{"label":"팝업 콘텐츠","field":{"label":"콘텐츠 항목"}}]}},{"label":"보조기술의 핫스팟 라벨","default":"핫스팟 #num","description":"핫스팟에 대해 지정된 헤더가 없을 경우 어떤 핫스팟 요소가 탐색되었는지 알려주는 데 도움이 될 것이다. #num 이 핫스팟 번호를 대체하여 사용될 수 있음"},{"label":"닫기 버튼에 대한 보조기술 라벨","default":"닫기"}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // lv.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'lv',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Fona attēls","description":"Fonā rādāmais attēls."},{"label":"Teksta alternatīva fona attēlam","description":"Ja pārlūkprogramma nevar ielādēt attēlu, tiks atspoguļots šis teksts. Tekstu izmanto arī asistīvās tehnoloģijas."},{"options":[{"label":"Sākotnējā ikona"},{"label":"Augšupielādēts attēls"}],"label":"Aktīvā punkta ikona"},{"label":"Sākotnējā ikona","options":[{"label":"Pluszīme"},{"label":"Mīnuss"},{"label":"Reizes"},{"label":"Pārbaudīt"},{"label":"Jautājums"},{"label":"Info"},{"label":"Izsaukuma zīme"}],"description":"Izmanto sākotnējo aktīvā punkta ikonu."},{"label":"Augšupielādēts attēls","description":"Aktīvā punkta ikonai izmantojiet savu attēlu.<br />Rekomendētais attēla izmērs ir 75px uz 75px."},{"description":"Aktīvo punktu krāsas","label":"Aktīvā punkta krāsa"},{"field":{"fields":[{"label":"Aktīvā punkta pozīcija","description":"Klikšķiniet uz sīktēla, lai ievietotu aktīvo punktu"},{"label":"Pārklāt visu fona attēlu","description":"Kad lietotājs klikšķina aktīvo punktu, iznirstošais logs nosegs visu fona attēlu"},{"label":"Galvene","description":"Neobligāta iznirstošā loga galvene"},{"label":"Iznirstošā loga saturs","field":{"label":"Satura elements"}}],"label":"Aktīvais punkts"},"entity":"aktīvais punkts","label":"Aktīvie punkti"},{"label":"Aktīvā punkta etiķete asistīvajām tehnoloģijām","default":"Aktīvais punkts #num","description":"Palīdzēs paziņot kurā aktīvajā punktā nonākts, ja aktīvajam punktam nav galvenes. #num var tikt izmantots, lai aizvietotu aktīvā punkta numuru."},{"label":"Aizvēršanas pogas etiķete asistīvajām tehnoloģijām","default":"Aizvērt"}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // nb.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'nb',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Bakgrunnsbilde","description":"Bilde brukt som bakgrunn"},{"label":"Alternative text for background image","description":"If the browser can\'t load the image this text will be displayed instead. Also used by assistive technologies."},{"label":"Hotspot Icon","options":[{"label":"Predefined icon"},{"label":"Uploaded image"}]},{"label":"Predefined icon","description":"Using a predefined icon for the hotspot.","options":[{"label":"Plus"},{"label":"Minus"},{"label":"Times"},{"label":"Check"},{"label":"Question"},{"label":"Info"},{"label":"Exclamation"}]},{"label":"Uploaded image","description":"Use your own image for the hotspot icon.<br />75px by 75px is recommended for your image."},{"label":"Hotspot color","description":"The color of the hotspots"},{"entity":"hotspot","label":"Hotspots","field":{"label":"Hotspot","fields":[{"label":"Hotspot position","description":"Click on the thumbnail image to place the hotspot"},{"label":"Cover entire background image","description":"When the user clicks the hotspot the popup will cover the entire background image"},{"label":"Header","description":"Optional header for the popup"},{"label":"Popup content","field":{"label":"Content Item"}}]}},{"label":"Hotspot label for assistive technologies","default":"Hotspot #num","description":"Will help announce what hotspot element has been navigated to if there is no header specified for a hotspot. #num can be used to replace the hotspot number."},{"label":"Close button label for assistive technologies","default":"Close"}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // nl.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'nl',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Achtergrondafbeelding","description":"Afbeelding die op de achtergrond wordt getoond."},{"label":"Alternatieve tekst voor achtergrondafbeelding","description":"Als de browser de afbeelding niet kan laden, wordt deze tekst getoond. Ook gebruikt door ondersteunende technologieën."},{"label":"Hotspot pictogram","options":[{"label":"Vooringesteld pictogram"},{"label":"Geüploade afbeelding"}]},{"label":"Vooringesteld pictogram","description":"Gebruik een vooringesteld pictogram voor de hotspot.","options":[{"label":"Plus"},{"label":"Min"},{"label":"Keer"},{"label":"Vink"},{"label":"Vraagteken"},{"label":"Info"},{"label":"Uitroepteken"}]},{"label":"Geüploade afbeelding","description":"Gebruik uw eigen afbeelding voor de hotspot pictogram.<br />75px bij 75px is de aanbevolen afmeting voor uw afbeelding."},{"label":"Hotspot kleur","description":"De kleur van de hotspots"},{"entity":"hotspot","label":"Hotspots","field":{"label":"Hotspot","fields":[{"label":"Hotspot positie","description":"Klik op de miniatuurafbeelding om de hotspot te plaatsen"},{"label":"Gehele achtergrondafbeelding afdekken","description":"Wanneer de gebruiker op de hotspot klikt, zal de pop-up de gehele achtergrondafbeelding afdekken"},{"label":"Koptekst","description":"Optionele koptekst voor de pop-up"},{"label":"Pop-up inhoud","field":{"label":"Inhoud item"}}]}},{"label":"Hotspot label voor ondersteunende technologieën","default":"Hotspot #num","description":"Kan vertellen naar welk hotspot element er is genavigeerd als er geen koptekst is opgegeven voor de hotspot. #num kan worden gebruikt als vervanging voor het hotspot nummer."},{"label":"Label voor \"Afsluiten\"-knop voor ondersteunende technologieën","default":"Afsluiten"}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // nn.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'nn',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Background image","description":"Image shown on background."},{"label":"Alternative text for background image","description":"If the browser can\'t load the image this text will be displayed instead. Also used by assistive technologies."},{"label":"Hotspot Icon","options":[{"label":"Predefined icon"},{"label":"Uploaded image"}]},{"label":"Predefined icon","description":"Using a predefined icon for the hotspot.","options":[{"label":"Plus"},{"label":"Minus"},{"label":"Times"},{"label":"Check"},{"label":"Question"},{"label":"Info"},{"label":"Exclamation"}]},{"label":"Uploaded image","description":"Use your own image for the hotspot icon.<br />75px by 75px is recommended for your image."},{"label":"Hotspot color","description":"The color of the hotspots"},{"entity":"hotspot","label":"Hotspots","field":{"label":"Hotspot","fields":[{"label":"Hotspot position","description":"Click on the thumbnail image to place the hotspot"},{"label":"Cover entire background image","description":"When the user clicks the hotspot the popup will cover the entire background image"},{"label":"Header","description":"Optional header for the popup"},{"label":"Popup content","field":{"label":"Content Item"}}]}},{"label":"Hotspot label for assistive technologies","default":"Hotspot #num","description":"Will help announce what hotspot element has been navigated to if there is no header specified for a hotspot. #num can be used to replace the hotspot number."},{"label":"Close button label for assistive technologies","default":"Lukk"}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // pl.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'pl',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Zdjęcie w tle","description":"Zdjęcie, które jest pokazywane w tle"},{"label":"Tekst alternatywny dla zdjęcia w tle","description":"Gdy przeglądarka nie będzie mogła załadować zdjęcia, ten tekst zostanie wyświetlony zamiast niego."},{"label":"Ikona znacznika","options":[{"label":"Dostępne ikony"},{"label":"Wgrane zdjęcie"}]},{"label":"Dostępne ikony","description":"Użyj jednej z dostępnych ikon dla znacznika.","options":[{"label":"Plus"},{"label":"Minus"},{"label":"Czas"},{"label":"Potwierdzenie"},{"label":"Znak zapytania"},{"label":"Informacje"},{"label":"Wykrzynik"}]},{"label":"Wgrane zdjęcie","description":"Użyj własnego obrazu jako ikony znacznika. <br /> Zalecane jest zdjęcie w rozdzielczości 75 na 75 pikseli."},{"label":"Kolor znacznika","description":"Kolor, który będą mieć wszystkie znaczniki"},{"entity":"znacznik","label":"Znaczniki","field":{"label":"Znacznik","fields":[{"label":"Pozycja znacznika","description":"Kliknij na zdjęcie aby dodać znacznik"},{"label":"Pokryj całe zdjecie w tle","description":"Kiedy użytkownik kliknie na znacznik, popup pokryje całe zdjęcie w tle."},{"label":"Nagłówek","description":"Opcjonalny nagłówek dla popupu"},{"label":"Zawartość popupu.","field":{"label":"Zawartość"}}]}},{"label":"Etykieta znacznika dla technologii wspomagających niewidomych","default":"Znacznik #num","description":"Pomoże ogłosić, do którego znacznika przeszedł użytkownik oraz, który popup został właśnie otwarty"},{"label":"Etykieta przycisku dla technologii wspomagających niewidomych","default":"Zamknij"}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // pt-br.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'pt-br',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Imagem de fundo","description":"Imagem exibida no fundo."},{"label":"Texto alternativo para a imagem de fundo","description":"Se o navegador não puder carregar a imagem, este texto será exibido no lugar. Também utilizado por tecnologias assistivas."},{"label":"Ícone de ponto de acesso","options":[{"label":"Ícone pré-definido"},{"label":"Imagem carregada"}]},{"label":"Ícone pré-definido","description":"Usando um ícone pré-definido para o ponto de acesso.","options":[{"label":"Mais"},{"label":"Menos"},{"label":"Vezes"},{"label":"Sinal de visto"},{"label":"Questão"},{"label":"Informação"},{"label":"Exclamação"}]},{"label":"Imagem carregada","description":"Use a sua própria imagem para o ícone do ponto de acesso.<br />75px por 75px é o tamanho recomendado para a imagem."},{"label":"Cor do ponto de acesso","description":"A cor dos pontos de acessos"},{"entity":"hotspot","label":"Pontos de acesso","field":{"label":"Ponto de acesso","fields":[{"label":"Posição do ponto de acesso","description":"Clique na miniatura da imagem para posicionar o ponto de acesso"},{"label":"Cobrir a imagem de fundo inteira","description":"Quando o usuário clicar no ponto de acesso, o popup cobrirá toda a imagem de fundo"},{"label":"Cabeçalho","description":"Cabeçalho opcional para o popup"},{"label":"Conteúdo do popup","field":{"label":"Item do conteúdo"}}]}},{"label":"Rótulo para pontos de acesso para tecnologias assistivas","default":"Ponto de acesso #num","description":"Ajudará a anunciar qual elemento de ponto de acesso está sendo navegado ou se não há um cabeçalho especificado para um ponto de acesso. #num pode ser usado para susbtituir o número do ponto de acesso."},{"label":"Rótulo do botão de Fechar para tecnologias assistivas","default":"Fechar"}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // pt.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'pt',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Imagem de fundo","description":"Imagem a mostrar no fundo."},{"label":"Texto alternativo para imagem de fundo","description":"Se não foi possível ao browser carregar a imagem, este texto vai ser mostrado em seu lugar. Também é utilizado em tecnologias de apoio."},{"label":"Hotspot Icon","options":[{"label":"Predefined icon"},{"label":"Uploaded image"}]},{"label":"Predefined icon","description":"Using a predefined icon for the hotspot.","options":[{"label":"Plus"},{"label":"Minus"},{"label":"Times"},{"label":"Check"},{"label":"Question"},{"label":"Info"},{"label":"Exclamation"}]},{"label":"Uploaded image","description":"Use your own image for the hotspot icon.<br />75px by 75px is recommended for your image."},{"label":"Cor do hotspot","description":"A cor dos hotspots"},{"entity":"hotspot","label":"Hotspots","field":{"label":"Hotspot","fields":[{"label":"Posição do hotspot","description":"Clique na miniatura da imagem para definir o hotspot"},{"label":"Cobrir a imagem de fundo","description":"Quando o utilizador clicar no hotspot a janela popup vai cobrir toda a imagem de fundo"},{"label":"Cabeçalho","description":"Cabeçalho opcional para a janela popup"},{"label":"Conteúdo da janela popup","field":{"label":"Item de Conteúdo"}}]}},{"label":"Hotspot para tecnologias de apoio","default":"Hotspot #num","description":"Esta etiqueta vai ajudar o leitor de ecrã a anunciar o elemento do hotspot se não existir informação específica. #num pode ser utilizado para substituir o número do hotspot."},{"label":"Etiqueta do botão fechar para tecnologias de apoio","default":"Fechar"}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // ro.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'ro',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Background image","description":"Image shown on background."},{"label":"Alternative text for background image","description":"If the browser can\'t load the image this text will be displayed instead. Also used by assistive technologies."},{"label":"Hotspot Icon","options":[{"label":"Predefined icon"},{"label":"Uploaded image"}]},{"label":"Predefined icon","description":"Using a predefined icon for the hotspot.","options":[{"label":"Plus"},{"label":"Minus"},{"label":"Times"},{"label":"Check"},{"label":"Question"},{"label":"Info"},{"label":"Exclamation"}]},{"label":"Uploaded image","description":"Use your own image for the hotspot icon.<br />75px by 75px is recommended for your image."},{"label":"Hotspot color","description":"The color of the hotspots"},{"entity":"hotspot","label":"Hotspots","field":{"label":"Hotspot","fields":[{"label":"Hotspot position","description":"Click on the thumbnail image to place the hotspot"},{"label":"Cover entire background image","description":"When the user clicks the hotspot the popup will cover the entire background image"},{"label":"Header","description":"Optional header for the popup"},{"label":"Popup content","field":{"label":"Content Item"}}]}},{"label":"Hotspot label for assistive technologies","default":"Hotspot #num","description":"Will help announce what hotspot element has been navigated to if there is no header specified for a hotspot. #num can be used to replace the hotspot number."},{"label":"Close button label for assistive technologies","default":"Close"}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // ru.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'ru',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Фоновое изображение","description":"Изображение показанное на фоне"},{"label":"Альтернативный текст для фонового изображения","description":"Если браузер не может загрузить изображение, этот текст будет отображаться вместо него. Также используется вспомогательными технологиями"},{"label":"Иконка метки","options":[{"label":"Предопрелелённая иконка"},{"label":"Загруженное изображение"}]},{"label":"Предопрелелённая иконка","description":"Использовать предопрелелённую иконку длф метки.","options":[{"label":"Плюс"},{"label":"Минус"},{"label":"Количество раз"},{"label":"Проверка"},{"label":"Вопрос"},{"label":"Инфо"},{"label":"Восклицание"}]},{"label":"Загруженное изображение","description":"Используйте своё изображение для иконки метки.<br />75px на 75px рекомендуемый размер для изображения."},{"label":"Цвет метки","description":"Цвет меток"},{"entity":"hotspot","label":"Метки","field":{"label":"Метка","fields":[{"label":"Позиция метки","description":"Нажмите на уменьшенное изображение для размещения метки"},{"label":"Обложка всего фонового изображения","description":"Когда пользователь нажимает на метку, всплывающее окно будет охватывать все фоновое изображение"},{"label":"Заголовок","description":"Необязательный заголовок для всплывающего окна"},{"label":"Содержимое всплывающего окна","field":{"label":"Элемент содержимого"}}]}},{"label":"Название метки для вспомогательных технологий","default":"Метка #num","description":"Будет помогать объявлять, какая метка была перемещена, если нет заголовка указанного для метки. #num можно использовать для замены номера метки."},{"label":"Кнопка \"Закрыть\" для вспомогательных технологий","default":"Закрыть"}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // sl.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'sl',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Slika za ozadje","description":"Slika je prikazana v ozadju aktivnosti."},{"label":"Neobvezno besedilo za sliko ozadja","description":"Besedilo je prikazano v primeru, ko slike ni mogoče naložiti. Uporabljajo ga tudi bralniki zaslona."},{"label":"Ikona interaktivne točke","options":[{"label":"Prednastavljena ikona"},{"label":"Naložena slika"}]},{"label":"Prednastavljena ikona","description":"Izbira iz galerije prednastavljenih ikon.","options":[{"label":"+"},{"label":"-"},{"label":"x"},{"label":"kljukica"},{"label":"?"},{"label":"i"},{"label":"!"}]},{"label":"Naložena slika","description":"Kot ikono je možno naložiti poljubno sliko.<br />Priporočena velikost slike je 75 x 75 px."},{"label":"Barva interaktivne točke","description":"Barva se izbere v barvni paleti ali z vpisom oznake barve."},{"entity":"interaktivna točka","label":"Interaktivne točke","field":{"label":"Interaktivna točka","fields":[{"label":"Položaj interaktivne točke","description":"Položaj posamezne točke se določi s klikom na želeno mesto na sličici."},{"label":"Prekrij celotno sliko ozadja","description":"Ob kliku na točko, bo pojavno okno prekrilo celotno sliko ozadja."},{"label":"Glava pojavnega okna","description":"Neobvezno. Besedilo se bo pokazalo v pojavnem oknu."},{"label":"Vsebina pojavnega okna","field":{"label":"Element z vsebino"}}]}},{"label":"Oznaka interaktivne točke za bralnike zaslona","default":"Interaktivna točka #num","description":"Besedilo je v pomoč bralniku zaslona, ko za vsebino v točkah niso dodane glave. Spremenljivka je #num."},{"label":"Besedilo gumba Zapri za bralnike zaslona","default":"Zapri"}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // sma.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'sma',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Media","fields":[{"label":"Type","description":"Optional media to display above the question."},{"label":"Disable image zooming"}]},{"label":"Task description","default":"Fill in the missing words","description":"A guide telling the user how to answer this task."},{"label":"Text blocks","entity":"text block","field":{"label":"Line of text","placeholder":"Oslo is the capital of *Norway*.","important":{"description":"<ul><li>Blanks are added with an asterisk (*) in front and behind the correct word/phrase.</li><li>Alternative answers are separated with a forward slash (/).</li><li>You may add a textual tip, using a colon (:) in front of the tip.</li></ul>","example":"H5P content may be edited using a *browser/web-browser:Something you use every day*."}}},{"label":"Overall Feedback","fields":[{"widgets":[{"label":"Default"}],"label":"Define custom feedback for any score range","description":"Click the \"Add range\" button to add as many ranges as you need. Example: 0-20% Bad score, 21-91% Average Score, 91-100% Great Score!","entity":"range","field":{"fields":[{"label":"Score Range"},{},{"label":"Feedback for defined score range","placeholder":"Fill in the feedback"}]}}]},{"label":"Text for \"Show solutions\" button","default":"Show solution"},{"label":"Text for \"Retry\" button","default":"Retry"},{"label":"Text for \"Check\" button","default":"Check"},{"label":"Text for \"Submit\" button","default":"Submit"},{"label":"Text for \"Not filled out\" message","default":"Please fill in all blanks to view solution"},{"label":"Text for \"\':ans\' is correct\" message","default":"\':ans\' is correct"},{"label":"Text for \"\':ans\' is wrong\" message","default":"\':ans\' is wrong"},{"label":"Text for \"Answered correctly\" message","default":"Answered correctly"},{"label":"Text for \"Answered incorrectly\" message","default":"Answered incorrectly"},{"label":"Assistive technology label for solution","default":"Correct answer:"},{"label":"Assistive technology label for input field","description":"Use @num and @total to replace current cloze number and total cloze number","default":"Blank input @num of @total"},{"label":"Assistive technology label for saying an input has a tip tied to it","default":"Tip available"},{"label":"Tip icon label","default":"Tip"},{"label":"Behavioural settings.","description":"These options will let you control how the task behaves.","fields":[{"label":"Enable \"Retry\""},{"label":"Enable \"Show solution\" button"},{"label":"Enable \"Check\" button"},{"label":"Automatically check answers after input"},{"label":"Case sensitive","description":"Makes sure the user input has to be exactly the same as the answer."},{"label":"Require all fields to be answered before the solution can be viewed"},{"label":"Put input fields on separate lines"},{"label":"Show confirmation dialog on \"Check\"","description":"This options is not compatible with the \"Automatically check answers after input\" option"},{"label":"Show confirmation dialog on \"Retry\""},{"label":"Accept minor spelling errors","description":"If activated, an answer will also count as correct with minor spelling errors (3-9 characters: 1 spelling error, more than 9 characters: 2 spelling errors)"}]},{"label":"Check confirmation dialog","fields":[{"label":"Header text","default":"Finish ?"},{"label":"Body text","default":"Are you sure you wish to finish ?"},{"label":"Cancel button label","default":"Cancel"},{"label":"Confirm button label","default":"Finish"}]},{"label":"Retry confirmation dialog","fields":[{"label":"Header text","default":"Retry ?"},{"label":"Body text","default":"Are you sure you wish to retry ?"},{"label":"Cancel button label","default":"Cancel"},{"label":"Confirm button label","default":"Confirm"}]},{"label":"Textual representation of the score bar for those using a readspeaker","default":"You got :num out of :total points"},{"label":"Assistive technology description for \"Check\" button","default":"Check the answers. The responses will be marked as correct, incorrect, or unanswered."},{"label":"Assistive technology description for \"Show Solution\" button","default":"Show the solution. The task will be marked with its correct solution."},{"label":"Assistive technology description for \"Retry\" button","default":"Retry the task. Reset all responses and start the task over again."},{"label":"Assistive technology description for starting task","default":"Checking mode"}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
         // sme.json
         DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'sme',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Background image","description":"Image shown on background."},{"label":"Alternative text for background image","description":"If the browser can\'t load the image this text will be displayed instead. Also used by assistive technologies."},{"label":"Hotspot Icon","options":[{"label":"Predefined icon"},{"label":"Uploaded image"}]},{"label":"Predefined icon","description":"Using a predefined icon for the hotspot.","options":[{"label":"Plus"},{"label":"Minus"},{"label":"Times"},{"label":"Check"},{"label":"Question"},{"label":"Info"},{"label":"Exclamation"}]},{"label":"Uploaded image","description":"Use your own image for the hotspot icon.<br />75px by 75px is recommended for your image."},{"label":"Hotspot color","description":"The color of the hotspots"},{"entity":"hotspot","label":"Hotspots","field":{"label":"Hotspot","fields":[{"label":"Hotspot position","description":"Click on the thumbnail image to place the hotspot"},{"label":"Cover entire background image","description":"When the user clicks the hotspot the popup will cover the entire background image"},{"label":"Header","description":"Optional header for the popup"},{"label":"Popup content","field":{"label":"Content Item"}}]}},{"label":"Hotspot label for assistive technologies","default":"Hotspot #num","description":"Will help announce what hotspot element has been navigated to if there is no header specified for a hotspot. #num can be used to replace the hotspot number."},{"label":"Close button label for assistive technologies","default":"Close"}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // smj.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'smj',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Background image","description":"Image shown on background."},{"label":"Alternative text for background image","description":"If the browser can\'t load the image this text will be displayed instead. Also used by assistive technologies."},{"label":"Hotspot Icon","options":[{"label":"Predefined icon"},{"label":"Uploaded image"}]},{"label":"Predefined icon","description":"Using a predefined icon for the hotspot.","options":[{"label":"Plus"},{"label":"Minus"},{"label":"Times"},{"label":"Check"},{"label":"Question"},{"label":"Info"},{"label":"Exclamation"}]},{"label":"Uploaded image","description":"Use your own image for the hotspot icon.<br />75px by 75px is recommended for your image."},{"label":"Hotspot color","description":"The color of the hotspots"},{"entity":"hotspot","label":"Hotspots","field":{"label":"Hotspot","fields":[{"label":"Hotspot position","description":"Click on the thumbnail image to place the hotspot"},{"label":"Cover entire background image","description":"When the user clicks the hotspot the popup will cover the entire background image"},{"label":"Header","description":"Optional header for the popup"},{"label":"Popup content","field":{"label":"Content Item"}}]}},{"label":"Hotspot label for assistive technologies","default":"Hotspot #num","description":"Will help announce what hotspot element has been navigated to if there is no header specified for a hotspot. #num can be used to replace the hotspot number."},{"label":"Close button label for assistive technologies","default":"Close"}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // sr.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'sr',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Позадинска слика","description":"Слика приказана на позадини."},{"label":"Алтернативни текст за позадинску слику","description":"Ако прегледач не може да учита слику, уместо тога ће се приказати овај текст."},{"label":"Икона вруће тачке","options":[{"label":"Унапред дефинисана икона"},{"label":"Отпремљена слика"}]},{"label":"Унапред дефинисана икона","description":"Коришћење предефинисане иконе за врућу тачку.","options":[{"label":"Плус"},{"label":"Минус"},{"label":"Пута"},{"label":"Провера"},{"label":"Питање"},{"label":"Информација"},{"label":"Узвик"}]},{"label":"Отпремљена слика","description":"Користите своју слику за врућу тачку.<br />75px пута 75px је препоручена."},{"label":"Боја вруће тачке","description":"Боја вруће тачке"},{"entity":"hotspot","label":"Врућа тачка","field":{"label":"Врућа тачка","fields":[{"label":"Позиција вруће тачке","description":"Кликните на сличицу да бисте поставили врућу тачку"},{"label":"Покријте целу позадинску слику","description":"Када корисник кликне на врућу тачку, искачући прозор ће покрити целу позадинску слику"},{"label":"Заглавље","description":"Необавезно заглавље за искачући прозор"},{"label":"Искачући садржај","field":{"label":"Садржај"}}]}},{"label":"Ознака вруће тачке за помоћне технологије","default":"Врућа тачка #num","description":"Помоћи ће вам да саопштите до ког је елемента вруће тачке кретао ако за врућу тачку није наведено заглавље. #num се може користити за замену броја вруће тачке."},{"label":"Ознака дугмета за затварање","default":"Затвори"}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // sv.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'sv',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Background image","description":"Image shown on background."},{"label":"Alternative text for background image","description":"If the browser can\'t load the image this text will be displayed instead. Also used by assistive technologies."},{"label":"Hotspot Icon","options":[{"label":"Predefined icon"},{"label":"Uploaded image"}]},{"label":"Predefined icon","description":"Using a predefined icon for the hotspot.","options":[{"label":"Plus"},{"label":"Minus"},{"label":"Times"},{"label":"Check"},{"label":"Question"},{"label":"Info"},{"label":"Exclamation"}]},{"label":"Uploaded image","description":"Use your own image for the hotspot icon.<br />75px by 75px is recommended for your image."},{"label":"Hotspot color","description":"The color of the hotspots"},{"entity":"hotspot","label":"Hotspots","field":{"label":"Hotspot","fields":[{"label":"Hotspot position","description":"Click on the thumbnail image to place the hotspot"},{"label":"Cover entire background image","description":"When the user clicks the hotspot the popup will cover the entire background image"},{"label":"Header","description":"Optional header for the popup"},{"label":"Popup content","field":{"label":"Content Item"}}]}},{"label":"Hotspot label for assistive technologies","default":"Hotspot #num","description":"Will help announce what hotspot element has been navigated to if there is no header specified for a hotspot. #num can be used to replace the hotspot number."},{"label":"Close button label for assistive technologies","default":"Close"}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        
        // tr.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'tr',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Arka plan resmi","description":"Arka planda gösterilecek resim."},{"label":"Arka plan resmi için alt metin.","description":"Tarayıcı resmi yükleyemezse, bunun yerine bu metin görüntülenecektir. Ayrıca yardımcı teknolojiler tarafından da kullanılır."},{"label":"Görsel Etkin Nokta İşaretleme (Hotspot) Simgesi","options":[{"label":"Tanımlı simgeler"},{"label":"Yüklenen resim"}]},{"label":"Tanımlı simgeler","description":"Etkin nokta için önceden tanımlanmış bir simge kullanma.","options":[{"label":"Artı"},{"label":"Tire"},{"label":"Zaman"},{"label":"Kontrol"},{"label":"Soru"},{"label":"Bilgi"},{"label":"Ünlem"}]},{"label":"Yüklenen resim","description":"Etkin nokta simegesi için kendiniz resim yükleyin.<br /> 75px x 75px boyutu önerilmektedir."},{"label":"Etkin nokta rengi","description":"Etkin noktaların rengi"},{"entity":"hotspot","label":"Hotspots","field":{"label":"Hotspot","fields":[{"label":"Etkin nokta pozisyonu","description":"Etkin noktayı yerleştirmek için küçük noktaya tıklayın."},{"label":"Tüm arka plan resmini örtün.","description":"Kullanıcı etkin noktayı tıkladığında açılır pencere tüm arka plan resmini kaplar."},{"label":"Başlık","description":"Açılır pencere için başlık (isteğe bağlı)"},{"label":"açılır pencere içeriği","field":{"label":"İçerik Öğesi"}}]}},{"label":"Yardımcı teknoloji için etkin nokta etiketi","default":"Etkin nokta #num","description":"Bir etkin nokta için başlık belirtilmemişse, hangi etkin nokta öğesine gidildiğini seslendirmeye yardımcı olur. #num, etkin nokta numarasını değiştirmek için kullanılabilir."},{"label":"Yardımcı teknolojileri kapat butonu etiketi","default":"Kapat"}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // uk.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'uk',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Зображення тла","description":"Зображення, яке показано на тлі"},{"label":"Альтернативний текст для зображення тла","description":"Якщо браузер не може завантажити зображення, цей текст буде відображатися замість нього. Також використовується допоміжними технологіями"},{"label":"Мітка","options":[{"label":"Наперед визначена позначка"},{"label":"Завантажене зображення"}]},{"label":"Наперед визначена позначка","description":"Використовувати наперед визначений значок для мітки","options":[{"label":"Плюс"},{"label":"Мінус"},{"label":"Час"},{"label":"Перевірити"},{"label":"Запитання"},{"label":"Інформація"},{"label":"Вигук"}]},{"label":"Завантажене зображення","description":"Використовуйте власне зображення мітки.<br />75px на 75px рекомендується для Вашого зображення."},{"label":"Колір мітки","description":"Колір міток"},{"entity":"hotspot","label":"Мітки","field":{"label":"Мітка","fields":[{"label":"Позиція мітки","description":"Натисніть зменшене зображення для розміщення мітки"},{"label":"Обкладинка всього зображення тла","description":"Коли користувач натискає на мітку, випливаюче вікно буде захоплювати все зображення тла"},{"label":"Заголовок","description":"Необов\'язковий заголовок для випливаючого вікна"},{"label":"Вміст випливаючого вікна","field":{"label":"Елемент вмісту"}}]}},{"label":"Назва мітки для допоміжних технологій","default":"Мітка #num","description":"Буде допомагати оголошувати, яка мітка була переміщена, якщо нема заголовка вказаного для мітки. #num можна використовувати для заміни номера мітки."},{"label":"Кнопка \"Закрити\" для допоміжних технологій","default":"Закрити"}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // vi.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'vi',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Background image","description":"Image shown on background."},{"label":"Alternative text for background image","description":"If the browser can\'t load the image this text will be displayed instead. Also used by assistive technologies."},{"label":"Hotspot Icon","options":[{"label":"Predefined icon"},{"label":"Uploaded image"}]},{"label":"Predefined icon","description":"Using a predefined icon for the hotspot.","options":[{"label":"Plus"},{"label":"Minus"},{"label":"Times"},{"label":"Check"},{"label":"Question"},{"label":"Info"},{"label":"Exclamation"}]},{"label":"Uploaded image","description":"Use your own image for the hotspot icon.<br />75px by 75px is recommended for your image."},{"label":"Hotspot color","description":"The color of the hotspots"},{"entity":"hotspot","label":"Hotspots","field":{"label":"Hotspot","fields":[{"label":"Hotspot position","description":"Click on the thumbnail image to place the hotspot"},{"label":"Cover entire background image","description":"When the user clicks the hotspot the popup will cover the entire background image"},{"label":"Header","description":"Optional header for the popup"},{"label":"Popup content","field":{"label":"Content Item"}}]}},{"label":"Hotspot label for assistive technologies","default":"Hotspot #num","description":"Will help announce what hotspot element has been navigated to if there is no header specified for a hotspot. #num can be used to replace the hotspot number."},{"label":"Close button label for assistive technologies","default":"Close"}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // zh-hans.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'zh-hans',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"背景图片","description":"背景要显示的图片。"},{"label":"替代文字","description":"当浏览器无法载入图片时会显示的文字，也用来作为视障者的辅助工具。"},{"label":"Hotspot Icon","options":[{"label":"Predefined icon"},{"label":"Uploaded image"}]},{"label":"Predefined icon","description":"Using a predefined icon for the hotspot.","options":[{"label":"Plus"},{"label":"Minus"},{"label":"Times"},{"label":"Check"},{"label":"Question"},{"label":"Info"},{"label":"Exclamation"}]},{"label":"Uploaded image","description":"Use your own image for the hotspot icon.<br />75px by 75px is recommended for your image."},{"label":"热点颜色","description":"热点要显示的颜色。"},{"entity":"热点","label":"所有热点","field":{"label":"热点","fields":[{"label":"热点位置","description":"在缩图上点击以放置热贴。"},{"label":"弹出视窗布满整个背景图片","description":"当用户点击热点时，弹出的视窗会与背景图片的尺寸一致。"},{"label":"弹出视窗标题文字","description":"弹出视窗上显示的标题。"},{"label":"弹出视窗内文文字","field":{"label":"项目"}}]}},{"label":"热点辅助说明","default":"热点 #num","description":"如果热点没有设定标题，这可以用来帮助说明热点。#num 参数为热点的自动编号。"},{"label":"视窗关闭辅助文字","default":"关闭"}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
         // zh-hant.json
         DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'zh-hant',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"背景圖片","description":"背景要顯示的圖片。"},{"label":"替代文字","description":"當瀏覽器無法載入圖片時會顯示的文字，也用來作為視障者的輔助工具。"},{"label":"Hotspot Icon","options":[{"label":"Predefined icon"},{"label":"Uploaded image"}]},{"label":"Predefined icon","description":"Using a predefined icon for the hotspot.","options":[{"label":"Plus"},{"label":"Minus"},{"label":"Times"},{"label":"Check"},{"label":"Question"},{"label":"Info"},{"label":"Exclamation"}]},{"label":"Uploaded image","description":"Use your own image for the hotspot icon.<br />75px by 75px is recommended for your image."},{"label":"熱點顏色","description":"熱點要顯示的顏色。"},{"entity":"hotspot","label":"所有熱點","field":{"label":"熱點","fields":[{"label":"熱點位置","description":"在縮圖上點擊以放置熱點。"},{"label":"彈出視窗佈滿整個背景圖片","description":"當用戶點擊熱點時，彈出的視窗會與背景圖片的尺寸一致。"},{"label":"彈出視窗標題文字","description":"彈出視窗上顯示的標題。"},{"label":"彈出視窗內文文字","field":{"label":"項目"}}]}},{"label":"熱點輔助說明","default":"Hotspot #num","description":"如果熱點沒有設定標題，這可以用來幫助說明熱點。#num 參數為熱點的自動編號。"},{"label":"視窗關閉輔助文字","default":"關閉"}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
         // zh-tw.json
         DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'zh-tw',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"背景圖示","description":"圖像顯示在背景上."},{"label":"Alternative text for background image","description":"If the browser can\'t load the image this text will be displayed instead. Also used by assistive technologies."},{"label":"Hotspot Icon","options":[{"label":"Predefined icon"},{"label":"Uploaded image"}]},{"label":"Predefined icon","description":"Using a predefined icon for the hotspot.","options":[{"label":"Plus"},{"label":"Minus"},{"label":"Times"},{"label":"Check"},{"label":"Question"},{"label":"Info"},{"label":"Exclamation"}]},{"label":"Uploaded image","description":"Use your own image for the hotspot icon.<br />75px by 75px is recommended for your image."},{"label":"Hotspot color","description":"The color of the hotspots"},{"entity":"hotspot","label":"Hotspots","field":{"label":"Hotspot","fields":[{"label":"Hotspot position","description":"Click on the thumbnail image to place the hotspot"},{"label":"Cover entire background image","description":"When the user clicks the hotspot the popup will cover the entire background image"},{"label":"Header","description":"Optional header for the popup"},{"label":"Popup content","field":{"label":"Content Item"}}]}},{"label":"Hotspot label for assistive technologies","default":"Hotspot #num","description":"Will help announce what hotspot element has been navigated to if there is no header specified for a hotspot. #num can be used to replace the hotspot number."},{"label":"Close button label for assistive technologies","default":"Close"}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);

    }

    private function getSemantics() {
        return '[
            {
              "label": "Background image",
              "importance": "high",
              "name": "image",
              "type": "image",
              "description": "Image shown on background."
            },
            {
              "name": "backgroundImageAltText",
              "type": "text",
              "label": "Alternative text for background image",
              "description": "If the browser can\'t load the image this text will be displayed instead. Also used by assistive technologies.",
              "optional": true
            },
            {
              "name": "iconType",
              "type": "select",
              "label": "Hotspot Icon",
              "options": [
                {
                  "value": "icon",
                  "label": "Predefined icon"
                },
                {
                  "value": "image",
                  "label": "Uploaded image"
                }
              ],
              "default": "icon"
            },
            {
              "name": "icon",
              "type": "select",
              "label": "Predefined icon",
              "importance": "medium",
              "description": "Using a predefined icon for the hotspot.",
              "widget": "showWhen",
              "showWhen": {
                "rules": [
                  {
                    "field": "iconType",
                    "equals": [
                      "icon"
                    ]
                  }
                ]
              },
              "options": [
                {
                  "value": "plus",
                  "label": "Plus"
                },
                {
                  "value": "minus",
                  "label": "Minus"
                },
                {
                  "value": "times",
                  "label": "Times"
                },
                {
                  "value": "check",
                  "label": "Check"
                },
                {
                  "value": "question",
                  "label": "Question"
                },
                {
                  "value": "info",
                  "label": "Info"
                },
                {
                  "value": "exclamation",
                  "label": "Exclamation"
                }
              ],
              "default": "plus"
            },
            {
              "name": "iconImage",
              "type": "image",
              "label": "Uploaded image",
              "optional": false,
              "description": "Use your own image for the hotspot icon.<br />75px by 75px is recommended for your image.",
              "widget": "showWhen",
              "showWhen": {
                "rules": [
                  {
                    "field": "iconType",
                    "equals": [
                      "image"
                    ]
                  }
                ]
              }
            },
            {
              "name": "color",
              "type": "text",
              "label": "Hotspot color",
              "importance": "medium",
              "description": "The color of the hotspots",
              "optional": true,
              "default": "#981d99",
              "widget": "showWhen",
              "spectrum": {
                "showInput": true
              },
              "showWhen": {
                "widget": "colorSelector",
                "rules": [
                  {
                    "field": "iconType",
                    "equals": [
                      "icon"
                    ]
                  }
                ]
              }
            },
            {
              "name": "hotspots",
              "type": "list",
              "entity": "hotspot",
              "label": "Hotspots",
              "importance": "high",
              "min": 1,
              "defaultNum": 1,
              "field": {
                "name": "hotspot",
                "type": "group",
                "label": "Hotspot",
                "importance": "high",
                "fields": [
                  {
                    "name": "position",
                    "type": "group",
                    "widget": "imageCoordinateSelector",
                    "imageFieldPath": "../../image",
                    "label": "Hotspot position",
                    "importance": "high",
                    "description": "Click on the thumbnail image to place the hotspot",
                    "fields": [
                      {
                        "name": "x",
                        "type": "number"
                      },
                      {
                        "name": "y",
                        "type": "number"
                      },
                      {
                        "name": "legacyPositioning",
                        "type": "boolean"
                      }
                    ]
                  },
                  {
                    "name": "alwaysFullscreen",
                    "type": "boolean",
                    "label": "Cover entire background image",
                    "importance": "low",
                    "description": "When the user clicks the hotspot the popup will cover the entire background image"
                  },
                  {
                    "name": "header",
                    "type": "text",
                    "label": "Header",
                    "importance": "low",
                    "description": "Optional header for the popup",
                    "optional": true
                  },
                  {
                    "name": "content",
                    "type": "list",
                    "label": "Popup content",
                    "importance": "medium",
                    "field": {
                      "name": "action",
                      "type": "library",
                      "label": "Content Item",
                      "options": [
                        "H5P.Text 1.1",
                        "H5P.Video 1.6",
                        "H5P.Image 1.1",
                        "H5P.Audio 1.5"
                      ]
                    }
                  }
                ]
              }
            },
            {
              "name": "hotspotNumberLabel",
              "type": "text",
              "label": "Hotspot label for assistive technologies",
              "default": "Hotspot #num",
              "description": "Will help announce what hotspot element has been navigated to if there is no header specified for a hotspot. #num can be used to replace the hotspot number.",
              "common": true
            },
            {
              "name": "closeButtonLabel",
              "type": "text",
              "label": "Close button label for assistive technologies",
              "default": "Close",
              "common": true
            }
          ]';
      }

}
