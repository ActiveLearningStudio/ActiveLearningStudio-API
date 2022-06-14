<?php

use Illuminate\Database\Seeder;

class UpdateH5pEditorCurrikiIVToH5pLibrariesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $H5PEditorCIVParams = ['name' => "H5PEditor.CurrikiInteractiveVideo", "major_version" => 1, "minor_version" => 0];
        $h5pEditorCIVLib = DB::table('h5p_libraries')->where($H5PEditorCIVParams)->first();
        if(empty($h5pEditorCIVLib)) {
            $h5pEditorCIVLibId = DB::table('h5p_libraries')->insertGetId([
                'name' => 'H5PEditor.CurrikiInteractiveVideo',
                'title' => 'Curriki Interactive Video Editor',
                'major_version' => 1,
                'minor_version' => 0,
                'patch_version' => 0,
                'embed_types' => '',
                'runnable' => 0,
                'restricted' => 0,
                'fullscreen' => 0,
                'preloaded_js' => 'Scripts/image-radio-button-group.js, Scripts/interactive-video-editor.js, Scripts/guided-tours.js, Scripts/require-completion.js',
                'preloaded_css' => 'styles/image-radio-button-group.css, styles/interactive-video-editor.css, styles/require-completion.css',
                'drop_library_css' => '',
                'semantics' => '',
                'tutorial_url' => '',
                'has_icon' => 0
            ]);
        } else {
            $h5pEditorCIVLibId = $h5pEditorCIVLib->id;
        }
        // insert dependent libraries
        $this->insertDependentLibraries($h5pEditorCIVLibId);
        // insert libraries languages
        $this->insertLibrariesLanguages($h5pEditorCIVLibId);
    }

    /**
     * Insert Dependent Libraries
     * @param $h5pEditorCIVLibId
     */
    private function insertDependentLibraries($h5pEditorCIVLibId)
    {
    	//Preloaded Dependencies
    	//FontAwesome
    	$h5pFontAwesomeParams = ['name' => "FontAwesome", "major_version" => 4, "minor_version" => 5];
        $h5pFontAwesomeLib = DB::table('h5p_libraries')->where($h5pFontAwesomeParams)->first();
        if(empty($h5pFontAwesomeLib)) {
            $h5pFontAwesomeLibId = DB::table('h5p_libraries')->insertGetId([
                'name' => 'FontAwesome',
                'title' => 'Font Awesome',
                'major_version' => 4,
                'minor_version' => 5,
                'patch_version' => 4,
                'embed_types' => '',
                'runnable' => 0,
                'restricted' => 0,
                'fullscreen' => 0,
                'preloaded_js' => '',
                'preloaded_css' => 'h5p-font-awesome.min.css',
                'drop_library_css' => '',
                'semantics' => '',
                'tutorial_url' => '',
                'has_icon' => 0
            ]);
        } else {
            $h5pFontAwesomeLibId = $h5pFontAwesomeLib->id;
        }
    	//H5P.DragNBar
    	$H5PDragNBarParams = ['name' => "H5P.DragNBar", "major_version" => 1, "minor_version" => 5];
        $h5pDranNBarLib = DB::table('h5p_libraries')->where($H5PDragNBarParams)->first();
        if(empty($h5pDranNBarLib)) {
            $h5pDranNBarLibId = DB::table('h5p_libraries')->insertGetId([
                'name' => 'H5P.DragNBar',
                'title' => 'Drag N Bar',
                'major_version' => 1,
                'minor_version' => 5,
                'patch_version' => 12,
                'embed_types' => '',
                'runnable' => 0,
                'restricted' => 0,
                'fullscreen' => 0,
                'preloaded_js' => 'scripts/drag-n-bar.js, scripts/context-menu.js, scripts/dialog.js, scripts/drag-n-bar-element.js, scripts/drag-n-bar-form-manager.js',
                'preloaded_css' => 'styles/drag-n-bar.css, styles/dialog.css, styles/context-menu.css, styles/drag-n-bar-form-manager.css',
                'drop_library_css' => '',
                'semantics' => '',
                'tutorial_url' => '',
                'has_icon' => 0
            ]);
        } else {
            $h5pDranNBarLibId = $h5pDranNBarLib->id;
        }
    	//H5P.Image
    	$H5PImageParams = ['name' => "H5P.Image", "major_version" => 1, "minor_version" => 1];
        $h5pImageLib = DB::table('h5p_libraries')->where($H5PImageParams)->first();
        if(empty($h5pImageLib)) {
            $h5pImageLibId = DB::table('h5p_libraries')->insertGetId([
                'name' => 'H5P.Image',
                'title' => 'Image',
                'major_version' => 1,
                'minor_version' => 1,
                'patch_version' => 11,
                'embed_types' => '',
                'runnable' => 0,
                'restricted' => 0,
                'fullscreen' => 0,
                'preloaded_js' => 'image.js',
                'preloaded_css' => 'image.css',
                'drop_library_css' => '',
                'semantics' => $this->getH5PImageSemantics(),
                'tutorial_url' => '',
                'has_icon' => 0
            ]);
        } else {
            $h5pImageLibId = $h5pImageLib->id;
        }
    	//H5P.Text
    	$H5PTextParams = ['name' => "H5P.Text", "major_version" => 1, "minor_version" => 1];
        $h5pTextLib = DB::table('h5p_libraries')->where($H5PTextParams)->first();
        if(empty($h5pTextLib)) {
            $h5pTextLibId = DB::table('h5p_libraries')->insertGetId([
                'name' => 'H5P.Text',
                'title' => 'Text',
                'major_version' => 1,
                'minor_version' => 1,
                'patch_version' => 15,
                'embed_types' => '',
                'runnable' => 0,
                'restricted' => 0,
                'fullscreen' => 0,
                'preloaded_js' => 'scripts/text.js',
                'preloaded_css' => 'styles/text.css',
                'drop_library_css' => '',
                'semantics' => $this->getH5PTextSemantics(),
                'tutorial_url' => '',
                'has_icon' => 0
            ]);
        } else {
            $h5pTextLibId = $h5pTextLib->id;
        }
    	//H5P.Table
    	$H5PTableParams = ['name' => "H5P.Table", "major_version" => 1, "minor_version" => 1];
        $h5pTableLib = DB::table('h5p_libraries')->where($H5PTableParams)->first();
        if(empty($h5pTableLib)) {
            $h5pTableLibId = DB::table('h5p_libraries')->insertGetId([
                'name' => 'H5P.Table',
                'title' => 'Table',
                'major_version' => 1,
                'minor_version' => 1,
                'patch_version' => 16,
                'embed_types' => '',
                'runnable' => 0,
                'restricted' => 0,
                'fullscreen' => 0,
                'preloaded_js' => 'scripts/table.js',
                'preloaded_css' => 'styles/table.css',
                'drop_library_css' => '',
                'semantics' => $this->getH5PTableSemantics(),
                'tutorial_url' => '',
                'has_icon' => 0
            ]);
        } else {
            $h5pTableLibId = $h5pTableLib->id;
        }
    	//H5P.Link
    	$H5PLinkParams = ['name' => "H5P.Link", "major_version" => 1, "minor_version" => 3];
        $h5pLinkLib = DB::table('h5p_libraries')->where($H5PLinkParams)->first();
        if(empty($h5pLinkLib)) {
            $h5pLinkLibId = DB::table('h5p_libraries')->insertGetId([
                'name' => 'H5P.Link',
                'title' => 'Link',
                'major_version' => 1,
                'minor_version' => 3,
                'patch_version' => 15,
                'embed_types' => '',
                'runnable' => 0,
                'restricted' => 0,
                'fullscreen' => 0,
                'preloaded_js' => 'link.js',
                'preloaded_css' => '',
                'drop_library_css' => '',
                'semantics' => $this->getH5PLinkSemantics(),
                'tutorial_url' => '',
                'has_icon' => 0
            ]);
        } else {
            $h5pLinkLibId = $h5pLinkLib->id;
        }
    	//H5P.SingleChoiceSet
    	$H5PSingleChoiceSetParams = ['name' => "H5P.SingleChoiceSet", "major_version" => 1, "minor_version" => 11];
        $h5pSingleChoiceSetLib = DB::table('h5p_libraries')->where($H5PSingleChoiceSetParams)->first();
        if(empty($h5pSingleChoiceSetLib)) {
            $h5pSingleChoiceSetLibId = DB::table('h5p_libraries')->insertGetId([
                'name' => 'H5P.SingleChoiceSet',
                'title' => 'Single Choice Set',
                'major_version' => 1,
                'minor_version' => 11,
                'patch_version' => 14,
                'embed_types' => 'iframe',
                'runnable' => 1,
                'restricted' => 0,
                'fullscreen' => 0,
                'preloaded_js' => 'scripts/stop-watch.js, scripts/sound-effects.js, scripts/xapi-event-builder.js, scripts/result-slide.js, scripts/solution-view.js, scripts/single-choice-alternative.js, scripts/single-choice.js, scripts/single-choice-set.js',
                'preloaded_css' => 'styles/single-choice-set.css',
                'drop_library_css' => '',
                'semantics' => $this->getH5PSingleChoiceSetSemantics(),
                'tutorial_url' => '',
                'has_icon' => 1
            ]);
        } else {
            $h5pSingleChoiceSetLibId = $h5pSingleChoiceSetLib->id;
        }
    	//H5P.MultiChoice
    	$H5PMultiChoiceParams = ['name' => "H5P.MultiChoice", "major_version" => 1, "minor_version" => 14];
        $h5pMultiChoiceLib = DB::table('h5p_libraries')->where($H5PMultiChoiceParams)->first();
        if(empty($h5pMultiChoiceLib)) {
            $h5pMultiChoiceLibId = DB::table('h5p_libraries')->insertGetId([
                'name' => 'H5P.MultiChoice',
                'title' => 'Multiple Choice',
                'major_version' => 1,
                'minor_version' => 14,
                'patch_version' => 7,
                'embed_types' => 'iframe',
                'runnable' => 1,
                'restricted' => 0,
                'fullscreen' => 0,
                'preloaded_js' => 'js/multichoice.js',
                'preloaded_css' => 'css/multichoice.css',
                'drop_library_css' => '',
                'semantics' => $this->getH5PMultiChoiceSemantics(),
                'tutorial_url' => '',
                'has_icon' => 1
            ]);
        } else {
            $h5pMultiChoiceLibId = $h5pMultiChoiceLib->id;
        }
    	//H5P.TrueFalse
    	$H5PTrueFalseParams = ['name' => "H5P.TrueFalse", "major_version" => 1, "minor_version" => 6];
        $h5pTrueFalseLib = DB::table('h5p_libraries')->where($H5PTrueFalseParams)->first();
        if(empty($h5pTrueFalseLib)) {
            $h5pTrueFalseLibId = DB::table('h5p_libraries')->insertGetId([
                'name' => 'H5P.TrueFalse',
                'title' => 'True/False Question',
                'major_version' => 1,
                'minor_version' => 6,
                'patch_version' => 7,
                'embed_types' => 'iframe',
                'runnable' => 1,
                'restricted' => 0,
                'fullscreen' => 0,
                'preloaded_js' => 'scripts/h5p-true-false.js, scripts/h5p-true-false-answer-group.js, scripts/h5p-true-false-answer.js',
                'preloaded_css' => 'styles/h5p-true-false.css',
                'drop_library_css' => '',
                'semantics' => $this->getH5PTrueFalseSemantics(),
                'tutorial_url' => '',
                'has_icon' => 1
            ]);
        } else {
            $h5pTrueFalseLibId = $h5pTrueFalseLib->id;
        }
    	//H5P.CurrikiInteractiveVideo
    	$H5PCIVParams = ['name' => "H5P.CurrikiInteractiveVideo", "major_version" => 1, "minor_version" => 0];
        $h5pCIVLib = DB::table('h5p_libraries')->where($H5PCIVParams)->first();
        if(empty($h5pCIVLib)) {
            $h5pCIVLibId = DB::table('h5p_libraries')->insertGetId([
                'name' => 'H5P.CurrikiInteractiveVideo',
                'title' => 'Curriki Interactive Video',
                'major_version' => 1,
                'minor_version' => 0,
                'patch_version' => 0,
                'embed_types' => 'iframe',
                'runnable' => 1,
                'restricted' => 0,
                'fullscreen' => 1,
                'preloaded_js' => 'dist/h5p-interactive-video.js',
                'preloaded_css' => 'dist/h5p-interactive-video.css',
                'drop_library_css' => '',
                'semantics' => $this->getCIVSemantics(),
                'tutorial_url' => '',
                'has_icon' => 1
            ]);
        } else {
            $h5pCIVLibId = $h5pCIVLib->id;
        }
    	//H5P.Summary
    	$H5PSummaryParams = ['name' => "H5P.Summary", "major_version" => 1, "minor_version" => 10];
        $h5pSummaryLib = DB::table('h5p_libraries')->where($H5PSummaryParams)->first();
        if(empty($h5pSummaryLib)) {
            $h5pSummaryLibId = DB::table('h5p_libraries')->insertGetId([
                'name' => 'H5P.Summary',
                'title' => 'Summary',
                'major_version' => 1,
                'minor_version' => 10,
                'patch_version' => 11,
                'embed_types' => 'iframe',
                'runnable' => 1,
                'restricted' => 0,
                'fullscreen' => 0,
                'preloaded_js' => 'js/stop-watch.js, js/xapi-event-builder.js, js/summary.js',
                'preloaded_css' => 'css/summary.css',
                'drop_library_css' => '',
                'semantics' => $this->getH5PSummarySemantics(),
                'tutorial_url' => '',
                'has_icon' => 1
            ]);
        } else {
            $h5pSummaryLibId = $h5pSummaryLib->id;
        }
    	//H5PEditor.Duration
    	$H5PDurationParams = ['name' => "H5PEditor.Duration", "major_version" => 1, "minor_version" => 1];
        $h5pDurationLib = DB::table('h5p_libraries')->where($H5PDurationParams)->first();
        if(empty($h5pDurationLib)) {
            $h5pDurationLibId = DB::table('h5p_libraries')->insertGetId([
                'name' => 'H5PEditor.Duration',
                'title' => 'H5PEditor.Duration',
                'major_version' => 1,
                'minor_version' => 1,
                'patch_version' => 12,
                'embed_types' => '',
                'runnable' => 0,
                'restricted' => 0,
                'fullscreen' => 0,
                'preloaded_js' => 'scripts/duration.js',
                'preloaded_css' => 'styles/duration.css',
                'drop_library_css' => '',
                'semantics' => '',
                'tutorial_url' => '',
                'has_icon' => 0
            ]);
        } else {
            $h5pDurationLibId = $h5pDurationLib->id;
        }
    	//H5PEditor.Timecode
    	$H5PTimecodeParams = ['name' => "H5PEditor.Timecode", "major_version" => 1, "minor_version" => 2];
        $h5pTimecodeLib = DB::table('h5p_libraries')->where($H5PTimecodeParams)->first();
        if(empty($h5pTimecodeLib)) {
            $h5pTimecodeLibId = DB::table('h5p_libraries')->insertGetId([
                'name' => 'H5PEditor.Timecode',
                'title' => 'Timecode Editor',
                'major_version' => 1,
                'minor_version' => 2,
                'patch_version' => 12,
                'embed_types' => '',
                'runnable' => 0,
                'restricted' => 0,
                'fullscreen' => 0,
                'preloaded_js' => 'timecode.js',
                'preloaded_css' => 'timecode.css',
                'drop_library_css' => '',
                'semantics' => '',
                'tutorial_url' => '',
                'has_icon' => 0
            ]);
        } else {
            $h5pTimecodeLibId = $h5pTimecodeLib->id;
        }
    	//H5PEditor.SelectToggleFields
    	$H5PSelectToggleFieldsParams = ['name' => "H5PEditor.SelectToggleFields", "major_version" => 1, "minor_version" => 1];
        $h5pSelectToggleFieldsLib = DB::table('h5p_libraries')->where($H5PSelectToggleFieldsParams)->first();
        if(empty($h5pSelectToggleFieldsLib)) {
            $h5pSelectToggleFieldsLibId = DB::table('h5p_libraries')->insertGetId([
                'name' => 'H5PEditor.SelectToggleFields',
                'title' => 'Toggle visibility of fields when selecting options in list',
                'major_version' => 1,
                'minor_version' => 1,
                'patch_version' => 1,
                'embed_types' => '',
                'runnable' => 0,
                'restricted' => 0,
                'fullscreen' => 0,
                'preloaded_js' => 'select-toggle-fields.js',
                'preloaded_css' => 'select-toggle-fields.css',
                'drop_library_css' => '',
                'semantics' => '',
                'tutorial_url' => '',
                'has_icon' => 0
            ]);
        } else {
            $h5pSelectToggleFieldsLibId = $h5pSelectToggleFieldsLib->id;
        }
    	//H5PEditor.ColorSelector
    	$H5PColorSelectorParams = ['name' => "H5PEditor.ColorSelector", "major_version" => 1, "minor_version" => 2];
        $h5pColorSelectorLib = DB::table('h5p_libraries')->where($H5PColorSelectorParams)->first();
        if(empty($h5pColorSelectorLib)) {
            $h5pColorSelectorLibId = DB::table('h5p_libraries')->insertGetId([
                'name' => 'H5PEditor.ColorSelector',
                'title' => 'H5PEditor.ColorSelector',
                'major_version' => 1,
                'minor_version' => 2,
                'patch_version' => 6,
                'embed_types' => '',
                'runnable' => 0,
                'restricted' => 0,
                'fullscreen' => 0,
                'preloaded_js' => 'scripts/spectrum.js, scripts/color-selector.js',
                'preloaded_css' => 'styles/spectrum.css, styles/color-selector.css',
                'drop_library_css' => '',
                'semantics' => '',
                'tutorial_url' => '',
                'has_icon' => 0
            ]);
        } else {
            $h5pColorSelectorLibId = $h5pColorSelectorLib->id;
        }
    	//H5P.Blanks
    	$H5PBlanksParams = ['name' => "H5P.Blanks", "major_version" => 1, "minor_version" => 12];
        $h5pBlanksLib = DB::table('h5p_libraries')->where($H5PBlanksParams)->first();
        if(empty($h5pBlanksLib)) {
            $h5pBlanksLibId = DB::table('h5p_libraries')->insertGetId([
                'name' => 'H5P.Blanks',
                'title' => 'Fill in the Blanks',
                'major_version' => 1,
                'minor_version' => 12,
                'patch_version' => 11,
                'embed_types' => 'iframe',
                'runnable' => 1,
                'restricted' => 0,
                'fullscreen' => 0,
                'preloaded_js' => 'js/blanks.js, js/cloze.js',
                'preloaded_css' => 'css/blanks.css',
                'drop_library_css' => '',
                'semantics' => $this->getH5PBlanksSemantics(),
                'tutorial_url' => '',
                'has_icon' => 1
            ]);
        } else {
            $h5pBlanksLibId = $h5pBlanksLib->id;
        }
    	//H5P.Nil
    	$H5PNillParams = ['name' => "H5P.Nil", "major_version" => 1, "minor_version" => 0];
        $h5pNillLib = DB::table('h5p_libraries')->where($H5PNillParams)->first();
        if(empty($h5pNillLib)) {
            $h5pNillLibId = DB::table('h5p_libraries')->insertGetId([
                'name' => 'H5P.Nil',
                'title' => 'Label',
                'major_version' => 1,
                'minor_version' => 0,
                'patch_version' => 14,
                'embed_types' => '',
                'runnable' => 0,
                'restricted' => 0,
                'fullscreen' => 0,
                'preloaded_js' => '',
                'preloaded_css' => '',
                'drop_library_css' => '',
                'semantics' => $this->getH5PNillSemantics(),
                'tutorial_url' => '',
                'has_icon' => 0
            ]);
        } else {
            $h5pNillLibId = $h5pNillLib->id;
        }
    	//H5P.DragQuestion
    	$H5PDragQuestionParams = ['name' => "H5P.DragQuestion", "major_version" => 1, "minor_version" => 13];
        $h5pDragQuestionLib = DB::table('h5p_libraries')->where($H5PDragQuestionParams)->first();
        if(empty($h5pDragQuestionLib)) {
            $h5pDragQuestionLibId = DB::table('h5p_libraries')->insertGetId([
                'name' => 'H5P.DragQuestion',
                'title' => 'Drag and Drop',
                'major_version' => 1,
                'minor_version' => 13,
                'patch_version' => 14,
                'embed_types' => 'iframe',
                'runnable' => 1,
                'restricted' => 0,
                'fullscreen' => 0,
                'preloaded_js' => 'h5p-drag-question.js',
                'preloaded_css' => 'css/dragquestion.css',
                'drop_library_css' => '',
                'semantics' => $this->getH5PDragQuestionSemantics(),
                'tutorial_url' => '',
                'has_icon' => 1
            ]);
        } else {
            $h5pDragQuestionLibId = $h5pDragQuestionLib->id;
        }
    	//H5P.MarkTheWords
    	$H5PMarkTheWordsParams = ['name' => "H5P.MarkTheWords", "major_version" => 1, "minor_version" => 9];
        $h5pMarkTheWordsLib = DB::table('h5p_libraries')->where($H5PMarkTheWordsParams)->first();
        if(empty($h5pMarkTheWordsLib)) {
            $h5pMarkTheWordsLibId = DB::table('h5p_libraries')->insertGetId([
                'name' => 'H5P.MarkTheWords',
                'title' => 'Mark the Words',
                'major_version' => 1,
                'minor_version' => 9,
                'patch_version' => 15,
                'embed_types' => 'iframe',
                'runnable' => 1,
                'restricted' => 0,
                'fullscreen' => 0,
                'preloaded_js' => 'scripts/keyboard-nav.js, scripts/xAPI-generator.js, scripts/word.js, scripts/mark-the-words.js',
                'preloaded_css' => 'styles/mark-the-words.css',
                'drop_library_css' => '',
                'semantics' => $this->getH5PMarkTheWordsSemantics(),
                'tutorial_url' => '',
                'has_icon' => 1
            ]);
        } else {
            $h5pMarkTheWordsLibId = $h5pMarkTheWordsLib->id;
        }
    	//H5P.DragText
    	$H5PDragTextParams = ['name' => "H5P.DragText", "major_version" => 1, "minor_version" => 8];
        $h5pDragTextLib = DB::table('h5p_libraries')->where($H5PDragTextParams)->first();
        if(empty($h5pDragTextLib)) {
            $h5pDragTextLibId = DB::table('h5p_libraries')->insertGetId([
                'name' => 'H5P.DragText',
                'title' => 'Drag Text',
                'major_version' => 1,
                'minor_version' => 8,
                'patch_version' => 13,
                'embed_types' => 'iframe',
                'runnable' => 1,
                'restricted' => 0,
                'fullscreen' => 0,
                'preloaded_js' => 'dist/h5p-drag-text.js',
                'preloaded_css' => 'dist/h5p-drag-text.css',
                'drop_library_css' => '',
                'semantics' => $this->getH5PDragTextSemantics(),
                'tutorial_url' => '',
                'has_icon' => 1
            ]);
        } else {
            $h5pDragTextLibId = $h5pDragTextLib->id;
        }
    	//H5P.GuidedTour
    	$H5PGuidedTourParams = ['name' => "H5P.GuidedTour", "major_version" => 1, "minor_version" => 0];
        $h5pGuidedTourLib = DB::table('h5p_libraries')->where($H5PGuidedTourParams)->first();
        if(empty($h5pGuidedTourLib)) {
            $h5pGuidedTourLibId = DB::table('h5p_libraries')->insertGetId([
                'name' => 'H5P.GuidedTour',
                'title' => 'Guided Tour',
                'major_version' => 1,
                'minor_version' => 0,
                'patch_version' => 5,
                'embed_types' => '',
                'runnable' => 0,
                'restricted' => 0,
                'fullscreen' => 0,
                'preloaded_js' => 'scripts/h5p-guided-tour.js',
                'preloaded_css' => 'styles/h5p-guided-tour.css',
                'drop_library_css' => '',
                'semantics' => '',
                'tutorial_url' => '',
                'has_icon' => 0
            ]);
        } else {
            $h5pGuidedTourLibId = $h5pGuidedTourLib->id;
        }
    	//H5P.GoToQuestion
    	$H5PGoToQuestionParams = ['name' => "H5P.GoToQuestion", "major_version" => 1, "minor_version" => 3];
        $h5pGoToQuestionLib = DB::table('h5p_libraries')->where($H5PGoToQuestionParams)->first();
        if(empty($h5pGoToQuestionLib)) {
            $h5pGoToQuestionLibId = DB::table('h5p_libraries')->insertGetId([
                'name' => 'H5P.GoToQuestion',
                'title' => 'Crossroads',
                'major_version' => 1,
                'minor_version' => 3,
                'patch_version' => 13,
                'embed_types' => '',
                'runnable' => 0,
                'restricted' => 0,
                'fullscreen' => 0,
                'preloaded_js' => 'scripts/go-to-question.js',
                'preloaded_css' => 'styles/go-to-question.css',
                'drop_library_css' => '',
                'semantics' => $this->getH5PGoToQuestionSemantics(),
                'tutorial_url' => '',
                'has_icon' => 0
            ]);
        } else {
            $h5pGoToQuestionLibId = $h5pGoToQuestionLib->id;
        }
    	//H5P.IVHotspot
    	$H5PIVHotspotParams = ['name' => "H5P.IVHotspot", "major_version" => 1, "minor_version" => 2];
        $h5pIVHotspotLib = DB::table('h5p_libraries')->where($H5PIVHotspotParams)->first();
        if(empty($h5pIVHotspotLib)) {
            $h5pIVHotspotLibId = DB::table('h5p_libraries')->insertGetId([
                'name' => 'H5P.IVHotspot',
                'title' => 'Navigation Hotspot',
                'major_version' => 1,
                'minor_version' => 2,
                'patch_version' => 14,
                'embed_types' => '',
                'runnable' => 0,
                'restricted' => 0,
                'fullscreen' => 0,
                'preloaded_js' => 'scripts/iv-hotspot.js',
                'preloaded_css' => 'styles/iv-hotspot.css',
                'drop_library_css' => '',
                'semantics' => $this->getH5PIVHotSpotSemantics(),
                'tutorial_url' => '',
                'has_icon' => 0
            ]);
        } else {
            $h5pIVHotspotLibId = $h5pIVHotspotLib->id;
        }
    	//H5P.Questionnaire
    	$H5PQuestionnaireParams = ['name' => "H5P.Questionnaire", "major_version" => 1, "minor_version" => 3];
        $h5pQuestionnaireLib = DB::table('h5p_libraries')->where($H5PQuestionnaireParams)->first();
        if(empty($h5pQuestionnaireLib)) {
            $h5pQuestionnaireLibId = DB::table('h5p_libraries')->insertGetId([
                'name' => 'H5P.Questionnaire',
                'title' => 'Questionnaire',
                'major_version' => 1,
                'minor_version' => 3,
                'patch_version' => 2,
                'embed_types' => 'iframe',
                'runnable' => 1,
                'restricted' => 0,
                'fullscreen' => 0,
                'preloaded_js' => 'dist/dist.js',
                'preloaded_css' => 'dist/styles.css',
                'drop_library_css' => '',
                'semantics' => $this->getH5PQuestionaireSemantics(),
                'tutorial_url' => '',
                'has_icon' => 1
            ]);
        } else {
            $h5pQuestionnaireLibId = $h5pQuestionnaireLib->id;
        }
    	//H5P.FontIcons
    	$H5PFontIconsParams = ['name' => "H5P.FontIcons", "major_version" => 1, "minor_version" => 0];
        $h5pFontIconsLib = DB::table('h5p_libraries')->where($H5PFontIconsParams)->first();
        if(empty($h5pFontIconsLib)) {
            $h5pFontIconsLibId = DB::table('h5p_libraries')->insertGetId([
                'name' => 'H5P.FontIcons',
                'title' => 'H5P.FontIcons',
                'major_version' => 1,
                'minor_version' => 0,
                'patch_version' => 6,
                'embed_types' => '',
                'runnable' => 0,
                'restricted' => 0,
                'fullscreen' => 0,
                'preloaded_js' => '',
                'preloaded_css' => 'styles/h5p-font-icons.css',
                'drop_library_css' => '',
                'semantics' => '',
                'tutorial_url' => '',
                'has_icon' => 0
            ]);
        } else {
            $h5pFontIconsLibId = $h5pFontIconsLib->id;
        }
    	//H5P.FreeTextQuestion
    	$H5PFreeTextQuestionParams = ['name' => "H5P.FreeTextQuestion", "major_version" => 1, "minor_version" => 0];
        $h5pFreeTextQuestionLib = DB::table('h5p_libraries')->where($H5PFreeTextQuestionParams)->first();
        if(empty($h5pFreeTextQuestionLib)) {
            $h5pFreeTextQuestionLibId = DB::table('h5p_libraries')->insertGetId([
                'name' => 'H5P.FreeTextQuestion',
                'title' => 'Free Text Question',
                'major_version' => 1,
                'minor_version' => 0,
                'patch_version' => 12,
                'embed_types' => '',
                'runnable' => 0,
                'restricted' => 0,
                'fullscreen' => 0,
                'preloaded_js' => 'h5p-free-text-question.js',
                'preloaded_css' => 'h5p-free-text-question.css',
                'drop_library_css' => '',
                'semantics' => $this->getH5PFreeTextQuestionSemantics(),
                'tutorial_url' => '',
                'has_icon' => 0
            ]);
        } else {
            $h5pFreeTextQuestionLibId = $h5pFreeTextQuestionLib->id;
        }

        DB::table('h5p_libraries_libraries')->where('library_id', $h5pEditorCIVLibId)->delete();
        DB::table('h5p_libraries_libraries')->insert([
        	[
        		'library_id' => $h5pEditorCIVLibId,
	            'required_library_id' => $h5pFontAwesomeLibId,
	            'dependency_type' => 'preloaded'
        	],
        	[
        		'library_id' => $h5pEditorCIVLibId,
	            'required_library_id' => $h5pDranNBarLibId,
	            'dependency_type' => 'preloaded'
        	],
        	[
        		'library_id' => $h5pEditorCIVLibId,
	            'required_library_id' => $h5pImageLibId,
	            'dependency_type' => 'preloaded'
        	],
        	[
        		'library_id' => $h5pEditorCIVLibId,
	            'required_library_id' => $h5pTextLibId,
	            'dependency_type' => 'preloaded'
        	],
        	[
        		'library_id' => $h5pEditorCIVLibId,
	            'required_library_id' => $h5pTableLibId,
	            'dependency_type' => 'preloaded'
        	],
        	[
        		'library_id' => $h5pEditorCIVLibId,
	            'required_library_id' => $h5pLinkLibId,
	            'dependency_type' => 'preloaded'
        	],
        	[
        		'library_id' => $h5pEditorCIVLibId,
	            'required_library_id' => $h5pSingleChoiceSetLibId,
	            'dependency_type' => 'preloaded'
        	],
        	[
        		'library_id' => $h5pEditorCIVLibId,
	            'required_library_id' => $h5pMultiChoiceLibId,
	            'dependency_type' => 'preloaded'
        	],
        	[
        		'library_id' => $h5pEditorCIVLibId,
	            'required_library_id' => $h5pTrueFalseLibId,
	            'dependency_type' => 'preloaded'
        	],
        	[
        		'library_id' => $h5pEditorCIVLibId,
	            'required_library_id' => $h5pCIVLibId,
	            'dependency_type' => 'preloaded'
        	],
        	[
        		'library_id' => $h5pEditorCIVLibId,
	            'required_library_id' => $h5pSummaryLibId,
	            'dependency_type' => 'preloaded'
        	],
        	[
        		'library_id' => $h5pEditorCIVLibId,
	            'required_library_id' => $h5pDurationLibId,
	            'dependency_type' => 'preloaded'
        	],
        	[
        		'library_id' => $h5pEditorCIVLibId,
	            'required_library_id' => $h5pTimecodeLibId,
	            'dependency_type' => 'preloaded'
        	],
        	[
        		'library_id' => $h5pEditorCIVLibId,
	            'required_library_id' => $h5pSelectToggleFieldsLibId,
	            'dependency_type' => 'preloaded'
        	],
        	[
        		'library_id' => $h5pEditorCIVLibId,
	            'required_library_id' => $h5pColorSelectorLibId,
	            'dependency_type' => 'preloaded'
        	],
        	[
        		'library_id' => $h5pEditorCIVLibId,
	            'required_library_id' => $h5pBlanksLibId,
	            'dependency_type' => 'preloaded'
        	],
        	[
        		'library_id' => $h5pEditorCIVLibId,
	            'required_library_id' => $h5pNillLibId,
	            'dependency_type' => 'preloaded'
        	],
        	[
        		'library_id' => $h5pEditorCIVLibId,
	            'required_library_id' => $h5pDragQuestionLibId,
	            'dependency_type' => 'preloaded'
        	],
        	[
        		'library_id' => $h5pEditorCIVLibId,
	            'required_library_id' => $h5pMarkTheWordsLibId,
	            'dependency_type' => 'preloaded'
        	],
        	[
        		'library_id' => $h5pEditorCIVLibId,
	            'required_library_id' => $h5pDragTextLibId,
	            'dependency_type' => 'preloaded'
        	],
        	[
        		'library_id' => $h5pEditorCIVLibId,
	            'required_library_id' => $h5pGuidedTourLibId,
	            'dependency_type' => 'preloaded'
        	],
        	[
        		'library_id' => $h5pEditorCIVLibId,
	            'required_library_id' => $h5pGoToQuestionLibId,
	            'dependency_type' => 'preloaded'
        	],
        	[
        		'library_id' => $h5pEditorCIVLibId,
	            'required_library_id' => $h5pIVHotspotLibId,
	            'dependency_type' => 'preloaded'
        	],
        	[
        		'library_id' => $h5pEditorCIVLibId,
	            'required_library_id' => $h5pQuestionnaireLibId,
	            'dependency_type' => 'preloaded'
        	],
        	[
        		'library_id' => $h5pEditorCIVLibId,
	            'required_library_id' => $h5pFontIconsLibId,
	            'dependency_type' => 'preloaded'
        	],
        	[
        		'library_id' => $h5pEditorCIVLibId,
	            'required_library_id' => $h5pFreeTextQuestionLibId,
	            'dependency_type' => 'preloaded'
        	]
        ]);
    }

    /**
     * Insert Library Language Semantics
     * @param $h5pEditorCIVLibId
     */
    private function insertLibrariesLanguages($h5pEditorCIVLibId)
    {
    	DB::table('h5p_libraries_languages')->where('library_id', $h5pEditorCIVLibId)->delete();
    	DB::table('h5p_libraries_languages')->insert([
    		//ar.json
    		[
    			'library_id' => $h5pEditorCIVLibId,
            	'language_code' => 'ar',
            	'translation' => '{
									"libraryStrings":{
									    "selectVideo":"يجب تحديد مقطع فيديو قبل إضافة التفاعل.",
									    "noVideoSource":"لا يوجد مصدر فيديو",
									    "notVideoField":"\":path\" ليس فيديو.",
									    "notImageField":"\":path\" ليست صورة.",
									    "insertElement":"أنقر وحرك :type",
									    "popupTitle":"تحرير :type",
									    "done":"تم الاطلاع عليه",
									    "loading":"تحميل ...",
									    "remove":"حذف",
									    "removeInteraction":"هل تريد حذف هذا التفاعل بالتأكيد؟",
									    "addBookmark":"إضافة إشارة مرجعية",
									    "newBookmark":"إشارة جديدة",
									    "bookmarkAlreadyExists":"الإشارة المرجعية موجودة من قبل هنا. حرك زر التشغيل وأضف إشارة مرجعية أو شاشة نهاية في وقت مختلف.",
									    "tourButtonStart":"ابدأ",
									    "tourButtonExit":"خروج",
									    "tourButtonDone":"تم",
									    "tourButtonBack":"السابق",
									    "tourButtonNext":"التالي",
									    "tourStepUploadIntroText":"<p>هذه الجولة ترشدك من خلال الملامح الرئيسية لمحرر الفيديو التفاعلي.<\/p><p>يمكن بدء هذه الجولة في أي وقت عن طريق النقر على زر الجولة في الجانب العلوي الأيسر.<\/p><p>من خلال النقر على خروج، يمكن تخطي هذه الجولة أو يمكنك الاستمرار بنقرة واحدة على التالي<\/p>",
									    "tourStepUploadFileTitle":"إضافة فيديو",
									    "tourStepUploadFileText":"<p>ابدأ بإضافة ملف فيديو. يمكنك تحميل ملف من الكمبيوتر، أو إدراج عنوان رابط لفيديو يوتيوب، أو استخدام ملف فيديو مدعوم.<\/p><p>لضمان التوافق عبر متصفحات متعددة، يمكن تحميل تنسيقات ملفات متعددة للفيديو نفسه، مثل mp4 و webm.<\/p>",
									    "tourStepUploadAddInteractionsTitle":"إضافة نقاط التفاعل",
									    "tourStepUploadAddInteractionsText":"<p>بعد إضافة مقطع فيديو، يمكن أن يبدأ في إضافة تفاعلات.<\/p><p>بنقرة على  <em>إضافة تفاعل<\/em> علامة التبويب يبدأ.<\/p>",
									    "tourStepCanvasToolbarTitle":"إضافة تفاعلات",
									    "tourStepCanvasToolbarText":"لإضافة تفاعل، يجب سحب عنصر من شريط الأدوات ووضعه على الفيديو.",
									    "tourStepCanvasEditingTitle":"تعديل التفاعلات",
									    "tourStepCanvasEditingText":"<p>بمجرد إضافة التفاعل، يمكن سحبه لإعادة وضعه.<\/p><p>عند تحديد تفاعل، ستظهر قائمة سياقية. لتعديل محتوى التفاعل، يجب الضغط على زر تحرير في قائمة السياق. يمكن إزالة التفاعل عن طريق الضغط على زر إزالة في قائمة السياق.<\/p>",
									    "tourStepCanvasBookmarksTitle":"المرجعية",
									    "tourStepCanvasBookmarksText":"يمكن إضافة الإشارات المرجعية من قائمة الإشارات المرجعية. انقر على زر الإشارة المرجعية لفتح القائمة.",
									    "tourStepCanvasEndscreensTitle":"إنهاء الشاشات",
									    "tourStepCanvasEndscreensText":"يمكن إضافة شاشات نهاية من قائمة الشاشة نهاية. انقر على زر الإشارة المرجعية لفتح القائمة.",
									    "tourStepCanvasPreviewTitle":"معاينة الفيديو التفاعلي",
									    "tourStepCanvasPreviewText":"أثناء التعديل، انقر على زر التشغيل لمعاينة الفيديو التفاعلي.",
									    "tourStepCanvasSaveTitle":"حفظ ومشاهدة",
									    "tourStepCanvasSaveText":"بعد الانتهاء، انقر على الزر حفظ / إنشاء لمشاهدة النتيجة النهائية.",
									    "tourStepSummaryText":"ستظهر مهمة الملخص التفاعلي الاختيارية هذه في نهاية الفيديو.",
									    "fullScoreRequiredPause":"\"Full score required\" option requires that \"Pause\" is enabled.",
									    "fullScoreRequiredRetry":"\"Full score required\" option requires that \"Retry\" is enabled.",
									    "fullScoreRequiredTimeFrame":"هناك بالفعل تفاعل يتطلب درجة كاملة في نفس الفترة مثل هذا التفاعل..<br /> سوف تكون هناك حاجة واحدة فقط من التفاعلات للرد.",
									    "addEndscreen":"إضافة شاشة النهاية",
									    "endscreen":"إنهاء الشاشة",
									    "endscreenAlreadyExists":"شاشة النهاية موجودة بالفعل هنا. نقل بلايهيد وإضافة شاشة نهاية أو إشارة مرجعية في وقت آخر.",
									    "tooltipBookmarks":"Click to add bookmark at the current point in the video",
									    "tooltipEndscreens":"Click to add submit screen at the current point in the video",
									    "expandBreadcrumbButtonLabel": "Go back",
									    "collapseBreadcrumbButtonLabel": "Close navigation"
								    }
								}'
    		],
    		//bg.json
    		[
    			'library_id' => $h5pEditorCIVLibId,
            	'language_code' => 'bg',
            	'translation' => '{
									"libraryStrings": {
									    "selectVideo": "Трябва да изберете видеоклип, преди да добавите интерактивност.",
									    "noVideoSource": "Няма видео източник",
									    "notVideoField": "\":path\" не е видео.",
									    "notImageField": "\":path\" не е изображение.",
									    "insertElement": "Кликнете и плъзнете, за да поставите :тип",
									    "popupTitle": "Редактиране на :типа",
									    "done": "Готово",
									    "loading": "Зареждане...",
									    "remove": "Премахване",
									    "removeInteraction": "Сигурен ли сте, че искате да премахнете тази интерактивност?",
									    "addBookmark": "Добавете отметка в @timecode",
									    "newBookmark": "Нова отметка",
									    "bookmarkAlreadyExists": "Отметката вече съществева тук. Преместете възпроизвеждащата глава и добавете отметка или екран за подаване по друго време.",
									    "tourButtonStart": "Обиколка",
									    "tourButtonExit": "Изход",
									    "tourButtonDone": "Готово",
									    "tourButtonBack": "Назад",
									    "tourButtonNext": "Следващ",
									    "tourStepUploadIntroText": "<p>Тази обиколка ви води през най-важните функции на редактора на интерактивното видео.</p><p>Започнете тази обиколка по всяко време, като натиснете бутона Обиколка в горния десен ъгъл. </p> <p>Натиснете бутона Изход, за да излезете от тази обиколка или натиснете бутона Следващ, за да продължите.</p>",
									    "tourStepUploadFileTitle": "Добавяне на видео",
									    "tourStepUploadFileText": "<p>Започнете с добавяне на видео файл. Можете да качите файл от компютъра си или да го поставите линк от YouTube или в поддържан видеофайл. </p> <p> За да осигурите съвместимост между браузърите, можете да качите множество файлови формати от един и същи видеоклип, като например mp4 и WebM.</p>",
									    "tourStepUploadAddInteractionsTitle": "Добавяне на интерактиност",
									    "tourStepUploadAddInteractionsText": "<p>След като добавите видеоклип, можете да започнете да добавяте интерактивност. </p> <p> Натиснете раздела <em> Добавяне на интерактивност </em>, за да започнете.</p>",
									    "tourStepCanvasToolbarTitle": "Добавяне на интерактивност",
									    "tourStepCanvasToolbarText": "За да добавите интерактивност, плъзнете елемент от лентата с инструменти и го пуснете върху видеоклипа.",
									    "tourStepCanvasEditingTitle": "Редактиране на интерактивността",
									    "tourStepCanvasEditingText": "<p>След като е добавена интерактивност, можете да я плъзнете, за да я преместите. </p> <p> За да промените размера на интерактивността натиснете и плъзнете. </p> <p> Когато изберете интерактивност контекстното меню ще се появи. За да редактирате съдържанието на интерактивността натиснете бутона Редактиране от менюто. Можете да премахнете интерактивността  чрез натискане на бутона Премахване в менюто.</p>",
									    "tourStepCanvasBookmarksTitle": "Отметки",
									    "tourStepCanvasBookmarksText": "Можете да добавите отметки от менюто Отметки. Натиснете бутона Отметки, за да отворите менюто.",
									    "tourStepCanvasEndscreensTitle": "Екрани",
									    "tourStepCanvasEndscreensText": "Можете да добавите екрани за показване от менюто за показване на екрани. Натиснете бутона за показване на екрана, за да отворите менюто.",
									    "tourStepCanvasPreviewTitle": "Преглед на Вашето интерактивно видео",
									    "tourStepCanvasPreviewText": "Натиснете бутона Възпроизвеждане/Play, за да прегледате интерактивното видео, докато редактирате.",
									    "tourStepCanvasSaveTitle": "Запази и виж",
									    "tourStepCanvasSaveText": "Когато приключите с добавянето на интерактивност към видеоклипа си, натиснете Запазване / Създаване/Save/Create, за да видите резултата..",
									    "tourStepSummaryText": "Това опционално обобщение резултатите от интерактивностто ще се покаже в края на видеото.",
									    "fullScoreRequiredPause": "\"Изискване на пълен резултат\" е необходима, когато опцията \"Пауза\" е разрешена.",
									    "fullScoreRequiredRetry": "\"Изискване на пълен резултат\" е необходима, когато опцията \"Опитай пак\" е разрешена.",
									    "fullScoreRequiredTimeFrame": "Вече съществува интерактивност, която изисква пълен резултат в същия интервал на тази интерактивност. <br /> Само една от интерактивностите ще трябва да отговори.",
									    "addEndscreen": "Добави Submit Екран към @timecode",
									    "endscreen": "Submit Екран",
									    "endscreenAlreadyExists": "Submit екрана вече съществува тук. Премести playhead и добави submit екран или сложи отметката в друго време.",
									    "tooltipBookmarks": "Кликнете, за да добавите отметка в текущата точка на видеоклипа",
									    "tooltipEndscreens": "Кликнете, за да добавите submit екран в текущата точка във видеоклипа",
									    "expandBreadcrumbButtonLabel": "Върни се",
									    "collapseBreadcrumbButtonLabel": "Затвaряне на навигацията"
									}
								}'
    		],
    		//bs.json
    		[
    			'library_id' => $h5pEditorCIVLibId,
            	'language_code' => 'bs',
            	'translation' => '{
									"libraryStrings": {
									    "selectVideo": "Mora biti odabran video prije njegove interaktivne obrade.",
									    "noVideoSource": "Nema video izvora",
									    "notVideoField": "\":path\" nije video.",
									    "notImageField": "\":path\" nije slika.",
									    "insertElement": "Klikni i donesi na donesi na odgovarajuće mjesto :type",
									    "popupTitle": "Uredi :type",
									    "done": "Gotovo",
									    "loading": "Učitava se...",
									    "remove": "Ukloni",
									    "removeInteraction": "Ukloniti ovu interakciju?",
									    "addBookmark": "Dodati oznaku (Bookmark) ",
									    "newBookmark": "Nova oznaka (Bookmark)",
									    "bookmarkAlreadyExists": "Oznake već postoje. Play-dugme pomjeri i unesi oznaku u neko drugo vrijeme.",
									    "tourButtonStart": "Idi",
									    "tourButtonExit": "Izlaz",
									    "tourButtonDone": "Gotovo",
									    "tourButtonBack": "Nazad",
									    "tourButtonNext": "Dalje",
									    "tourStepUploadIntroText": "<p>Ovaj video prolazi kroz najvažnije osobine Interaktivnog videoeditora.</p><p>Video može biti pušten u bilo koje vrijeme klikom na dugme u desnom gornjem uglu.</p><p>Klikom na IZLAZ, ovaj video može biti preskočen ili nastavljen klikom na DALJE.</p>",
									    "tourStepUploadFileTitle": "Dodavanje videa",
									    "tourStepUploadFileText": "<p>Počni s postavljanjem video fajla. Video može biti uploadovan sa kompjutera, unošenjem URL linka za unošenje YouTube videa ili da se koristi video fajl sa podržanim formatom.</p><p>Kako bi video bio kompatibilan sa većinom pretraživača budite sigurni da je video formata mp4 i webm.</p>",
									    "tourStepUploadAddInteractionsTitle": "Dodavanje interakcije ",
									    "tourStepUploadAddInteractionsText": "<p>Kada video bude učitan, može se početi sa dodavanjem interakcija.</p><p>Sa klikom na tab <em>Dodavanje interakcija</em>.</p>",
									    "tourStepCanvasToolbarTitle": "Dodavanje interakcija",
									    "tourStepCanvasToolbarText": "Kako bi se dodala interakcija mora biti jedan element sa liste alata uzet i postavljen na video.",
									    "tourStepCanvasEditingTitle": "Uređivanje interakcija",
									    "tourStepCanvasEditingText": "<p>Kada bude dodana interakcija, ona može biti premještena na neko drugo mjesto.</p><p>Kada bude neka interakcija odabrana, onda se pojavi kontekstualni menu. Kako bi sadržaj interakcije uređivali, mora se kliknuti na dugme Uredi u kontektualnom menuju. Interakcija može biti uklonjena klikom na dugme Ukloni koje se nalazi u kontekstualnom meniju.</p>",
									    "tourStepCanvasBookmarksTitle": "Oznake - Bookmarks",
									    "tourStepCanvasBookmarksText": "Oznake - Bookmarks se mogu unijeti iz menija za oznake. Kliknuti na dugme za oznake kako bi se otvorio menu.",
									    "tourStepCanvasEndscreensTitle":"Submit Screens",
									    "tourStepCanvasEndscreensText":"You can add submit screens from the submit screens menu. Press the submit screen button to open the menu.",
									    "tourStepCanvasPreviewTitle": "Pregled interaktivnog videa",
									    "tourStepCanvasPreviewText": "Pritisni dugme Pusti kako bi mogao pregledati video tokom obrade.",
									    "tourStepCanvasSaveTitle": "Spašavanje i pregledavanje",
									    "tourStepCanvasSaveText": "Kada je unošenje interakcija u jedan video završeno  onda je moguće vidjeti rezultatet klikom na Spasi/Napravi.",
									    "tourStepSummaryText": "Opcija u kojoj se sažeto govori o videu biće pokazana nakon završetka videa.",
									    "fullScoreRequiredPause": "\"Obavezan kompletan rezultat\" zahtjeva da je dugme \"Pausa\" omogućeno.",
									    "fullScoreRequiredRetry": "\"Obavezan kompletan rezultat\" zahtjeva da je dugme \"Ponovi\" omogućeno.",
									    "fullScoreRequiredTimeFrame": "There already exists an interaction that requires full score at the same interval as this interaction.<br /> Only one of the interactions will be required to answer.",
									    "addEndscreen":"Add submit screen at @timecode",
									    "endscreen":"Submit screen",
									    "endscreenAlreadyExists":"Submit screen already exists here. Move playhead and add a submit screen or a bookmark at another time.",
									    "tooltipBookmarks":"Click to add bookmark at the current point in the video",
									    "tooltipEndscreens":"Click to add submit screen at the current point in the video",
									    "expandBreadcrumbButtonLabel": "Go back",
									    "collapseBreadcrumbButtonLabel": "Close navigation"
								   }
								}'
    		],
    		//ca.json
    		[
    			'library_id' => $h5pEditorCIVLibId,
            	'language_code' => 'ca',
            	'translation' => '{
									"libraryStrings": {
									    "selectVideo": "Heu de seleccionar un vídeo abans d’afegir interaccions.",
									    "noVideoSource": "No hi ha font de vídeo",
									    "notVideoField": "\":path\" no es un vídeo.",
									    "notImageField": "\":path\" no es una imatge.",
									    "insertElement": "Feu clic i arrossegueu per col·locar :type",
									    "popupTitle": "Edita :type",
									    "done": "Fet",
									    "loading": "S’està carregant...",
									    "remove": "Suprimeix",
									    "removeInteraction": "Segur que voleu suprimir aquesta interacció?",
									    "addBookmark": "Afegeix un marcador a @timecode",
									    "newBookmark": "Marcador nou",
									    "bookmarkAlreadyExists": "Aquí ja hi ha un marcador. Moveu l’indicador de reproducció i afegiu un marcador o una pantalla d’enviament en un altre moment.",
									    "tourButtonStart": "Recorregut",
									    "tourButtonExit": "Surt",
									    "tourButtonDone": "Fet",
									    "tourButtonBack": "Tornar",
									    "tourButtonNext": "Següent",
									    "tourStepUploadIntroText": "<p>Aquest recorregut us porta per les funcions més importants de l’editor de vídeo interactiu.</p><p>Podeu iniciar aquest recorregut en qualsevol moment prement el botó Recorregut situat a l’extrem superior dret.</p><p>Premeu SURT per ometre aquest recorregut o premeu SEGÜENT per continuar.</p>",
									    "tourStepUploadFileTitle": "Afegint vídeo",
									    "tourStepUploadFileText": "<p>Comenceu per afegir un fitxer de vídeo. Podeu pujar un fitxer de l’ordinador o enganxar un URL que dirigeixi a un vídeo de YouTube o a un fitxer de vídeo admès.</p><p>Per garantir la compatibilitat entre navegadors, podeu pujar el mateix vídeo en diversos formats de fitxer, com ara MP4 i WEBM.</p>",
									    "tourStepUploadAddInteractionsTitle": "Addició d’interaccions",
									    "tourStepUploadAddInteractionsText": "<p>Quan hàgiu afegit un vídeo, podreu començar a afegir interaccions.</p><p>Premeu la pestanya <em>Afegeix interaccions</em> per començar.</p>",
									    "tourStepCanvasToolbarTitle": "Addició d’interaccions",
									    "tourStepCanvasToolbarText": "Per afegir una interacció, arrossegueu un element de la barra d’eines i deixeu-lo anar al vídeo.",
									    "tourStepCanvasEditingTitle": "S’estan editant les interaccions",
									    "tourStepCanvasEditingText": "<p>Un cop s’ha afegit una interacció, podeu arrossegar-la per canviar-la de lloc.</p><p>Per canviar la mida d’una interacció, premeu-ne el punter i arrossegueu-lo.</p><p>En seleccionar una interacció, es mostrarà un menú contextual. Per editar el contingut de la interacció, premeu el botó \"Edita\" del menú contextual. Per suprimir una interacció, premeu el botó \"Suprimeix\" del menú contextual.</p> ",
									    "tourStepCanvasBookmarksTitle": "Marcadors",
									    "tourStepCanvasBookmarksText": "Podeu afegir marcadors des del menú de marcadors. Premeu el botó \"Marcador\" per obrir aquest menú.",
									    "tourStepCanvasEndscreensTitle": "Pantalles d’enviament",
									    "tourStepCanvasEndscreensText": "Podeu afegir pantalles d’enviament des del menú de pantalles d’enviament. Premeu el botó de pantalla d’enviament per obrir aquest menú.",
									    "tourStepCanvasPreviewTitle": "Previsualitza el vídeo interactiu",
									    "tourStepCanvasPreviewText": "Premeu el botó \"Reprodueix\" per previsualitzar el vídeo interactiu durant l’edició.",
									    "tourStepCanvasSaveTitle": "Desa i mostra",
									    "tourStepCanvasSaveText": "Quan hàgiu acabat d’afegir interaccions al vídeo, premeu Desa/Crea per veure’n el resultat.",
									    "tourStepSummaryText": "Aquest qüestionari de resum opcional es mostrarà al final del vídeo.",
									    "fullScoreRequiredPause": "L’opció \"Es requereix una puntuació total\" requereix que l’opció \"Pausa\" estigui activada.",
									    "fullScoreRequiredRetry": "L’opció \"Es requereix una puntuació total\" requereix que l’opció \"Torna-ho a provar\" estigui activada.",
									    "fullScoreRequiredTimeFrame": "Ja hi ha una interacció que requereix la puntuació total en el mateix interval que aquesta interacció.<br /> Només es requerirà que es respongui a una de les interaccions.",
									    "addEndscreen": "Afegeix una pantalla d’enviament a @timecode",
									    "endscreen": "Pantalla d’enviament",
									    "endscreenAlreadyExists": "Aquí ja hi ha una pantalla d’enviament. Moveu l’indicador de reproducció i afegiu una pantalla d’enviament o un marcador més endavant.",
									    "tooltipBookmarks": "Feu clic per afegir un marcador al punt actual del vídeo.",
									    "tooltipEndscreens": "Feu clic per afegir una pantalla d’enviament al punt actual del vídeo.",
									    "expandBreadcrumbButtonLabel": "Enrere",
									    "collapseBreadcrumbButtonLabel": "Tanca la navegació"
								    }
								}'
    		],
    		//cs.json
    		[
    			'library_id' => $h5pEditorCIVLibId,
            	'language_code' => 'cs',
            	'translation' => '{
									"libraryStrings": {
									    "selectVideo": "Před přidáním interakcí musíte vybrat video.",
									    "noVideoSource": "Žádný zdroj videa",
									    "notVideoField": "\":path\" není video.",
									    "notImageField": "\":path\" není obrázek.",
									    "insertElement": "Kliknětě a přetáhněte na místo :type",
									    "popupTitle": "Upravit :type",
									    "done": "Hotovo",
									    "loading": "Nahrávám...",
									    "remove": "Vymazat",
									    "removeInteraction": "Skutečně chcete smazat tuto interakci?",
									    "addBookmark": "Nastavit záložku na @timecode",
									    "newBookmark": "Nová záložka",
									    "bookmarkAlreadyExists": "Záložka již existuje. Nastavte posuvník jinam a přidejte záložku nebo přidejte obrzovku v jiném čase.",
									    "tourButtonStart": "Průvodce",
									    "tourButtonExit": "Konec",
									    "tourButtonDone": "Hotovo",
									    "tourButtonBack": "Zpět",
									    "tourButtonNext": "Další",
									    "tourStepUploadIntroText": "<p>Tento průvodce vás provede nedůležitějšími parametry Interaktivního video editoru.</p><p>Stisknutím tlačítka Průvodce v pravém horním rohu spusťte kdykoliv tuto akci.</p><p>Stikněte KONEC pro přeskočení průvodce nebo stiskněte DALŠÍ pro pokračování.</p>",
									    "tourStepUploadFileTitle": "Přidání videa",
									    "tourStepUploadFileText": "<p>Začněte přidáním videa. Můžete nahrát soubor z vašeho počítače nebo zadejte URL odkaz na YouTube či podporovaného videa.</p><p>Aby byla zachována kompatibilita skrz prohlížeče, nahrajte více verzí jednoho videa jako např. mp4 nebo webm.</p>",
									    "tourStepUploadAddInteractionsTitle": "Přidání interakcí",
									    "tourStepUploadAddInteractionsText": "<p>Poté, co jste přidali video, můžete začít s přidáním interakcí.</p><p>Abyste začali s přidáváním, klikněte na záložku <em>Přidat interakce</em>.</p>",
									    "tourStepCanvasToolbarTitle": "Přidání interakcí",
									    "tourStepCanvasToolbarText": "Pro přidání interakce přetáhněte element z nástrojové lišty přímo na video.",
									    "tourStepCanvasEditingTitle": "Úpravy interakcí",
									    "tourStepCanvasEditingText": "<p>Poté co byla interakce přidána, můžete ji přetažením přemístit.</p><p>Pro zvětšení interakce klikněte na úchyt a přetáhněte jej.</p><p>Pokud vyberete interakci, objeví se kontextová nabídka. Pro úpravu interakce stiskněte tlačíto Upravit v kontextové nabídce. Interakci můžete odstranit  kliknutím na tlačítko Odstranit z kontextové nabídky.</p>",
									    "tourStepCanvasBookmarksTitle": "Záložky",
									    "tourStepCanvasBookmarksText": "Můžete přidat záložky z nabídky záložek. Klikněte na tlačítko Záložky pro otevření nabídky.",
									    "tourStepCanvasEndscreensTitle": "Odesílací obrazovka",
									    "tourStepCanvasEndscreensText": "Můžete přidat odesílací obrazovky z nabídky Odesílací obrazovky. Klikněte na tlačítko Odesílací obrazovka pro otevření nabídky.",
									    "tourStepCanvasPreviewTitle": "Náhled vašeho interaktivního videa",
									    "tourStepCanvasPreviewText": "Stiskněte tlačítko Přehrát pro nádleh na video při editaci.",
									    "tourStepCanvasSaveTitle": "Uložit a prohlédnout",
									    "tourStepCanvasSaveText": "Pokud jste dokončili přidávání interakcí do videa, stiskněte Uložit/Vytvořit pro zobrazení výsledného videa.",
									    "tourStepSummaryText": "Tento volitelný shrnující kvíz se objeví na konci videa.",
									    "fullScoreRequiredPause": "Volba \"Úplné hodnocení požadováno\" je nutná pro povolení \"Pozastavení\".",
									    "fullScoreRequiredRetry": "Volba \"Úplné hodnocení požadováno\" je nutná pro povolení \"Znovu\"",
									    "fullScoreRequiredTimeFrame": "Již zde existuje interakce, která vyžaduje úplné hodnocení stejného intervalu jako tato interakce.<br />K zodpovězení bude stačit pouze jedna interakce.",
									    "addEndscreen": "Přidat oesílací obrazovku na @timecode",
									    "endscreen": "Odesílací obrazovka",
									    "endscreenAlreadyExists": "Odesílací obrazovka již zde existuje. Posuňte posuvník a přidejte Odesílací obrazovku  nebo záložku na jiný čas.",
									    "tooltipBookmarks": "Klikněte pro přidání záložky do vybraného bodu ve videu",
									    "tooltipEndscreens": "Klikněte pro přidání Odesílací obrazovky do vybraného bodu ve videu",
									    "expandBreadcrumbButtonLabel": "Jít zpět",
									    "collapseBreadcrumbButtonLabel": "Zavřít navigaci"
								    }
								}'
    		],
    		//da.json
    		[
    			'library_id' => $h5pEditorCIVLibId,
            	'language_code' => 'da',
            	'translation' => '{
									"libraryStrings":{
									    "selectVideo":"Du skal vælge en video, før du kan påføre interaktion",
									    "noVideoSource":"No Video Source",
									    "notVideoField":"\":path\" er ikke en video.",
									    "notImageField":"\":path\" er ikke et billede",
									    "insertElement":"Klik og træk for at placere :type",
									    "popupTitle":"Ændre :type",
									    "done":"Færdig",
									    "loading":"Indlæser.",
									    "remove":"Fjern",
									    "removeInteraction":"Er du sikker på at du vil fjerne denne interaktion?",
									    "addBookmark":"Tilføj bogmærke på @timecode",
									    "newBookmark":"Nyt bogmærke",
									    "bookmarkAlreadyExists":"Der findes allerede et bogmærke her. Spol og placer bogmærket eller resultatskærmen på en anden tidskode.",
									    "tourButtonStart":"Rundvisning",
									    "tourButtonExit":"Afslut",
									    "tourButtonDone":"Færdig",
									    "tourButtonBack":"Forrige",
									    "tourButtonNext":"Næste",
									    "tourStepUploadIntroText":"<p>Denne rundvisning forklarer de vigtigste funktioner i editoren til Interaktiv Video.<\/p><p>Du kan når som helst starte rundvisningen ved at klikke på rundsvisningsknappen i øvre højre hjørne.<\/p><p>Tryk på afslut for at hoppe over rundvisningen, eller tryk på NÆSTE for at fortsætte.<\/p>",
									    "tourStepUploadFileTitle":"Tilføj en video",
									    "tourStepUploadFileText":"<p>Start med at tilføje en videofil. Du kan enten uploade en videofil fra dit device eller indsætte en URL til en YouTube- eller understøttet videofil.<\/p><p>For at være kompatibel med alle browsere, kan du uploade videofiler i flere forskellige formater, f.eks. mp4 og webm.<\/p>",
									    "tourStepUploadAddInteractionsTitle":"Tilføj interaktion",
									    "tourStepUploadAddInteractionsText":"<p>Når du har tilføjet en video, kan du tilføje interaktioner.<\/p><p>Tryk på fanen <em>Tilføj interaktion<\/em> for at starte.<\/p>",
									    "tourStepCanvasToolbarTitle":"Tilføj interaktion",
									    "tourStepCanvasToolbarText":"For at tilføje en interkation, skal du trække et element fra værktøjslinjen, og slippe det på videoen.",
									    "tourStepCanvasEditingTitle":"Rediger interaktion",
									    "tourStepCanvasEditingText":"<p>Når en interaktion er tilføjet, kan du trække i den for at flytte på den.<\/p><p>For at ændre størrelsen, skal du trykke på håndtagene og trække.<\/p><p>Når du vælger et element dukker der en kontekstmenu op. For at redigere indholdet til en interaktion, tryk da på Rediger-knappen i kontekstmenuen. Du kan slette en interaktion ved at trykke på Slet-knappen i kontekstmenuen.<\/p>",
									    "tourStepCanvasBookmarksTitle":"Bogmærker",
									    "tourStepCanvasBookmarksText":"Bogmærker kan tilføjes gennem bogmærkemenuen. Klik på bogmærkeknappen for at åbne menuen",
									    "tourStepCanvasEndscreensTitle":"Submit Screens",
									    "tourStepCanvasEndscreensText":"You can add submit screens from the submit screens menu. Press the submit screen button to open the menu.",
									    "tourStepCanvasPreviewTitle":"Forhåndsvisning",
									    "tourStepCanvasPreviewText":"Tryk på playknappen for at forhåndsvise din interaktive video mens du redigerer.",
									    "tourStepCanvasSaveTitle":"Gem og vis",
									    "tourStepCanvasSaveText":"Når du er færdig, tryk Gem\/Opret for at se det endelige resultatet.",
									    "tourStepSummaryText":"Denne valgfrie, interaktive opsummeringsopgave vil blive vist i slutningen af videoen.",
									    "fullScoreRequiredPause":"\"Alle rigtige\" alternativet kræver at \"Pause\" er aktiveret.",
									    "fullScoreRequiredRetry":"\"Alle rigtige\" alternativet kræver at \"Prøv igen\" er aktiveret.",
									    "fullScoreRequiredTimeFrame":"Det findes allerede en indholdstype som kræver alle rigtige i dette tidsinterval.<br /> Bare en af indholdstyperne vil kræve alt rigtigt.",
									    "addEndscreen":"Add submit screen at @timecode",
									    "endscreen":"Submit screen",
									    "endscreenAlreadyExists":"Submit screen already exists here. Move playhead and add a submit screen or a bookmark at another time.",
									    "tooltipBookmarks":"Click to add bookmark at the current point in the video",
									    "tooltipEndscreens":"Click to add submit screen at the current point in the video",
									    "expandBreadcrumbButtonLabel": "Go back",
									    "collapseBreadcrumbButtonLabel": "Close navigation"
								    }
								}'
    		],
    		//de.json
    		[
    			'library_id' => $h5pEditorCIVLibId,
            	'language_code' => 'de',
            	'translation' => '{
									"libraryStrings": {
									    "selectVideo": "Es muss ein Video ausgewählt werden, bevor eine Interaktion hinzugefügt werden kann.",
									    "noVideoSource": "Fehlendes Video",
									    "notVideoField": "Unter \":path\" ist kein Video zu finden.",
									    "notImageField": "Unter \":path\" ist kein Bild zu finden.",
									    "insertElement": "Klicken und an die gewünschte Stelle ziehen, um :type zu platzieren",
									    "popupTitle": ":type bearbeiten",
									    "done": "Fertig",
									    "loading": "Lädt...",
									    "remove": "Entfernen",
									    "removeInteraction": "Diese Interaktion ganz sicher löschen?",
									    "addBookmark": "Lesezeichen hinzufügen bei @timecode",
									    "newBookmark": "Neues Lesezeichen",
									    "bookmarkAlreadyExists": "Lesezeichen existiert hier bereits. Bewege den Abspielknopf und füge ein Lesezeichen oder eine Antwortübermittlung an einer anderen Stelle hinzu.",
									    "tourButtonStart": "Tour",
									    "tourButtonExit": "Beenden",
									    "tourButtonDone": "Fertig",
									    "tourButtonBack": "Zurück",
									    "tourButtonNext": "Weiter",
									    "tourStepUploadIntroText": "<p>Diese Tour führt durch die wichtigsten Funktionen des Interaktiven Videoeditors.</p><p>Du kannst sie jederzeit starten, indem du auf den Tour-Button in der oberen rechten Ecke klickst.</p><p>Klicke auf BEENDEN, um sie zu überspringen oder auf WEITER, um mit ihr fortzufahren.</p>",
									    "tourStepUploadFileTitle": "Ein Video hinzufügen",
									    "tourStepUploadFileText": "<p>Beginne, indem du eine Videodatei hinzufügst. Es kann eine Datei vom Computer hochgeladen oder eine URL zu einem YouTube-Video bzw. einer Online-Videodatei eingefügt werden.</p><p>Um die Kompatibilität über mehrere Browser hinweg sicher zu stellen, können mehrere Dateiformate des gleichen Videos hochgeladen werden, wie beispielsweise mp4 und webm.</p>",
									    "tourStepUploadAddInteractionsTitle": "Hinzufügen von Interaktionen",
									    "tourStepUploadAddInteractionsText": "<p>Sobald du ein Video hinzugefügt hast, kannst du Interaktionen hinzufügen.</p><p>Mit einem Klick auf <em>Interaktion hinzufügen</em> geht es los.</p>",
									    "tourStepCanvasToolbarTitle": "Interaktionen hinzufügen",
									    "tourStepCanvasToolbarText": "Um eine Interaktion hinzuzufügen, muss ein Element von der Werkzeugleiste gezogen und auf dem Video platziert werden.",
									    "tourStepCanvasEditingTitle": "Interaktionen bearbeiten",
									    "tourStepCanvasEditingText": "<p>Sobald eine Interaktion hinzugefügt wurde, kann diese mit der Maus verschoben werden, um sie neu zu positionieren.</p><p>Ihre Größe kann verändert werden, indem man an dem Rahmen zieht.</p><p>Wenn eine Interaktion ausgewählt wird, erscheint ein Kontextmenü. Um den Inhalt der Interaktion zu bearbeiten, muss der \"Bearbeiten\"-Button im Kontextmenü ausgewählt werden. Eine Interaktion kann durch das Drücken des \"Entfernen\"-Buttons im Kontextmenü gelöscht werden.</p>",
									    "tourStepCanvasBookmarksTitle": "Lesezeichen",
									    "tourStepCanvasBookmarksText": "Es können Lesezeichen im Lesezeichenmenü hinzugefügt werden. Klick den Lesezeichen-Button rechts neben dem Abspiel-Button an, um das Menü zu öffnen.",
									    "tourStepCanvasEndscreensTitle": "Übermittlung der Antworten",
									    "tourStepCanvasEndscreensText": "Du kannst den Lernenden die Möglichkeit geben, ihre Antworten an dich zu übermitteln. Klicke auf den \"Antwortübermittlungs\"-Button, um das entsprechende Menü zu öffnen.",
									    "tourStepCanvasPreviewTitle": "Vorschau deines interaktiven Videos",
									    "tourStepCanvasPreviewText": "Drücke den Abspiel-Button, um eine Vorschau des interaktiven Videos während des Bearbeitens zu sehen.",
									    "tourStepCanvasSaveTitle": "Speichern und ansehen",
									    "tourStepCanvasSaveText": "Wenn du damit fertig bist, Interaktionen zum Video hinzuzufügen, kannst du das Ergebnis mit einem Klick auf Speichern/Erstellen anschauen.",
									    "tourStepSummaryText": "Diese optionale Zusammenfassungsaufgabe wird am Ende des Videos erscheinen.",
									    "fullScoreRequiredPause": "Die Option \"Volle Punktzahl erforderlich\" setzt voraus, dass \"Pause\" aktiviert ist.",
									    "fullScoreRequiredRetry": "Die Option \"Volle Punktzahl erforderlich\" setzt voraus, dass \"Wiederholen\" aktiviert ist.",
									    "fullScoreRequiredTimeFrame": "Es existiert bereits eine Interaktion, die die volle Punktzahl im selben Intervall wie diese Interaktion erfordert. <br /> Nur eine der Interaktionen muss beantwortet werden.",
									    "addEndscreen": "Antwortübermittlung einfügen bei @timecode",
									    "endscreen": "Antwortübermittlung",
									    "endscreenAlreadyExists": "Antwortübermittlung existiert hier bereits. Bewege den Abspielknopf und füge ein Lesezeichen oder eine Antwortübermittlung an einer anderen Stelle hinzu.",
									    "tooltipBookmarks": "Klicke hier, um ein Lesezeichen an der aktuellen Position einzufügen",
									    "tooltipEndscreens": "Klicke hier, um eine Antwortübermittlung an der aktuellen Position einzufügen",
									    "expandBreadcrumbButtonLabel": "Zurück",
									    "collapseBreadcrumbButtonLabel": "Navigation schließen"
								    }
								}'
    		],
    		//el.json
    		[
    			'library_id' => $h5pEditorCIVLibId,
            	'language_code' => 'el',
            	'translation' => '{
									"libraryStrings":{
									    "selectVideo":"Πρέπει να επιλέξετε ένα βίντεο προτού προσθέσετε διαδραστικά στοιχεία",
									    "noVideoSource":"Δεν υπάρχει αρχείο προέλευσης βίντεο",
									    "notVideoField":"Το \":path\" δεν είναι βίντεο",
									    "notImageField":"Το \":path\" δεν είναι εικόνα",
									    "insertElement":"Κάντε κλικ και τοποθετήστε το :type",
									    "popupTitle":"Επεξεργασία του :type",
									    "done":"Ολοκλήρωση",
									    "loading":"Φόρτωση...",
									    "remove":"Αφαίρεση",
									    "removeInteraction":"Είστε σίγουρος ότι θέλετε να αφαιρέσετε αυτό το διαδραστικό στοιχείο;",
									    "addBookmark":"Προσθήκη σελιδοδείκτη",
									    "newBookmark":"Νέος σελιδοδείκτης",
									    "bookmarkAlreadyExists":"Ο σελιδοδείκτης υπάρχει ήδη. Προσθέστε έναν σελιδοδείκτη σε άλλη χρονική στιγμή.",
									    "tourButtonStart":"Περιήγηση",
									    "tourButtonExit":"ΕΞΟΔΟΣ",
									    "tourButtonDone":"ΟΛΟΚΛΗΡΩΣΗ",
									    "tourButtonBack":"ΠΡΟΗΓΟΥΜΕΝΟ",
									    "tourButtonNext":"ΕΠΟΜΕΝΟ",
									    "tourStepUploadIntroText":"<p>Αυτή η περιήγηση σάς καθοδηγεί στις πιο σημαντικές λειτουργίες του προγράμματος επεξεργασίας του διαδραστικού βίντεο.</p><p>Μπορείτε να ξεκινήσετε αυτήν την περιήγηση ανά πάσα στιγμή επιλέγοντας το κουμπί \"Περιήγηση\" στην επάνω δεξιά γωνία.</p><p>Επιλέξτε \"Έξοδος\" για να παραλείψετε αυτήν την περιήγηση ή πατήστε \"Επόμενο\" για να συνεχίσετε.</p>",
									    "tourStepUploadFileTitle":"Προσθήκη βίντεο",
									    "tourStepUploadFileText":"<p>Ξεκινήστε προσθέτοντας ένα αρχείο βίντεο. Μπορείτε να ανεβάσετε ένα αρχείο από τον υπολογιστή σας ή να επικολλήσετε μια διεύθυνση URL προς ένα βίντεο στο YouTube ή σε ένα υποστηριζόμενο αρχείο βίντεο.</p><p>Για να διασφαλίσετε τη συμβατότητα μεταξύ των φυλλομετρητών, μπορείτε να ανεβάσετε πολλαπλές μορφές αρχείων του ίδιου βίντεο, όπως mp4 και webm.</p>",
									    "tourStepUploadAddInteractionsTitle":"Προσθήκη διαδραστικών στοιχείων",
									    "tourStepUploadAddInteractionsText":"<p>Μόλις προσθέσετε ένα βίντεο, μπορείτε να ξεκινήσετε να προσθέτετε διαδραστικά στοιχεία.</p><p>Για να ξεκινήσετε, επιλέξτε <em>\"Προσθήκη διαδραστικών σοιχείων\"</em></p>",
									    "tourStepCanvasToolbarTitle":"Προσθήκη διαδραστικών στοιχείων",
									    "tourStepCanvasToolbarText":"Για να προσθέσετε ένα διαδραστικό στοιχείο, σύρετέ το από τη γραμμή εργαλείων και αποθέστε το στο βίντεο",
									    "tourStepCanvasEditingTitle":"Επεξεργασία διαδραστικών στοιχείων",
									    "tourStepCanvasEditingText":"<p>Μόλις προστεθεί ένα διαδραστικό στοιχείο, μπορείτε να το σύρετε για να το επανατοποθετήσετε.</p><p>Για να αλλάξετε το μέγεθος του διαδραστικού στοιχείου, επιλέξτε τις λαβές και σύρετε.</p><p>Όταν επιλέξετε ένα διαδραστικό στοιχείο, θα εμφανιστεί ένα μενού διαθέσιμων ενεργειών. Για να επεξεργαστείτε το περιεχόμενο του διαδραστικού στοιχείου, επιλέξτε από το μενού την ενέργεια \"Επεξεργασία\". Μπορείτε να αφαιρέσετε ένα διαδραστικό στοιχείο επιλέγοντας από το μενού την ενέργεια \"Αφαίρεση\".</p>",
									    "tourStepCanvasBookmarksTitle":"Σελιδοδείκτες",
									    "tourStepCanvasBookmarksText":"Μπορείτε να προσθέσετε Σελιδοδείκτες από το μενού σελιδοδεικτών. Επιλέξτε το κουμπί \"Σελιδοδείκτης\" για να ανοίξετε το μενού",
									    "tourStepCanvasEndscreensTitle":"Οθόνες υποβολής",
									    "tourStepCanvasEndscreensText":"Μπορείτε να προσθέσετε Οθόνες υποβολής από το μενού οθονών υποβολής. Επιλέξτε το κουμπί \"Οθόνη υποβολής\" για να ανοίξετε το μενού.",
									    "tourStepCanvasPreviewTitle":"Προεπισκόπηση διαδραστικού βίντεο",
									    "tourStepCanvasPreviewText":"Επιλέξτε το κουμπί αναπαραγωγής (play) για να δείτε το διαδραστικό σας βίντεο καθώς το επεξεργάζεστε",
									    "tourStepCanvasSaveTitle":"Αποθήκευση και Προβολή",
									    "tourStepCanvasSaveText":"Όταν ολοκληρώσετε την προσθήκη διαδραστικών στοιχείων στο βίντεό σας, πατήστε \"Δημιουργία\"/\"Ενημέρωση\" για να δείτε το αποτέλεσμα",
									    "tourStepSummaryText":"Αυτή η σύνοψη θα εμφανιστεί στο τέλος του βίντεο. (Προαιρετικό)",
									    "fullScoreRequiredPause":"Η επιλογή \"Απαιτείται πλήρης βαθμολογία\" απαιτεί την ενεργοποίηση της \"Παύσης\"",
									    "fullScoreRequiredRetry":"Η επιλογή \"Απαιτείται πλήρης βαθμολογία\" απαιτεί την ενεργοποίηση της \"Επανάληψης\"",
									    "fullScoreRequiredTimeFrame":"Υπάρχει ήδη μια αλληλεπίδραση που απαιτεί πλήρη βαθμολογία την ίδια χρονική στιγμή με αυτό το διαδραστικό στοιχείο.<br />Μόνο ένα από τα διδραστικά στοιχεία απαιτείται να απαντηθεί",
									    "addEndscreen":"Προσθέστε οθόνη υποβολής στο @timecode",
									    "endscreen":"Οθόνη υποβολής",
									    "endscreenAlreadyExists":"Μία οθόνη υποβολής υπάρχει ήδη σαυτό το σημείο. Μετακινήστε την κεφαλή και προσθέστε οθόνη υποβολής ή σελιδοδείκτη σε κάποιο άλλο σημείο.",
									    "tooltipBookmarks":"Κάντε κλικ για να προσθέσετε σελιδοδείκτη στο τρέχον σημείο του βίντεο",
									    "tooltipEndscreens":"Κάντε κλικ για να προσθέσετε οθόνη υποβολής στο τρέχον σημείο του βίντεο",
									    "expandBreadcrumbButtonLabel": "Πίσω",
									    "collapseBreadcrumbButtonLabel": "Κλείσιμο πλοήγησης"
								    }
								}'
    		],
    		//en.json
    		[
    			'library_id' => $h5pEditorCIVLibId,
            	'language_code' => 'en',
            	'translation' => '{
									"libraryStrings": {
									    "selectVideo": "You must select a video before adding interactions.",
									    "noVideoSource": "No Video Source",
									    "notVideoField": "\":path\" is not a video.",
									    "notImageField": "\":path\" is not a image.",
									    "insertElement": "Click and drag to place :type",
									    "popupTitle": "Edit :type",
									    "done": "Done",
									    "loading": "Loading...",
									    "remove": "Delete",
									    "removeInteraction": "Are you sure you wish to delete this interaction?",
									    "addBookmark": "Add bookmark at @timecode",
									    "newBookmark": "New bookmark",
									    "bookmarkAlreadyExists": "Bookmark already exists here. Move playhead and add a bookmark or a submit screen at another time.",
									    "tourButtonStart": "Tour",
									    "tourButtonExit": "Exit",
									    "tourButtonDone": "Done",
									    "tourButtonBack": "Back",
									    "tourButtonNext": "Next",
									    "tourStepUploadIntroText": "<p>This tour guides you through the most important features of the Interactive Video editor.</p><p>Start this tour at any time by pressing the Tour button in the top right corner.</p><p>Press EXIT to skip this tour or press NEXT to continue.</p>",
									    "tourStepUploadFileTitle": "Adding video",
									    "tourStepUploadFileText": "<p>Start by adding a video file. You can upload a file from your computer or paste a URL to a YouTube video or a supported video file.</p><p>To ensure compatibility across browsers, you can upload multiple file formats of the same video, such as mp4 and webm.</p>",
									    "tourStepUploadAddInteractionsTitle": "Adding interactions",
									    "tourStepUploadAddInteractionsText": "<p>Once you have added a video, you can start adding interactions.</p><p>Press the <em>Add interactions</em> tab to get started.</p>",
									    "tourStepCanvasToolbarTitle": "Adding interactions",
									    "tourStepCanvasToolbarText": "To add an interaction, drag an element from the toolbar and drop it onto the video.",
									    "tourStepCanvasEditingTitle": "Editing interactions",
									    "tourStepCanvasEditingText": "<p>Once an interaction has been added, you can drag to reposition it.</p><p>To resize an interaction, press on the handles and drag.</p><p>When you select an interaction, a context menu will appear. To edit the content of the interaction, press the Edit button in the context menu. You can remove an interaction by pressing the Remove button on the context menu.</p>",
									    "tourStepCanvasBookmarksTitle": "Bookmarks",
									    "tourStepCanvasBookmarksText": "You can add Bookmarks from the Bookmarks menu. Press the Bookmark button to open the menu.",
									    "tourStepCanvasEndscreensTitle": "Submit Screens",
									    "tourStepCanvasEndscreensText": "You can add submit screens from the submit screens menu. Press the submit screen button to open the menu.",
									    "tourStepCanvasPreviewTitle": "Preview your interactive video",
									    "tourStepCanvasPreviewText": "Press the Play button to preview your interactive video while editing.",
									    "tourStepCanvasSaveTitle": "Save and view",
									    "tourStepCanvasSaveText": "When you are done adding interactions to your video, press Save/Create to view the result.",
									    "tourStepSummaryText": "This optional Summary quiz will appear at the end of the video.",
									    "fullScoreRequiredPause": "\"Full score required\" option requires that \"Pause\" is enabled.",
									    "fullScoreRequiredRetry": "\"Full score required\" option requires that \"Retry\" is enabled",
									    "fullScoreRequiredTimeFrame": "There already exists an interaction that requires full score at the same interval as this interaction.<br /> Only one of the interactions will be required to answer.",
									    "addEndscreen": "Add submit screen at @timecode",
									    "endscreen": "Submit screen",
									    "endscreenAlreadyExists": "Submit screen already exists here. Move playhead and add a submit screen or a bookmark at another time.",
									    "tooltipBookmarks": "Click to add bookmark at the current point in the video",
									    "tooltipEndscreens": "Click to add submit screen at the current point in the video",
									    "expandBreadcrumbButtonLabel": "Go back",
									    "collapseBreadcrumbButtonLabel": "Close navigation"
								    }
								}'
    		],
    		//es.json
    		[
    			'library_id' => $h5pEditorCIVLibId,
            	'language_code' => 'es',
            	'translation' => '{
									"libraryStrings": {
									    "selectVideo": "Usted debe seleccionar un video antes de añadir interacciones.",
									    "noVideoSource": "No hay Fuente de Video",
									    "notVideoField": "\":path\" no es un video.",
									    "notImageField": "\":path\" no es una imagen.",
									    "insertElement": "Clic y arrastrar para colocar :type",
									    "popupTitle": "Editar :type",
									    "done": "Hecho",
									    "loading": "Cargando...",
									    "remove": "Eliminar",
									    "removeInteraction": "¿Esta seguro de querer eliminar esta interacción?",
									    "addBookmark": "Añadir marcador en @timecode",
									    "newBookmark": "Nuevo marcador",
									    "bookmarkAlreadyExists": "Un marcador ya existe aquí. Mueva el cursor y añada un marcador o envíe la pantalla en otro momento.",
									    "tourButtonStart": "Tour",
									    "tourButtonExit": "Salir",
									    "tourButtonDone": "Hecho",
									    "tourButtonBack": "Regresar",
									    "tourButtonNext": "Siguiente",
									    "tourStepUploadIntroText": "<p>Este tour lo guía por las características más importantes del editor de Video Interactivo.</p><p>Comience este tour en cualquier momento al apretar el botón del Tour en la esquina superior derecha.</p><p>Presione SALIR para saltarse este tour o presione SIGUIENTE para continuar.</p>",
									    "tourStepUploadFileTitle": "Añadiendo video",
									    "tourStepUploadFileText": "<p>Comience añadiendo un archivo de video. Uste puede subir un video desde su ordenador o puede pegar una URL hacia un video de YouTube o un archivo de video soportado.</p><p>Para asegurar compatibilidad entre navegadores, puede subir varios formatos de archivos del mismo video, como mp4 y webm.</p>",
									    "tourStepUploadAddInteractionsTitle": "Añadiendo interacciones",
									    "tourStepUploadAddInteractionsText": "<p>Una vez que haya añadido un video, puede comenzar a añadir interacciones.</p><p>Presione la pestaña <em>Añadir interacciones</em> para comenzar.</p>",
									    "tourStepCanvasToolbarTitle": "Añadiendo interacciones",
									    "tourStepCanvasToolbarText": "Para añadir una interacción, arrastre un elemento de la barra de herramientas y suéltelo en el video.",
									    "tourStepCanvasEditingTitle": "Editando interacciones",
									    "tourStepCanvasEditingText": "<p>Una vez que se haya añadido una interacción, puede arrastrarla para re-posicionarla.</p><p>Para cambiarle de tamaño a una interacción, presione las agarraderas y arrastre.</p><p>Cuando Usted seleccionauna interacción, aparecerá un menú contextual. Para editar el contenido de la interacción, presione el botón de Editar en el menú contextual. Usted puede eliminar una interacción al presionar el botón Eliminar en el menú contextual.</p>",
									    "tourStepCanvasBookmarksTitle": "Marcadores",
									    "tourStepCanvasBookmarksText": "Usted puede añadir Marcadores desde el menú de Marcadores. Presione el botón Marcador (Bookmark) para abrir el menú.",
									    "tourStepCanvasEndscreensTitle": "Enviar Pantallas",
									    "tourStepCanvasEndscreensText": "Usted puede enviar pantallas desde el menú para enviar pantallas. Presione el botón de enviar pantallas para abrir el menú.",
									    "tourStepCanvasPreviewTitle": "Previsualizar su video interactivo",
									    "tourStepCanvasPreviewText": "Presione el botón de Reproducir (Play) para previsualizar su video interactivo mientras lo edita.",
									    "tourStepCanvasSaveTitle": "Guardar y ver",
									    "tourStepCanvasSaveText": "Cuando haya terminado de añadirle interacciones a su video, presione Guardar/Crear para ver el resultado.",
									    "tourStepSummaryText": "Este examen resumen opcional aparecerá al final del video.",
									    "fullScoreRequiredPause": "La opción \"Se requiere puntaje completo\" requiere que esté habilitada \"Pausa\".",
									    "fullScoreRequiredRetry": "La opción \"Se requiere puntaje completo\" requiere que esté habilitado \"Reintentar\"",
									    "fullScoreRequiredTimeFrame": "Ya existe una interacción que requiere de puntaje completo en el mismo intervalo de esta interacción.<br /> Solamente una de las interacciones será necesaria para contestar.",
									    "addEndscreen": "Añadir enviar pantalla en @timecode",
									    "endscreen": "Enviar pantalla",
									    "endscreenAlreadyExists": "Enviar pantalla ya existe aquí. Mueva el puntero y añada un enviar pantalla o un marcador en otro momento.",
									    "tooltipBookmarks": "Clic para añadir marcador en el punto actual en el video",
									    "tooltipEndscreens":"Clic para añadir pantalla para enviar en el punto actual en el video",
									    "expandBreadcrumbButtonLabel": "Regresar",
									    "collapseBreadcrumbButtonLabel": "Cerrar navigación"
								    }
								}'
    		],
    		//es-mx.json
    		[
    			'library_id' => $h5pEditorCIVLibId,
            	'language_code' => 'es-mx',
            	'translation' => '{
									"libraryStrings": {
									    "selectVideo": "Usted debe seleccionar un video antes de añadir interacciones.",
									    "noVideoSource": "No hay Fuente de Video",
									    "notVideoField": "\":path\" no es un video.",
									    "notImageField": "\":path\" no es una imagen.",
									    "insertElement": "Clic y arrastrar para colocar :type",
									    "popupTitle": "Editar :type",
									    "done": "Hecho",
									    "loading": "Cargando...",
									    "remove": "Eliminar",
									    "removeInteraction": "¿Esta seguro de querer eliminar esta interacción?",
									    "addBookmark": "Añadir marcador en @timecode",
									    "newBookmark": "Nuevo marcador",
									    "bookmarkAlreadyExists": "Un marcador ya existe aquí. Mueva el cursor y añada un marcador o la pantalla para enviar en otro momento.",
									    "tourButtonStart": "Tour",
									    "tourButtonExit": "Salir",
									    "tourButtonDone": "Hecho",
									    "tourButtonBack": "Regresar",
									    "tourButtonNext": "Siguiente",
									    "tourStepUploadIntroText": "<p>Este tour lo guía por las características más importantes del editor de Video Interactivo.</p><p>Comience este tour en cualquier momento al apretar el botón del Tour en la esquina superior derecha.</p><p>Presione SALIR para saltarse este tour o presione SIGUIENTE para continuar.</p>",
									    "tourStepUploadFileTitle": "Añadiendo video",
									    "tourStepUploadFileText": "<p>Comience añadiendo un archivo de video. Uste puede subir un video desde su computadora o puede pegar una URL hacia un video de YouTube o un archivo de video soportado.</p><p>Para asegurar compatibilidad entre navegadores, puede subir varios formatos de archivos del mismo video, como mp4 y webm.</p>",
									    "tourStepUploadAddInteractionsTitle": "Añadiendo interacciones",
									    "tourStepUploadAddInteractionsText": "<p>Una vez que haya añadido un video, puede comenzar a añadir interacciones.</p><p>Presione la pestaña <em>Añadir interacciones</em> para comenzar.</p>",
									    "tourStepCanvasToolbarTitle": "Añadiendo interacciones",
									    "tourStepCanvasToolbarText": "Para añadir una interacción, arrastre un elemento de la barra de herramientas y suéltelo en el video.",
									    "tourStepCanvasEditingTitle": "Editando interacciones",
									    "tourStepCanvasEditingText": "<p>Una vez que se haya añadido una interacción, puede arrastrarla para re-posicionarla.</p><p>Para cambiarle de tamaño a una interacción, presione las agarraderas y arrastre.</p><p>Cuando Usted selecciona una interacción, aparecerá un menú contextual. Para editar el contenido de la interacción, presione el botón de Editar en el menú contextual. Usted puede eliminar una interacción al presionar el botón Eliminar en el menú contextual.</p>",
									    "tourStepCanvasBookmarksTitle": "Marcadores",
									    "tourStepCanvasBookmarksText": "Usted puede añadir Marcadores desde el menú de Marcadores. Presione el botón Marcador (Bookmark) para abrir el menú.",
									    "tourStepCanvasEndscreensTitle": "Pantallas de envío",
									    "tourStepCanvasEndscreensText": "Usted puede añadir pantallas para envío desde el menú de pantallas de envío. Presione el botón de pantallas de envío para abrir el menú.",
									    "tourStepCanvasPreviewTitle": "Previsualizar su video interactivo",
									    "tourStepCanvasPreviewText": "Presione el botón de Reproducir (Play) para previsualizar su video interactivo mientras lo edita.",
									    "tourStepCanvasSaveTitle": "Guardar y ver",
									    "tourStepCanvasSaveText": "Cuando haya terminado de añadirle interacciones a su video, presione Guardar/Crear para ver el resultado.",
									    "tourStepSummaryText": "Este examen de resumen opcional aparecerá al final del video.",
									    "fullScoreRequiredPause": "La opción \"Se requiere puntaje completo\" requiere que esté habilitada \"Pausa\".",
									    "fullScoreRequiredRetry": "La opción \"Se requiere puntaje completo\" requiere que esté habilitado \"Reintentar\"",
									    "fullScoreRequiredTimeFrame": "Ya existe una interacción que requiere de puntaje completo en el mismo intervalo de esta interacción.<br /> Solamente una de las interacciones será necesaria para contestar.",
									    "addEndscreen": "Añadir pantalla de envío en @timecode",
									    "endscreen": "Pantalla de envío",
									    "endscreenAlreadyExists": "Pantalla de envío ya existe aquí. Mueva el puntero y añada una pantalla de envíoa o un marcador en otro momento.",
									    "tooltipBookmarks": "Clic para añadir marcador en el punto actual en el video",
									    "tooltipEndscreens":"Clic para añadir pantalla para enviar en el punto actual en el video",
									    "expandBreadcrumbButtonLabel": "Regresar",
									    "collapseBreadcrumbButtonLabel": "Cerrar navigación"
								    }
								}'
    		],
    		//et.json
    		[
    			'library_id' => $h5pEditorCIVLibId,
            	'language_code' => 'et',
            	'translation' => '{
									"libraryStrings": {
									    "selectVideo": "Interaktsiooni lisamiseks vali video.",
									    "noVideoSource": "Video allikas ei ole näidatud",
									    "notVideoField": "\":path\" ei ole video.",
									    "notImageField": "\":path\" ei ole pilt.",
									    "insertElement": "Kliki ja lohista paika :type",
									    "popupTitle": "Töötle :type",
									    "done": "Valmis",
									    "loading": "Laadib...",
									    "remove": "Eemalda",
									    "removeInteraction": "Kas oled kindel, et soovid selle interaktsiooni eemaldada?",
									    "addBookmark": "Lisa järjehoidja @timecode",
									    "newBookmark": "Uus järjehoidja",
									    "bookmarkAlreadyExists": "Järjehoidja on juba olemas. Liiguta videokursorit ja lisa järjehoidja või esita kuva hiljem.",
									    "tourButtonStart": "Ülevaade",
									    "tourButtonExit": "Välju",
									    "tourButtonDone": "Valmis",
									    "tourButtonBack": "Tagasi",
									    "tourButtonNext": "Järgmine",
									    "tourStepUploadIntroText": "<p>See ülevaade tutvustab sulle interaktiivse videoredaktori tähtsamaid võimalusi.</p><p>Ülevaate käivitamiseks vajuta paremas ülanurgas nupule Ülevaade.</p><p>Ülevaate vahelejätmiseks vajuta Välju nupule, jätkamiseks vajuta Järgmine nupule.</p>",
									    "tourStepUploadFileTitle": "Video lisamine",
									    "tourStepUploadFileText": "<p>Alusta videofaili lisamisega. Võimalik on faili laadimine oma arvutist või Youtube või mõne muu toetatud video lingi kleepimine.</p><p>Brauserite ühilduvuse tagamiseks saad video üleslaadida mitmes erinevas formaadis, näiteks mp4 ja webm.</p>",
									    "tourStepUploadAddInteractionsTitle": "Interaktsioonide lisamine",
									    "tourStepUploadAddInteractionsText": "<p>Peale video lisamist saad alustada interaktsioonide lisamisega.</p><p>Alustamiseks vajuta <em>Interaktsioonide lisamine</em> vahekaardile.</p>",
									    "tourStepCanvasToolbarTitle": "Interaktsioonide lisamine",
									    "tourStepCanvasToolbarText": "Interaktsiooni lisamiseks lohista tööriistaribalt element ja paiguta see videole.",
									    "tourStepCanvasEditingTitle": "Interaktsioonide töötlemine",
									    "tourStepCanvasEditingText": "<p>Peale interaktsiooni lisamist saad selle asukohta lohistamisega muuta.</p><p>Interaktsiooni suuruse muutmiseks vajuta selle sangadele ja lohista.</p><p>Interaktsiooni valimisel ilmub kontekstmenüü. Interaktsiooni sisu töötlemiseks vajuta kontekstmenüü Töötle nupule. Interaktsiooni eemaldamiseks vajuta kontekstmenüü Eemalda nupule.</p>",
									    "tourStepCanvasBookmarksTitle": "Järjehoidjad",
									    "tourStepCanvasBookmarksText": "Järjehoidjad saad lisada Järjehoidjad menüüst. Vajuta Järjehoidja nuppu menüü avamiseks.",
									    "tourStepCanvasEndscreensTitle": "Esitamiskuvad",
									    "tourStepCanvasEndscreensText": "Kasuta Esitamiskuva menüüd. Menüü avamiseks vajuta Esitamiskuva nuppu.",
									    "tourStepCanvasPreviewTitle": "Interaktiivse video eelvaade",
									    "tourStepCanvasPreviewText": "Interaktiivse video eelvaateks töötlemise ajal vajuta Mängi nuppu.",
									    "tourStepCanvasSaveTitle": "Salvesta ja vaata",
									    "tourStepCanvasSaveText": "Kui oled lõpetanud interaktsioonide videole lisamise, vajuta tulemuse vaatamiseks Salvesta/Loo nuppu.",
									    "tourStepSummaryText": "Seda valikulist kokkuvõtvat küsimustikku näidatakse video lõpus.",
									    "fullScoreRequiredPause": "\"Täispunktid nõutud\" valik vajab, et \"Paus\" oleks lubatud.",
									    "fullScoreRequiredRetry": "\"Täispunktid nõutud\" valik vajab, et \"Proovi uuesti\" oleks lubatud",
									    "fullScoreRequiredTimeFrame": "Juba on olemas interaktsioon, mis nõuab täispunkte samas vahemikus, kui see interaktsioon.<br /> Vastata on vaja vaid ühele interaktsioonidest.",
									    "addEndscreen": "Lisa esitamiskuva siia @timecode",
									    "endscreen": "Esitamiskuva",
									    "endscreenAlreadyExists": "Siin on esitamiskuva juba olemas. Liiguta videokursorit ja lisa esitamiskuva või järjehoidja hiljem.",
									    "tooltipBookmarks": "Kliki järjehoidja lisamiseks video jooksvale kaadrile",
									    "tooltipEndscreens": "Kliki esitamiskuva lisamiseks video jooksvale kaadrile",
									    "expandBreadcrumbButtonLabel": "Go back",
									    "collapseBreadcrumbButtonLabel": "Sulge navigatsioon"
								    }
								}'
    		],
    		//eu.json
    		[
    			'library_id' => $h5pEditorCIVLibId,
            	'language_code' => 'eu',
            	'translation' => '{
									"libraryStrings": {
									    "selectVideo": "Bideo bat hautatu behar duzu interakzioak gehitu aurretik.",
									    "noVideoSource": "Bideo iturririk ez dago",
									    "notVideoField": "\":path\" ez da bideo bat.",
									    "notImageField": "\":path\" ez da irudi bat.",
									    "insertElement": "Klikatu eta arrastatu hona: :type",
									    "popupTitle": "Editatu  :type",
									    "done": "Egina!",
									    "loading": "Kargatzen...",
									    "remove": "Ezabatu",
									    "removeInteraction": "Ziur zaude nahi duzula interakzio hau hau ezabatu?",
									    "addBookmark": "Gehitu laster-marka hemen:  @timecode",
									    "newBookmark": "Laster-marka berria",
									    "bookmarkAlreadyExists": "Hemen dagoeneko beste laster-marka dago.  Mugitu erreprodukzio-burua eta gehitu laster-marka edo bidalketa-pantaila beste une batean.",
									    "tourButtonStart": "Gida",
									    "tourButtonExit": "Irten",
									    "tourButtonDone": "Egina!",
									    "tourButtonBack": "Atzera",
									    "tourButtonNext": "Hurrengoa",
									    "tourStepUploadIntroText": "<p>Testu honek gidatuko zaitu Bideo interaktiboen editorearen eginbide nagusietatik.</p><p>Hasi gida hau nahi duzun unean eskuineko goiko ertzean dagoen Gida botoia sakatuta.</p><p>Sakatu IRTEN gida hau saltatzeko edo HURRENGOA jarraitzeko.</p>",
									    "tourStepUploadFileTitle": "Bideoa gehitzen",
									    "tourStepUploadFileText": "<p>Hasi bideo fitxategi bat gehitzen. Kargatu dezakezu fitxategi bat zure ordenagailutik edo Youtubeko zein onartutako beste iturriko URLa itsatsi.</p><p>Zure nabigatzailearekiko bateragarritasuna segurtatzeko bideo bera hainbat formatutan kargatu dezakezu, hala nola mp4 eta webm.</p>b",
									    "tourStepUploadAddInteractionsTitle": "Interakzioak gehitzen",
									    "tourStepUploadAddInteractionsText": "<p>Behin bideo bat gehitu ondoren, has zaitezke interakzioak gehitzen.</p><p>Sakatu <em>Gehitu interakzioak</em>fitxa hasteko.</p>",
									    "tourStepCanvasToolbarTitle": "Interakzioak gehitzen",
									    "tourStepCanvasToolbarText": "Interakzio bat gehitzeko, arrastatu tresna-barrako elementu bat eta jaregin bideoan.",
									    "tourStepCanvasEditingTitle": "Interakzioak editatzen",
									    "tourStepCanvasEditingText": "<p>Behin interakzio bat gehitu denean, arrastatuz birkokatu dezakezu.</p><p>Interakzio bat tamainaz aldatzeko, sakatu kontroletan eta arrastatu.</p><p>Interakzio bat hautatzen duzunean, laster-menu bat agertuko da.  Interakzioaren edukia editatzeko, sakatu laster-menuko Editatu botoia. Interakzio bat ezabatzeko sakatu laster-menuko Ezabatu botoia. </p>",
									    "tourStepCanvasBookmarksTitle": "Laster-markak",
									    "tourStepCanvasBookmarksText": "Laster-marka bat gehitu dezakezu laster-marken menutik. Saka ezazu Lastermarkak botoia menua irekitzeko.",
									    "tourStepCanvasEndscreensTitle": "Bidalketa-pantailak",
									    "tourStepCanvasEndscreensText": "Gehitu ditzakezu bidalketa-pantailak bidalketa-pantailen menutik. Sakatu bidalketa-pantailaren botoia menua irekitzeko.",
									    "tourStepCanvasPreviewTitle": "Aurreikusi bideo interaktiboa",
									    "tourStepCanvasPreviewText": "Sakatu Erreprodukzio-botoia aurreikusteko bideo interaktiboa editatzen duzun bitartean.",
									    "tourStepCanvasSaveTitle": "Gorde eta bistaratu",
									    "tourStepCanvasSaveText": "Bideoan interakzio bat gehitzen ari zarenean, saka ezazu Gorde/Sortu emaitza ikusteko.",
									    "tourStepSummaryText": "Aukerazko laburpen-galdetegi hau bideoaren bukaeran agertuko da.",
									    "fullScoreRequiredPause": "\"Puntuazio osoa behar da\" opzioak behartzen du \"Pausarazi\" gaitua izatea.",
									    "fullScoreRequiredRetry": "\"Puntuazio osoa behar da\" opzioak behartzen du \"Saiatu berriro\" gaitua izatea",
									    "fullScoreRequiredTimeFrame": "Dagoeneko badago interakzio honen tarte berean puntuazio osoa eskatzen duen beste interakzio bat.<br /> Interakzioetako bat bakarrik erantzun beharko da.",
									    "addEndscreen": "Gehitu bidaltzeko pantaila hemen: @timecode",
									    "endscreen": "Bidalketa-pantaila",
									    "endscreenAlreadyExists": "Hemen dagoeneko badago bidalketa-pantaila bat. Mugitu erreprodukzio-burua eta gehitu  bidalketa-pantaila edo laster-marka beste une batean.",
									    "tooltipBookmarks": "Egin klik gehitzeko laster-marka bat bideoaren une honetan",
									    "tooltipEndscreens": "Egin klik gehitzeko bidalketa-pantaila bat bideoaren une honetan",
									    "expandBreadcrumbButtonLabel": "Joan atzera",
									    "collapseBreadcrumbButtonLabel": "Itxi nabigazioa"
								    }
								}'
    		],
    		//fi.json
    		[
    			'library_id' => $h5pEditorCIVLibId,
            	'language_code' => 'fi',
            	'translation' => '{
									"libraryStrings": {
									    "selectVideo": "Sinun täytyy valita video ennen kuin voit lisätä interaktiivisia elementtejä sille.",
									    "noVideoSource": "Videolähde puuttuu.",
									    "notVideoField": "\":path\" ei ole video.",
									    "notImageField": "\":path\" ei ole kuva.",
									    "insertElement": "Klikkaa ja raahaa paikalleen :type",
									    "popupTitle": "Editoi :type",
									    "done": "Valmis",
									    "loading": "Lataa...",
									    "remove": "Poista",
									    "removeInteraction": "Oletko varma että halut poistaa tämän interaktiivisen elementin?",
									    "addBookmark": "Lisää uusi kirjanmerkki kohtaan @timecode",
									    "newBookmark": "Uusi kirjanmerkki",
									    "bookmarkAlreadyExists": "Tässä on jo kirjanmerkki. Liikuta videopalkkia ja lisää kirjanmerkki toiseen kohtaan.",
									    "tourButtonStart": "Kiertoajelu",
									    "tourButtonExit": "Poistu",
									    "tourButtonDone": "Valmis",
									    "tourButtonBack": "Takaisin",
									    "tourButtonNext": "Seuraava",
									    "tourStepUploadIntroText": "<p>Tämä kiertoajelu perehdyttää sinut Interaktiivisen videon tärkeimpiin ominaisuuksiin.</p><p>Käynnistä kiertoajelu koska vain painamalla Kiertoajelu-painiketta oikeassa yläkulmassa.</p><p>paina poistu-painiketta keskeyttääksesi kiertoajelun tai paina seuraava-painiketta jatkaaksesi sitä</p>",
									    "tourStepUploadFileTitle": "Lataa tai upota video",
									    "tourStepUploadFileText": "<p>Aloita lisäämällä videotiedosto. Voit ladata videotiedoston tietokoneeltasi tai liittää Youtube- tai muun tuetun videopalvelun videon URL-osoitteen</p><p>Varmistaaksesi yhteensopivuuden eri laitteiden ja selainten välillä voit ladata saman videon moneen kertaan eri videomuodoissa kuten mp4 ja webm tiedostomuodoissa tähän tehtävään.</p>",
									    "tourStepUploadAddInteractionsTitle": "Lisää toiminto tai tehtävä",
									    "tourStepUploadAddInteractionsText": "<p>Kun olet lisännyt videon voit lisätä sille interaktiivisia elemettejä.</p><p>Siirry <em>Lisää toiminto tai tehtävä-välilehdelle</em> aloittaakesi</p>",
									    "tourStepCanvasToolbarTitle": "Lisää toiminto tai tehtävä",
									    "tourStepCanvasToolbarText": "Lisätäksesi interaktiivisen elmentin, raahaa elementti työkaluriviltä ja tiputa se haluaamsi kohtaan videota.",
									    "tourStepCanvasEditingTitle": "Interaktiivisen elementin muokkaaminen",
									    "tourStepCanvasEditingText": "<p>Kun interaktiivinen elementti on lisätty voit siirtää sitä raahamalla</p><p>Muuttaaksesi elementin kokoa valitse elementti ja tämän jälkeen voit muuttaa elementin kokoa raahamalla sitä nurkista (ei toimi painikemuotoisen elementin kanssa).Muokataksesi elementin asetuksia valitse se ja paina kynä-ikonia. Poistaakesi elementin valitse se ja valitse roskoakori-ikoni.</p>",
									    "tourStepCanvasBookmarksTitle": "Kirjanmerkit",
									    "tourStepCanvasBookmarksText": "Voit lisätä kirjanmerkkejä kirjanmerkkivalikosta. Paina kirjanmerkki-painiketta avataksesi kirjanmerkkivalikon.",
									    "tourStepCanvasEndscreensTitle": "Palautusikkunat",
									    "tourStepCanvasEndscreensText": "Voit lisätä useita palautusikkunoita painamalla palautusikkunat-painiketta videon perästä(tähti-ikoni.)",
									    "tourStepCanvasPreviewTitle": "Esikatsele interaktiivista videotasi",
									    "tourStepCanvasPreviewText": "Paina Play-painiketta esikatsellaksesi interaktiivista videotasi kun olet editoimassa sitä.",
									    "tourStepCanvasSaveTitle": "Tallenna ja näytä",
									    "tourStepCanvasSaveText": "Kun olet lisännyt haluamasi interaktiiviset elementit videoosi paina tallenna ja näytä katsellaksesi lopputulosta.",
									    "tourStepSummaryText": "Tämä vapaaehtoinen kyselytentti esitetään videon lopussa.",
									    "fullScoreRequiredPause": "\"Full score required\" asetus vaatii että \"Pause\" on myös sallittu",
									    "fullScoreRequiredRetry": "\"Full score required\" asetus vaatii että \"Retry\" on myös sallittu",
									    "fullScoreRequiredTimeFrame": "Tässä samassa ajankohdassa on jo elementti joka vaatii täydet pisteet, täten vain toiseen näistä vaaditaan vastaus jotta suorittaja saa täydet pisteet",
									    "addEndscreen": "Lisää Palautusikkuna ajankohtaan @timecode",
									    "endscreen": "Palautusikkuna",
									    "endscreenAlreadyExists": "Tälle samalle ajankohdalle on jo määritetty palautusikkuna, lisää uusi palautusikkuna toiseen ajankohtaan.",
									    "tooltipBookmarks": "Klikkaa lisätäksesi kirjanmerkin tähän kyseiseen videon ajankohtaan.",
									    "tooltipEndscreens": "Klikkaa lisätäksesi palautusikkunan tähän videon ajankohtaan.",
									    "expandBreadcrumbButtonLabel": "Go back",
									    "collapseBreadcrumbButtonLabel": "Close navigation"
								    }
								}'
    		],
    		//fr.json
    		[
    			'library_id' => $h5pEditorCIVLibId,
            	'language_code' => 'fr',
            	'translation' => '{
									"libraryStrings": {
									    "selectVideo": "Vous devez ajouter une vidéo avant dajouter des interactions.",
									    "noVideoSource": "Aucune source vidéo",
									    "notVideoField": "\":path\" nest pas une vidéo.",
									    "notImageField": "\":path\" nest pas une image.",
									    "insertElement": "Cliquez et glissez jusquà lemplacement :type",
									    "popupTitle": "Édition :type",
									    "done": "Valider",
									    "loading": "Chargement...",
									    "remove": "Supprimer",
									    "removeInteraction": "Voulez-vous vraiment supprimer cette interaction ?",
									    "addBookmark": "Ajouter un signet à @timecode",
									    "newBookmark": "Nouveau signet",
									    "bookmarkAlreadyExists": "Un signet existe déjà ici. Déplacez le curseur et ajoutez un signet ou un écran de soumission à un autre moment.",
									    "tourButtonStart": "Visite guidée",
									    "tourButtonExit": "Sortir",
									    "tourButtonDone": "Valider",
									    "tourButtonBack": "Retour",
									    "tourButtonNext": "Suivant",
									    "tourStepUploadIntroText": "<p>Cette visite guidée vous montre les fonctionnalités les plus importantes de léditeur de vidéo interactive.</p><p>Commencez cette visite guidée quand vous voulez en cliquant sur le bouton \"Visite guidée\" dans le coin supérieur droit.</p><p>Cliquez sur \"SORTIR\" pour terminer la visite guidée ou sur \"SUIVANT\" pour continuer.</p>",
									    "tourStepUploadFileTitle": "Ajout de la vidéo",
									    "tourStepUploadFileText": "<p>Commencez en ajoutant un fichier vidéo. Vous pouvez déposer un fichier depuis votre ordinateur ou copier lURL vers une vidéo YouTube ou un autre type de vidéo.</p><p>Pour assurer la compatibilité entre les navigateurs, vous pouvez télécharger plusieurs formats de fichier de la même vidéo, tels que mp4 et webm.</p>",
									    "tourStepUploadAddInteractionsTitle": "Ajout des interactions",
									    "tourStepUploadAddInteractionsText": "<p>Une fois que vous avez ajouté une vidéo, vous pouvez ajouter des interactions.</p><p>Cliquez sur \"<em>Ajouter des interactions</em>\" pour commencer.</p>",
									    "tourStepCanvasToolbarTitle": "Ajouter des interactions",
									    "tourStepCanvasToolbarText": "Pour ajouter une interaction, glissez-déposez un élément depuis la barre doutils vers la vidéo.",
									    "tourStepCanvasEditingTitle": "Édition des interactions",
									    "tourStepCanvasEditingText": "<p>Une fois quune interaction a été ajoutée, vous pouvez la faire glisser pour la repositionner.</p><p>Pour modifier la taille dune interaction, cliquez sur les poignées et faites glisser.</p><p>À la sélection dune interaction, un menu contextuel apparaît. Pour éditer le contenu dune interaction, cliquez sur le bouton \"Éditer\" dans le menu contextuel. Vous pouvez supprimer une interaction en cliquant sur le bouton \"Supprimer\" du menu contextuel.</p>",
									    "tourStepCanvasBookmarksTitle": "Signets",
									    "tourStepCanvasBookmarksText": "Vous pouvez ajouter des signets depuis le menu spécifique. Cliquez sur le bouton \"Signets\" pour ouvrir ce menu.",
									    "tourStepCanvasEndscreensTitle": "Écrans de soumission",
									    "tourStepCanvasEndscreensText": "Vous pouvez ajouter des écrans de soumission depuis le menu spécifique. Cliquez sur le bouton \"Écrans de soumission\" pour ouvrir ce menu.",
									    "tourStepCanvasPreviewTitle": "Prévisualiser votre vidéo interactive",
									    "tourStepCanvasPreviewText": "Cliquez sur le bouton de lecture pour visualiser votre vidéo interactive pendant lédition.",
									    "tourStepCanvasSaveTitle": "Enregistrer et visualiser",
									    "tourStepCanvasSaveText": "Quand vous avez terminé dajouter des interactions à votre vidéo, cliquez sur le bouton \"Enregistrer/Créer\" pour voir le résultat.",
									    "tourStepSummaryText": "Ce quiz optionnel apparaîtra à la fin de la vidéo.",
									    "fullScoreRequiredPause": "Loption \"Score complet requis\" requiert que loption \"Pause\" soit activée.",
									    "fullScoreRequiredRetry": "Loption \"Score complet requis\" requiert que loption \"Réessayer\" soit activée",
									    "fullScoreRequiredTimeFrame": "Il existe déjà une interaction qui requiert un score complet sur le même intervalle que cette interaction.<br /> Une seule de ces interactions sera requise pour répondre.",
									    "addEndscreen": "Ajouter un écran de soumission à @timecode",
									    "endscreen": "Écran de soumission",
									    "endscreenAlreadyExists": "Un écran de soumission existe déjà ici. Déplacez le curseur et ajoutez un écran de soumission ou un signet à un autre moment.",
									    "tooltipBookmarks": "Cliquez pour ajouter un signet pour le moment actuel de la vidéo",
									    "tooltipEndscreens": "Cliquez pour ajouter un écran de soumission pour le moment actuel de la vidéo",
									    "expandBreadcrumbButtonLabel": "Retour",
									    "collapseBreadcrumbButtonLabel": "Fermer la navigation"
								    }
								}'
    		],
    		//he.json
    		[
    			'library_id' => $h5pEditorCIVLibId,
            	'language_code' => 'he',
            	'translation' => '{
									"libraryStrings": {
									    "selectVideo": "עליכם לבחור סרטון לפני הוספת אינטראקציות.",
									    "noVideoSource": "אין מקור וידאו",
									    "notVideoField": "\":path\" אינו וידאו.",
									    "notImageField": "\":path\" זו לא תמונה.",
									    "insertElement": "יש להקליק ולגרור למקום :type",
									    "popupTitle": "עריכת :type",
									    "done": "הסתיים",
									    "loading": "טוען...",
									    "remove": "הסרה",
									    "removeInteraction": "האם למחוק אינטראקציה זו?",
									    "addBookmark": "הוספת סימניה ב: @timecode",
									    "newBookmark": "סימניה חדשה",
									    "bookmarkAlreadyExists": "הסימניות כבר קיימות כאן. יש להעביר את המיקום בציר־הזמן כדי להוסיף סימניה במיקום אחר.",
									    "tourButtonStart": "סיור",
									    "tourButtonExit": "יציאה",
									    "tourButtonDone": "הסתיים",
									    "tourButtonBack": "הקודם",
									    "tourButtonNext": "הבא",
									    "tourStepUploadIntroText": "<p>סיור זה מנחה אתכם דרך התכונות החשובות ביותר של עורך וידאו אינטראקטיבי.</p><p>התחילו סיור זה בכל עת על ידי לחיצה על הלחצן סיור בפינה השמאלית העליונה.</p><p>הקישו על יציאה כדי לדלג על הסיור או על המשך כדי להמשיך.</p>",
									    "tourStepUploadFileTitle": "הוספת וידאו",
									    "tourStepUploadFileText": "<p>התחילו על ידי הוספת קובץ וידאו. תוכלו להעלות קובץ מהמחשב שלכם או להדביק כתובת אתר לסרטון YouTube או לקובץ שתומך וידאו.</p><p>כדי להבטיח תאימות בין דפדפנים, תוכלו לטעון פורמטים של קבצים מרובים באותו סרטון, כגון mp4 ו- webm.</p>",
									    "tourStepUploadAddInteractionsTitle": "הוספת אינטראקציות",
									    "tourStepUploadAddInteractionsText": "<p>לאחר שהוספתם סרטון, תוכלו להתחיל להוסיף אינטראקציות.</p><p>יש להקליק על הלשונית <em>הוספת אינטראקציות</em> כדי להתחיל</p>",
									    "tourStepCanvasToolbarTitle": "הוספת אינטראקציות",
									    "tourStepCanvasToolbarText": "כדי להוסיף אינטראקציה, יש לגרור אלמנט מסרגל הכלים ולשחרר אותו על הסרטון.",
									    "tourStepCanvasEditingTitle": "עריכת אינטראקציות",
									    "tourStepCanvasEditingText": "<p>לאחר הוספת אינטראקציה, ניתן לגרור אותה כדי לשנות את מיקומה.</p> <p>לשינוי גודל של אינטראקציה,יש להקליק ולגרור.</p><p>בעת בחירה באינטראקציה, תפריט ההקשר יופיע. כדי לערוך את תוכן האינטראקציה, הקליקו על כפתור עריכה בתפריט. ניתן להסיר אינטראקציה על-ידי לחיצה על לחצן הסר בתפריט. </p>",
									    "tourStepCanvasBookmarksTitle": "סימניות",
									    "tourStepCanvasBookmarksText": "ניתן להוסיף סימניות מתפריט הסימניות. יש ללחוץ על הלחצן סימניות כדי לפתוח את התפריט.",
									    "tourStepCanvasEndscreensTitle": "עמודי סיום",
									    "tourStepCanvasEndscreensText": "ניתן להוסיף עמודי־סיום מתפריט עמודי־סיום. יש להשתמש בכפתור עמודי־סיום לפתיחה של תפריט עמודי־סיום.",
									    "tourStepCanvasPreviewTitle": "צפיה בתצוגה מקדימה של הסרטון שלכם",
									    "tourStepCanvasPreviewText": "יש להקליק על הכפתור הפעלה  כדי לצפות בתצוגה מקדימה של הווידאו האינטראקטיבי בעת עריכה.",
									    "tourStepCanvasSaveTitle": "שמירה ולאחר מכן צפיה",
									    "tourStepCanvasSaveText": "כאשר מסיימים להוסיף אנטרקציות לסרטון שלכם, בחרו בשמירה/יצירה כדי לראות את התוצאה.",
									    "tourStepSummaryText": "חידון סיכום לא־מחייב זה יופיע בסוף הסרטון.",
									    "fullScoreRequiredPause": "האפשרות \"ציון מלא נדרש\" זמינה כאשר האפשרות \"השהייה\" מופעלת.",
									    "fullScoreRequiredRetry": "האפשרות \"ציון מלא נדרש\" זמינה כאשר האפשרות \"ניסיון נוסף\" מופעלת",
									    "fullScoreRequiredTimeFrame": "כבר קיימת אינטראקציה שדורשת ציון מלא באינטראקציה זו. <br /> תידרשו לענות רק על אחת מהאינטראקציות .",
									    "addEndscreen": "הוספת עמוד סיום והגשה ב: @timecode",
									    "endscreen": "עמוד סיום והגשה",
									    "endscreenAlreadyExists": "עמוד סיום והגשה כבר קיים פה. יש להעביר את סמן ציר־הזמן למקום חדש כדי להוסיף עמוד־סיום חדש או סימניה במיקום אחר.",
									    "tooltipBookmarks": "הקליקו להוספת סימניה בנקודת הזמן הנוכחית של הסרטון",
									    "tooltipEndscreens": "הקליקו להוספת מסך הגשה בנקודת הזמן הנוכחית של הסרטון",
									    "expandBreadcrumbButtonLabel": "חזרה לאחור",
									    "collapseBreadcrumbButtonLabel": "סגירת ניווט"
								    }
								}'
    		],
    		//hr.json
    		[
    			'library_id' => $h5pEditorCIVLibId,
            	'language_code' => 'hr',
            	'translation' => '{
									"libraryStrings": {
									  "selectVideo": "Potrebno je odabrati video kako biste mogli dodati interakcije.",
									  "noVideoSource": "Nema video izvora",
									  "notVideoField": "\":path\"  nije video.",
									  "notImageField": "\":path\" nije slika.",
									  "insertElement": "Kliknite i povucite na mjesto :type",
									  "popupTitle": "Uredi :type",
									  "done": "Gotovo",
									  "loading": "Učitavanje...",
									  "remove": "Ukloni",
									  "removeInteraction": "Jeste li sigurni da želite izbrisati ovu interakciju?",
									  "addBookmark": "Dodajte oznaku u @timecode",
									  "newBookmark": "Nova oznaka",
									  "bookmarkAlreadyExists": "Ovdje već postoji oznaka. Pomaknite reprodukciju i dodajte oznaku ili zaslon za slanje u drugom trenutku.",
									  "tourButtonStart": "Turneja",
									  "tourButtonExit": "Izlaz",
									  "tourButtonDone": "Završi",
									  "tourButtonBack": "Natrag",
									  "tourButtonNext": "Sljedeće",
									  "tourStepUploadIntroText": "<p>Ovaj obilazak vodi vas kroz najvažnije značajke interaktivnog video uređivača.</p><p>U bilo kojem trenutku započnite ovaj obilazak pritiskom na gumb Turneja u gornjem desnom kutu.</p><p>Pritisnite IZLAZ da preskočite ovaj obilazak ili pritisnite SLJEDEĆE za nastavak.</p>",
									  "tourStepUploadFileTitle": "Dodavanje videa",
									  "tourStepUploadFileText": "<p>Započnite dodavanjem video datoteke. Možete prenijeti datoteku s računala, zalijepiti URL na YouTube videozapis ili podržanu video datoteku.</p><p>Da biste osigurali kompatibilnost putem web preglednika, možete prenijeti više formata datoteka istog videozapisa, poput mp4 i webm.</p>",
									  "tourStepUploadAddInteractionsTitle": "Dodavanje interakcija",
									  "tourStepUploadAddInteractionsText": "<p>Nakon što dodate videozapis, možete početi dodavati interakcije.</p><p>Za početak pritisnite karticu Dodaj interakcije.</p>",
									  "tourStepCanvasToolbarTitle": "Dodaj interakciju",
									  "tourStepCanvasToolbarText": "Da biste dodali interakciju, povucite element s alatne trake i ispustite ga na video.",
									  "tourStepCanvasEditingTitle": "Uređivanje interakcija",
									  "tourStepCanvasEditingText": "<p>Nakon dodavanja interakcije, možete je povući da biste je premjestili.</p><p>Da biste promijenili veličinu interakcije, pritisnite ju i povucite.</p><p>Kad odaberete interakciju, pojavit će se kontekstni izbornik. Za uređivanje sadržaja interakcije pritisnite gumb Uredi u kontekstnom izborniku. Interakciju možete ukloniti pritiskom na tipku Ukloni u kontekstnom izborniku.</p>",
									  "tourStepCanvasBookmarksTitle": "Oznake",
									  "tourStepCanvasBookmarksText": "Oznake možete dodati s izbornika Oznake. Pritisnite gumb Oznaka za otvaranje izbornika.",
									  "tourStepCanvasEndscreensTitle": "Pošaljite zaslone",
									  "tourStepCanvasEndscreensText": "Zaslone za slanje možete dodati iz izbornika zaslona za slanje. Pritisnite gumb za zaslon za prijavu da biste otvorili izbornik.",
									  "tourStepCanvasPreviewTitle": "Pregledajte svoj interaktivni videozapis",
									  "tourStepCanvasPreviewText": "Pritisnite gumb Reproduciraj da biste pregledali svoj interaktivni videozapis tijekom uređivanja.",
									  "tourStepCanvasSaveTitle": "Spremi i pogledaj",
									  "tourStepCanvasSaveText": "Kad završite s dodavanjem interakcija u vaš video, pritisnite Spremi / Stvori da biste vidjeli rezultat.",
									  "tourStepSummaryText": "Ovaj neobavezni kviz će se pojaviti na kraju videozapisa.",
									  "fullScoreRequiredPause": "Opcija \"Potreban je potpuni rezultat\" zahtijeva da je omogućena opcija \"Pauza\".",
									  "fullScoreRequiredRetry": "Opcija \"Potreban je potpuni rezultat\" zahtijeva da je omogućena opcija \"Pokušaj ponovo\".",
									  "fullScoreRequiredTimeFrame": "<p>Već postoji interakcija koja zahtijeva puni rezultat u istom intervalu kao i ova interakcija.</p><p>Za odgovor će biti potrebna samo jedna od interakcija.</p>",
									  "addEndscreen": "Dodaj zaslon za slanje u @timecode",
									  "endscreen": "Pošalji zaslon",
									  "endscreenAlreadyExists": "Zaslon za slanje već postoji. Pomaknite reprodukciju i dodajte zaslon za slanje ili oznaku u drugo vrijeme.",
									  "tooltipBookmarks": "Kliknite da biste dodali oznaku u trenutnoj točki videozapisa",
									  "tooltipEndscreens": "Kliknite za dodavanje zaslona za slanje u trenutnoj točki videozapisa",
									  "expandBreadcrumbButtonLabel": "Idi natrag",
									  "collapseBreadcrumbButtonLabel": "Zatvori navigaciju"
								    }
								}'
    		],
    		//it.json
    		[
    			'library_id' => $h5pEditorCIVLibId,
            	'language_code' => 'it',
            	'translation' => '{
									"libraryStrings": {
									    "selectVideo": "Devi selezionare un video prima di aggiungere interazioni",
									    "noVideoSource": "Nessuna sorgente video",
									    "notVideoField": "\":path\" non è un video",
									    "notImageField": "\":path\" non è un immagine",
									    "insertElement": "Clicca e trascina per posizionare :type",
									    "popupTitle": "Modifica :type",
									    "done": "Fatto",
									    "loading": "Caricamento in corso...",
									    "remove": "Elimina",
									    "removeInteraction": "Sei sicuro di voler eliminare questa interazione?",
									    "addBookmark": "Aggiungi segnalibro a @timecode",
									    "newBookmark": "Nuovo segnalibro",
									    "bookmarkAlreadyExists": "Esiste già un segnalibro in questo punto. Sposta la testina di riproduzione e aggiungi un segnalibro o una schermata di invio in un altro punto",
									    "tourButtonStart": "Tour",
									    "tourButtonExit": "Esci",
									    "tourButtonDone": "Fatto",
									    "tourButtonBack": "Indietro",
									    "tourButtonNext": "Avanti",
									    "tourStepUploadIntroText": "<p>Questo tour ti offre una guida alle funzionalità più importanti dell editor di video interattivo.</p><p>Inizialo in qualsiasi momento premendo il pulsante Tour nell angolo in alto a destra.</p><p>Premi ESCI per saltarlo o premi AVANTI per continuare</p>",
									    "tourStepUploadFileTitle": "Aggiungi video",
									    "tourStepUploadFileText": "<p>Inizia aggiungendo un video. Puoi caricare un file dal tuo computer, incollare un URL da YouTube o un altro video supportato.</p><p>Per garantire la compatibilità tra i browser, puoi caricare più formati di file dello stesso video, come mp4 e WebM</p>",
									    "tourStepUploadAddInteractionsTitle": "Aggiungi interazioni",
									    "tourStepUploadAddInteractionsText": "<p>Dopo aver aggiunto un video, puoi iniziare ad aggiungere le interazioni.</p><p>Premi la scheda <em>Aggiungi interazioni</em> per iniziare</p>",
									    "tourStepCanvasToolbarTitle": "Aggiungi interazioni",
									    "tourStepCanvasToolbarText": "Per aggiungere uninterazione, trascina un elemento dalla barra degli strumenti e rilascialo sul video",
									    "tourStepCanvasEditingTitle": "Modifica le interazioni",
									    "tourStepCanvasEditingText": "<p>Dopo aver aggiunto uninterazione, la puoi trascinare per riposizionarla.</p><p>Per ridimensionare uninterazione, premi sulle maniglie e trascina.</p><p>Quando selezioni uninterazione, apparirà un menu contestuale. Per modificare il contenuto dell interazione, premi il pulsante Modifica nel menu contestuale. Puoi rimuovere uninterazione premendo il pulsante Rimuovi nel menu contestuale</p>",
									    "tourStepCanvasBookmarksTitle": "Segnalibri",
									    "tourStepCanvasBookmarksText": "Puoi aggiungere Segnalibri dal menù omonimo. Premi il pulsante Segnalibri per aprire il menù",
									    "tourStepCanvasEndscreensTitle": "Schermate di invio",
									    "tourStepCanvasEndscreensText": "Puoi aggiungere schermate di invio dal menù omonimo. Premi il pulsante schermata di invio per aprire il menù",
									    "tourStepCanvasPreviewTitle": "Anteprima del tuo video interattivo",
									    "tourStepCanvasPreviewText": "Premi il pulsante di avvio per vedere il video in anteprima mentre lo modifichi",
									    "tourStepCanvasSaveTitle": "Salva e visualizza",
									    "tourStepCanvasSaveText": "Quando hai finito di aggiungere interazioni al tuo video premi Salva/Crea per vederne il risultato",
									    "tourStepSummaryText": "Questa attività di riepilogo facoltativa apparirà alla fine del video",
									    "fullScoreRequiredPause": "Lopzione \"Punteggio completo obbligatorio\" richiede che \"Pausa\" sia abilitato",
									    "fullScoreRequiredRetry": "Lopzione \"Punteggio completo richiesto\" richiede che \"Riprova\" sia abilitato",
									    "fullScoreRequiredTimeFrame": "Esiste già uninterazione che richiede il punteggio completo in questo stesso intervallo.<br />È ammessa una sola interazione per rispondere",
									    "addEndscreen": "Aggiungi schermata di invio a @timecode",
									    "endscreen": "Schermata di invio",
									    "endscreenAlreadyExists": "Esiste già una schermata di invio in questo punto. Sposta la testina di riproduzione e aggiungi una schermata di invio o un segnalibro in un altro punto",
									    "tooltipBookmarks": "Clicca per aggiungere un segnalibro in questo punto del video",
									    "tooltipEndscreens": "Clicca per aggiungere una schermata di invio in questo punto del video",
									    "expandBreadcrumbButtonLabel": "Torna indietro",
									    "collapseBreadcrumbButtonLabel": "Chiudi navigazione"
								    }
								}'
    		],
    		//ko.json
    		[
    			'library_id' => $h5pEditorCIVLibId,
            	'language_code' => 'ko',
            	'translation' => '{
									"libraryStrings": {
									    "selectVideo": "상호작용을 추가하기 전에 비디오를 선택해야 함.",
									    "noVideoSource": "비디오 소스 없음",
									    "notVideoField": "\":path\" 는 비디오가 아님.",
									    "notImageField": "\":path\" 는 이미지가 아님.",
									    "insertElement": "클릭해서 :type 로 끌어 놓기",
									    "popupTitle": "편집 :type",
									    "done": "완료",
									    "loading": "로딩중...",
									    "remove": "삭제",
									    "removeInteraction": "이 상호작용을 삭제하시겠습니까?",
									    "addBookmark": "@timecode에 북마크 추가",
									    "newBookmark": "새로운 북마크",
									    "bookmarkAlreadyExists": "북마크는 이미 여기에 존재함. 플레이헤드를 이동하여 책갈피 또는 제출 화면을 다른 시간에 추가하십시오.",
									    "tourButtonStart": "둘러보기",
									    "tourButtonExit": "나가기",
									    "tourButtonDone": "완료",
									    "tourButtonBack": "돌아가기",
									    "tourButtonNext": "다음",
									    "tourStepUploadIntroText": "<p>이 둘러보기에서는 상호작용형 비디오 편집기의 가장 중요한 기능을 안내한다. </p><p>오른쪽 상단 모서리에 있는 둘러보기 버튼을 눌러 언제든지 이 둘러보기를 시작하십시오.</p> <p>EXIT(나가기)를 눌러 이 둘러보기를 그만두거나 NEXT(다음)을 눌러 계속 진행하십시오.</p>",
									    "tourStepUploadFileTitle": "비디오 추가하기",
									    "tourStepUploadFileText": "<p>비디오 파일을 추가하여 시작하십시오. 컴퓨터에서 파일을 업로드하거나 유튜브 동영상 또는 지원되는 동영상 파일에 URL을 붙여넣을 수 있습니다. 브라우저 간 호환성을 보장하기 위해 mp4, webm 등 동일한 동영상의 여러 파일 형식을 업로드할 수 있습니다.</p>",
									    "tourStepUploadAddInteractionsTitle": "상호작용 추가하기",
									    "tourStepUploadAddInteractionsText": "<p>비디오를 추가했으면 상호 작용 추가를 시작할 수 있음.</p><p>시작하려면 <em>상호작용 추가</em> (add interactions) 탭을 누르세요</p>",
									    "tourStepCanvasToolbarTitle": "상호작용 추가하기",
									    "tourStepCanvasToolbarText": "상호작용을 추가하려면 도구 모음에서 항목을 끌어 비디오에 놓으십시오.",
									    "tourStepCanvasEditingTitle": "상호작용 편집하기",
									    "tourStepCanvasEditingText": "<p>상호작용이 추가되면 끌어서 위치를 변경할 수 있다.</p><p>상호 작용의 크기를 조정하려면 핸들을 누르고 드래그하십시오.</p><p>상호작용을 선택하면 상황에 맞는 메뉴가 나타난다. 상호 작용의 내용을 편집하려면 상황에 맞는 메뉴에서 편집 버튼을 누르십시오. Remove(제거) 버튼을 눌러 상호 작용을 제거할 수 있다.</p>",
									    "tourStepCanvasBookmarksTitle": "북마크",
									    "tourStepCanvasBookmarksText": "책갈피 메뉴에서 책갈피를 추가할 수 있다. Bookmark(북마크) 버튼을 눌러 메뉴를 여십시오.",
									    "tourStepCanvasEndscreensTitle": "제출 화면",
									    "tourStepCanvasEndscreensText": "제출 화면 메뉴에서 제출 화면을 추가할 수 있다. 메뉴판을 열려면 제출 화면 버튼을 누르십시오.",
									    "tourStepCanvasPreviewTitle": "대화형 비디오 미리 보기",
									    "tourStepCanvasPreviewText": "재생 버튼을 눌러 편집하는 동안 대화형 비디오를 미리 보십시오.",
									    "tourStepCanvasSaveTitle": "저장 후 보기",
									    "tourStepCanvasSaveText": "동작 내용을 동영상에 추가했으면 Save(저장)/Create(만들기)를 눌러 결과를 보십시오.",
									    "tourStepSummaryText": "(선택사항) 요약 퀴즈는 비디오 끝에 나타납니다.",
									    "fullScoreRequiredPause": "\"Full score required\" (만점 필수) 옵션은 \"Pause\" (일시멈춤)이 활성화될 때 나타남.",
									    "fullScoreRequiredRetry": "\"Full score required\" (만점 필수) 옵션은 \"Retry\" (재시도)가 활성화되어야 가능함",
									    "fullScoreRequiredTimeFrame": "이 상호 작용과 동일한 간격에서 전체 점수를 요구하는 상호 작용이 이미 존재한다.<br /> 상호작용 중 하나만 대답하면 된다.",
									    "addEndscreen": "@timecode 에서 제출 화면 추가",
									    "endscreen": "제출 화면",
									    "endscreenAlreadyExists": "제출 화면이 이미 여기에 존재함. 플레이헤드를 이동하여 제출 화면 또는 북마크를 다른 시간에 추가하십시오.",
									    "tooltipBookmarks": "동영상의 현재 지점에서 책갈피를 추가하려면 클릭하십시오",
									    "tooltipEndscreens": "동영상의 현재 시점에서 제출 화면을 추가하려면 클릭하십시오.",
									    "expandBreadcrumbButtonLabel": "돌아가기",
									    "collapseBreadcrumbButtonLabel": "탐색 닫기"
								    }
								}'
    		],
    		//nb.json
    		[
    			'library_id' => $h5pEditorCIVLibId,
            	'language_code' => 'nb',
            	'translation' => '{
									"libraryStrings":{
									    "selectVideo":"Du må sette inn en video før du kan plassere interaksjoner.",
									    "noVideoSource":"Ingen videokilde",
									    "notVideoField":"\":path\" er ikke en video.",
									    "notImageField":"\":path\" er ikke et bilde.",
									    "insertElement":"Klikk å dra for å plassere :type",
									    "popupTitle":"Endre :type",
									    "done":"Ferdig",
									    "loading":"Laster...",
									    "remove":"Fjern",
									    "removeInteraction":"Er du sikker på at du vil fjerne denne interaksjonen?",
									    "addBookmark":"Legg til bokmerke på @timecode",
									    "newBookmark":"Nytt bokmerke",
									    "bookmarkAlreadyExists":"Det finnes allerde et bokmerke her. Spol og plasser bokmerket på en annen tidskode.",
									    "tourButtonStart":"Omvisning",
									    "tourButtonExit":"Avslutt",
									    "tourButtonDone":"Ferdig",
									    "tourButtonBack":"Forrige",
									    "tourButtonNext":"Neste",
									    "tourStepUploadIntroText":"<p>Denne omvisningen forklarer de viktigste funksjonene i editoren til Interaktiv Video.<\/p><p>Du kan når som helst starte omvisningen ved å klikke på omvisningsknappen i øvre høyre hjørne.<\/p><p>Trykk på AVSLUTT for å hoppe over omvisningen, eller trykke på NESTE for å fortsette.<\/p>",
									    "tourStepUploadFileTitle":"Legge til video",
									    "tourStepUploadFileText":"<p>Start med å legge til en videofil. Du kan enten laste opp en videofil fra din maskin eller lime inn en URL til en YouTube- eller støttet videofil.<\/p><p>For å være kompatibel med alle nettlesere, kan du laste opp videofiler i flere forskjellige formater, f.eks. mp4 og webm.<\/p>",
									    "tourStepUploadAddInteractionsTitle":"Legge til interaksjoner",
									    "tourStepUploadAddInteractionsText":"<p>Etter at du har lagt til en video, kan du legge inn interaksjoner.<\/p><p>Trykk på fanen <em>Legg til interaksjoner<\/em> for å starte.<\/p>",
									    "tourStepCanvasToolbarTitle":"Legge til interaksjoner",
									    "tourStepCanvasToolbarText":"For å legge til en interaksjon, dra et element fra verktøylinja, og slipp den på videoen.",
									    "tourStepCanvasEditingTitle":"Redigere interaksjoner",
									    "tourStepCanvasEditingText":"<p>Når en interaksjon har blitt lagt til, kan du dra i den for å flytte på den.<\/p><p>For å endre størrelsen, trykk på håndtakene og dra.<\/p><p>Når du velger et element dukker det opp en kontekstmeny. For å redigere innholdet til en interaksjon, trykk på Rediger-knappen i kontekstmenyen. Du kan slette en interaksjon ved å trykke på Slett-knappen i kontekstmenyen.<\/p>",
									    "tourStepCanvasBookmarksTitle":"Bokmerker",
									    "tourStepCanvasBookmarksText":"Bokmerker kan legges til gjennom bokmerkemenyen. Klikk på bokmerkeknappen for å åpne menyen",
									    "tourStepCanvasEndscreensTitle":"Submit Screens",
									    "tourStepCanvasEndscreensText":"You can add submit screens from the submit screens menu. Press the submit screen button to open the menu.",
									    "tourStepCanvasPreviewTitle":"Forhåndsvisning",
									    "tourStepCanvasPreviewText":"Trykk på avspillerknappen for å forhåndsvise din interaktive video mens du redigerer.",
									    "tourStepCanvasSaveTitle":"Lagre og vise",
									    "tourStepCanvasSaveText":"Når du er ferdig, trykk Lagre\/Opprett for å se det endelige resultatet.",
									    "tourStepSummaryText":"Denne valgfrie, interaktive oppsummeringsoppgaven vil vises på slutten av videoen.",
									    "fullScoreRequiredPause":"\"Alt rett\" alternativet krever at \"Pause\" er aktivert.",
									    "fullScoreRequiredRetry":"\"Alt rett\" alternativet krever at \"Prøv igjen\" er aktivert.",
									    "fullScoreRequiredTimeFrame":"Det finnes allerede en innholdstype som krever alt rett i dette tidsintervallet.<br /> Bare en av innholdstypene vil kreve alt rett.",
									    "addEndscreen":"Add submit screen at @timecode",
									    "endscreen":"Submit screen",
									    "endscreenAlreadyExists":"Submit screen already exists here. Move playhead and add a submit screen or a bookmark at another time.",
									    "tooltipBookmarks":"Click to add bookmark at the current point in the video",
									    "tooltipEndscreens":"Click to add submit screen at the current point in the video",
									    "expandBreadcrumbButtonLabel": "Go back",
									    "collapseBreadcrumbButtonLabel": "Close navigation"
								    }
								}'
    		],
    		//nl.json
    		[
    			'library_id' => $h5pEditorCIVLibId,
            	'language_code' => 'nl',
            	'translation' => '{
									"libraryStrings": {
									    "selectVideo": "Je moet eerst een video selecteren voordat je interacties kan toevoegen.",
									    "noVideoSource": "Geen Videobron",
									    "notVideoField": "\":path\" is geen video.",
									    "notImageField": "\":path\" is geen afbeelding.",
									    "insertElement": "Klik-en-sleep naar positie :type",
									    "popupTitle": "Bewerk :type",
									    "done": "Klaar",
									    "loading": "Aan het laden...",
									    "remove": "Verwijder",
									    "removeInteraction": "Weet je zeker dat je deze interactie wilt verwijderen?",
									    "addBookmark": "Voeg bladwijzer toe op @timecode",
									    "newBookmark": "Nieuwe bladwijzer",
									    "bookmarkAlreadyExists": "Bladwijzer bestaat hier al. Verplaats je positie en voeg een bladwijzer of inzendscherm op een ander tijdstip toe.",
									    "tourButtonStart": "Rondleiding",
									    "tourButtonExit": "Afsluiten",
									    "tourButtonDone": "Klaar",
									    "tourButtonBack": "Terug",
									    "tourButtonNext": "Volgende",
									    "tourStepUploadIntroText": "<p>Deze rondleiding begeleidt je door de belangrijkste kenmerken van de Interactieve Video editor.</p><p>Start deze rondleiding op ieder willekeurig moment via de knop in de rechterbovenhoek.</p><p>Klik AFSLUITEN om deze rondleiding over te slaan of klik VOLGENDE om door te gaan.</p>",
									    "tourStepUploadFileTitle": "Video toevoegen",
									    "tourStepUploadFileText": "<p>Begin met het toevoegen van een videobestand. Je kunt een bestand van je computer uploaden of een URL te plakken naar een YouTube video of ondersteund videobestand.</p><p>Om compatibiliteit tussen browsers te garanderen, kun je meerdere bestandsformaten van dezelfde video uploaden, zoals mp4 en webm.</p>",
									    "tourStepUploadAddInteractionsTitle": "Interacties toevoegen",
									    "tourStepUploadAddInteractionsText": "<p>Wanneer een video is toegevoegd, kun je beginnen met toevoegen van interacties.</p><p>Klik de <em>Interacties toevoegen</em> tab om van start te gaan.</p>",
									    "tourStepCanvasToolbarTitle": "Interacties toevoegen",
									    "tourStepCanvasToolbarText": "Om een interactie toe te voegen, sleep je een element uit de toolbar en plaats het op de video.",
									    "tourStepCanvasEditingTitle": "Interacties bewerken",
									    "tourStepCanvasEditingText": "<p>Wanneer een interactie is toegevoegd, kun je die slepen naar de juiste positie.</p><p>Om de grootte van de interactie opnieuw in te stellen, klik je op de grepen en kun je slepen.</p><p>Als je een interactie selecteert, verschijnt er een context menu. Om de inhoud van de interactie te bewerken, klik de Bewerk-knop in het context menu. Je kunt een interactie verwijderen door de Verwijder-knop te klikken in het context menu.</p>",
									    "tourStepCanvasBookmarksTitle": "Bladwijzers",
									    "tourStepCanvasBookmarksText": "Je kunt bladwijzers toevoegen via het Bladwijzersmenu. Klik de Bladwijzer-knop om het menu te openen.",
									    "tourStepCanvasEndscreensTitle": "Inzendschermen",
									    "tourStepCanvasEndscreensText": "Je kunt inzendschermen toevoegen via het Inzendscherm menu. Klik de Inzendscherm-knop om het menu te openen.",
									    "tourStepCanvasPreviewTitle": "Bekijk je interactieve video",
									    "tourStepCanvasPreviewText": "Klik de Play-knop om je interactieve video te bekijken tijdens het bewerken.",
									    "tourStepCanvasSaveTitle": "Opslaan en bekijken",
									    "tourStepCanvasSaveText": "Als je klaar bent met het toevoegen van interacties aan je video, klik Opslaan/Maken om je resultaat te bekijken.",
									    "tourStepSummaryText": "Deze optionele Samenvattingsquiz zal verschijnen aan het einde van de video.",
									    "fullScoreRequiredPause": "\"Volledige score vereist\" optie vereist dat \"Pause\" is ingeschakeld.",
									    "fullScoreRequiredRetry": "\"Volledige score vereist\" optie vereist dat \"Retry\" is ingeschakeld",
									    "fullScoreRequiredTimeFrame": "Er bestaat reeds een interactie die volledige score vereist in hetzelfde tijdsinterval.<br /> Slechts één van de interacties is verplicht om te beantwoorden.",
									    "addEndscreen": "Voeg inzendscherm toe op @timecode",
									    "endscreen": "Inzendscherm",
									    "endscreenAlreadyExists": "Er bestaat hier reeds een inzendscherm. Verplaats je positie en voeg een inzendscherm of bladwijzer toe op een ander tijdstip.",
									    "tooltipBookmarks": "Klik om een bladwijzer toe te voegen op het huidige tijdstip in de video",
									    "tooltipEndscreens": "Klik om een inzendscherm toe te voegen op het huidige tijdstip in de video",
									    "expandBreadcrumbButtonLabel": "Ga terug",
									    "collapseBreadcrumbButtonLabel": "Sluit navigatie"
								    }
								}'
    		],
    		//nn.json
    		[
    			'library_id' => $h5pEditorCIVLibId,
            	'language_code' => 'nn',
            	'translation' => '{
									"libraryStrings":{
									    "selectVideo":"Du må velge ein video før du kan sette ut interaksjonar.",
									    "noVideoSource":"Ingen videokilde",
									    "notVideoField":"\":path\" er ikkje ein video.",
									    "notImageField":"\":path\" er ikkje eit bilete.",
									    "insertElement":"Klikk å dra for å plassere :type",
									    "popupTitle":"Endre :type",
									    "done":"Ferdig",
									    "loading":"Lastar.",
									    "remove":"Fjern",
									    "removeInteraction":"Er du sikker på at du vil fjerne denne interaksjonen?",
									    "addBookmark":"Legg til bokmerke på @timecode",
									    "newBookmark":"Nytt bokmerke",
									    "bookmarkAlreadyExists":"Det finnes allerde eit bokmerke her. Spol og plasser bokmerket eller resultatet skjermen på ein annen tidskode.",
									    "tourButtonStart":"Omvising",
									    "tourButtonExit":"Avslutt",
									    "tourButtonDone":"Ferdig",
									    "tourButtonBack":"Forrige",
									    "tourButtonNext":"Neste",
									    "tourStepUploadIntroText":"<p>Denne omvisningen forklarer de viktigste funksjonene i editoren til Interaktiv Video.<\/p><p>Du kan når som helst starte omvisningen ved å klikke på omvisningsknappen i øvre høyre hjørne.<\/p><p>Trykk på AVSLUTT for å hoppe over omvisningen, eller trykke på NESTE for å fortsette.<\/p>",
									    "tourStepUploadFileTitle":"Legge til video",
									    "tourStepUploadFileText":"<p>Start med å legge til en videofil. Du kan enten laste opp en videofil fra din maskin eller lime inn en URL til en YouTube- eller støttet videofil.<\/p><p>For å være kompatibel med alle nettlesere, kan du laste opp videofiler i flere forskjellige formater, f.eks. mp4 og webm.<\/p>",
									    "tourStepUploadAddInteractionsTitle":"Legge til interaksjoner",
									    "tourStepUploadAddInteractionsText":"<p>Etter at du har lagt til en video, kan du legge inn interaksjoner.<\/p><p>Trykk på fanen <em>Legg til interaksjoner<\/em> for å starte.<\/p>",
									    "tourStepCanvasToolbarTitle":"Legge til interaksjoner",
									    "tourStepCanvasToolbarText":"For å legge til en interaksjon, dra et element fra verktøylinja, og slipp den på videoen.",
									    "tourStepCanvasEditingTitle":"Redigere interaksjoner",
									    "tourStepCanvasEditingText":"<p>Når en interaksjon har blitt lagt til, kan du dra i den for å flytte på den.<\/p><p>For å endre størrelsen, trykk på håndtakene og dra.<\/p><p>Når du velger et element dukker det opp en kontekstmeny. For å redigere innholdet til en interaksjon, trykk på Rediger-knappen i kontekstmenyen. Du kan slette en interaksjon ved å trykke på Slett-knappen i kontekstmenyen.<\/p>",
									    "tourStepCanvasBookmarksTitle":"Bokmerker",
									    "tourStepCanvasBookmarksText":"Bokmerker kan legges til gjennom bokmerkemenyen. Klikk på bokmerkeknappen for å åpne menyen",
									    "tourStepCanvasEndscreensTitle":"Submit Screens",
									    "tourStepCanvasEndscreensText":"You can add submit screens from the submit screens menu. Press the submit screen button to open the menu.",
									    "tourStepCanvasPreviewTitle":"Forhåndsvisning",
									    "tourStepCanvasPreviewText":"Trykk på avspillerknappen for å forhåndsvise din interaktive video mens du redigerer.",
									    "tourStepCanvasSaveTitle":"Lagre og vise",
									    "tourStepCanvasSaveText":"Når du er ferdig, trykk Lagre\/Opprett for å se det endelige resultatet.",
									    "tourStepSummaryText":"Denne valgfrie, interaktive oppsummeringsoppgaven vil vises på slutten av videoen.",
									    "fullScoreRequiredPause":"\"Alt rett\" alternativet krever at \"Pause\" er aktivert.",
									    "fullScoreRequiredRetry":"\"Alt rett\" alternativet krever at \"Prøv igjen\" er aktivert.",
									    "fullScoreRequiredTimeFrame":"Det finnes allerede en innholdstype som krever alt rett i dette tidsintervallet.<br /> Bare en av innholdstypene vil kreve alt rett.",
									    "addEndscreen":"Add submit screen at @timecode",
									    "endscreen":"Submit screen",
									    "endscreenAlreadyExists":"Submit screen already exists here. Move playhead and add a submit screen or a bookmark at another time.",
									    "tooltipBookmarks":"Click to add bookmark at the current point in the video",
									    "tooltipEndscreens":"Click to add submit screen at the current point in the video",
									    "expandBreadcrumbButtonLabel": "Go back",
									    "collapseBreadcrumbButtonLabel": "Close navigation"
								    }
								}'
    		],
    		//pl.json
    		[
    			'library_id' => $h5pEditorCIVLibId,
            	'language_code' => 'pl',
            	'translation' => '{
									"libraryStrings": {
									    "selectVideo": "Aby dodać interakcje, trzeba najpierw wybrać wideo.",
									    "noVideoSource": "Brak wideo",
									    "notVideoField": ":path nie jest filmem.",
									    "notImageField": ":path nie jest obrazem.",
									    "insertElement": "Kliknij i przeciągnij, aby utworzyć :type",
									    "popupTitle": "Edytuj :type",
									    "done": "Gotowe",
									    "loading": "Ładuję...",
									    "remove": "Usuń",
									    "removeInteraction": "Czy na pewno chcesz usunąć tę interakcję?",
									    "addBookmark": "Dodaj zakładkę na czasie @timecode",
									    "newBookmark": "Nowa zakładka",
									    "bookmarkAlreadyExists": "Na tym czasie istnieje już zakładka. Wybierz inne miejsce w filmie.",
									    "tourButtonStart": "Instrukcja",
									    "tourButtonExit": "Wyjdź",
									    "tourButtonDone": "OK",
									    "tourButtonBack": "Wstecz",
									    "tourButtonNext": "Dalej",
									    "tourStepUploadIntroText": "<p>Ta instrukcja poprowadzi cię przez najważniejsze funkcje edytora Interaktywnego Wideo.<\/p><p>Możesz ją włączyć w każdej chwili klikając przycisk \"Instrukcja\" w prawym górnym rogu.<\/p><p>Kliknij \"Wyjdź\", aby opuścić instrukcję, albo \"Dalej\", aby kontynuować.<\/p>",
									    "tourStepUploadFileTitle": "Dodawanie wideo",
									    "tourStepUploadFileText": "<p>Zacznij od dodania wideo. Możesz załadować plik ze swojego komputera albo wkleić link do YouTube lub pliku online we wspieranym formacie.<\/p><p>Aby twoje wideo działało na większości przeglądarek, zalecamy dodanie kilku wersji tego samego wideo w różnych formatach, takich jak MP4 czy WebM.<\/p>",
									    "tourStepUploadAddInteractionsTitle": "Dodawanie interakcji",
									    "tourStepUploadAddInteractionsText": "<p>Gdy masz już dodane wideo, możesz zacząć dodawać interakcje.<\/p><p>Kliknij \"Dodaj interakcje\", aby rozpocząć.<\/p>",
									    "tourStepCanvasToolbarTitle": "Dodawanie interakcji",
									    "tourStepCanvasToolbarText": "Aby dodać interakcję, przeciągnij element z paska narzędzi i upuść go w wybranym miejscu na wideo.",
									    "tourStepCanvasEditingTitle": "Edytowanie interakcji",
									    "tourStepCanvasEditingText": "<p>Po dodaniu interakcji możesz ją przesuwać.<\/p><p>Aby zmienić jej rozmiar, kliknij na punkt ramki i ciągnij.<\/p><p>Gdy zaznaczysz interakcję, pojawi się menu kontekstowe. W celu edycji treści interakcji, wybierz opcję \"Edytuj\" w menu kontekstowym. Aby usunąć interakcję, wybierz \"Usuń\".<\/p>",
									    "tourStepCanvasBookmarksTitle": "Zakładki",
									    "tourStepCanvasBookmarksText": "Menu \"Zakładki\" pozwala dodawać zakładki do wideo. Kliknij tę opcję, aby otworzyć menu.",
									    "tourStepCanvasEndscreensTitle": "Ekrany wyników",
									    "tourStepCanvasEndscreensText": "Menu \"Ekrany wyników\" pozwala dodawać ekrany podsumowujące do wideo. Kliknij tę opcję, aby otworzyć menu.",
									    "tourStepCanvasPreviewTitle": "Podgląd interaktywnego wideo",
									    "tourStepCanvasPreviewText": "Kliknij przycisk odtwarzania, aby zobaczyć, jak działają twoje interakcje.",
									    "tourStepCanvasSaveTitle": "Zakończenie pracy",
									    "tourStepCanvasSaveText": "Gdy skończysz dodawać interakcje, kliknij przycisk \"Zapisz\/Utwórz\", aby obejrzeć efekt.",
									    "tourStepSummaryText": "Opcjonalny quiz, który pojawi się na końcu wideo.",
									    "fullScoreRequiredPause": "Opcja \"Wymagany pełny wynik\" wymaga włączenia opcji \"Pauzuj\".",
									    "fullScoreRequiredRetry": "Opcja \"Wymagany pełny wynik\" wymaga włączenia opcji \"Spróbuj ponownie\".",
									    "fullScoreRequiredTimeFrame": "Istnieje już interakcja wymagająca pełnego wyniku w tym samym fragmencie co niniejsza.<br />Warunek będzie zastosowany tylko do jednej, dowolnej interakcji.",
									    "addEndscreen": "Dodaj ekran wyników na czasie @timecode",
									    "endscreen": "Ekran wyników",
									    "endscreenAlreadyExists": "Na tym czasie istnieje już ekran wyników. Wybierz inne miejsce w filmie.",
									    "tooltipBookmarks": "Kliknij, aby dodać zakładkę w tym miejscu w wideo.",
									    "tooltipEndscreens": "Kliknij, aby dodać ekran wyników w tym miejscu w wideo",
									    "expandBreadcrumbButtonLabel": "Go back",
									    "collapseBreadcrumbButtonLabel": "Close navigation"
								    }
								}'
    		],
    		//pt-br.json
    		[
    			'library_id' => $h5pEditorCIVLibId,
            	'language_code' => 'pt-br',
            	'translation' => '{
									"libraryStrings": {
									    "selectVideo": "Você precisa selecionar um vídeo antes de adicionar interações.",
									    "noVideoSource": "Sem fonte de vídeo",
									    "notVideoField": "\":path\" não é um vídeo.",
									    "notImageField": "\":path\" não é uma imagem.",
									    "insertElement": "Clique a arraste para posicionar :type",
									    "popupTitle": "Editar :type",
									    "done": "Terminado",
									    "loading": "Carregando...",
									    "remove": "Remover",
									    "removeInteraction": "Você tem certeza que deseja remover esta interação?",
									    "addBookmark": "Adicionar marcação em @timecode",
									    "newBookmark": "Nova marcação",
									    "bookmarkAlreadyExists": "Marcação já existe neste local. Mova o cursor de repordução e adicione uma marcação ou uma tela de submisssão em outro tempo.",
									    "tourButtonStart": "Tour",
									    "tourButtonExit": "Sair",
									    "tourButtonDone": "Finalizar",
									    "tourButtonBack": "Voltar",
									    "tourButtonNext": "Próximo",
									    "tourStepUploadIntroText": "<p>Essa Tour guia você através das mais importantes funcionalidades do editor de vídeo interativo.</p><p>Inicie esta tour em qualquer momento pressinando o botão de Tour no canto superior direito.</p><p>Pressione SAIR para pular esta tour ou pressione PRÓXIMO para continuar.</p>",
									    "tourStepUploadFileTitle": "Adicionando vídeo",
									    "tourStepUploadFileText": "<p>Comece adicionando um arquivo de vídeo. Você pode carregar um arquivo do seu computador ou colar uma URL para um vídeo do YouTube ou arquiv ode vídeo suportado.</p><p>Para garantir compatibilidade entre navegadores, você pode carregar múltiplos formatos do mesmo vídeo, como mp4 e webm.</p>",
									    "tourStepUploadAddInteractionsTitle": "Adicionando interações",
									    "tourStepUploadAddInteractionsText": "<p>Uma vez que você tenha adicionado um vídeo, você pode começar a adicionar interações. </p><p> Pressione a aba <em>Adicionar interações</em> para começar.</p>",
									    "tourStepCanvasToolbarTitle": "Adicionando interações",
									    "tourStepCanvasToolbarText": "Para adicionar uma interação, arraste um elemento da barra de ferramentas e solte em cima do vídeo.",
									    "tourStepCanvasEditingTitle": "Editando interações",
									    "tourStepCanvasEditingText": "<p>Uma vez que a interação foi adicionada, você pode arrastar para reposicioná-la.</p><p>Para redimensionar uma interação, pressione o manipulador de tamanho e arraste.</p><p>Quando você selecionar uma interação, um menu de contexto irá aparecer. Para editar o conteúdo de uma interação, pressione o botão Editar no menu de contexto. Você pode remover uma interação pressionando o botão Remover no menu de contexto.</p>",
									    "tourStepCanvasBookmarksTitle": "Marcações",
									    "tourStepCanvasBookmarksText": "Você pode adicionar marcações do menu de Marcações. Pressione o botão de marcações para abrir o menu.",
									    "tourStepCanvasEndscreensTitle": "Telas de submissão",
									    "tourStepCanvasEndscreensText": "Você pode adicionar telas de submissão do menu de telas de submissão. Pressione o botão de telas de submissão para abrir o menu.",
									    "tourStepCanvasPreviewTitle": "Pré-visualizar seu vídeo interativo",
									    "tourStepCanvasPreviewText": "Pressione o botão Reproduzir para pré-visualizar o seu vídeo interativo enquanto estiver editando.",
									    "tourStepCanvasSaveTitle": "Salvar e ver",
									    "tourStepCanvasSaveText": "Quando você terminar de adicionar interações ao seu vídeo, pressione Salvar/Criar para visualizar o resultado.",
									    "tourStepSummaryText": "Este quiz opcional de resumo irá aparecer ao final do vídeo.",
									    "fullScoreRequiredPause": "A opção \"Pontuação total exigida\" requer que \"Pausar\" esteja habilitado.",
									    "fullScoreRequiredRetry": "A opção \"Pontuação total exigida\" requer que \"Tentar novamente\" esteja habilitado.",
									    "fullScoreRequiredTimeFrame": "Já existe uma interação que requer pontuação total no mesmo intervalo que esta interação.<br /> Apenas uma das interações será obrigatória para responder.",
									    "addEndscreen": "Adicionar tela de submissão em @timecode",
									    "endscreen": "Tela de submissão",
									    "endscreenAlreadyExists": "Uma tela de submissão já existe aqui. Mova o cursor de repordução e adicione uma uma tela de submisssão ou marcação em outro tempo.",
									    "tooltipBookmarks": "Clique para adicionar uma marcação no ponto atual do vídeo",
									    "tooltipEndscreens": "Clique para adicionar uma tela de submissão no ponto atual do vídeo",
									    "expandBreadcrumbButtonLabel": "Voltar",
									    "collapseBreadcrumbButtonLabel": "Fechar navegação"
								    }
								}'
    		],
    		//ru.json
    		[
    			'library_id' => $h5pEditorCIVLibId,
            	'language_code' => 'ru',
            	'translation' => '{
									"libraryStrings": {
									    "selectVideo": "Перед добавлением действий необходимо выбрать видео.",
									    "noVideoSource": "Источник видео отсутствует",
									    "notVideoField": "\":path\" не является видео.",
									    "notImageField": "\":path\" не является изображением.",
									    "insertElement": "Нажмите и переместите на место :type",
									    "popupTitle": "Изменить :type",
									    "done": "Готово",
									    "loading": "Загрузка...",
									    "remove": "Удалить",
									    "removeInteraction": "Вы уверены, что хотите удалить действие?",
									    "addBookmark": "Добавить закладку для @timecode",
									    "newBookmark": "Новая закладка",
									    "bookmarkAlreadyExists": "Закладка здесь уже существует. Переместите закладку в другое место или установите другое время.",
									    "tourButtonStart": "Тур",
									    "tourButtonExit": "Выход",
									    "tourButtonDone": "Готово",
									    "tourButtonBack": "Назад",
									    "tourButtonNext": "Дальше",
									    "tourStepUploadIntroText": "<p>Этот тур покажет Вам самые важные свойства редактора интерактивного видео.</p><p>Начать тур можно в любое время, нажав кнопку Тур в правом верхнем углу.</p><p>Для продолжения нажмите ДАЛЬШЕ и для остановки тура нажмите ВЫХОД.</p>",
									    "tourStepUploadFileTitle": "Добавить видео",
									    "tourStepUploadFileText": "<p>Начните с добавления видео файла. Вы можете загрузить файл с компьютера или скопировав ссылку YouTube.</p><p>Для того, чтобы обеспечить работоспособность в разных браузерах и на устройствах, загрузите видео в разных формата, например mp4 и webm.</p>",
									    "tourStepUploadAddInteractionsTitle": "Добавить действия",
									    "tourStepUploadAddInteractionsText": "<p>Когда Вы добавите видео, Вы можете начать добавлять действия.</p><p>Нажмите <em>Добавить действия</em> чтобы начать.</p>",
									    "tourStepCanvasToolbarTitle": "Добавить действия",
									    "tourStepCanvasToolbarText": "Для того, чтобы добавить действие, переместите элемент с панели инструментов на видео.",
									    "tourStepCanvasEditingTitle": "Изменить действия",
									    "tourStepCanvasEditingText": "<p>Добавленное действие можно переместить.</p><p>Для изменения размера необходимо потянуть за края.</p><p>При выборе действия откроется контекстное меню. Чтобы изменить содержимое действия, нажмите Изменить в контекстном меню. Для удаления действия нажмите кнопку Удалить в контекстном меню.</p>",
									    "tourStepCanvasBookmarksTitle": "Закладки",
									    "tourStepCanvasBookmarksText": "Закладки можно добавить в меню закладок. Для открытия меню нажмите на кнопку Закладки.",
									    "tourStepCanvasEndscreensTitle": "Утвердить экран",
									    "tourStepCanvasEndscreensText": "Вы можете утвердить желаемый вид экрана в меню. Для этого нажмите кнопку утверждения экрана.",
									    "tourStepCanvasPreviewTitle": "Предпросмотр интерактивного видео",
									    "tourStepCanvasPreviewText": "Для предпросмотра интерактивного видео в процессе редактирования нажмите кнопку Воспроизвести.",
									    "tourStepCanvasSaveTitle": "Сохранить и смотреть",
									    "tourStepCanvasSaveText": "При окончании добавления действий, нажмите Сохранить/Создать для просмотра результата.",
									    "tourStepSummaryText": "Этот необязательный краткий тест появиться в конце видео.",
									    "fullScoreRequiredPause": "Опция \"Требуются все баллы\" требует, чтобы \"Пауза\" была разрешена.",
									    "fullScoreRequiredRetry": "Опция \"Требуются все баллы\" требует, чтобы \"Повтор\" был разрешен.",
									    "fullScoreRequiredTimeFrame": "Действие с этим же интервалом, требуемое всех баллов, уже существует.<br /> Лишь одно действие будет действительно в качестве ответа.",
									    "addEndscreen": "Добавить утверждение экрана для @timecode",
									    "endscreen": "Утвердить экран",
									    "endscreenAlreadyExists": "Утверждение экрана здесь уже существует. Передвиньте точку текущего момента, добавьте утверждение экрана или установите закладку на другое время.",
									    "tooltipBookmarks": "Для добавления закладки нажмите на текущую точку в видео",
									    "tooltipEndscreens": "Для утверждения экрана нажмите на текущую точку в видео",
									    "expandBreadcrumbButtonLabel": "Вернуться назад",
									    "collapseBreadcrumbButtonLabel": "Закрыть навигацию"
								    }
								}'
    		],
    		//sl.json
    		[
    			'library_id' => $h5pEditorCIVLibId,
            	'language_code' => 'sl',
            	'translation' => '{
									"libraryStrings": {
									    "selectVideo": "Pred dodajanjem interaktivnih elementov je potrebno naložiti videoposnetek.",
									    "noVideoSource": "Ni videoposnetka",
									    "notVideoField": "\":path\" ni videoposnetek.",
									    "notImageField": "\":path\" ni slika.",
									    "insertElement": "S klikom postavi aktivnost :type",
									    "popupTitle": "Uredi :type",
									    "done": "Potrdi",
									    "loading": "Nalagam ...",
									    "remove": "Izbriši",
									    "removeInteraction": "Resnično izbrišem izbran interaktivni element?",
									    "addBookmark": "Dodaj zaznamek na @timecode",
									    "newBookmark": "Nov zaznamek",
									    "bookmarkAlreadyExists": "Zaznamek na tem mestu že obstaja. Za ureditev novega zaznamka je potrebno izbrati drug čas posnetka.",
									    "tourButtonStart": "Ogled napotkov",
									    "tourButtonExit": "Zapri",
									    "tourButtonDone": "Potrdi",
									    "tourButtonBack": "Nazaj",
									    "tourButtonNext": "Naprej",
									    "tourStepUploadIntroText": "<p>Ti napotki obsegajo najpomembnejše funkcije urejevalnika interaktivnih videoposnetkov.</p><p>Gumb Zapri zaključi ogled napotkov, medtem ko so naslednji koraki dosegljivi z gumbom Naprej.</p>",
									    "tourStepUploadFileTitle": "Nalaganje videoposnetka",
									    "tourStepUploadFileText": "<p>Najprej je potrebno naložiti videoposnetek. To se stori s pretokom datoteke iz računalnika ali dodajanjem spletne povezave posnetka (npr. iz YouTube).</p><p>Za povečanje združljivosti z brskalniki, se lahko posnetek doda tudi v več različnih datotekah/formatih (npr. mp4, webm).</p>",
									    "tourStepUploadAddInteractionsTitle": "Dodajanje interakcij",
									    "tourStepUploadAddInteractionsText": "<p>Po uspešno naloženem videoposnetku, se lahko v koraku 2 dodajo interakcije.</p>",
									    "tourStepCanvasToolbarTitle": "Dodajanje interakcij",
									    "tourStepCanvasToolbarText": "Interaktivni elementi se na ustrezno mesto v videoposnetku povlečejo neposredno iz orodne vrstice.",
									    "tourStepCanvasEditingTitle": "Urejanje interakcij",
									    "tourStepCanvasEditingText": "<p>Postavitev elementov je možno spremeniti tudi kasneje.</p><p>Za spremembo velikosti se enostavno povleče ročice okvirja elementa ali uporabi meni za urejanje.</p><p>Meni za urejanje se prikaže s klikom na izbran interaktivni element.</p>",
									    "tourStepCanvasBookmarksTitle": "Zaznamki",
									    "tourStepCanvasBookmarksText": "Zaznamke se doda v meniju zaznamkov. Ta se odpre s klikom na gumb Zaznamki.",
									    "tourStepCanvasEndscreensTitle": "Zaslon za potrditev odgovorov",
									    "tourStepCanvasEndscreensText": "Zaslon s potrditvami odgovorov iz aktivnosti v videoposnetku se doda v meniju zaslona za potrditev odgovorov. Ta se odpre s klikom na gumb Potrditev odgovorov.",
									    "tourStepCanvasPreviewTitle": "Predogled interaktivnega videoposnetka",
									    "tourStepCanvasPreviewText": "Videoposnetek se lahko ogleda že med urejanjem, in sicer s pomočjo gumba za predvajanje.",
									    "tourStepCanvasSaveTitle": "Shrani in prikaži",
									    "tourStepCanvasSaveText": "Po koncu urejanja interakcij v videoposnetku, je končen rezultat viden po kliku na gumb Shrani in prikaži.",
									    "tourStepSummaryText": "Vprašanja neobvezne aktivnosti Povzetek (Summary) se pokažejo po koncu ogleda videoposnetka.",
									    "fullScoreRequiredPause": "Možnost \"Zahtevane vse točke\" pogojuje omogočeno možnost \"Premor\".",
									    "fullScoreRequiredRetry": "Možnost \"Zahtevane vse točke\" pogojuje omogočeno možnost \"Poskusi ponovno\".",
									    "fullScoreRequiredTimeFrame": "Na tem mestu že obstaja aktivnost, ki za zaključek zahteva vse točke.<br /> Udeleženci bodo morali rešiti le eno aktivnost.",
									    "addEndscreen": "Dodaj zaslon za potrditev odgovorov na @timecode",
									    "endscreen": "Zaslon za potrditev odgovorov",
									    "endscreenAlreadyExists": "Zaslon za potrditev odgovorov na tem mestu že obstaja. Za ureditev novega je potrebno izbrati drug čas posnetka.",
									    "tooltipBookmarks": "S klikom se na izbranem mestu videoposnetka doda zaznamek",
									    "tooltipEndscreens": "S klikom se na izbranem mestu videoposnetka doda zaslon za potrditev odgovorov",
									    "expandBreadcrumbButtonLabel": "Pojdi nazaj",
									    "collapseBreadcrumbButtonLabel": "Zapri navigacijo"
								    }
								}'
    		],
    		//sma.json
    		[
    			'library_id' => $h5pEditorCIVLibId,
            	'language_code' => 'sma',
            	'translation' => '{
									"libraryStrings": {
									    "selectVideo": "You must select a video before adding interactions.",
									    "noVideoSource": "No Video Source",
									    "notVideoField": "\":path\" is not a video.",
									    "notImageField": "\":path\" is not a image.",
									    "insertElement": "Click and drag to place :type",
									    "popupTitle": "Edit :type",
									    "done": "Done",
									    "loading": "Loading...",
									    "remove": "Delete",
									    "removeInteraction": "Are you sure you wish to delete this interaction?",
									    "addBookmark": "Add bookmark at @timecode",
									    "newBookmark": "New bookmark",
									    "bookmarkAlreadyExists": "Bookmark already exists here. Move playhead and add a bookmark or a submit screen at another time.",
									    "tourButtonStart": "Tour",
									    "tourButtonExit": "Exit",
									    "tourButtonDone": "Done",
									    "tourButtonBack": "Back",
									    "tourButtonNext": "Next",
									    "tourStepUploadIntroText": "<p>This tour guides you through the most important features of the Interactive Video editor.</p><p>Start this tour at any time by pressing the Tour button in the top right corner.</p><p>Press EXIT to skip this tour or press NEXT to continue.</p>",
									    "tourStepUploadFileTitle": "Adding video",
									    "tourStepUploadFileText": "<p>Start by adding a video file. You can upload a file from your computer or paste a URL to a YouTube video or a supported video file.</p><p>To ensure compatibility across browsers, you can upload multiple file formats of the same video, such as mp4 and webm.</p>",
									    "tourStepUploadAddInteractionsTitle": "Adding interactions",
									    "tourStepUploadAddInteractionsText": "<p>Once you have added a video, you can start adding interactions.</p><p>Press the <em>Add interactions</em> tab to get started.</p>",
									    "tourStepCanvasToolbarTitle": "Adding interactions",
									    "tourStepCanvasToolbarText": "To add an interaction, drag an element from the toolbar and drop it onto the video.",
									    "tourStepCanvasEditingTitle": "Editing interactions",
									    "tourStepCanvasEditingText": "<p>Once an interaction has been added, you can drag to reposition it.</p><p>To resize an interaction, press on the handles and drag.</p><p>When you select an interaction, a context menu will appear. To edit the content of the interaction, press the Edit button in the context menu. You can remove an interaction by pressing the Remove button on the context menu.</p>",
									    "tourStepCanvasBookmarksTitle": "Bookmarks",
									    "tourStepCanvasBookmarksText": "You can add Bookmarks from the Bookmarks menu. Press the Bookmark button to open the menu.",
									    "tourStepCanvasEndscreensTitle": "Submit Screens",
									    "tourStepCanvasEndscreensText": "You can add submit screens from the submit screens menu. Press the submit screen button to open the menu.",
									    "tourStepCanvasPreviewTitle": "Preview your interactive video",
									    "tourStepCanvasPreviewText": "Press the Play button to preview your interactive video while editing.",
									    "tourStepCanvasSaveTitle": "Save and view",
									    "tourStepCanvasSaveText": "When you are done adding interactions to your video, press Save/Create to view the result.",
									    "tourStepSummaryText": "This optional Summary quiz will appear at the end of the video.",
									    "fullScoreRequiredPause": "\"Full score required\" option requires that \"Pause\" is enabled.",
									    "fullScoreRequiredRetry": "\"Full score required\" option requires that \"Retry\" is enabled",
									    "fullScoreRequiredTimeFrame": "There already exists an interaction that requires full score at the same interval as this interaction.<br /> Only one of the interactions will be required to answer.",
									    "addEndscreen": "Add submit screen at @timecode",
									    "endscreen": "Submit screen",
									    "endscreenAlreadyExists": "Submit screen already exists here. Move playhead and add a submit screen or a bookmark at another time.",
									    "tooltipBookmarks": "Click to add bookmark at the current point in the video",
									    "tooltipEndscreens": "Click to add submit screen at the current point in the video",
									    "expandBreadcrumbButtonLabel": "Go back",
									    "collapseBreadcrumbButtonLabel": "Close navigation"
								    }
								}'
    		],
    		//sme.json
    		[
    			'library_id' => $h5pEditorCIVLibId,
            	'language_code' => 'sme',
            	'translation' => '{
									"libraryStrings": {
									    "selectVideo": "You must select a video before adding interactions.",
									    "noVideoSource": "No Video Source",
									    "notVideoField": "\":path\" is not a video.",
									    "notImageField": "\":path\" is not a image.",
									    "insertElement": "Click and drag to place :type",
									    "popupTitle": "Edit :type",
									    "done": "Done",
									    "loading": "Loading...",
									    "remove": "Delete",
									    "removeInteraction": "Are you sure you wish to delete this interaction?",
									    "addBookmark": "Add bookmark at @timecode",
									    "newBookmark": "New bookmark",
									    "bookmarkAlreadyExists": "Bookmark already exists here. Move playhead and add a bookmark or a submit screen at another time.",
									    "tourButtonStart": "Tour",
									    "tourButtonExit": "Exit",
									    "tourButtonDone": "Done",
									    "tourButtonBack": "Back",
									    "tourButtonNext": "Next",
									    "tourStepUploadIntroText": "<p>This tour guides you through the most important features of the Interactive Video editor.</p><p>Start this tour at any time by pressing the Tour button in the top right corner.</p><p>Press EXIT to skip this tour or press NEXT to continue.</p>",
									    "tourStepUploadFileTitle": "Adding video",
									    "tourStepUploadFileText": "<p>Start by adding a video file. You can upload a file from your computer or paste a URL to a YouTube video or a supported video file.</p><p>To ensure compatibility across browsers, you can upload multiple file formats of the same video, such as mp4 and webm.</p>",
									    "tourStepUploadAddInteractionsTitle": "Adding interactions",
									    "tourStepUploadAddInteractionsText": "<p>Once you have added a video, you can start adding interactions.</p><p>Press the <em>Add interactions</em> tab to get started.</p>",
									    "tourStepCanvasToolbarTitle": "Adding interactions",
									    "tourStepCanvasToolbarText": "To add an interaction, drag an element from the toolbar and drop it onto the video.",
									    "tourStepCanvasEditingTitle": "Editing interactions",
									    "tourStepCanvasEditingText": "<p>Once an interaction has been added, you can drag to reposition it.</p><p>To resize an interaction, press on the handles and drag.</p><p>When you select an interaction, a context menu will appear. To edit the content of the interaction, press the Edit button in the context menu. You can remove an interaction by pressing the Remove button on the context menu.</p>",
									    "tourStepCanvasBookmarksTitle": "Bookmarks",
									    "tourStepCanvasBookmarksText": "You can add Bookmarks from the Bookmarks menu. Press the Bookmark button to open the menu.",
									    "tourStepCanvasEndscreensTitle": "Submit Screens",
									    "tourStepCanvasEndscreensText": "You can add submit screens from the submit screens menu. Press the submit screen button to open the menu.",
									    "tourStepCanvasPreviewTitle": "Preview your interactive video",
									    "tourStepCanvasPreviewText": "Press the Play button to preview your interactive video while editing.",
									    "tourStepCanvasSaveTitle": "Save and view",
									    "tourStepCanvasSaveText": "When you are done adding interactions to your video, press Save/Create to view the result.",
									    "tourStepSummaryText": "This optional Summary quiz will appear at the end of the video.",
									    "fullScoreRequiredPause": "\"Full score required\" option requires that \"Pause\" is enabled.",
									    "fullScoreRequiredRetry": "\"Full score required\" option requires that \"Retry\" is enabled",
									    "fullScoreRequiredTimeFrame": "There already exists an interaction that requires full score at the same interval as this interaction.<br /> Only one of the interactions will be required to answer.",
									    "addEndscreen": "Add submit screen at @timecode",
									    "endscreen": "Submit screen",
									    "endscreenAlreadyExists": "Submit screen already exists here. Move playhead and add a submit screen or a bookmark at another time.",
									    "tooltipBookmarks": "Click to add bookmark at the current point in the video",
									    "tooltipEndscreens": "Click to add submit screen at the current point in the video",
									    "expandBreadcrumbButtonLabel": "Go back",
									    "collapseBreadcrumbButtonLabel": "Close navigation"
								    }
								}'
    		],
    		//smj.json
    		[
    			'library_id' => $h5pEditorCIVLibId,
            	'language_code' => 'smj',
            	'translation' => '{
									"libraryStrings": {
									    "selectVideo": "You must select a video before adding interactions.",
									    "noVideoSource": "No Video Source",
									    "notVideoField": "\":path\" is not a video.",
									    "notImageField": "\":path\" is not a image.",
									    "insertElement": "Click and drag to place :type",
									    "popupTitle": "Edit :type",
									    "done": "Done",
									    "loading": "Loading...",
									    "remove": "Delete",
									    "removeInteraction": "Are you sure you wish to delete this interaction?",
									    "addBookmark": "Add bookmark at @timecode",
									    "newBookmark": "New bookmark",
									    "bookmarkAlreadyExists": "Bookmark already exists here. Move playhead and add a bookmark or a submit screen at another time.",
									    "tourButtonStart": "Tour",
									    "tourButtonExit": "Exit",
									    "tourButtonDone": "Done",
									    "tourButtonBack": "Back",
									    "tourButtonNext": "Next",
									    "tourStepUploadIntroText": "<p>This tour guides you through the most important features of the Interactive Video editor.</p><p>Start this tour at any time by pressing the Tour button in the top right corner.</p><p>Press EXIT to skip this tour or press NEXT to continue.</p>",
									    "tourStepUploadFileTitle": "Adding video",
									    "tourStepUploadFileText": "<p>Start by adding a video file. You can upload a file from your computer or paste a URL to a YouTube video or a supported video file.</p><p>To ensure compatibility across browsers, you can upload multiple file formats of the same video, such as mp4 and webm.</p>",
									    "tourStepUploadAddInteractionsTitle": "Adding interactions",
									    "tourStepUploadAddInteractionsText": "<p>Once you have added a video, you can start adding interactions.</p><p>Press the <em>Add interactions</em> tab to get started.</p>",
									    "tourStepCanvasToolbarTitle": "Adding interactions",
									    "tourStepCanvasToolbarText": "To add an interaction, drag an element from the toolbar and drop it onto the video.",
									    "tourStepCanvasEditingTitle": "Editing interactions",
									    "tourStepCanvasEditingText": "<p>Once an interaction has been added, you can drag to reposition it.</p><p>To resize an interaction, press on the handles and drag.</p><p>When you select an interaction, a context menu will appear. To edit the content of the interaction, press the Edit button in the context menu. You can remove an interaction by pressing the Remove button on the context menu.</p>",
									    "tourStepCanvasBookmarksTitle": "Bookmarks",
									    "tourStepCanvasBookmarksText": "You can add Bookmarks from the Bookmarks menu. Press the Bookmark button to open the menu.",
									    "tourStepCanvasEndscreensTitle": "Submit Screens",
									    "tourStepCanvasEndscreensText": "You can add submit screens from the submit screens menu. Press the submit screen button to open the menu.",
									    "tourStepCanvasPreviewTitle": "Preview your interactive video",
									    "tourStepCanvasPreviewText": "Press the Play button to preview your interactive video while editing.",
									    "tourStepCanvasSaveTitle": "Save and view",
									    "tourStepCanvasSaveText": "When you are done adding interactions to your video, press Save/Create to view the result.",
									    "tourStepSummaryText": "This optional Summary quiz will appear at the end of the video.",
									    "fullScoreRequiredPause": "\"Full score required\" option requires that \"Pause\" is enabled.",
									    "fullScoreRequiredRetry": "\"Full score required\" option requires that \"Retry\" is enabled",
									    "fullScoreRequiredTimeFrame": "There already exists an interaction that requires full score at the same interval as this interaction.<br /> Only one of the interactions will be required to answer.",
									    "addEndscreen": "Add submit screen at @timecode",
									    "endscreen": "Submit screen",
									    "endscreenAlreadyExists": "Submit screen already exists here. Move playhead and add a submit screen or a bookmark at another time.",
									    "tooltipBookmarks": "Click to add bookmark at the current point in the video",
									    "tooltipEndscreens": "Click to add submit screen at the current point in the video",
									    "expandBreadcrumbButtonLabel": "Go back",
									    "collapseBreadcrumbButtonLabel": "Close navigation"
								    }
								}'
    		],
    		//sv.json
    		[
    			'library_id' => $h5pEditorCIVLibId,
            	'language_code' => 'sv',
            	'translation' => '{
									"libraryStrings": {
									    "selectVideo": "Du måste välja en video innan du kan placera ut interaktioner.",
									    "noVideoSource": "Ingen videokälla",
									    "notVideoField": "\":path\" är inte en video.",
									    "notImageField": "\":path\" är inte en bild.",
									    "insertElement": "Klicka-och-dra för att placera ut :type",
									    "popupTitle": "Redigera :type",
									    "done": "Färdig",
									    "loading": "Laddar...",
									    "remove": "Radera",
									    "removeInteraction": "Är du säker på att du vill radera denna interaktion?",
									    "addBookmark": "Lägg till bokmärke på @timecode",
									    "newBookmark": "Nytt bokmärke",
									    "bookmarkAlreadyExists": "Det finns redan ett bokmärke här. Spola och placera bokmärket eller inlämningsskärmen på en annan  tidskod.",
									    "tourButtonStart": "Guide",
									    "tourButtonExit": "Avsluta",
									    "tourButtonDone": "Färdig",
									    "tourButtonBack": "Förra",
									    "tourButtonNext": "Nästa",
									    "tourStepUploadIntroText": "<p>Denna guide förklarar de viktigaste funktionerna i redigeraren för Interaktiv Video.</p><p>Du kan när som helst starta om guiden via knappen Guide i övre högra hörnet.</p><p>Tryck på Avsluta för att stänga, eller tryck på Nästa för att fortsätta.</p>",
									    "tourStepUploadFileTitle": "Lägga till video",
									    "tourStepUploadFileText": "<p>Börja med att lägga till en videofil. Du kan antingen ladda upp en fil från din dator eller klistra in en URL till en Youtube-video eller annan videofil som stöds.</p><p>För att säkerställa kompatibilitet med olika webbläsare, så kan du ladda upp videofiler i flera olika format, t ex mp4 och webm. Detta behövs normalt inte om du använder separat videotjänst såsom Youtube.</p>",
									    "tourStepUploadAddInteractionsTitle": "Lägga till interaktioner",
									    "tourStepUploadAddInteractionsText": "<p>Efter du har lagt till en video, så kan du lägga till interaktioner.</p><p>Tyck på fliken <em>Lägg till interaktioner</em> för att börja.</p>",
									    "tourStepCanvasToolbarTitle": "Lägga till interaktioner",
									    "tourStepCanvasToolbarText": "För att lägga till en interaktion, drag ett element från verktygsfältet, och släpp den i videoen.",
									    "tourStepCanvasEditingTitle": "Redigera interaktioner",
									    "tourStepCanvasEditingText": "<p>När en interaktion är tillagd, så kan du dra i den för att flytta på den.</p><p>För att ändra på storleken, tryck på handtagen och dra.</p><p>När du väljer ett element så visas en meny. För att redigera innehållet i en interaktion, tryck på knappen Redigera i menyn. Du kan här också ta bort en interaktion.</p>",
									    "tourStepCanvasBookmarksTitle": "Bokmärken",
									    "tourStepCanvasBookmarksText": "Bokmärken kan läggas till via bokmärkesmenyn. Klicka på knappen Bokmärken för att öppna menyn.",
									    "tourStepCanvasEndscreensTitle": "Inlämningssidor",
									    "tourStepCanvasEndscreensText": "Du kan lägga till inlämningssidor. Klicka knappen Inlämningssida för att öppna menyn.",
									    "tourStepCanvasPreviewTitle": "Förhandsvisning",
									    "tourStepCanvasPreviewText": "Tryck på Spela-knappen för att förhandsvisa din interaktiva video medan du redigerar.",
									    "tourStepCanvasSaveTitle": "Spara och visa",
									    "tourStepCanvasSaveText": "När du är färdig, tryck på  Spara/Skapa för att se det slutgiltiga resultatet.",
									    "tourStepSummaryText": "Denna valfria, interaktiva sammanfattningsuppgiften kommer att visas i slutet på videon.",
									    "fullScoreRequiredPause": "\"Alla rätt\" alternativet kräver att \"Paus\" är aktiverat.",
									    "fullScoreRequiredRetry": "\"Alla rätt\" alternativet kräver att \"Försök igen\" är aktiverat.",
									    "fullScoreRequiredTimeFrame": "Det finns redan en innehållstyp som kräver alla rätt, på samma tidsintervall som denna interaktion.<br /> Endast en av innehållstyperna kommer att kräva alla rätt.",
									    "addEndscreen": "Lägg till inlämningssida vid @timecode",
									    "endscreen": "Inlämningssida",
									    "endscreenAlreadyExists": "Inlämningssida finns redan här. Spola i videon och lägg till inlämningssida eller bokmärke vid annan tid.",
									    "tooltipBookmarks": "Klicka för att lägga till bokmärke på denna tidpunkt i videon",
									    "tooltipEndscreens": "Klicka för att lägga till inlämningssida på denna tidpunkt i videon",
									    "expandBreadcrumbButtonLabel": "Gå tillbaka",
									    "collapseBreadcrumbButtonLabel": "Stäng navigering"
								    }
								}'
    		],
    		//zh.json
    		[
    			'library_id' => $h5pEditorCIVLibId,
            	'language_code' => 'zh',
            	'translation' => '{
									"libraryStrings": {
									    "selectVideo": "添加互動元件前，請先上傳或嵌入影片。",
									    "noVideoSource": "無影片來源",
									    "notVideoField": "\":path\" - 無法識別的影片。",
									    "notImageField": "\":path\" - 無法識別的圖片。",
									    "insertElement": "點擊以添加 :type",
									    "popupTitle": "變更 :type",
									    "done": "完成",
									    "loading": "正在載入",
									    "remove": "移除",
									    "removeInteraction": "確定要移除此互動元件？",
									    "addBookmark": "添加書籤 @timecode",
									    "newBookmark": "新書籤",
									    "bookmarkAlreadyExists": "此處已有書籤，請試試其它時間點。",
									    "tourButtonStart": "使用引導",
									    "tourButtonExit": "退出",
									    "tourButtonDone": "完成",
									    "tourButtonBack": "上一步",
									    "tourButtonNext": "下一步",
									    "tourStepUploadIntroText": "<p>透過指引可以學習互動式影片編輯器的主要功能，</p><p>你可以隨時點擊右上角的使用引導按鈕開始，</p><p>點擊退出可跳過引導，或者點擊下一步繼續。</p>",
									    "tourStepUploadFileTitle": "添加影片",
									    "tourStepUploadFileText": "<p>先添加影片，你可以上傳影片檔案，或是輸入影片的來源網址（支援 Youtube）。</p><p>為了能讓所有瀏覽器都能看到影片，建議同時使用二個以上不同的格式的影片，優先建議格式為 MP4 或 Webm。</p>",
									    "tourStepUploadAddInteractionsTitle": "添加互動元件",
									    "tourStepUploadAddInteractionsText": "<p>影片添加後，就可以開始加入互動元件了。</p><p>趕快點擊「添加互動元件」吧！</p>",
									    "tourStepCanvasToolbarTitle": "添加互動元件",
									    "tourStepCanvasToolbarText": "若要加入互動，請從工具列將要用的元件拖曳到影片上。",
									    "tourStepCanvasEditingTitle": "編輯互動元件",
									    "tourStepCanvasEditingText": "<p>加入互動元件之後，在影片的某個位置上會出現互動點，</p><p>按住互動點，拖曳可改變其互動位置，</p><p>點擊元件時會彈出即時編輯工具，可以對元件進行修改、移除等操作。</p>",
									    "tourStepCanvasBookmarksTitle": "書籤",
									    "tourStepCanvasBookmarksText": "書籤按鈕可添加書籤，點擊後會出現編輯面板。",
									    "tourStepCanvasEndscreensTitle":"提交畫面",
									    "tourStepCanvasEndscreensText":"你可以在提交畫面選單中添加提交畫面。點擊提交畫面按鈕以開啟選單。",
									    "tourStepCanvasPreviewTitle": "預覽",
									    "tourStepCanvasPreviewText": "編輯過程中，點擊播放按鈕可以即時預覽影片的互動。",
									    "tourStepCanvasSaveTitle": "儲存並顯示",
									    "tourStepCanvasSaveText": "在完成後，點擊創建/更新按鈕可以檢視最終的結果。",
									    "tourStepSummaryText": "任務摘要可以選擇是否要顯示在影片的結尾。",
									    "fullScoreRequiredPause": "「需作答正確」選項必須啟用「暫停」功能。",
									    "fullScoreRequiredRetry": "「需作答正確」選項必須啟用「重試」功能。",
									    "fullScoreRequiredTimeFrame": "當前時間已存在一個需作答正確的互動元件，<br />同一時間只能有一個需作答正確的互動元件。",
									    "addEndscreen":"添加提交畫面於 @timecode",
									    "endscreen":"提交畫面",
									    "endscreenAlreadyExists":"此處已有提交畫面，移動播放時間軸，在其它的時間點添加提交畫面或書籤。",
									    "tooltipBookmarks":"點擊以在當前時間點添加書籤。",
									    "tooltipEndscreens":"點擊以在當前時間點添加提交畫面。",
									    "expandBreadcrumbButtonLabel": "Go back",
									    "collapseBreadcrumbButtonLabel": "Close navigation"
								    }
								}'
    		]
    	]);
    }

    /**
     * Get H5P Image Library Semantics
     */
    private function getH5PImageSemantics()
    {
    	return '[
				  {
				    "name": "file",
				    "type": "image",
				    "label": "Image",
				    "importance": "high",
				    "disableCopyright": true
				  },
				  {
				    "name": "alt",
				    "type": "text",
				    "label": "Alternative text",
				    "importance": "high",
				    "description": "Required. If the browser can not load the image this text will be displayed instead. Also used by \"text-to-speech\" readers."
				  },
				  {
				    "name": "title",
				    "type": "text",
				    "label": "Hover text",
				    "importance": "low",
				    "description": "Optional. This text is displayed when the users hover their pointing device over the image.",
				    "optional": true
				  },
				  {
				    "name": "contentName",
				    "type": "text",
				    "label": "Image content name",
				    "importance": "low",
				    "common": true,
				    "default": "Image"
				  }
				]';
    }

    /**
     * Get H5P Text Library Semantics
     */
    private function getH5PTextSemantics()
    {
    	return '[
				  {
				    "name": "text",
				    "type": "text",
				    "widget": "html",
				    "importance": "high",
				    "label": "Text",
				    "enterMode": "p",
				    "tags": [
				      "strong",
				      "em",
				      "del",
				      "a",
				      "ul",
				      "ol",
				      "h2",
				      "h3",
				      "hr",
				      "pre",
				      "code"
				    ]
				  }
				]';
    }

    /**
     * Get H5P Table Library Semantics
     */
    private function getH5PTableSemantics()
    {
    	return '[
				  {
				    "name": "text",
				    "type": "text",
				    "widget": "html",
				    "label": "Table",
				    "importance": "high",
				    "default": "<table class=\"h5p-table\"><thead><tr><th scope=\"col\">Heading Column 1</th><th scope=\"col\">Heading Column 2</th></tr></thead><tbody><tr><td>Row 1 Col 1</td><td>Row 1 Col 2</td></tr><tr><td>Row 2 Col 1</td><td>Row 2 Col 2</td></tr></tbody></table>",
				    "tags": [
				      "strong",
				      "em",
				      "del",
				      "a",
				      "table",
				      "code"
				    ],
				    "font": {
				      "color": true
				    }
				  }
				]';
    }

    /**
     * Get H5P Link Library Semantics
     */
    private function getH5PLinkSemantics()
    {
    	return '[
				  {
				    "name": "title",
				    "type": "text",
				    "importance": "high",
				    "label": "Title"
				  },
				  {
				    "name": "linkWidget",
				    "type": "group",
				    "importance": "high",
				    "widget": "linkWidget",
				    "fields": [
				      {
				        "name": "protocol",
				        "type": "select",
				        "importance": "high",
				        "label": "Protocol",
				        "options": [
				          {
				            "value": "http://",
				            "label": "http://"
				          },
				          {
				            "value": "https://",
				            "label": "https://"
				          },
				          {
				            "value": "/",
				            "label": "(root relative)"
				          },
				          {
				            "value": "other",
				            "label": "other"
				          }
				        ],
				        "optional": true,
				        "default": "http://"
				      },
				      {
				        "name": "url",
				        "type": "text",
				        "importance": "high",
				        "label": "URL"
				      }
				    ]
				  }
				]';
    }

    /**
     * Get H5P Single Choice Set Library Semantics
     */
    private function getH5PSingleChoiceSetSemantics()
    {
    	return '[
                  {
                    "name": "choices",
                    "type": "list",
                    "label": "List of questions",
                    "importance": "high",
                    "entity": "question",
                    "min": 1,
                    "defaultNum": 2,
                    "widgets": [
                      {
                        "name": "ListEditor",
                        "label": "Default"
                      },
                      {
                        "name": "SingleChoiceSetTextualEditor",
                        "label": "Textual"
                      }
                    ],
                    "field": {
                      "name": "choice",
                      "type": "group",
                      "isSubContent": true,
                      "label": "Question & alternatives",
                      "importance": "high",
                      "fields": [
                        {
                          "name": "question",
                          "type": "text",
                          "widget": "html",
                          "tags": [
                            "p",
                            "br",
                            "strong",
                            "em",
                            "code"
                          ],
                          "label": "Question",
                          "importance": "high"
                        },
                        {
                          "name": "answers",
                          "type": "list",
                          "label": "Alternatives - first alternative is the correct one.",
                          "importance": "medium",
                          "entity": "answer",
                          "min": 2,
                          "max": 4,
                          "defaultNum": 2,
                          "field": {
                            "name": "answer",
                            "type": "text",
                            "widget": "html",
                            "tags": [
                              "p",
                              "br",
                              "strong",
                              "em",
                              "code"
                            ],
                            "label": "Alternative",
                            "importance": "medium"
                          }
                        }
                      ]
                    }
                  },
                  {
                    "name": "overallFeedback",
                    "type": "group",
                    "label": "Overall Feedback",
                    "importance": "low",
                    "expanded": true,
                    "fields": [
                      {
                        "name": "overallFeedback",
                        "type": "list",
                        "widgets": [
                          {
                            "name": "RangeList",
                            "label": "Default"
                          }
                        ],
                        "importance": "high",
                        "label": "Define custom feedback for any score range",
                        "description": "Click the \"Add range\" button to add as many ranges as you need. Example: 0-20% Bad score, 21-91% Average Score, 91-100% Great Score!",
                        "entity": "range",
                        "min": 1,
                        "defaultNum": 1,
                        "optional": true,
                        "field": {
                          "name": "overallFeedback",
                          "type": "group",
                          "importance": "low",
                          "fields": [
                            {
                              "name": "from",
                              "type": "number",
                              "label": "Score Range",
                              "min": 0,
                              "max": 100,
                              "default": 0,
                              "unit": "%"
                            },
                            {
                              "name": "to",
                              "type": "number",
                              "min": 0,
                              "max": 100,
                              "default": 100,
                              "unit": "%"
                            },
                            {
                              "name": "feedback",
                              "type": "text",
                              "label": "Feedback for defined score range",
                              "importance": "low",
                              "placeholder": "Fill in the feedback",
                              "optional": true
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
                        "name": "autoContinue",
                        "type": "boolean",
                        "label": "Auto continue",
                        "description": "Automatically go to next question when alternative is selected",
                        "default": true
                      },
                      {
                        "name": "timeoutCorrect",
                        "type": "number",
                        "label": "Timeout on correct answers",
                        "importance": "low",
                        "description": "Value in milliseconds",
                        "default": 2000,
                        "widget": "showWhen",
                        "showWhen": {
                          "rules": [
                            {
                              "field": "autoContinue",
                              "equals": true
                            }
                          ]
                        }
                      },
                      {
                        "name": "timeoutWrong",
                        "type": "number",
                        "label": "Timeout on wrong answers",
                        "importance": "low",
                        "description": "Value in milliseconds",
                        "default": 3000,
                        "widget": "showWhen",
                        "showWhen": {
                          "rules": [
                            {
                              "field": "autoContinue",
                              "equals": true
                            }
                          ]
                        }
                      },
                      {
                        "name": "soundEffectsEnabled",
                        "type": "boolean",
                        "label": "Enable sound effects",
                        "importance": "low",
                        "default": true
                      },
                      {
                        "name": "enableRetry",
                        "type": "boolean",
                        "label": "Enable retry button",
                        "importance": "low",
                        "default": true
                      },
                      {
                        "name": "enableSolutionsButton",
                        "type": "boolean",
                        "label": "Enable show solution button",
                        "importance": "low",
                        "default": true
                      },
                      {
                        "name": "passPercentage",
                        "type": "number",
                        "label": "Pass percentage",
                        "description": "Percentage of Total score required for passing the quiz.",
                        "min": 0,
                        "max": 100,
                        "step": 1,
                        "default": 100
                      }
                    ]
                  },
                  {
                    "name": "currikisettings",
                    "type": "group",
                    "label": "Curriki settings",
                    "importance": "low",
                    "description": "These options will let you control how the curriki studio behaves.",
                    "optional": true,
                    "fields": [
                      {
                        "label": "Do not Show Submit Button",
                        "importance": "low",
                        "name": "disableSubmitButton",
                        "type": "boolean",
                        "default": false,
                        "optional": true,
                        "description": "This option only applies to a standalone activity. The Submit button is required for grade passback to an LMS."
                      },
                      {
                        "label": "Placeholder",
                        "importance": "low",
                        "name": "placeholder",
                        "type": "boolean",
                        "default": false,
                        "optional": true,
                        "description": "This option is a place holder. will be used in future"
                      },
                      {
                        "label": "Curriki Localization",
                        "description": "Here you can edit settings or translate texts used in curriki settings",
                        "importance": "low",
                        "name": "currikil10n",
                        "type": "group",
                        "fields": [
                          {
                            "label": "Text for \"Submit\" button",
                            "name": "submitAnswer",
                            "type": "text",
                            "default": "Submit",
                            "optional": true
                          },
                          {
                            "label": "Text for \"Placeholder\" button",
                            "importance": "low",
                            "name": "placeholderButton",
                            "type": "text",
                            "default": "Placeholder",
                            "optional": true
                          }
                        ]
                      }
                    ]
                  },
                  {
                    "name": "l10n",
                    "type": "group",
                    "label": "Localize single choice set",
                    "importance": "low",
                    "common": true,
                    "fields": [
                      {
                        "name": "nextButtonLabel",
                        "type": "text",
                        "label": "Label for the \"Next\" button",
                        "importance": "low",
                        "default": "Next question"
                      },
                      {
                        "name": "showSolutionButtonLabel",
                        "type": "text",
                        "label": "Label for the \"Show solution\" button",
                        "importance": "low",
                        "default": "Show solution"
                      },
                      {
                        "name": "retryButtonLabel",
                        "type": "text",
                        "label": "Label for the \"Retry\" button",
                        "importance": "low",
                        "default": "Retry"
                      },
                      {
                        "name": "solutionViewTitle",
                        "type": "text",
                        "label": "Title for the show solution view",
                        "importance": "low",
                        "default": "Solution list"
                      },
                      {
                        "name": "correctText",
                        "type": "text",
                        "label": "Readspeaker text for correct answer",
                        "importance": "low",
                        "default": "Correct!"
                      },
                      {
                        "name": "incorrectText",
                        "type": "text",
                        "label": "Readspeaker text for incorrect answer",
                        "importance": "low",
                        "default": "Incorrect!"
                      },
                      {
                        "name": "muteButtonLabel",
                        "type": "text",
                        "label": "Label for the \"mute\" button, to disable feedback sound",
                        "importance": "low",
                        "default": "Mute feedback sound"
                      },
                      {
                        "name": "closeButtonLabel",
                        "type": "text",
                        "label": "Label for the \"Close\" button",
                        "importance": "low",
                        "default": "Close"
                      },
                      {
                        "name": "slideOfTotal",
                        "type": "text",
                        "label": "Slide number text",
                        "importance": "low",
                        "description": "Announces current slide and total number of slides, variables are :num and :total",
                        "default": "Slide :num of :total"
                      },
                      {
                        "name": "scoreBarLabel",
                        "type": "text",
                        "label": "Textual representation of the score bar for those using a readspeaker",
                        "default": "You got :num out of :total points",
                        "importance": "low"
                      },
                      {
                        "name": "solutionListQuestionNumber",
                        "type": "text",
                        "label": "Label for the question number in the solution list",
                        "importance": "low",
                        "description": "Announces current question index in solution list, variables are :num",
                        "default": "Question :num"
                      },
                      
                      {
                        "name": "a11yShowSolution",
                        "type": "text",
                        "label": "Assistive technology description for \"Show Solution\" button",
                        "default": "Show the solution. The task will be marked with its correct solution.",
                        "importance": "low"
                      },
                      {
                        "name": "a11yRetry",
                        "type": "text",
                        "label": "Assistive technology description for \"Retry\" button",
                        "default": "Retry the task. Reset all responses and start the task over again.",
                        "importance": "low"
                      }
                    ]
                  }
                ]';
    }

    /**
     * Get H5P Multiple Choice Library Semantics
     */
    private function getH5PMultiChoiceSemantics()
    {
    	return '[
				  {
				    "name": "media",
				    "type": "group",
				    "label": "Media",
				    "importance": "medium",
				    "fields": [
				      {
				        "name": "type",
				        "type": "library",
				        "label": "Type",
				        "importance": "medium",
				        "options": [
				          "H5P.Image 1.1",
				          "H5P.Video 1.5"
				        ],
				        "optional": true,
				        "description": "Optional media to display above the question."
				      },
				      {
				        "name": "disableImageZooming",
				        "type": "boolean",
				        "label": "Disable image zooming",
				        "importance": "low",
				        "default": false,
				        "optional": true,
				        "widget": "showWhen",
				        "showWhen": {
				          "rules": [
				            {
				              "field": "type",
				              "equals": "H5P.Image 1.1"
				            }
				          ]
				        }
				      }
				    ]
				  },
				  {
				    "name": "question",
				    "type": "text",
				    "importance": "medium",
				    "widget": "html",
				    "label": "Question",
				    "enterMode": "p",
				    "tags": [
				      "strong",
				      "em",
				      "sub",
				      "sup",
				      "h2",
				      "h3",
				      "pre",
				      "code"
				    ]
				  },
				  {
				    "name": "answers",
				    "type": "list",
				    "importance": "high",
				    "label": "Available options",
				    "entity": "option",
				    "min": 1,
				    "defaultNum": 2,
				    "field": {
				      "name": "answer",
				      "type": "group",
				      "label": "Option",
				      "importance": "high",
				      "fields": [
				        {
				          "name": "text",
				          "type": "text",
				          "importance": "medium",
				          "widget": "html",
				          "label": "Text",
				          "tags": [
				            "strong",
				            "em",
				            "sub",
				            "sup",
				            "code"
				          ]
				        },
				        {
				          "name": "correct",
				          "type": "boolean",
				          "label": "Correct",
				          "importance": "low"
				        },
				        {
				          "name": "tipsAndFeedback",
				          "type": "group",
				          "label": "Tips and feedback",
				          "importance": "low",
				          "optional": true,
				          "fields": [
				            {
				              "name": "tip",
				              "type": "text",
				              "widget": "html",
				              "label": "Tip text",
				              "importance": "low",
				              "description": "Hint for the user. This will appear before user checks his answer/answers.",
				              "optional": true,
				              "tags": [
				                "p",
				                "br",
				                "strong",
				                "em",
				                "a",
				                "code"
				              ]
				            },
				            {
				              "name": "chosenFeedback",
				              "type": "text",
				              "widget": "html",
				              "label": "Message displayed if answer is selected",
				              "importance": "low",
				              "description": "Message will appear below the answer on \"check\" if this answer is selected.",
				              "optional": true,
				              "tags": [
				                "strong",
				                "em",
				                "sub",
				                "sup",
				                "a",
				                "code"
				              ]
				            },
				            {
				              "name": "notChosenFeedback",
				              "type": "text",
				              "widget": "html",
				              "label": "Message displayed if answer is not selected",
				              "importance": "low",
				              "description": "Message will appear below the answer on \"check\" if this answer is not selected.",
				              "optional": true,
				              "tags": [
				                "strong",
				                "em",
				                "sub",
				                "sup",
				                "a",
				                "code"
				              ]
				            }
				          ]
				        }
				      ]
				    }
				  },
				  {
				    "name": "overallFeedback",
				    "type": "group",
				    "label": "Overall Feedback",
				    "importance": "low",
				    "expanded": true,
				    "fields": [
				      {
				        "name": "overallFeedback",
				        "type": "list",
				        "widgets": [
				          {
				            "name": "RangeList",
				            "label": "Default"
				          }
				        ],
				        "importance": "high",
				        "label": "Define custom feedback for any score range",
				        "description": "Click the \"Add range\" button to add as many ranges as you need. Example: 0-20% Bad score, 21-91% Average Score, 91-100% Great Score!",
				        "entity": "range",
				        "min": 1,
				        "defaultNum": 1,
				        "optional": true,
				        "field": {
				          "name": "overallFeedback",
				          "type": "group",
				          "importance": "low",
				          "fields": [
				            {
				              "name": "from",
				              "type": "number",
				              "label": "Score Range",
				              "min": 0,
				              "max": 100,
				              "default": 0,
				              "unit": "%"
				            },
				            {
				              "name": "to",
				              "type": "number",
				              "min": 0,
				              "max": 100,
				              "default": 100,
				              "unit": "%"
				            },
				            {
				              "name": "feedback",
				              "type": "text",
				              "label": "Feedback for defined score range",
				              "importance": "low",
				              "placeholder": "Fill in the feedback",
				              "optional": true
				            }
				          ]
				        }
				      }
				    ]
				  },
				  {
				    "name": "UI",
				    "type": "group",
				    "label": "User interface translations for multichoice",
				    "importance": "low",
				    "common": true,
				    "fields": [
				      {
				        "name": "checkAnswerButton",
				        "type": "text",
				        "label": "Check answer button label",
				        "importance": "low",
				        "default": "Check"
				      },
				      {
				        "name": "showSolutionButton",
				        "type": "text",
				        "label": "Show solution button label",
				        "importance": "low",
				        "default": "Show solution"
				      },
				      {
				        "name": "tryAgainButton",
				        "type": "text",
				        "label": "Retry button label",
				        "importance": "low",
				        "default": "Retry",
				        "optional": true
				      },
				      {
				        "name": "tipsLabel",
				        "type": "text",
				        "label": "Tip label",
				        "importance": "low",
				        "default": "Show tip",
				        "optional": true
				      },
				      {
				        "name": "scoreBarLabel",
				        "type": "text",
				        "label": "Textual representation of the score bar for those using a readspeaker",
				        "description": "Available variables are :num and :total",
				        "importance": "low",
				        "default": "You got :num out of :total points",
				        "optional": true
				      },
				      {
				        "name": "tipAvailable",
				        "type": "text",
				        "label": "Tip Available (not displayed)",
				        "importance": "low",
				        "default": "Tip available",
				        "description": "Accessibility text used for readspeakers",
				        "optional": true
				      },
				      {
				        "name": "feedbackAvailable",
				        "type": "text",
				        "label": "Feedback Available (not displayed)",
				        "importance": "low",
				        "default": "Feedback available",
				        "description": "Accessibility text used for readspeakers",
				        "optional": true
				      },
				      {
				        "name": "readFeedback",
				        "type": "text",
				        "label": "Read Feedback (not displayed)",
				        "importance": "low",
				        "default": "Read feedback",
				        "description": "Accessibility text used for readspeakers",
				        "optional": true,
				        "deprecated": true
				      },
				      {
				        "name": "wrongAnswer",
				        "type": "text",
				        "label": "Wrong Answer (not displayed)",
				        "importance": "low",
				        "default": "Wrong answer",
				        "description": "Accessibility text used for readspeakers",
				        "optional": true,
				        "deprecated": true
				      },
				      {
				        "name": "correctAnswer",
				        "type": "text",
				        "label": "Correct Answer (not displayed)",
				        "importance": "low",
				        "default": "Correct answer",
				        "description": "Accessibility text used for readspeakers",
				        "optional": true
				      },
				      {
				        "name": "shouldCheck",
				        "type": "text",
				        "label": "Option should have been checked",
				        "importance": "low",
				        "default": "Should have been checked",
				        "optional": true
				      },
				      {
				        "name": "shouldNotCheck",
				        "type": "text",
				        "label": "Option should not have been checked",
				        "importance": "low",
				        "default": "Should not have been checked",
				        "optional": true
				      },
				      {
				        "label": "Text for \"Requires answer\" message",
				        "importance": "low",
				        "name": "noInput",
				        "type": "text",
				        "default": "Please answer before viewing the solution",
				        "optional": true
				      },
				      {
				        "name": "a11yCheck",
				        "type": "text",
				        "label": "Assistive technology description for \"Check\" button",
				        "default": "Check the answers. The responses will be marked as correct, incorrect, or unanswered.",
				        "importance": "low",
				        "common": true
				      },
				      {
				        "name": "a11yShowSolution",
				        "type": "text",
				        "label": "Assistive technology description for \"Show Solution\" button",
				        "default": "Show the solution. The task will be marked with its correct solution.",
				        "importance": "low",
				        "common": true
				      },
				      {
				        "name": "a11yRetry",
				        "type": "text",
				        "label": "Assistive technology description for \"Retry\" button",
				        "default": "Retry the task. Reset all responses and start the task over again.",
				        "importance": "low",
				        "common": true
				      }
				    ]
				  },
				  {
				    "name": "behaviour",
				    "type": "group",
				    "label": "Behavioural settings",
				    "importance": "low",
				    "description": "These options will let you control how the task behaves.",
				    "optional": true,
				    "fields": [
				      {
				        "name": "enableRetry",
				        "type": "boolean",
				        "label": "Enable \"Retry\" button",
				        "importance": "low",
				        "default": true,
				        "optional": true
				      },
				      {
				        "name": "enableSolutionsButton",
				        "type": "boolean",
				        "label": "Enable \"Show Solution\" button",
				        "importance": "low",
				        "default": true,
				        "optional": true
				      },
				      {
				        "name": "enableCheckButton",
				        "type": "boolean",
				        "label": "Enable \"Check\" button",
				        "widget": "none",
				        "importance": "low",
				        "default": true,
				        "optional": true
				      },
				      {
				        "name": "type",
				        "type": "select",
				        "label": "Question Type",
				        "importance": "low",
				        "description": "Select the look and behaviour of the question.",
				        "default": "auto",
				        "options": [
				          {
				            "value": "auto",
				            "label": "Automatic"
				          },
				          {
				            "value": "multi",
				            "label": "Multiple Choice (Checkboxes)"
				          },
				          {
				            "value": "single",
				            "label": "Single Choice (Radio Buttons)"
				          }
				        ]
				      },
				      {
				        "name": "singlePoint",
				        "type": "boolean",
				        "label": "Give one point for the whole task",
				        "importance": "low",
				        "description": "Enable to give a total of one point for multiple correct answers. This will not be an option in \"Single answer\" mode.",
				        "default": false
				      },
				      {
				        "name": "randomAnswers",
				        "type": "boolean",
				        "label": "Randomize answers",
				        "importance": "low",
				        "description": "Enable to randomize the order of the answers on display.",
				        "default": true
				      },
				      {
				        "label": "Require answer before the solution can be viewed",
				        "importance": "low",
				        "name": "showSolutionsRequiresInput",
				        "type": "boolean",
				        "default": true,
				        "optional": true
				      },
				      {
				        "label": "Show confirmation dialog on \"Check\"",
				        "importance": "low",
				        "name": "confirmCheckDialog",
				        "type": "boolean",
				        "default": false
				      },
				      {
				        "label": "Show confirmation dialog on \"Retry\"",
				        "importance": "low",
				        "name": "confirmRetryDialog",
				        "type": "boolean",
				        "default": false
				      },
				      {
				        "label": "Automatically check answers",
				        "importance": "low",
				        "name": "autoCheck",
				        "type": "boolean",
				        "default": false,
				        "description": "Enabling this option will make accessibility suffer, make sure you know what you are doing."
				      },
				      {
				        "label": "Pass percentage",
				        "name": "passPercentage",
				        "type": "number",
				        "description": "This setting often won not have any effect. It is the percentage of the total score required for getting 1 point when one point for the entire task is enabled, and for getting result.success in xAPI statements.",
				        "min": 0,
				        "max": 100,
				        "step": 1,
				        "default": 100
				      },
				      {
				        "name": "showScorePoints",
				        "type": "boolean",
				        "label": "Show score points",
				        "description": "Show points earned for each answer. This will not be an option in Single answer mode or if Give one point for the whole task option is enabled.",
				        "importance": "low",
				        "default": true
				      }
				    ]
				  },
				  {
				    "label": "Check confirmation dialog",
				    "importance": "low",
				    "name": "confirmCheck",
				    "type": "group",
				    "common": true,
				    "fields": [
				      {
				        "label": "Header text",
				        "importance": "low",
				        "name": "header",
				        "type": "text",
				        "default": "Finish ?"
				      },
				      {
				        "label": "Body text",
				        "importance": "low",
				        "name": "body",
				        "type": "text",
				        "default": "Are you sure you wish to finish ?",
				        "widget": "html",
				        "enterMode": "p",
				        "tags": [
				          "strong",
				          "em",
				          "del",
				          "u",
				          "code"
				        ]
				      },
				      {
				        "label": "Cancel button label",
				        "importance": "low",
				        "name": "cancelLabel",
				        "type": "text",
				        "default": "Cancel"
				      },
				      {
				        "label": "Confirm button label",
				        "importance": "low",
				        "name": "confirmLabel",
				        "type": "text",
				        "default": "Finish"
				      }
				    ]
				  },
				  {
				    "label": "Retry confirmation dialog",
				    "importance": "low",
				    "name": "confirmRetry",
				    "type": "group",
				    "common": true,
				    "fields": [
				      {
				        "label": "Header text",
				        "importance": "low",
				        "name": "header",
				        "type": "text",
				        "default": "Retry ?"
				      },
				      {
				        "label": "Body text",
				        "importance": "low",
				        "name": "body",
				        "type": "text",
				        "default": "Are you sure you wish to retry ?",
				        "widget": "html",
				        "enterMode": "p",
				        "tags": [
				          "strong",
				          "em",
				          "del",
				          "u",
				          "code"
				        ]
				      },
				      {
				        "label": "Cancel button label",
				        "importance": "low",
				        "name": "cancelLabel",
				        "type": "text",
				        "default": "Cancel"
				      },
				      {
				        "label": "Confirm button label",
				        "importance": "low",
				        "name": "confirmLabel",
				        "type": "text",
				        "default": "Confirm"
				      }
				    ]
				  }
				]';
    }

    /**
     * Get H5P True False Library Semantics
     */
    private function getH5PTrueFalseSemantics()
    {
    	return '[
		          {
		            "name": "media",
		            "type": "group",
		            "label": "Media",
		            "importance": "medium",
		            "fields": [
		              {
		                "name": "type",
		                "type": "library",
		                "label": "Type",
		                "importance": "medium",
		                "options": [
		                  "H5P.Image 1.1",
		                  "H5P.Video 1.5"
		                ],
		                "optional": true,
		                "description": "Optional media to display above the question."
		              },
		              {
		                "name": "disableImageZooming",
		                "type": "boolean",
		                "label": "Disable image zooming",
		                "importance": "low",
		                "default": false,
		                "optional": true,
		                "widget": "showWhen",
		                "showWhen": {
		                  "rules": [
		                    {
		                      "field": "type",
		                      "equals": "H5P.Image 1.1"
		                    }
		                  ]
		                }
		              }
		            ]
		          },
		          {
		            "name": "question",
		            "type": "text",
		            "widget": "html",
		            "label": "Question",
		            "importance": "high",
		            "enterMode": "p",
		            "tags": [
		              "strong",
		              "em",
		              "sub",
		              "sup",
		              "h2",
		              "h3",
		              "pre",
		              "code"
		            ]
		          },
		          {
		            "name": "correct",
		            "type": "select",
		            "widget": "radioGroup",
		            "alignment": "horizontal",
		            "label": "Correct answer",
		            "importance": "high",
		            "options": [
		              {
		                "value": "true",
		                "label": "True"
		              },
		              {
		                "value": "false",
		                "label": "False"
		              }
		            ],
		            "default": "true"
		          },
		          {
		            "name": "l10n",
		            "type": "group",
		            "common": true,
		            "label": "User interface translations for True/False Questions",
		            "importance": "low",
		            "fields": [
		              {
		                "name": "trueText",
		                "type": "text",
		                "label": "Label for true button",
		                "importance": "low",
		                "default": "True"
		              },
		              {
		                "name": "falseText",
		                "type": "text",
		                "label": "Label for false button",
		                "importance": "low",
		                "default": "False"
		              },
		              {
		                "label": "Feedback text",
		                "importance": "low",
		                "name": "score",
		                "type": "text",
		                "default": "You got @score of @total points",
		                "description": "Feedback text, variables available: @score and @total. Example: You got @score of @total possible points"
		              },
		              {
		                "label": "Text for \"Check\" button",
		                "importance": "low",
		                "name": "checkAnswer",
		                "type": "text",
		                "default": "Check"
		              },
		              {
		                "label": "Text for \"Show solution\" button",
		                "importance": "low",
		                "name": "showSolutionButton",
		                "type": "text",
		                "default": "Show solution"
		              },
		              {
		                "label": "Text for \"Retry\" button",
		                "importance": "low",
		                "name": "tryAgain",
		                "type": "text",
		                "default": "Retry"
		              },
		              {
		                "name": "wrongAnswerMessage",
		                "type": "text",
		                "label": "Wrong Answer",
		                "importance": "low",
		                "default": "Wrong answer"
		              },
		              {
		                "name": "correctAnswerMessage",
		                "type": "text",
		                "label": "Correct Answer",
		                "importance": "low",
		                "default": "Correct answer"
		              },
		              {
		                "name": "scoreBarLabel",
		                "type": "text",
		                "label": "Textual representation of the score bar for those using a readspeaker",
		                "default": "You got :num out of :total points",
		                "importance": "low"
		              },
		              {
		                "name": "a11yCheck",
		                "type": "text",
		                "label": "Assistive technology description for \"Check\" button",
		                "default": "Check the answers. The responses will be marked as correct, incorrect, or unanswered.",
		                "importance": "low"
		              },
		              {
		                "name": "a11yShowSolution",
		                "type": "text",
		                "label": "Assistive technology description for \"Show Solution\" button",
		                "default": "Show the solution. The task will be marked with its correct solution.",
		                "importance": "low"
		              },
		              {
		                "name": "a11yRetry",
		                "type": "text",
		                "label": "Assistive technology description for \"Retry\" button",
		                "default": "Retry the task. Reset all responses and start the task over again.",
		                "importance": "low"
		              }
		            ]
		          },
		          {
		            "name": "behaviour",
		            "type": "group",
		            "label": "Behavioural settings",
		            "importance": "low",
		            "description": "These options will let you control how the task behaves.",
		            "fields": [
		              {
		                "name": "enableRetry",
		                "type": "boolean",
		                "label": "Enable \"Retry\" button",
		                "importance": "low",
		                "default": true
		              },
		              {
		                "name": "enableSolutionsButton",
		                "type": "boolean",
		                "label": "Enable \"Show Solution\" button",
		                "importance": "low",
		                "default": true
		              },
		              {
		                "name": "enableCheckButton",
		                "type": "boolean",
		                "label": "Enable \"Check\" button",
		                "widget": "none",
		                "importance": "low",
		                "default": true,
		                "optional": true
		              },
		              {
		                "label": "Show confirmation dialog on \"Check\"",
		                "importance": "low",
		                "name": "confirmCheckDialog",
		                "type": "boolean",
		                "default": false
		              },
		              {
		                "label": "Show confirmation dialog on \"Retry\"",
		                "importance": "low",
		                "name": "confirmRetryDialog",
		                "type": "boolean",
		                "default": false
		              },
		              {
		                "label": "Automatically check answer",
		                "importance": "low",
		                "description": "Note that accessibility will suffer if enabling this option",
		                "name": "autoCheck",
		                "type": "boolean",
		                "default": false
		              },
		              {
		                "name": "feedbackOnCorrect",
		                "label": "Feedback on correct answer",
		                "importance": "low",
		                "description": "This will override the default feedback text. Variables available: @score and @total",
		                "type": "text",
		                "maxLength": 2048,
		                "optional": true
		              },
		              {
		                "name": "feedbackOnWrong",
		                "label": "Feedback on wrong answer",
		                "importance": "low",
		                "description": "This will override the default feedback text. Variables available: @score and @total",
		                "type": "text",
		                "maxLength": 2048,
		                "optional": true
		              }
		            ]
		          },
		          {
		            "label": "Check confirmation dialog",
		            "importance": "low",
		            "name": "confirmCheck",
		            "type": "group",
		            "common": true,
		            "fields": [
		              {
		                "label": "Header text",
		                "importance": "low",
		                "name": "header",
		                "type": "text",
		                "default": "Finish ?"
		              },
		              {
		                "label": "Body text",
		                "importance": "low",
		                "name": "body",
		                "type": "text",
		                "default": "Are you sure you wish to finish ?",
		                "widget": "html",
		                "enterMode": "p",
		                "tags": [
		                  "strong",
		                  "em",
		                  "del",
		                  "u",
		                  "code"
		                ]
		              },
		              {
		                "label": "Cancel button label",
		                "importance": "low",
		                "name": "cancelLabel",
		                "type": "text",
		                "default": "Cancel"
		              },
		              {
		                "label": "Confirm button label",
		                "importance": "low",
		                "name": "confirmLabel",
		                "type": "text",
		                "default": "Finish"
		              }
		            ]
		          },
		          {
		            "label": "Retry confirmation dialog",
		            "importance": "low",
		            "name": "confirmRetry",
		            "type": "group",
		            "common": true,
		            "fields": [
		              {
		                "label": "Header text",
		                "importance": "low",
		                "name": "header",
		                "type": "text",
		                "default": "Retry ?"
		              },
		              {
		                "label": "Body text",
		                "importance": "low",
		                "name": "body",
		                "type": "text",
		                "default": "Are you sure you wish to retry ?",
		                "widget": "html",
		                "enterMode": "p",
		                "tags": [
		                  "strong",
		                  "em",
		                  "del",
		                  "u",
		                  "code"
		                ]
		              },
		              {
		                "label": "Cancel button label",
		                "importance": "low",
		                "name": "cancelLabel",
		                "type": "text",
		                "default": "Cancel"
		              },
		              {
		                "label": "Confirm button label",
		                "importance": "low",
		                "name": "confirmLabel",
		                "type": "text",
		                "default": "Confirm"
		              }
		            ]
		          },{
		            "name": "currikisettings",
		            "type": "group",
		            "label": "Curriki settings",
		            "importance": "low",
		            "description": "These options will let you control how the curriki studio behaves.",
		            "optional": true,
		            "fields": [
		              {
		                "label": "Do not Show Submit Button",
		                "importance": "low",
		                "name": "disableSubmitButton",
		                "type": "boolean",
		                "default": false,
		                "optional": true,
		                "description": "This option only applies to a standalone activity. The Submit button is required for grade passback to an LMS."
		              },
		              {
		                "label": "Placeholder",
		                "importance": "low",
		                "name": "placeholder",
		                "type": "boolean",
		                "default": false,
		                "optional": true,
		                "description": "This option is a place holder. will be used in future"
		              },
		              {
		                "label": "Curriki Localization",
		                "description": "Here you can edit settings or translate texts used in curriki settings",
		                "importance": "low",
		                "name": "currikil10n",
		                "type": "group",
		                "fields": [
		                  {
		                    "label": "Text for \"Submit\" button",
		                    "name": "submitAnswer",
		                    "importance": "low",
		                    "type": "text",
		                    "default": "Submit",
		                    "optional": true
		                  },
		                  {
		                    "label": "Text for \"Placeholder\" button",
		                    "importance": "low",
		                    "name": "placeholderButton",
		                    "type": "text",
		                    "default": "Placeholder",
		                    "optional": true
		                  }
		                ]
		              }
		            ]
		          }
		        ]';
    }

    /**
     * Get H5P Curriki Interactive Video Library Semantics
     */
    private function getCIVSemantics() {
      return '[
      	{
		    "name": "interactiveVideo",
		    "type": "group",
		    "widget": "wizard",
		    "label": "Interactive Video Editor",
		    "importance": "high",
		    "fields": [
		      {
		        "name": "video",
		        "type": "group",
		        "label": "Upload/embed video",
		        "importance": "high",
		        "fields": [
		          {
		            "name": "files",
		            "type": "video",
		            "label": "Add a video",
		            "importance": "high",
		            "description": "Click below to add a video you wish to use in your interactive video. You can add a video link or upload video files. It is possible to add several versions of the video with different qualities. To ensure maximum support in browsers at least add a version in webm and mp4 formats.",
		            "extraAttributes": [
		              "metadata"
		            ],
		            "enableCustomQualityLabel": true
		          },
		          {
		            "name": "startScreenOptions",
		            "type": "group",
		            "label": "Start screen options (unsupported for YouTube videos)",
		            "importance": "low",
		            "fields": [
		              {
		                "name": "title",
		                "type": "text",
		                "label": "The title of this interactive video",
		                "importance": "low",
		                "maxLength": 60,
		                "default": "Interactive Video",
		                "description": "Used in summaries, statistics etc."
		              },
		              {
		                "name": "hideStartTitle",
		                "type": "boolean",
		                "label": "Hide title on video start screen",
		                "importance": "low",
		                "optional": true,
		                "default": false
		              },
		              {
		                "name": "shortStartDescription",
		                "type": "text",
		                "label": "Short description (Optional)",
		                "importance": "low",
		                "optional": true,
		                "maxLength": 120,
		                "description": "Optional. Display a short description text on the video start screen. Does not work for YouTube videos."
		              },
		              {
		                "name": "poster",
		                "type": "image",
		                "label": "Poster image",
		                "importance": "low",
		                "optional": true,
		                "description": "Image displayed before the user launches the video. Does not work for YouTube Videos."
		              }
		            ]
		          },
		          {
		            "name": "ltiConsumerSettings",
		            "type": "group",
		            "label": "LTI Consumer Settings",
		            "importance": "low",
		            "optional": true,
		            "fields": [
		              {
		                "name": "title",
		                "type": "text",
		                "label": "The title of this tab container",
		                "importance": "low",
		                "maxLength": 60,
		                "default": "LTI Consumer Settings",
		                "description": "Used in summaries, statistics etc."
		              },
		              {
		                "name": "tool_title",
		                "type": "text",
		                "label": "Tool Name",
		                "importance": "low",
		                "maxLength": 60,
		                "default": "Safari Montage",
		                "description": "The tool name is used to identify the tool provider."
		              },
		              {
		                "name": "tool_url",
		                "type": "text",
		                "label": "Tool Url",
		                "importance": "low",
		                "maxLength": 300,
		                "description": "The tool Url is used to match Urls to the correct tool configuration."
		              },
		              {
		                "type": "text",
		                "name": "tool_description",
		                "widget": "html",
		                "label": "Tool Description",
		                "optional": true,
		                "description": "Description of the tool.",
		                "tags": [
		                  "strong",
		                  "em",
		                  "sub"
		                ],
		                "font": {
		                  "size": true,
		                  "family": true,
		                  "color": true,
		                  "background": true
		                }
		              },
		              {
		                "name": "tool_consumer_key",
		                "type": "text",
		                "label": "Consumer Key",
		                "importance": "low",
		                "maxLength": 500,
		                "description": "The consumer key can be though of as a username used to authenticate access to the tool."
		              },
		              {
		                "name": "tool_secret_key",
		                "type": "text",
		                "label": "Shared Secret",
		                "importance": "low",
		                "maxLength": 500,
		                "description": "The shared secret can be though of as a password used to authenticate access to the tool."
		              }
		            ]
		          },
		          {
		            "name": "textTracks",
		            "type": "group",
		            "label": "Text tracks (unsupported for YouTube videos)",
		            "importance": "low",
		            "fields": [
		              {
		                "name": "videoTrack",
		                "type": "list",
		                "label": "Available text tracks",
		                "importance": "low",
		                "optional": true,
		                "entity": "Track",
		                "min": 0,
		                "defaultNum": 1,
		                "field": {
		                  "name": "track",
		                  "type": "group",
		                  "label": "Track",
		                  "importance": "low",
		                  "expanded": false,
		                  "fields": [
		                    {
		                      "name": "label",
		                      "type": "text",
		                      "label": "Track label",
		                      "description": "Used if you offer multiple tracks and the user has to choose a track. For instance Spanish subtitles could be the label of a Spanish subtitle track.",
		                      "importance": "low",
		                      "default": "Subtitles",
		                      "optional": true
		                    },
		                    {
		                      "name": "kind",
		                      "type": "select",
		                      "label": "Type of text track",
		                      "importance": "low",
		                      "default": "subtitles",
		                      "options": [
		                        {
		                          "value": "subtitles",
		                          "label": "Subtitles"
		                        },
		                        {
		                          "value": "captions",
		                          "label": "Captions"
		                        },
		                        {
		                          "value": "descriptions",
		                          "label": "Descriptions"
		                        }
		                      ]
		                    },
		                    {
		                      "name": "srcLang",
		                      "type": "text",
		                      "label": "Source language, must be defined for subtitles",
		                      "importance": "low",
		                      "default": "en",
		                      "description": "Must be a valid BCP 47 language tag. If Subtitles is the type of text track selected, the source language of the track must be defined."
		                    },
		                    {
		                      "name": "track",
		                      "type": "file",
		                      "label": "Track source (WebVTT file)",
		                      "importance": "low"
		                    }
		                  ]
		                }
		              },
		              {
		                "name": "defaultTrackLabel",
		                "type": "text",
		                "label": "Default text track",
		                "description": "If left empty or not matching any of the text tracks the first text track will be used as the default.",
		                "importance": "low",
		                "optional": true
		              }
		            ]
		          }
		        ]
		      },
		      {
		        "name": "assets",
		        "type": "group",
		        "label": "Add interactions",
		        "importance": "high",
		        "widget": "interactiveVideo",
		        "video": "video/files",
		        "poster": "video/startScreenOptions/poster",
		        "fields": [
		          {
		            "name": "interactions",
		            "type": "list",
		            "field": {
		              "name": "interaction",
		              "type": "group",
		              "fields": [
		                {
		                  "name": "duration",
		                  "type": "group",
		                  "widget": "duration",
		                  "label": "Display time",
		                  "importance": "low",
		                  "fields": [
		                    {
		                      "name": "from",
		                      "type": "number"
		                    },
		                    {
		                      "name": "to",
		                      "type": "number"
		                    }
		                  ]
		                },
		                {
		                  "name": "pause",
		                  "label": "Pause video",
		                  "importance": "low",
		                  "type": "boolean"
		                },
		                {
		                  "name": "displayType",
		                  "label": "Display as",
		                  "importance": "low",
		                  "description": "<b>Button</b> is a collapsed interaction the user must press to open. <b>Poster</b> is an expanded interaction displayed directly on top of the video",
		                  "type": "select",
		                  "widget": "imageRadioButtonGroup",
		                  "options": [
		                    {
		                      "value": "button",
		                      "label": "Button"
		                    },
		                    {
		                      "value": "poster",
		                      "label": "Poster"
		                    }
		                  ],
		                  "default": "button"
		                },
		                {
		                  "name": "buttonOnMobile",
		                  "label": "Turn into button on small screens",
		                  "importance": "low",
		                  "type": "boolean",
		                  "default": false
		                },
		                {
		                  "name": "label",
		                  "type": "text",
		                  "widget": "html",
		                  "label": "Label",
		                  "importance": "low",
		                  "description": "Label displayed next to interaction icon.",
		                  "optional": true,
		                  "enterMode": "p",
		                  "tags": [
		                    "p"
		                  ]
		                },
		                {
		                  "name": "x",
		                  "type": "number",
		                  "importance": "low",
		                  "widget": "none"
		                },
		                {
		                  "name": "y",
		                  "type": "number",
		                  "importance": "low",
		                  "widget": "none"
		                },
		                {
		                  "name": "width",
		                  "type": "number",
		                  "widget": "none",
		                  "importance": "low",
		                  "optional": true
		                },
		                {
		                  "name": "height",
		                  "type": "number",
		                  "widget": "none",
		                  "importance": "low",
		                  "optional": true
		                },
		                {
		                  "name": "libraryTitle",
		                  "type": "text",
		                  "importance": "low",
		                  "optional": true,
		                  "widget": "none"
		                },
		                {
		                  "name": "action",
		                  "type": "library",
		                  "importance": "low",
		                  "options": [
		                    "H5P.Nil 1.0",
		                    "H5P.Text 1.1",
		                    "H5P.Table 1.1",
		                    "H5P.Link 1.3",
		                    "H5P.Image 1.1",
		                    "H5P.Summary 1.10",
		                    "H5P.SingleChoiceSet 1.11",
		                    "H5P.MultiChoice 1.14",
		                    "H5P.TrueFalse 1.6",
		                    "H5P.Blanks 1.12",
		                    "H5P.DragQuestion 1.13",
		                    "H5P.MarkTheWords 1.9",
		                    "H5P.DragText 1.8",
		                    "H5P.GoToQuestion 1.3",
		                    "H5P.IVHotspot 1.2",
		                    "H5P.Questionnaire 1.3",
		                    "H5P.FreeTextQuestion 1.0"
		                  ]
		                },
		                {
		                  "name": "adaptivity",
		                  "type": "group",
		                  "label": "Adaptivity",
		                  "importance": "low",
		                  "optional": true,
		                  "fields": [
		                    {
		                      "name": "correct",
		                      "type": "group",
		                      "label": "Action on all correct",
		                      "fields": [
		                        {
		                          "name": "seekTo",
		                          "type": "number",
		                          "widget": "timecode",
		                          "label": "Seek to",
		                          "description": "Enter timecode in the format M:SS"
		                        },
		                        {
		                          "name": "allowOptOut",
		                          "type": "boolean",
		                          "label": "Allow the user to opt out and continue"
		                        },
		                        {
		                          "name": "message",
		                          "type": "text",
		                          "widget": "html",
		                          "enterMode": "p",
		                          "tags": [
		                            "strong",
		                            "em",
		                            "del",
		                            "a",
		                            "code"
		                          ],
		                          "label": "Message"
		                        },
		                        {
		                          "name": "seekLabel",
		                          "type": "text",
		                          "label": "Label for seek button"
		                        }
		                      ]
		                    },
		                    {
		                      "name": "wrong",
		                      "type": "group",
		                      "label": "Action on wrong",
		                      "fields": [
		                        {
		                          "name": "seekTo",
		                          "type": "number",
		                          "widget": "timecode",
		                          "label": "Seek to",
		                          "description": "Enter timecode in the format M:SS"
		                        },
		                        {
		                          "name": "allowOptOut",
		                          "type": "boolean",
		                          "label": "Allow the user to opt out and continue"
		                        },
		                        {
		                          "name": "message",
		                          "type": "text",
		                          "widget": "html",
		                          "enterMode": "p",
		                          "tags": [
		                            "strong",
		                            "em",
		                            "del",
		                            "a",
		                            "code"
		                          ],
		                          "label": "Message"
		                        },
		                        {
		                          "name": "seekLabel",
		                          "type": "text",
		                          "label": "Label for seek button"
		                        }
		                      ]
		                    },
		                    {
		                      "name": "requireCompletion",
		                      "type": "boolean",
		                      "label": "Require full score for task before proceeding",
		                      "description": "For best functionality this option should be used in conjunction with the \"Prevent skipping forward in a video\" option of Interactive Video."
		                    }
		                  ]
		                },
		                {
		                  "name": "visuals",
		                  "label": "Visuals",
		                  "importance": "low",
		                  "type": "group",
		                  "fields": [
		                    {
		                      "name": "backgroundColor",
		                      "type": "text",
		                      "label": "Background color",
		                      "widget": "colorSelector",
		                      "default": "rgb(255, 255, 255)",
		                      "spectrum": {
		                        "showInput": true,
		                        "showAlpha": true,
		                        "preferredFormat": "rgb",
		                        "showPalette": true,
		                        "palette": [
		                          [
		                            "rgba(0, 0, 0, 0)"
		                          ],
		                          [
		                            "rgb(67, 67, 67)",
		                            "rgb(102, 102, 102)",
		                            "rgb(204, 204, 204)",
		                            "rgb(217, 217, 217)",
		                            "rgb(255, 255, 255)"
		                          ],
		                          [
		                            "rgb(152, 0, 0)",
		                            "rgb(255, 0, 0)",
		                            "rgb(255, 153, 0)",
		                            "rgb(255, 255, 0)",
		                            "rgb(0, 255, 0)",
		                            "rgb(0, 255, 255)",
		                            "rgb(74, 134, 232)",
		                            "rgb(0, 0, 255)",
		                            "rgb(153, 0, 255)",
		                            "rgb(255, 0, 255)"
		                          ],
		                          [
		                            "rgb(230, 184, 175)",
		                            "rgb(244, 204, 204)",
		                            "rgb(252, 229, 205)",
		                            "rgb(255, 242, 204)",
		                            "rgb(217, 234, 211)",
		                            "rgb(208, 224, 227)",
		                            "rgb(201, 218, 248)",
		                            "rgb(207, 226, 243)",
		                            "rgb(217, 210, 233)",
		                            "rgb(234, 209, 220)",
		                            "rgb(221, 126, 107)",
		                            "rgb(234, 153, 153)",
		                            "rgb(249, 203, 156)",
		                            "rgb(255, 229, 153)",
		                            "rgb(182, 215, 168)",
		                            "rgb(162, 196, 201)",
		                            "rgb(164, 194, 244)",
		                            "rgb(159, 197, 232)",
		                            "rgb(180, 167, 214)",
		                            "rgb(213, 166, 189)",
		                            "rgb(204, 65, 37)",
		                            "rgb(224, 102, 102)",
		                            "rgb(246, 178, 107)",
		                            "rgb(255, 217, 102)",
		                            "rgb(147, 196, 125)",
		                            "rgb(118, 165, 175)",
		                            "rgb(109, 158, 235)",
		                            "rgb(111, 168, 220)",
		                            "rgb(142, 124, 195)",
		                            "rgb(194, 123, 160)",
		                            "rgb(166, 28, 0)",
		                            "rgb(204, 0, 0)",
		                            "rgb(230, 145, 56)",
		                            "rgb(241, 194, 50)",
		                            "rgb(106, 168, 79)",
		                            "rgb(69, 129, 142)",
		                            "rgb(60, 120, 216)",
		                            "rgb(61, 133, 198)",
		                            "rgb(103, 78, 167)",
		                            "rgb(166, 77, 121)",
		                            "rgb(91, 15, 0)",
		                            "rgb(102, 0, 0)",
		                            "rgb(120, 63, 4)",
		                            "rgb(127, 96, 0)",
		                            "rgb(39, 78, 19)",
		                            "rgb(12, 52, 61)",
		                            "rgb(28, 69, 135)",
		                            "rgb(7, 55, 99)",
		                            "rgb(32, 18, 77)",
		                            "rgb(76, 17, 48)"
		                          ]
		                        ]
		                      }
		                    },
		                    {
		                      "name": "boxShadow",
		                      "type": "boolean",
		                      "label": "Box shadow",
		                      "default": true,
		                      "description": "Adds a subtle shadow around the interaction. You might want to disable this for completely transparent interactions"
		                    }
		                  ]
		                },
		                {
		                  "name": "goto",
		                  "label": "Go to on click",
		                  "importance": "low",
		                  "type": "group",
		                  "fields": [
		                    {
		                      "name": "type",
		                      "label": "Type",
		                      "type": "select",
		                      "widget": "selectToggleFields",
		                      "options": [
		                        {
		                          "value": "timecode",
		                          "label": "Timecode",
		                          "hideFields": [
		                            "url"
		                          ]
		                        },
		                        {
		                          "value": "url",
		                          "label": "Another page (URL)",
		                          "hideFields": [
		                            "time"
		                          ]
		                        }
		                      ],
		                      "optional": true
		                    },
		                    {
		                      "name": "time",
		                      "type": "number",
		                      "widget": "timecode",
		                      "label": "Go To",
		                      "description": "The target time the user will be taken to upon pressing the hotspot. Enter timecode in the format M:SS.",
		                      "optional": true
		                    },
		                    {
		                      "name": "url",
		                      "type": "group",
		                      "label": "URL",
		                      "widget": "linkWidget",
		                      "optional": true,
		                      "fields": [
		                        {
		                          "name": "protocol",
		                          "type": "select",
		                          "label": "Protocol",
		                          "options": [
		                            {
		                              "value": "http://",
		                              "label": "http://"
		                            },
		                            {
		                              "value": "https://",
		                              "label": "https://"
		                            },
		                            {
		                              "value": "/",
		                              "label": "(root relative)"
		                            },
		                            {
		                              "value": "other",
		                              "label": "other"
		                            }
		                          ],
		                          "optional": true,
		                          "default": "http://"
		                        },
		                        {
		                          "name": "url",
		                          "type": "text",
		                          "label": "URL",
		                          "optional": true
		                        }
		                      ]
		                    },
		                    {
		                      "name": "visualize",
		                      "type": "boolean",
		                      "label": "Visualize",
		                      "description": "Show that interaction can be clicked by adding a border and an icon"
		                    }
		                  ]
		                }
		              ]
		            }
		          },
		          {
		            "name": "bookmarks",
		            "importance": "low",
		            "type": "list",
		            "field": {
		              "name": "bookmark",
		              "type": "group",
		              "fields": [
		                {
		                  "name": "time",
		                  "type": "number"
		                },
		                {
		                  "name": "label",
		                  "type": "text"
		                }
		              ]
		            }
		          },
		          {
		            "name": "endscreens",
		            "importance": "low",
		            "type": "list",
		            "field": {
		              "name": "endscreen",
		              "type": "group",
		              "fields": [
		                {
		                  "name": "time",
		                  "type": "number"
		                },
		                {
		                  "name": "label",
		                  "type": "text"
		                }
		              ]
		            }
		          }
		        ]
		      },
		      {
		        "name": "summary",
		        "type": "group",
		        "label": "Summary task",
		        "importance": "high",
		        "fields": [
		          {
		            "name": "task",
		            "type": "library",
		            "options": [
		              "H5P.Summary 1.10"
		            ],
		            "default": {
		              "library": "H5P.Summary 1.10",
		              "params": {}
		            }
		          },
		          {
		            "name": "displayAt",
		            "type": "number",
		            "label": "Display at",
		            "description": "Number of seconds before the video ends.",
		            "default": 3
		          }
		        ]
		      }
		    ]
		},
		{
		    "name": "override",
		    "type": "group",
		    "label": "Behavioural settings",
		    "importance": "low",
		    "optional": true,
		    "fields": [
		      {
		        "name": "startVideoAt",
		        "type": "number",
		        "widget": "timecode",
		        "label": "Start video at",
		        "importance": "low",
		        "optional": true,
		        "description": "Enter timecode in the format M:SS"
		      },
		      {
		        "name": "autoplay",
		        "type": "boolean",
		        "label": "Auto-play video",
		        "default": false,
		        "optional": true,
		        "description": "Start playing the video automatically"
		      },
		      {
		        "name": "loop",
		        "type": "boolean",
		        "label": "Loop the video",
		        "default": false,
		        "optional": true,
		        "description": "Check if video should run in a loop"
		      },
		      {
		        "name": "showSolutionButton",
		        "type": "select",
		        "label": "Override \"Show Solution\" button",
		        "importance": "low",
		        "description": "This option determines if the \"Show Solution\" button will be shown for all questions, disabled for all or configured for each question individually.",
		        "optional": true,
		        "options": [
		          {
		            "value": "on",
		            "label": "Enabled"
		          },
		          {
		            "value": "off",
		            "label": "Disabled"
		          }
		        ]
		      },
		      {
		        "name": "retryButton",
		        "type": "select",
		        "label": "Override \"Retry\" button",
		        "importance": "low",
		        "description": "This option determines if the \"Retry\" button will be shown for all questions, disabled for all or configured for each question individually.",
		        "optional": true,
		        "options": [
		          {
		            "value": "on",
		            "label": "Enabled"
		          },
		          {
		            "value": "off",
		            "label": "Disabled"
		          }
		        ]
		      },
		      {
		        "name": "showBookmarksmenuOnLoad",
		        "type": "boolean",
		        "label": "Start with bookmarks menu open",
		        "importance": "low",
		        "default": false,
		        "description": "This function is not available on iPad when using YouTube as video source."
		      },
		      {
		        "name": "showRewind10",
		        "type": "boolean",
		        "label": "Show button for rewinding 10 seconds",
		        "importance": "low",
		        "default": false
		      },
		      {
		        "name": "preventSkipping",
		        "type": "boolean",
		        "default": false,
		        "label": "Prevent skipping forward in a video",
		        "importance": "low",
		        "description": "Enabling this options will disable user video navigation through default controls."
		      },
		      {
		        "name": "deactivateSound",
		        "type": "boolean",
		        "default": false,
		        "label": "Deactivate sound",
		        "importance": "low",
		        "description": "Enabling this option will deactivate the videos sound and prevent it from being switched on."
		      }
		    ]
		},
		{
		    "name": "l10n",
		    "type": "group",
		    "label": "Localize",
		    "importance": "low",
		    "common": true,
		    "optional": true,
		    "fields": [
		      {
		        "name": "interaction",
		        "type": "text",
		        "label": "Interaction title",
		        "importance": "low",
		        "default": "Interaction",
		        "optional": true
		      },
		      {
		        "name": "play",
		        "type": "text",
		        "label": "Play title",
		        "importance": "low",
		        "default": "Play",
		        "optional": true
		      },
		      {
		        "name": "pause",
		        "type": "text",
		        "label": "Pause title",
		        "importance": "low",
		        "default": "Pause",
		        "optional": true
		      },
		      {
		        "name": "mute",
		        "type": "text",
		        "label": "Mute title",
		        "importance": "low",
		        "default": "Mute",
		        "optional": true
		      },
		      {
		        "name": "unmute",
		        "type": "text",
		        "label": "Unmute title",
		        "importance": "low",
		        "default": "Unmute",
		        "optional": true
		      },
		      {
		        "name": "quality",
		        "type": "text",
		        "label": "Video quality title",
		        "importance": "low",
		        "default": "Video Quality",
		        "optional": true
		      },
		      {
		        "name": "captions",
		        "type": "text",
		        "label": "Video captions title",
		        "importance": "low",
		        "default": "Captions",
		        "optional": true
		      },
		      {
		        "name": "close",
		        "type": "text",
		        "label": "Close button text",
		        "importance": "low",
		        "default": "Close",
		        "optional": true
		      },
		      {
		        "name": "fullscreen",
		        "type": "text",
		        "label": "Fullscreen title",
		        "importance": "low",
		        "default": "Fullscreen",
		        "optional": true
		      },
		      {
		        "name": "exitFullscreen",
		        "type": "text",
		        "label": "Exit fullscreen title",
		        "importance": "low",
		        "default": "Exit Fullscreen",
		        "optional": true
		      },
		      {
		        "name": "summary",
		        "type": "text",
		        "label": "Summary title",
		        "importance": "low",
		        "default": "Open summary dialog",
		        "optional": true
		      },
		      {
		        "name": "bookmarks",
		        "type": "text",
		        "label": "Bookmarks title",
		        "importance": "low",
		        "default": "Bookmarks",
		        "optional": true
		      },
		      {
		        "name": "endscreen",
		        "type": "text",
		        "label": "Submit screen title",
		        "importance": "low",
		        "default": "Submit screen",
		        "optional": true
		      },
		      {
		        "name": "defaultAdaptivitySeekLabel",
		        "type": "text",
		        "label": "Default label for adaptivity seek button",
		        "importance": "low",
		        "default": "Continue",
		        "optional": true
		      },
		      {
		        "name": "continueWithVideo",
		        "type": "text",
		        "label": "Default label for continue video button",
		        "importance": "low",
		        "default": "Continue with video",
		        "optional": true
		      },
		      {
		        "name": "playbackRate",
		        "type": "text",
		        "label": "Set playback rate",
		        "importance": "low",
		        "default": "Playback Rate",
		        "optional": true
		      },
		      {
		        "name": "rewind10",
		        "type": "text",
		        "label": "Rewind 10 Seconds",
		        "importance": "low",
		        "default": "Rewind 10 Seconds",
		        "optional": true
		      },
		      {
		        "name": "navDisabled",
		        "type": "text",
		        "label": "Navigation is disabled text",
		        "importance": "low",
		        "default": "Navigation is disabled",
		        "optional": true
		      },
		      {
		        "name": "sndDisabled",
		        "type": "text",
		        "label": "Sound is disabled text",
		        "importance": "low",
		        "default": "Sound is disabled",
		        "optional": true
		      },
		      {
		        "name": "requiresCompletionWarning",
		        "type": "text",
		        "label": "Warning that the user must answer the question correctly before continuing",
		        "importance": "low",
		        "default": "You need to answer all the questions correctly before continuing.",
		        "optional": true
		      },
		      {
		        "name": "back",
		        "type": "text",
		        "label": "Back button",
		        "importance": "low",
		        "default": "Back",
		        "optional": true
		      },
		      {
		        "name": "hours",
		        "type": "text",
		        "label": "Passed time hours",
		        "importance": "low",
		        "default": "Hours",
		        "optional": true
		      },
		      {
		        "name": "minutes",
		        "type": "text",
		        "label": "Passed time minutes",
		        "importance": "low",
		        "default": "Minutes",
		        "optional": true
		      },
		      {
		        "name": "seconds",
		        "type": "text",
		        "label": "Passed time seconds",
		        "importance": "low",
		        "default": "Seconds",
		        "optional": true
		      },
		      {
		        "name": "currentTime",
		        "type": "text",
		        "label": "Label for current time",
		        "importance": "low",
		        "default": "Current time:",
		        "optional": true
		      },
		      {
		        "name": "totalTime",
		        "type": "text",
		        "label": "Label for total time",
		        "importance": "low",
		        "default": "Total time:",
		        "optional": true
		      },
		      {
		        "name": "singleInteractionAnnouncement",
		        "type": "text",
		        "label": "Text explaining that a single interaction with a name has come into view",
		        "importance": "low",
		        "default": "Interaction appeared:",
		        "optional": true
		      },
		      {
		        "name": "multipleInteractionsAnnouncement",
		        "type": "text",
		        "label": "Text for explaining that multiple interactions have come into view",
		        "importance": "low",
		        "default": "Multiple interactions appeared.",
		        "optional": true
		      },
		      {
		        "name": "videoPausedAnnouncement",
		        "type": "text",
		        "label": "Video is paused announcement",
		        "importance": "low",
		        "default": "Video is paused",
		        "optional": true
		      },
		      {
		        "name": "content",
		        "type": "text",
		        "label": "Content label",
		        "importance": "low",
		        "default": "Content",
		        "optional": true
		      },
		      {
		        "name": "answered",
		        "type": "text",
		        "label": "Answered message (@answered will be replaced with the number of answered questions)",
		        "importance": "low",
		        "default": "@answered answered",
		        "optional": true
		      },
		      {
		        "name": "endcardTitle",
		        "type": "text",
		        "label": "Submit screen title",
		        "importance": "low",
		        "default": "@answered Question(s) answered",
		        "description": "@answered will be replaced by the number of answered questions.",
		        "optional": true
		      },
		      {
		        "name": "endcardInformation",
		        "type": "text",
		        "label": "Submit screen information",
		        "importance": "low",
		        "default": "You have answered @answered questions, click below to submit your answers.",
		        "description": "@answered will be replaced by the number of answered questions.",
		        "optional": true
		      },
		      {
		        "name": "endcardInformationNoAnswers",
		        "type": "text",
		        "label": "Submit screen information for missing answers",
		        "importance": "low",
		        "default": "You have not answered any questions.",
		        "optional": true
		      },
		      {
		        "name": "endcardInformationMustHaveAnswer",
		        "type": "text",
		        "label": "Submit screen information for answer needed",
		        "importance": "low",
		        "default": "You have to answer at least one question before you can submit your answers.",
		        "optional": true
		      },
		      {
		        "name": "endcardSubmitButton",
		        "type": "text",
		        "label": "Submit screen submit button",
		        "importance": "low",
		        "default": "Submit Answers",
		        "optional": true
		      },
		      {
		        "name": "endcardSubmitMessage",
		        "type": "text",
		        "label": "Submit screen submit message",
		        "importance": "low",
		        "default": "Your answers have been submitted!",
		        "optional": true
		      },
		      {
		        "name": "endcardTableRowAnswered",
		        "type": "text",
		        "label": "Submit screen table row title: Answered questions",
		        "importance": "low",
		        "default": "Answered questions",
		        "optional": true
		      },
		      {
		        "name": "endcardTableRowScore",
		        "type": "text",
		        "label": "Submit screen table row title: Score",
		        "importance": "low",
		        "default": "Score",
		        "optional": true
		      },
		      {
		        "name": "endcardAnsweredScore",
		        "type": "text",
		        "label": "Submit screen answered score",
		        "importance": "low",
		        "default": "answered",
		        "optional": true
		      },
		      {
		        "name": "endCardTableRowSummaryWithScore",
		        "type": "text",
		        "label": "Submit screen row summary including score (for readspeakers)",
		        "importance": "low",
		        "default": "You got @score out of @total points for the @question that appeared after @minutes minutes and @seconds seconds.",
		        "optional": true
		      },
		      {
		        "name": "endCardTableRowSummaryWithoutScore",
		        "type": "text",
		        "label": "Submit screen row summary for no score (for readspeakers)",
		        "importance": "low",
		        "default": "You have answered the @question that appeared after @minutes minutes and @seconds seconds.",
		        "optional": true
		      }
		    ]
		}
  	  ]';
    }

    /**
     * Get H5P Summary Library Semantics
     */
    private function getH5PSummarySemantics()
    {
    	return '[
				  {
				    "name": "intro",
				    "type": "text",
				    "widget": "html",
				    "label": "Introduction text",
				    "importance": "high",
				    "default": "Choose the correct statement.",
				    "description": "Will be displayed above the summary task.",
				    "enterMode": "p",
				    "tags": [
				      "strong",
				      "em",
				      "u",
				      "a",
				      "ul",
				      "ol",
				      "h2",
				      "h3",
				      "hr",
				      "pre",
				      "code"
				    ],
				    "common": false
				  },
				  {
				    "name": "summaries",
				    "importance": "high",
				    "type": "list",
				    "widgets": [
				      {
				        "name": "ListEditor",
				        "label": "Default"
				      },
				      {
				        "name": "SummaryTextualEditor",
				        "label": "Textual"
				      }
				    ],
				    "label": "Summary",
				    "entity": "statements",
				    "max": 100,
				    "min": 1,
				    "field": {
				      "name": "statements",
				      "type": "group",
				      "label": "Set of statements",
				      "importance": "high",
				      "isSubContent": true,
				      "fields": [
				        {
				          "name": "summary",
				          "type": "list",
				          "label": "List of statements for the summary - the first statement is correct.",
				          "entity": "statement",
				          "importance": "medium",
				          "min": 2,
				          "field": {
				            "name": "text",
				            "type": "text",
				            "label": "Statement",
				            "importance": "medium",
				            "widget": "html",
				            "enterMode": "p",
				            "tags": []
				          }
				        },
				        {
				          "name": "tip",
				          "type": "group",
				          "label": "Tip",
				          "importance": "low",
				          "optional": true,
				          "fields": [
				            {
				              "name": "tip",
				              "label": "Tip text",
				              "importance": "low",
				              "type": "text",
				              "widget": "html",
				              "tags": [
				                "p",
				                "br",
				                "strong",
				                "em",
				                "code"
				              ],
				              "optional": true
				            }
				          ]
				        }
				      ]
				    }
				  },
				  {
				    "name": "overallFeedback",
				    "type": "group",
				    "label": "Overall Feedback",
				    "importance": "low",
				    "expanded": true,
				    "fields": [
				      {
				        "name": "overallFeedback",
				        "type": "list",
				        "widgets": [
				          {
				            "name": "RangeList",
				            "label": "Default"
				          }
				        ],
				        "importance": "high",
				        "label": "Define custom feedback for any score range",
				        "description": "Click the \"Add range\" button to add as many ranges as you need. Example: 0-20% Bad score, 21-91% Average Score, 91-100% Great Score!",
				        "entity": "range",
				        "min": 1,
				        "defaultNum": 1,
				        "optional": true,
				        "field": {
				          "name": "overallFeedback",
				          "type": "group",
				          "importance": "low",
				          "fields": [
				            {
				              "name": "from",
				              "type": "number",
				              "label": "Score Range",
				              "min": 0,
				              "max": 100,
				              "default": 0,
				              "unit": "%"
				            },
				            {
				              "name": "to",
				              "type": "number",
				              "min": 0,
				              "max": 100,
				              "default": 100,
				              "unit": "%"
				            },
				            {
				              "name": "feedback",
				              "type": "text",
				              "label": "Feedback for defined score range",
				              "importance": "low",
				              "placeholder": "Fill in the feedback",
				              "optional": true
				            }
				          ]
				        }
				      }
				    ]
				  },
				  {
				    "name": "solvedLabel",
				    "type": "text",
				    "label": "Text displayed before number of statements solved in the summary task.",
				    "importance": "low",
				    "default": "Progress:",
				    "description": "Will be displayed above the statements. Example: Progress: 2/5",
				    "common": true
				  },
				  {
				    "name": "scoreLabel",
				    "type": "text",
				    "label": "Text displayed before number of wrong statements selected in the summary task.",
				    "importance": "low",
				    "default": "Wrong answers:",
				    "description": "Will be displayed above the statements. Example: Wrong answers: 4",
				    "common": true
				  },
				  {
				    "name": "resultLabel",
				    "type": "text",
				    "label": "Summary feedback header",
				    "importance": "low",
				    "default": "Your result",
				    "description": "Will be displayed above the summary feedback.",
				    "common": true
				  },
				  {
				    "name": "labelCorrect",
				    "type": "text",
				    "label": "Readspeaker text for correct answer",
				    "importance": "low",
				    "default": "Correct.",
				    "common": true
				  },
				  {
				    "name": "labelIncorrect",
				    "type": "text",
				    "label": "Readspeaker text for announcing incorrect answer",
				    "importance": "low",
				    "default": "Incorrect! Please try again.",
				    "common": true
				  },
				  {
				    "name": "alternativeIncorrectLabel",
				    "type": "text",
				    "label": "Readspeaker label for incorrect answer",
				    "importance": "low",
				    "default": "Incorrect",
				    "common": true
				  },
				  {
				    "name": "labelCorrectAnswers",
				    "type": "text",
				    "label": "Label list of correct answers",
				    "importance": "low",
				    "default": "Correct answers.",
				    "common": true
				  },
				  {
				    "name": "tipButtonLabel",
				    "type": "text",
				    "label": "Label for the show tip button",
				    "importance": "low",
				    "default": "Show tip",
				    "common": true
				  },
				  {
				    "name": "scoreBarLabel",
				    "type": "text",
				    "label": "Textual representation of the score bar for those using a readspeaker",
				    "description": ":num and :total are special keywords which are programmatically updated",
				    "default": "You got :num out of :total points",
				    "importance": "low",
				    "common": true
				  },
				  {
				    "name": "progressText",
				    "type": "text",
				    "label": "Text used for readspeakers to communicate progress",
				    "description": ":num and :total are special keywords which are programmatically updated",
				    "default": "Progress :num of :total",
				    "importance": "low",
				    "common": true
				  }
				]';
    }

    /**
     * Get H5P Blanks Library Semantics
     */
    private function getH5PBlanksSemantics()
    {
    	return '[
                  {
                    "name": "media",
                    "type": "group",
                    "label": "Media",
                    "importance": "medium",
                    "fields": [
                      {
                        "name": "type",
                        "type": "library",
                        "label": "Type",
                        "options": [
                          "H5P.Image 1.1",
                          "H5P.Video 1.5"
                        ],
                        "optional": true,
                        "description": "Optional media to display above the question."
                      },
                      {
                        "name": "disableImageZooming",
                        "type": "boolean",
                        "label": "Disable image zooming",
                        "importance": "low",
                        "default": false,
                        "optional": true,
                        "widget": "showWhen",
                        "showWhen": {
                          "rules": [
                            {
                              "field": "type",
                              "equals": "H5P.Image 1.1"
                            }
                          ]
                        }
                      }
                    ]
                  },
                  {
                    "label": "Task description",
                    "importance": "high",
                    "name": "text",
                    "type": "text",
                    "widget": "html",
                    "default": "Fill in the missing words",
                    "description": "A guide telling the user how to answer this task.",
                    "enterMode": "p",
                    "tags": [
                      "strong",
                      "em",
                      "u",
                      "a",
                      "ul",
                      "ol",
                      "h2",
                      "h3",
                      "hr",
                      "pre",
                      "code"
                    ]
                  },
                  {
                    "name": "questions",
                    "type": "list",
                    "label": "Text blocks",
                    "importance": "high",
                    "entity": "text block",
                    "min": 1,
                    "max": 31,
                    "field": {
                      "name": "question",
                      "type": "text",
                      "widget": "html",
                      "label": "Line of text",
                      "importance": "high",
                      "placeholder": "Oslo is the capital of *Norway*.",
                      "description": "",
                      "important": {
                        "description": "<ul><li>Blanks are added with an asterisk (*) in front and behind the correct word/phrase.</li><li>Alternative answers are separated with a forward slash (/).</li><li>You may add a textual tip, using a colon (:) in front of the tip.</li></ul>",
                        "example": "H5P content may be edited using a *browser/web-browser:Something you use every day*."
                      },
                      "enterMode": "p",
                      "tags": [
                        "strong",
                        "em",
                        "del",
                        "u",
                        "code"
                      ]
                    }
                  },
                  {
                    "name": "overallFeedback",
                    "type": "group",
                    "label": "Overall Feedback",
                    "importance": "low",
                    "expanded": true,
                    "fields": [
                      {
                        "name": "overallFeedback",
                        "type": "list",
                        "widgets": [
                          {
                            "name": "RangeList",
                            "label": "Default"
                          }
                        ],
                        "importance": "high",
                        "label": "Define custom feedback for any score range",
                        "description": "Click the \"Add range\" button to add as many ranges as you need. Example: 0-20% Bad score, 21-91% Average Score, 91-100% Great Score!",
                        "entity": "range",
                        "min": 1,
                        "defaultNum": 1,
                        "optional": true,
                        "field": {
                          "name": "overallFeedback",
                          "type": "group",
                          "importance": "low",
                          "fields": [
                            {
                              "name": "from",
                              "type": "number",
                              "label": "Score Range",
                              "min": 0,
                              "max": 100,
                              "default": 0,
                              "unit": "%"
                            },
                            {
                              "name": "to",
                              "type": "number",
                              "min": 0,
                              "max": 100,
                              "default": 100,
                              "unit": "%"
                            },
                            {
                              "name": "feedback",
                              "type": "text",
                              "label": "Feedback for defined score range",
                              "importance": "low",
                              "placeholder": "Fill in the feedback",
                              "optional": true
                            }
                          ]
                        }
                      }
                    ]
                  },
                  {
                    "label": "Text for \"Show solutions\" button",
                    "name": "showSolutions",
                    "type": "text",
                    "default": "Show solution",
                    "common": true
                  },
                  {
                    "label": "Text for \"Retry\" button",
                    "importance": "low",
                    "name": "tryAgain",
                    "type": "text",
                    "default": "Retry",
                    "common": true,
                    "optional": true
                  },
                  {
                    "label": "Text for \"Check\" button",
                    "importance": "low",
                    "name": "checkAnswer",
                    "type": "text",
                    "default": "Check",
                    "common": true,
                    "optional": true
                  },
                  {
                    "label": "Text for \"Not filled out\" message",
                    "importance": "low",
                    "name": "notFilledOut",
                    "type": "text",
                    "default": "Please fill in all blanks to view solution",
                    "common": true,
                    "optional": true
                  },
                  {
                    "label": "Text for \":ans is correct\" message",
                    "importance": "low",
                    "name": "answerIsCorrect",
                    "type": "text",
                    "default": ":ans is correct",
                    "common": true,
                    "optional": true
                  },
                  {
                    "label": "Text for \":ans is wrong\" message",
                    "importance": "low",
                    "name": "answerIsWrong",
                    "type": "text",
                    "default": ":ans is wrong",
                    "common": true,
                    "optional": true
                  },
                  {
                    "label": "Text for \"Answered correctly\" message",
                    "importance": "low",
                    "name": "answeredCorrectly",
                    "type": "text",
                    "default": "Answered correctly",
                    "common": true,
                    "optional": true
                  },
                  {
                    "label": "Text for \"Answered incorrectly\" message",
                    "importance": "low",
                    "name": "answeredIncorrectly",
                    "type": "text",
                    "default": "Answered incorrectly",
                    "common": true,
                    "optional": true
                  },
                  {
                    "label": "Assistive technology label for solution",
                    "importance": "low",
                    "name": "solutionLabel",
                    "type": "text",
                    "default": "Correct answer:",
                    "common": true,
                    "optional": true
                  },
                  {
                    "label": "Assistive technology label for input field",
                    "importance": "low",
                    "name": "inputLabel",
                    "type": "text",
                    "description": "Use @num and @total to replace current cloze number and total cloze number",
                    "default": "Blank input @num of @total",
                    "common": true,
                    "optional": true
                  },
                  {
                    "label": "Assistive technology label for saying an input has a tip tied to it",
                    "importance": "low",
                    "name": "inputHasTipLabel",
                    "type": "text",
                    "default": "Tip available",
                    "common": true,
                    "optional": true
                  },
                  {
                    "label": "Tip icon label",
                    "importance": "low",
                    "name": "tipLabel",
                    "type": "text",
                    "default": "Tip",
                    "common": true,
                    "optional": true
                  },
                  {
                    "name": "behaviour",
                    "type": "group",
                    "label": "Behavioural settings.",
                    "importance": "low",
                    "description": "These options will let you control how the task behaves.",
                    "optional": true,
                    "fields": [
                      {
                        "label": "Enable \"Retry\"",
                        "importance": "low",
                        "name": "enableRetry",
                        "type": "boolean",
                        "default": true,
                        "optional": true
                      },
                      {
                        "label": "Enable \"Show solution\" button",
                        "importance": "low",
                        "name": "enableSolutionsButton",
                        "type": "boolean",
                        "default": true,
                        "optional": true
                      },
                      {
                        "name": "enableCheckButton",
                        "type": "boolean",
                        "label": "Enable \"Check\" button",
                        "widget": "none",
                        "importance": "low",
                        "default": true,
                        "optional": true
                      },
                      {
                        "label": "Automatically check answers after input",
                        "importance": "low",
                        "name": "autoCheck",
                        "type": "boolean",
                        "default": false,
                        "optional": true
                      },
                      {
                        "name": "caseSensitive",
                        "importance": "low",
                        "type": "boolean",
                        "default": true,
                        "label": "Case sensitive",
                        "description": "Makes sure the user input has to be exactly the same as the answer."
                      },
                      {
                        "label": "Require all fields to be answered before the solution can be viewed",
                        "importance": "low",
                        "name": "showSolutionsRequiresInput",
                        "type": "boolean",
                        "default": true,
                        "optional": true
                      },
                      {
                        "label": "Put input fields on separate lines",
                        "importance": "low",
                        "name": "separateLines",
                        "type": "boolean",
                        "default": false,
                        "optional": true
                      },
                      {
                        "label": "Show confirmation dialog on \"Check\"",
                        "importance": "low",
                        "name": "confirmCheckDialog",
                        "type": "boolean",
                        "description": "This options is not compatible with the \"Automatically check answers after input\" option",
                        "default": false
                      },
                      {
                        "label": "Show confirmation dialog on \"Retry\"",
                        "importance": "low",
                        "name": "confirmRetryDialog",
                        "type": "boolean",
                        "default": false
                      },
                      {
                        "name": "acceptSpellingErrors",
                        "type": "boolean",
                        "label": "Accept minor spelling errors",
                        "importance": "low",
                        "description": "If activated, an answer will also count as correct with minor spelling errors (3-9 characters: 1 spelling error, more than 9 characters: 2 spelling errors)",
                        "default": false,
                        "optional": true
                      }
                    ]
                  },
                  {
                    "name": "currikisettings",
                    "type": "group",
                    "label": "Curriki settings",
                    "importance": "low",
                    "description": "These options will let you control how the curriki studio behaves.",
                    "optional": true,
                    "fields": [
                      {
                        "label": "Do not Show Submit Button",
                        "importance": "low",
                        "name": "disableSubmitButton",
                        "type": "boolean",
                        "default": false,
                        "optional": true,
                        "description": "This option only applies to a standalone activity. The Submit button is required for grade passback to an LMS."
                      },
                      {
                        "label": "Placeholder",
                        "importance": "low",
                        "name": "placeholder",
                        "type": "boolean",
                        "default": false,
                        "optional": true,
                        "description": "This option is a place holder. will be used in future"
                      },
                      {
                        "label": "Curriki Localization",
                        "description": "Here you can edit settings or translate texts used in curriki settings",
                        "importance": "low",
                        "name": "currikil10n",
                        "type": "group",
                        "fields": [
                          {
                            "label": "Text for \"Submit\" button",
                            "name": "submitAnswer",
                            "type": "text",
                            "default": "Submit",
                            "optional": true
                          },
                          {
                            "label": "Text for \"Placeholder\" button",
                            "importance": "low",
                            "name": "placeholderButton",
                            "type": "text",
                            "default": "Placeholder",
                            "optional": true
                          }
                        ]
                      }
                    ]
                  },
                  {
                    "label": "Check confirmation dialog",
                    "importance": "low",
                    "name": "confirmCheck",
                    "type": "group",
                    "common": true,
                    "fields": [
                      {
                        "label": "Header text",
                        "importance": "low",
                        "name": "header",
                        "type": "text",
                        "default": "Finish ?"
                      },
                      {
                        "label": "Body text",
                        "importance": "low",
                        "name": "body",
                        "type": "text",
                        "default": "Are you sure you wish to finish ?",
                        "widget": "html",
                        "enterMode": "p",
                        "tags": [
                          "strong",
                          "em",
                          "del",
                          "u",
                          "code"
                        ]
                      },
                      {
                        "label": "Cancel button label",
                        "importance": "low",
                        "name": "cancelLabel",
                        "type": "text",
                        "default": "Cancel"
                      },
                      {
                        "label": "Confirm button label",
                        "importance": "low",
                        "name": "confirmLabel",
                        "type": "text",
                        "default": "Finish"
                      }
                    ]
                  },
                  {
                    "label": "Retry confirmation dialog",
                    "importance": "low",
                    "name": "confirmRetry",
                    "type": "group",
                    "common": true,
                    "fields": [
                      {
                        "label": "Header text",
                        "importance": "low",
                        "name": "header",
                        "type": "text",
                        "default": "Retry ?"
                      },
                      {
                        "label": "Body text",
                        "importance": "low",
                        "name": "body",
                        "type": "text",
                        "default": "Are you sure you wish to retry ?",
                        "widget": "html",
                        "enterMode": "p",
                        "tags": [
                          "strong",
                          "em",
                          "del",
                          "u",
                          "code"
                        ]
                      },
                      {
                        "label": "Cancel button label",
                        "importance": "low",
                        "name": "cancelLabel",
                        "type": "text",
                        "default": "Cancel"
                      },
                      {
                        "label": "Confirm button label",
                        "importance": "low",
                        "name": "confirmLabel",
                        "type": "text",
                        "default": "Confirm"
                      }
                    ]
                  },
                  {
                    "name": "scoreBarLabel",
                    "type": "text",
                    "label": "Textual representation of the score bar for those using a readspeaker",
                    "default": "You got :num out of :total points",
                    "importance": "low",
                    "common": true
                  },
                  {
                    "name": "a11yCheck",
                    "type": "text",
                    "label": "Assistive technology description for \"Check\" button",
                    "default": "Check the answers. The responses will be marked as correct, incorrect, or unanswered.",
                    "importance": "low",
                    "common": true
                  },
                  {
                    "name": "a11yShowSolution",
                    "type": "text",
                    "label": "Assistive technology description for \"Show Solution\" button",
                    "default": "Show the solution. The task will be marked with its correct solution.",
                    "importance": "low",
                    "common": true
                  },
                  {
                    "name": "a11yRetry",
                    "type": "text",
                    "label": "Assistive technology description for \"Retry\" button",
                    "default": "Retry the task. Reset all responses and start the task over again.",
                    "importance": "low",
                    "common": true
                  },
                  {
                    "name": "a11yCheckingModeHeader",
                    "type": "text",
                    "label": "Assistive technology description for starting task",
                    "default": "Checking mode",
                    "importance": "low",
                    "common": true
                  }
                ]';
    }

    /**
     * Get H5P Nill Library Semantics
     */
    private function getH5PNillSemantics()
    {
    	return '[
				  {
				    "name": "nil",
				    "type": "boolean",
				    "widget": "none",
				    "optional": true
				  }
				]';
    }

    /**
     * Get H5P Drag Question Library Semantics
     */
    private function getH5PDragQuestionSemantics()
    {
    	return '[
            {
              "name": "scoreShow",
              "type": "text",
              "label": "Check answer button",
              "importance": "low",
              "default": "Check",
              "common": true
            },
            {
              "name": "submitAnswers",
              "type": "text",
              "label": "submit answer button",
              "importance": "low",
              "default": "Submit Answers",
              "common": true
            },
            {
              "name": "tryAgain",
              "type": "text",
              "label": "Retry button",
              "importance": "low",
              "default": "Retry",
              "common": true,
              "optional": true
            },
            {
              "label": "Score explanation text",
              "importance": "low",
              "name": "scoreExplanation",
              "type": "text",
              "default": "Correct answers give +1 point. Incorrect answers give -1 point. The lowest possible score is 0.",
              "common": true,
              "optional": true
            },
            {
              "name": "question",
              "importance": "high",
              "type": "group",
              "widget": "wizard",
              "fields": [
                {
                  "name": "settings",
                  "type": "group",
                  "label": "Settings",
                  "importance": "high",
                  "fields": [
                    {
                      "name": "background",
                      "type": "image",
                      "label": "Background image",
                      "importance": "low",
                      "optional": true,
                      "description": "Optional. Select an image to use as background for your drag and drop task."
                    },
                    {
                      "name": "size",
                      "type": "group",
                      "widget": "dimensions",
                      "label": "Task size",
                      "importance": "low",
                      "description": "Specify how large (in px) the play area should be.",
                      "default": {
                        "width": 620,
                        "height": 310,
                        "field": "background"
                      },
                      "fields": [
                        {
                          "name": "width",
                          "type": "number"
                        },
                        {
                          "name": "height",
                          "type": "number"
                        }
                      ]
                    }
                  ]
                },
                {
                  "name": "task",
                  "type": "group",
                  "widget": "dragQuestion",
                  "label": "Task",
                  "importance": "high",
                  "description": "Start by placing your drop zones.<br/>Next, place your droppable elements and check off the appropriate drop zones.<br/>Last, edit your drop zone again and check off the correct answers.",
                  "fields": [
                    {
                      "name": "elements",
                      "type": "list",
                      "label": "Elements",
                      "importance": "high",
                      "entity": "element",
                      "field": {
                        "type": "group",
                        "label": "Element",
                        "importance": "high",
                        "fields": [
                          {
                            "name": "type",
                            "type": "library",
                            "description": "Choose the type of content you would like to add.",
                            "importance": "medium",
                            "options": [
                              "H5P.AdvancedText 1.1",
                              "H5P.Image 1.1"
                            ]
                          },
                          {
                            "name": "x",
                            "type": "number",
                            "widget": "none"
                          },
                          {
                            "name": "y",
                            "type": "number",
                            "widget": "none"
                          },
                          {
                            "name": "height",
                            "type": "number",
                            "widget": "none"
                          },
                          {
                            "name": "width",
                            "type": "number",
                            "widget": "none"
                          },
                          {
                            "name": "dropZones",
                            "type": "select",
                            "widget": "dynamicCheckboxes",
                            "label": "Select drop zones",
                            "importance": "high",
                            "multiple": true
                          },
                          {
                            "name": "backgroundOpacity",
                            "type": "number",
                            "label": "Background Opacity",
                            "importance": "low",
                            "min": 0,
                            "max": 100,
                            "step": 5,
                            "default": 100,
                            "optional": true
                          },
                          {
                            "name": "multiple",
                            "type": "boolean",
                            "label": "Infinite number of element instances",
                            "importance": "low",
                            "description": "Clones this element so that it can be dragged to multiple drop zones.",
                            "default": false
                          }
                        ]
                      }
                    },
                    {
                      "name": "dropZones",
                      "type": "list",
                      "label": "Drop Zones",
                      "importance": "high",
                      "entity": "Drop Zone",
                      "field": {
                        "type": "group",
                        "label": "Drop Zone",
                        "importance": "high",
                        "fields": [
                          {
                            "name": "label",
                            "type": "text",
                            "widget": "html",
                            "label": "Label",
                            "importance": "medium",
                            "enterMode": "div",
                            "tags": [
                              "strong",
                              "em",
                              "del",
                              "code"
                            ]
                          },
                          {
                            "name": "showLabel",
                            "type": "boolean",
                            "label": "Show label",
                            "importance": "low"
                          },
                          {
                            "name": "x",
                            "type": "number",
                            "widget": "none"
                          },
                          {
                            "name": "y",
                            "type": "number",
                            "widget": "none"
                          },
                          {
                            "name": "height",
                            "type": "number",
                            "widget": "none"
                          },
                          {
                            "name": "width",
                            "type": "number",
                            "widget": "none"
                          },
                          {
                            "name": "correctElements",
                            "type": "select",
                            "widget": "dynamicCheckboxes",
                            "label": "Select correct elements",
                            "importance": "low",
                            "multiple": true
                          },
                          {
                            "name": "backgroundOpacity",
                            "type": "number",
                            "label": "Background Opacity",
                            "importance": "low",
                            "min": 0,
                            "max": 100,
                            "step": 5,
                            "default": 100,
                            "optional": true
                          },
                          {
                            "name": "tipsAndFeedback",
                            "type": "group",
                            "label": "Tips and feedback",
                            "importance": "low",
                            "optional": true,
                            "fields": [
                              {
                                "name": "tip",
                                "label": "Tip text",
                                "importance": "low",
                                "type": "text",
                                "widget": "html",
                                "tags": [
                                  "p",
                                  "br",
                                  "strong",
                                  "em",
                                  "code"
                                ],
                                "optional": true
                              },
                              {
                                "name": "feedbackOnCorrect",
                                "type": "text",
                                "label": "Message displayed on correct match",
                                "importance": "low",
                                "description": "Message will appear below the task on \"check\" if correct droppable is matched.",
                                "optional": true
                              },
                              {
                                "name": "feedbackOnIncorrect",
                                "type": "text",
                                "label": "Message displayed on incorrect match",
                                "importance": "low",
                                "description": "Message will appear below the task on \"check\" if the match is incorrect.",
                                "optional": true
                              }
                            ]
                          },
                          {
                            "name": "single",
                            "type": "boolean",
                            "label": "This drop zone can only contain one element",
                            "description": "Make sure there is only one correct answer for this dropzone",
                            "importance": "low",
                            "default": false
                          },
                          {
                            "name": "autoAlign",
                            "type": "boolean",
                            "label": "Enable Auto-Align",
                            "importance": "low",
                            "description": "Will auto-align all draggables dropped in this zone."
                          }
                        ]
                      }
                    }
                  ]
                }
              ]
            },
            {
              "name": "overallFeedback",
              "type": "group",
              "label": "Overall Feedback",
              "importance": "low",
              "expanded": true,
              "fields": [
                {
                  "name": "overallFeedback",
                  "type": "list",
                  "widgets": [
                    {
                      "name": "RangeList",
                      "label": "Default"
                    }
                  ],
                  "importance": "high",
                  "label": "Define custom feedback for any score range",
                  "description": "Click the \"Add range\" button to add as many ranges as you need. Example: 0-20% Bad score, 21-91% Average Score, 91-100% Great Score!",
                  "entity": "range",
                  "min": 1,
                  "defaultNum": 1,
                  "optional": true,
                  "field": {
                    "name": "overallFeedback",
                    "type": "group",
                    "importance": "low",
                    "fields": [
                      {
                        "name": "from",
                        "type": "number",
                        "label": "Score Range",
                        "min": 0,
                        "max": 100,
                        "default": 0,
                        "unit": "%"
                      },
                      {
                        "name": "to",
                        "type": "number",
                        "min": 0,
                        "max": 100,
                        "default": 100,
                        "unit": "%"
                      },
                      {
                        "name": "feedback",
                        "type": "text",
                        "label": "Feedback for defined score range",
                        "importance": "low",
                        "placeholder": "Fill in the feedback",
                        "optional": true
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
              "description": "These options will let you control how the task behaves.",
              "optional": true,
              "fields": [
                {
                  "name": "enableRetry",
                  "type": "boolean",
                  "label": "Enable \"Retry\"",
                  "importance": "low",
                  "default": true
                },
                {
                  "name": "showSubmitAnswersButton",
                  "type": "boolean",
                  "label": "Enable \"Submit Answers\"",
                  "importance": "low",
                  "default": true
                },
                {
                  "name": "enableCheckButton",
                  "type": "boolean",
                  "label": "Enable \"Check\" button",
                  "widget": "none",
                  "importance": "low",
                  "default": true,
                  "optional": true
                },
                {
                  "label": "Require user input before the solution can be viewed",
                  "importance": "low",
                  "name": "showSolutionsRequiresInput",
                  "type": "boolean",
                  "default": true
                },
                {
                  "name": "singlePoint",
                  "type": "boolean",
                  "label": "Give one point for the whole task",
                  "importance": "low",
                  "description": "Disable to give one point for each draggable that is placed correctly.",
                  "default": false
                },
                {
                  "label": "Apply penalties",
                  "name": "applyPenalties",
                  "type": "boolean",
                  "description": "Apply penalties for elements dropped in the wrong drop zones. This must be enabled when the same element(s) are able to be dropped into multiple drop zones, or if there is only one drop-zone. If this is not enabled, learners may match all items to all drop-zones and always receive a full score.",
                  "default": true
                },
                {
                  "name": "enableScoreExplanation",
                  "type": "boolean",
                  "label": "Enable score explanation",
                  "description": "Display a score explanation to user when checking their answers (if the Apply penalties option has been selected).",
                  "importance": "low",
                  "default": true,
                  "optional": true,
                  "widget": "showWhen",
                  "showWhen": {
                    "rules": [
                      {
                        "field": "singlePoint",
                        "equals": false
                      }
                    ]
                  }
                },
                {
                  "name": "backgroundOpacity",
                  "type": "text",
                  "label": "Background opacity for draggables",
                  "importance": "low",
                  "description": "If this field is set, it will override opacity set on all draggable elements. This should be a number between 0 and 100, where 0 means full transparency and 100 means no transparency",
                  "optional": true
                },
                {
                  "name": "dropZoneHighlighting",
                  "type": "select",
                  "label": "Drop Zone Highlighting",
                  "importance": "low",
                  "description": "Choose when to highlight drop zones.",
                  "default": "dragging",
                  "options": [
                    {
                      "value": "dragging",
                      "label": "When dragging"
                    },
                    {
                      "value": "always",
                      "label": "Always"
                    },
                    {
                      "value": "never",
                      "label": "Never"
                    }
                  ]
                },
                {
                  "name": "autoAlignSpacing",
                  "type": "number",
                  "label": "Spacing for Auto-Align (in px)",
                  "importance": "low",
                  "min": 0,
                  "default": 2,
                  "optional": true
                },
                {
                  "name": "enableFullScreen",
                  "label": "Enable FullScreen",
                  "importance": "low",
                  "type": "boolean",
                  "description": "Check this option to enable the full screen button.",
                  "default": false
                },
                {
                  "name": "showScorePoints",
                  "type": "boolean",
                  "label": "Show score points",
                  "description": "Show points earned for each answer. Not available when the Give one point for the whole task option is enabled.",
                  "importance": "low",
                  "default": true
                },
                {
                  "name": "showTitle",
                  "type": "boolean",
                  "label": "Show Title",
                  "importance": "low",
                  "description": "Uncheck this option if you do not want this title to be displayed. The title will only be displayed in summaries, statistics etc.",
                  "default": true
                }
              ]
            },
            {
              "name": "localize",
              "type": "group",
              "label": "Localize",
              "common": true,
              "fields": [
                {
                  "name": "fullscreen",
                  "type": "text",
                  "label": "Fullscreen label",
                  "default": "Fullscreen"
                },
                {
                  "name": "exitFullscreen",
                  "type": "text",
                  "label": "Exit fullscreen label",
                  "default": "Exit fullscreen"
                }
              ]
            },
            {
              "name": "grabbablePrefix",
              "type": "text",
              "label": "Grabbable prefix",
              "importance": "low",
              "default": "Grabbable {num} of {total}.",
              "common": true
            },
            {
              "name": "grabbableSuffix",
              "type": "text",
              "label": "Grabbable suffix",
              "importance": "low",
              "default": "Placed in dropzone {num}.",
              "common": true
            },
            {
              "name": "dropzonePrefix",
              "type": "text",
              "label": "Dropzone prefix",
              "importance": "low",
              "default": "Dropzone {num} of {total}.",
              "common": true
            },
            {
              "name": "noDropzone",
              "type": "text",
              "label": "No dropzone selection label",
              "importance": "low",
              "default": "No dropzone.",
              "common": true
            },
            {
              "name": "tipLabel",
              "type": "text",
              "label": "Label for show tip button",
              "importance": "low",
              "default": "Show tip.",
              "common": true
            },
            {
              "name": "tipAvailable",
              "type": "text",
              "label": "Label for tip available",
              "importance": "low",
              "default": "Tip available",
              "common": true
            },
            {
              "name": "correctAnswer",
              "type": "text",
              "label": "Label for correct answer",
              "importance": "low",
              "default": "Correct answer",
              "common": true
            },
            {
              "name": "wrongAnswer",
              "type": "text",
              "label": "Label for incorrect answer",
              "importance": "low",
              "default": "Wrong answer",
              "common": true
            },
            {
              "name": "feedbackHeader",
              "type": "text",
              "label": "Header for panel containing feedback for correct/incorrect answers",
              "importance": "low",
              "default": "Feedback",
              "common": true
            },
            {
              "name": "scoreBarLabel",
              "type": "text",
              "label": "Textual representation of the score bar for those using a readspeaker",
              "default": "You got :num out of :total points",
              "importance": "low",
              "common": true
            },
            {
              "name": "scoreExplanationButtonLabel",
              "type": "text",
              "label": "Textual representation of the score explanation button",
              "default": "Show score explanation",
              "importance": "low",
              "common": true
            },
            {
              "name": "a11yCheck",
              "type": "text",
              "label": "Assistive technology label for \"Check\" button",
              "default": "Check the answers. The responses will be marked as correct, incorrect, or unanswered.",
              "importance": "low",
              "common": true
            },
            {
              "name": "a11yRetry",
              "type": "text",
              "label": "Assistive technology label for \"Retry\" button",
              "default": "Retry the task. Reset all responses and start the task over again.",
              "importance": "low",
              "common": true
            }
          ]';
    }

    /**
     * Get H5P Mark TheWords Library Semantics
     */
    private function getH5PMarkTheWordsSemantics()
    {
    	return '[
                  {
                    "label": "Task description",
                    "importance": "high",
                    "name": "taskDescription",
                    "type": "text",
                    "widget": "html",
                    "description": "Describe how the user should solve the task.",
                    "placeholder": "Click on all the verbs in the text that follows.",
                    "enterMode": "p",
                    "tags": [
                      "strong",
                      "em",
                      "u",
                      "a",
                      "ul",
                      "ol",
                      "h2",
                      "h3",
                      "hr",
                      "pre",
                      "code"
                    ]
                  },
                  {
                    "label": "Textfield",
                    "importance": "high",
                    "name": "textField",
                    "type": "text",
                    "widget": "html",
                    "tags": [
                      "p",
                      "br",
                      "strong",
                      "em",
                      "code"
                    ],
                    "placeholder": "This is an answer: *answer*.",
                    "description": "",
                    "important": {
                      "description": "<ul><li>Correct words are marked with asterisks (*) before and after the word.</li><li>Asterisks can be added within marked words by adding another asterisk, *correctword*** =&gt; correctword*.</li><li>Only words may be marked as correct. Not phrases.</li></ul>",
                      "example": "The correct words are marked like this: *correctword*, an asterisk is written like this: *correctword***."
                    }
                  },
                  {
                    "name": "overallFeedback",
                    "type": "group",
                    "label": "Overall Feedback",
                    "importance": "low",
                    "expanded": true,
                    "fields": [
                      {
                        "name": "overallFeedback",
                        "type": "list",
                        "widgets": [
                          {
                            "name": "RangeList",
                            "label": "Default"
                          }
                        ],
                        "importance": "high",
                        "label": "Define custom feedback for any score range",
                        "description": "Click the \"Add range\" button to add as many ranges as you need. Example: 0-20% Bad score, 21-91% Average Score, 91-100% Great Score!",
                        "entity": "range",
                        "min": 1,
                        "defaultNum": 1,
                        "optional": true,
                        "field": {
                          "name": "overallFeedback",
                          "type": "group",
                          "importance": "low",
                          "fields": [
                            {
                              "name": "from",
                              "type": "number",
                              "label": "Score Range",
                              "min": 0,
                              "max": 100,
                              "default": 0,
                              "unit": "%"
                            },
                            {
                              "name": "to",
                              "type": "number",
                              "min": 0,
                              "max": 100,
                              "default": 100,
                              "unit": "%"
                            },
                            {
                              "name": "feedback",
                              "type": "text",
                              "label": "Feedback for defined score range",
                              "importance": "low",
                              "placeholder": "Fill in the feedback",
                              "optional": true
                            }
                          ]
                        }
                      }
                    ]
                  },
                  {
                    "label": "Text for \"Check\" button",
                    "importance": "low",
                    "name": "checkAnswerButton",
                    "type": "text",
                    "default": "Check",
                    "common": true
                  },
                  {
                    "label": "Text for \"Retry\" button",
                    "importance": "low",
                    "name": "tryAgainButton",
                    "type": "text",
                    "default": "Retry",
                    "common": true
                  },
                  {
                    "label": "Text for \"Show solution\" button",
                    "importance": "low",
                    "name": "showSolutionButton",
                    "type": "text",
                    "default": "Show solution",
                    "common": true
                  },
                  {
                    "name": "behaviour",
                    "importance": "low",
                    "type": "group",
                    "label": "Behavioural settings.",
                    "description": "These options will let you control how the task behaves.",
                    "optional": true,
                    "fields": [
                      {
                        "name": "enableRetry",
                        "type": "boolean",
                        "label": "Enable \"Retry\"",
                        "importance": "low",
                        "default": true
                      },
                      {
                        "name": "enableSolutionsButton",
                        "type": "boolean",
                        "label": "Enable \"Show solution\" button",
                        "importance": "low",
                        "default": true
                      },
                      {
                        "name": "enableCheckButton",
                        "type": "boolean",
                        "label": "Enable \"Check\" button",
                        "widget": "none",
                        "importance": "low",
                        "default": true,
                        "optional": true
                      },
                      {
                        "name": "showScorePoints",
                        "type": "boolean",
                        "label": "Show score points",
                        "description": "Show points earned for each answer.",
                        "importance": "low",
                        "default": true
                      }
                    ]
                  },
                  {
                    "name": "currikisettings",
                    "type": "group",
                    "label": "Curriki settings",
                    "importance": "low",
                    "description": "These options will let you control how the curriki studio behaves.",
                    "optional": true,
                    "fields": [
                      {
                        "label": "Do not Show Submit Button",
                        "importance": "low",
                        "name": "disableSubmitButton",
                        "type": "boolean",
                        "default": false,
                        "optional": true,
                        "description": "This option only applies to a standalone activity. The Submit button is required for grade passback to an LMS."
                      },
                      {
                        "label": "Placeholder",
                        "importance": "low",
                        "name": "placeholder",
                        "type": "boolean",
                        "default": false,
                        "optional": true,
                        "description": "This option is a place holder. will be used in future"
                      },
                      {
                        "label": "Curriki Localization",
                        "description": "Here you can edit settings or translate texts used in curriki settings",
                        "importance": "low",
                        "name": "currikil10n",
                        "type": "group",
                        "fields": [
                          {
                            "label": "Text for \"Submit\" button",
                            "name": "submitAnswer",
                            "type": "text",
                            "default": "Submit",
                            "optional": true
                          },
                          {
                            "label": "Text for \"Placeholder\" button",
                            "importance": "low",
                            "name": "placeholderButton",
                            "type": "text",
                            "default": "Placeholder",
                            "optional": true
                          }
                        ]
                      }
                    ]
                  },
                  {
                    "label": "Correct answer text",
                    "importance": "low",
                    "name": "correctAnswer",
                    "type": "text",
                    "default": "Correct!",
                    "description": "Text used to indicate that an answer is correct",
                    "common": true
                  },
                  {
                    "label": "Incorrect answer text",
                    "importance": "low",
                    "name": "incorrectAnswer",
                    "type": "text",
                    "default": "Incorrect!",
                    "description": "Text used to indicate that an answer is incorrect",
                    "common": true
                  },
                  {
                    "label": "Missed answer text",
                    "importance": "low",
                    "name": "missedAnswer",
                    "type": "text",
                    "default": "Answer not found!",
                    "description": "Text used to indicate that an answer is missing",
                    "common": true
                  },
                  {
                    "label": "Description for Display Solution",
                    "importance": "low",
                    "name": "displaySolutionDescription",
                    "type": "text",
                    "default": "Task is updated to contain the solution.",
                    "description": "This text tells the user that the tasks has been updated with the solution.",
                    "common": true
                  },
                  {
                    "name": "scoreBarLabel",
                    "type": "text",
                    "label": "Textual representation of the score bar for those using a readspeaker",
                    "default": "You got :num out of :total points",
                    "importance": "low",
                    "common": true
                  },
                  {
                    "name": "a11yFullTextLabel",
                    "type": "text",
                    "label": "Label for the full readable text for assistive technologies",
                    "default": "Full readable text",
                    "importance": "low",
                    "common": true
                  },
                  {
                    "name": "a11yClickableTextLabel",
                    "type": "text",
                    "label": "Label for the text where words can be marked for assistive technologies",
                    "default": "Full text where words can be marked",
                    "importance": "low",
                    "common": true
                  },
                  {
                    "name": "a11ySolutionModeHeader",
                    "type": "text",
                    "label": "Solution mode header for assistive technologies",
                    "default": "Solution mode",
                    "importance": "low",
                    "common": true
                  },
                  {
                    "name": "a11yCheckingHeader",
                    "type": "text",
                    "label": "Checking mode header for assistive technologies",
                    "default": "Checking mode",
                    "importance": "low",
                    "common": true
                  },
                  {
                    "name": "a11yCheck",
                    "type": "text",
                    "label": "Assistive technology description for \"Check\" button",
                    "default": "Check the answers. The responses will be marked as correct, incorrect, or unanswered.",
                    "importance": "low",
                    "common": true
                  },
                  {
                    "name": "a11yShowSolution",
                    "type": "text",
                    "label": "Assistive technology description for \"Show Solution\" button",
                    "default": "Show the solution. The task will be marked with its correct solution.",
                    "importance": "low",
                    "common": true
                  },
                  {
                    "name": "a11yRetry",
                    "type": "text",
                    "label": "Assistive technology description for \"Retry\" button",
                    "default": "Retry the task. Reset all responses and start the task over again.",
                    "importance": "low",
                    "common": true
                  }
                ]';
    }

    /**
     * Get H5P Drag Text Library Semantics
     */
    private function getH5PDragTextSemantics()
    {
    	return '[
				  {
				    "label": "Task description",
				    "importance": "high",
				    "name": "taskDescription",
				    "type": "text",
				    "widget": "html",
				    "description": "Describe how the user should solve the task.",
				    "default": "Drag the words into the correct boxes",
				    "enterMode": "p",
				    "tags": [
				      "strong",
				      "em",
				      "u",
				      "a",
				      "ul",
				      "ol",
				      "h2",
				      "h3",
				      "hr",
				      "pre",
				      "code"
				    ]
				  },
				  {
				    "label": "Text",
				    "importance": "high",
				    "name": "textField",
				    "type": "text",
				    "widget": "textarea",
				    "placeholder": "*Oslo* is the capital of Norway, *Stockholm* is the capital of Sweden and *Copenhagen* is the capital of Denmark. All cities are located in the *Scandinavian:Northern Part of Europe* peninsula.",
				    "description": "",
				    "important": {
				      "description": "<ul><li>Droppable words are added with an asterisk (*) in front and behind the correct word/phrase.</li><li>You may add a textual tip, using a colon (:) in front of the tip.</li><li>For every empty spot there is only one correct word.</li><li>You may add feedback to be displayed when a task is completed. Use + for correct and - for incorrect feedback.</li></ul>",
				      "example": "H5P content may be edited using a *browser:What type of program is Chrome?*. </br> H5P content is *interactive +Correct! -Incorrect, try again!*"
				    }
				  },
				  {
				    "name": "overallFeedback",
				    "type": "group",
				    "label": "Overall Feedback",
				    "importance": "low",
				    "expanded": true,
				    "fields": [
				      {
				        "name": "overallFeedback",
				        "type": "list",
				        "widgets": [
				          {
				            "name": "RangeList",
				            "label": "Default"
				          }
				        ],
				        "importance": "high",
				        "label": "Define custom feedback for any score range",
				        "description": "Click the \"Add range\" button to add as many ranges as you need. Example: 0-20% Bad score, 21-91% Average Score, 91-100% Great Score!",
				        "entity": "range",
				        "min": 1,
				        "defaultNum": 1,
				        "optional": true,
				        "field": {
				          "name": "overallFeedback",
				          "type": "group",
				          "importance": "low",
				          "fields": [
				            {
				              "name": "from",
				              "type": "number",
				              "label": "Score Range",
				              "min": 0,
				              "max": 100,
				              "default": 0,
				              "unit": "%"
				            },
				            {
				              "name": "to",
				              "type": "number",
				              "min": 0,
				              "max": 100,
				              "default": 100,
				              "unit": "%"
				            },
				            {
				              "name": "feedback",
				              "type": "text",
				              "label": "Feedback for defined score range",
				              "importance": "low",
				              "placeholder": "Fill in the feedback",
				              "optional": true
				            }
				          ]
				        }
				      }
				    ]
				  },
				  {
				    "label": "Text for \"Check\" button",
				    "importance": "low",
				    "name": "checkAnswer",
				    "type": "text",
				    "default": "Check",
				    "common": true
				  },
				  {
				    "label": "Text for \"Retry\" button",
				    "importance": "low",
				    "name": "tryAgain",
				    "type": "text",
				    "default": "Retry",
				    "common": true
				  },
				  {
				    "label": "Text for \"Show Solution\" button",
				    "importance": "low",
				    "name": "showSolution",
				    "type": "text",
				    "default": "Show solution",
				    "common": true
				  },
				  {
				    "label": "Numbered Drop zone label",
				    "importance": "low",
				    "name": "dropZoneIndex",
				    "type": "text",
				    "default": "Drop Zone @index.",
				    "description": "Label used for accessibility, where the Read speaker will read the index of a drop zone. Variable available: @index",
				    "common": true
				  },
				  {
				    "label": "Empty Drop Zone label",
				    "importance": "low",
				    "name": "empty",
				    "type": "text",
				    "default": "Drop Zone @index is empty.",
				    "description": "Label used for accessibility, where the Read speaker will read that the drop zone is empty",
				    "common": true
				  },
				  {
				    "label": "Contains Drop Zone label",
				    "importance": "low",
				    "name": "contains",
				    "type": "text",
				    "default": "Drop Zone @index contains draggable @draggable.",
				    "description": "Label used for accessibility, where the Read speaker will read that the drop zone contains a draggable",
				    "common": true
				  },
				  {
				    "label": "Draggable elements label",
				    "importance": "low",
				    "name": "ariaDraggableIndex",
				    "type": "text",
				    "default": "@index of @count draggables.",
				    "description": "Label used for accessibility, where the Read speaker reads that this is a draggable element. Variable available: @index, @count",
				    "common": true
				  },
				  {
				    "label": "Label for show tip button",
				    "importance": "low",
				    "name": "tipLabel",
				    "type": "text",
				    "default": "Show tip",
				    "description": "Label used for accessibility, where the Read speaker reads it before the tip is read out",
				    "common": true
				  },
				  {
				    "name": "correctText",
				    "type": "text",
				    "label": "Readspeaker text for correct answer",
				    "importance": "low",
				    "default": "Correct!",
				    "common": true
				  },
				  {
				    "name": "incorrectText",
				    "type": "text",
				    "label": "Readspeaker text for incorrect answer",
				    "importance": "low",
				    "default": "Incorrect!",
				    "common": true
				  },
				  {
				    "name": "resetDropTitle",
				    "type": "text",
				    "label": "Confirmation dialog title that user wants to reset a droppable",
				    "importance": "low",
				    "default": "Reset drop",
				    "common": true
				  },
				  {
				    "name": "resetDropDescription",
				    "type": "text",
				    "label": "Confirmation dialog description that user wants to reset a droppable",
				    "importance": "low",
				    "default": "Are you sure you want to reset this drop zone?",
				    "common": true
				  },
				  {
				    "name": "grabbed",
				    "type": "text",
				    "label": "Label used for accessibility, where the read speaker indicates that dragging is initiated",
				    "importance": "low",
				    "default": "Draggable is grabbed.",
				    "common": true
				  },
				  {
				    "name": "cancelledDragging",
				    "type": "text",
				    "label": "Label used for accessibility, where the read speaker indicates that dragging is canceled",
				    "importance": "low",
				    "default": "Cancelled dragging.",
				    "common": true
				  },
				  {
				    "name": "correctAnswer",
				    "type": "text",
				    "label": "Label used for accessibility, where the read speaker indicates that a text is the correct answer",
				    "importance": "low",
				    "default": "Correct answer:",
				    "common": true
				  },
				  {
				    "name": "feedbackHeader",
				    "type": "text",
				    "label": "Header for panel containing feedback for correct/incorrect answers",
				    "importance": "low",
				    "default": "Feedback",
				    "common": true
				  },
				  {
				    "name": "behaviour",
				    "type": "group",
				    "label": "Behavioural settings.",
				    "importance": "low",
				    "description": "These options will let you control how the task behaves.",
				    "optional": true,
				    "fields": [
				      {
				        "label": "Enable \"Retry\"",
				        "importance": "low",
				        "name": "enableRetry",
				        "type": "boolean",
				        "default": true
				      },
				      {
				        "label": "Enable \"Show Solution\" button",
				        "importance": "low",
				        "name": "enableSolutionsButton",
				        "type": "boolean",
				        "default": true
				      },
				      {
				        "name": "enableCheckButton",
				        "type": "boolean",
				        "label": "Enable \"Check\" button",
				        "widget": "none",
				        "importance": "low",
				        "default": true,
				        "optional": true
				      },
				      {
				        "label": "Instant feedback",
				        "importance": "low",
				        "name": "instantFeedback",
				        "type": "boolean",
				        "default": false,
				        "optional": true
				      }
				    ]
				  },
				  {
				    "name": "scoreBarLabel",
				    "type": "text",
				    "label": "Textual representation of the score bar for those using a readspeaker",
				    "default": "You got :num out of :total points",
				    "importance": "low",
				    "common": true
				  },
				  {
				    "name": "a11yCheck",
				    "type": "text",
				    "label": "Assistive technology label for \"Check\" button",
				    "default": "Check the answers. The responses will be marked as correct, incorrect, or unanswered.",
				    "importance": "low",
				    "common": true
				  },
				  {
				    "name": "a11yShowSolution",
				    "type": "text",
				    "label": "Assistive technology label for \"Show Solution\" button",
				    "default": "Show the solution. The task will be marked with its correct solution.",
				    "importance": "low",
				    "common": true
				  },
				  {
				    "name": "a11yRetry",
				    "type": "text",
				    "label": "Assistive technology label for \"Retry\" button",
				    "default": "Retry the task. Reset all responses and start the task over again.",
				    "importance": "low",
				    "common": true
				  }
				]';
    }

    /**
     * Get H5P Drag Text Library Semantics
     */
    private function getH5PGoToQuestionSemantics()
    {
    	return '[
				  {
				    "name": "text",
				    "type": "text",
				    "label": "Question Text",
				    "importance": "high",
				    "description": "The question that the user will make a choice based upon."
				  },
				  {
				    "name": "choices",
				    "type": "list",
				    "label": "Choices",
				    "importance": "high",
				    "entity": "choice",
				    "min": 2,
				    "field": {
				      "name": "choice",
				      "type": "group",
				      "fields": [
				        {
				          "name": "text",
				          "type": "text",
				          "label": "Choice Text",
				          "importance": "high",
				          "description": "The label that will displayed on the choice button."
				        },
				        {
				          "name": "goTo",
				          "type": "number",
				          "widget": "timecode",
				          "label": "Go To",
				          "importance": "high",
				          "description": "The target time the user will be taken to upon pressing the choice button. Enter timecode in the format M:SS."
				        },
				        {
				          "name": "ifChosenText",
				          "type": "text",
				          "optional": true,
				          "label": "If Chosen Text",
				          "importance": "low",
				          "description": "An optional confirmation text that will be displayed after the user has pressed the choice button."
				        }
				      ]
				    }
				  },
				  {
				    "name": "continueButtonLabel",
				    "type": "text",
				    "label": "Continue Button Label",
				    "importance": "low",
				    "default": "Continue",
				    "common": true
				  }
				]';
    }

    /**
     * Get H5P IV Hot Spot Library Semantics
     */
    private function getH5PIVHotSpotSemantics()
    {
    	return '[
				  {
				    "name": "destination",
				    "label": "Destination",
				    "type": "group",
				    "importance": "high",
				    "expanded": true,
				    "fields": [
				      {
				        "name": "type",
				        "label": "Type",
				        "type": "select",
				        "importance": "high",
				        "widget": "selectToggleFields",
				        "options": [
				          {
				            "value": "timecode",
				            "label": "Timecode",
				            "hideFields": [
				              "url"
				            ]
				          },
				          {
				            "value": "url",
				            "label": "Another page (URL)",
				            "hideFields": [
				              "time"
				            ]
				          }
				        ],
				        "default": "timecode"
				      },
				      {
				        "name": "time",
				        "type": "number",
				        "widget": "timecode",
				        "label": "Go To",
				        "importance": "high",
				        "description": "The target time the user will be taken to upon pressing the hotspot. Enter timecode in the format M:SS.",
				        "optional": true
				      },
				      {
				        "name": "url",
				        "type": "group",
				        "label": "URL",
				        "importance": "high",
				        "widget": "linkWidget",
				        "optional": true,
				        "fields": [
				          {
				            "name": "protocol",
				            "type": "select",
				            "label": "Protocol",
				            "importance": "high",
				            "options": [
				              {
				                "value": "http://",
				                "label": "http://"
				              },
				              {
				                "value": "https://",
				                "label": "https://"
				              },
				              {
				                "value": "/",
				                "label": "(root relative)"
				              },
				              {
				                "value": "other",
				                "label": "other"
				              }
				            ],
				            "optional": true,
				            "default": "http://"
				          },
				          {
				            "name": "url",
				            "type": "text",
				            "label": "URL",
				            "importance": "high",
				            "optional": true
				          }
				        ]
				      }
				    ]
				  },
				  {
				    "name": "visuals",
				    "type": "group",
				    "label": "Visuals",
				    "importance": "low",
				    "expanded": true,
				    "fields": [
				      {
				        "name": "shape",
				        "type": "select",
				        "label": "Shape",
				        "importance": "low",
				        "options": [
				          {
				            "value": "rectangular",
				            "label": "Rectangular"
				          },
				          {
				            "value": "circular",
				            "label": "Circular"
				          },
				          {
				            "value": "rounded-rectangle",
				            "label": "Rounded Rectangle"
				          }
				        ],
				        "default": "rectangular"
				      },
				      {
				        "name": "backgroundColor",
				        "type": "text",
				        "label": "Background color for hotspot",
				        "widget": "colorSelector",
				        "importance": "low",
				        "default": "rgba(255, 255, 255, 0)",
				        "spectrum": {
				          "showInput": true,
				          "showAlpha": true,
				          "preferredFormat": "rgb",
				          "showPalette": true,
				          "palette": [
				            [
				              "rgba(255, 255, 255, 0)"
				            ],
				            [
				              "rgb(67, 67, 67)",
				              "rgb(102, 102, 102)",
				              "rgb(204, 204, 204)",
				              "rgb(217, 217, 217)",
				              "rgb(255, 255, 255)"
				            ],
				            [
				              "rgb(152, 0, 0)",
				              "rgb(255, 0, 0)",
				              "rgb(255, 153, 0)",
				              "rgb(255, 255, 0)",
				              "rgb(0, 255, 0)",
				              "rgb(0, 255, 255)",
				              "rgb(74, 134, 232)",
				              "rgb(0, 0, 255)",
				              "rgb(153, 0, 255)",
				              "rgb(255, 0, 255)"
				            ],
				            [
				              "rgb(230, 184, 175)",
				              "rgb(244, 204, 204)",
				              "rgb(252, 229, 205)",
				              "rgb(255, 242, 204)",
				              "rgb(217, 234, 211)",
				              "rgb(208, 224, 227)",
				              "rgb(201, 218, 248)",
				              "rgb(207, 226, 243)",
				              "rgb(217, 210, 233)",
				              "rgb(234, 209, 220)",
				              "rgb(221, 126, 107)",
				              "rgb(234, 153, 153)",
				              "rgb(249, 203, 156)",
				              "rgb(255, 229, 153)",
				              "rgb(182, 215, 168)",
				              "rgb(162, 196, 201)",
				              "rgb(164, 194, 244)",
				              "rgb(159, 197, 232)",
				              "rgb(180, 167, 214)",
				              "rgb(213, 166, 189)",
				              "rgb(204, 65, 37)",
				              "rgb(224, 102, 102)",
				              "rgb(246, 178, 107)",
				              "rgb(255, 217, 102)",
				              "rgb(147, 196, 125)",
				              "rgb(118, 165, 175)",
				              "rgb(109, 158, 235)",
				              "rgb(111, 168, 220)",
				              "rgb(142, 124, 195)",
				              "rgb(194, 123, 160)",
				              "rgb(166, 28, 0)",
				              "rgb(204, 0, 0)",
				              "rgb(230, 145, 56)",
				              "rgb(241, 194, 50)",
				              "rgb(106, 168, 79)",
				              "rgb(69, 129, 142)",
				              "rgb(60, 120, 216)",
				              "rgb(61, 133, 198)",
				              "rgb(103, 78, 167)",
				              "rgb(166, 77, 121)",
				              "rgb(91, 15, 0)",
				              "rgb(102, 0, 0)",
				              "rgb(120, 63, 4)",
				              "rgb(127, 96, 0)",
				              "rgb(39, 78, 19)",
				              "rgb(12, 52, 61)",
				              "rgb(28, 69, 135)",
				              "rgb(7, 55, 99)",
				              "rgb(32, 18, 77)",
				              "rgb(76, 17, 48)"
				            ]
				          ]
				        }
				      },
				      {
				        "name": "pointerCursor",
				        "type": "boolean",
				        "label": "Use pointer cursor",
				        "importance": "low",
				        "default": true
				      },
				      {
				        "name": "animation",
				        "type": "boolean",
				        "label": "Add blinking effect",
				        "importance": "low",
				        "description": "Note: Blinking effect is always enabled in the editor so you are able to find transparent hotspots"
				      }
				    ]
				  },
				  {
				    "name": "texts",
				    "type": "group",
				    "label": "Texts",
				    "expanded": true,
				    "importance": "low",
				    "fields": [
				      {
				        "name": "alternativeText",
				        "type": "text",
				        "importance": "low",
				        "label": "Alternative Text",
				        "description": "Describe the subject the hotspot covers. Used for readspeakers",
				        "placeholder": "An apple on a table",
				        "optional": false
				      },
				      {
				        "name": "label",
				        "type": "text",
				        "importance": "low",
				        "label": "Hotspot Label",
				        "optional": true
				      },
				      {
				        "name": "showLabel",
				        "type": "boolean",
				        "label": "Show label",
				        "importance": "low",
				        "default": false
				      },
				      {
				        "name": "labelColor",
				        "type": "text",
				        "label": "Label color",
				        "widget": "showWhen",
				        "importance": "low",
				        "optional": true,
				        "default": "rgb(0, 0, 0)",
				        "showWhen": {
				          "detach": false,
				          "widget": "colorSelector",
				          "rules": [
				            {
				              "field": "showLabel",
				              "equals": true
				            }
				          ]
				        },
				        "spectrum": {
				          "showInput": true,
				          "showAlpha": true,
				          "preferredFormat": "rgb",
				          "showPalette": true,
				          "palette": [
				            [
				              "rgba(255, 255, 255, 0)"
				            ],
				            [
				              "rgb(67, 67, 67)",
				              "rgb(102, 102, 102)",
				              "rgb(204, 204, 204)",
				              "rgb(217, 217, 217)",
				              "rgb(255, 255, 255)"
				            ],
				            [
				              "rgb(152, 0, 0)",
				              "rgb(255, 0, 0)",
				              "rgb(255, 153, 0)",
				              "rgb(255, 255, 0)",
				              "rgb(0, 255, 0)",
				              "rgb(0, 255, 255)",
				              "rgb(74, 134, 232)",
				              "rgb(0, 0, 255)",
				              "rgb(153, 0, 255)",
				              "rgb(255, 0, 255)"
				            ],
				            [
				              "rgb(230, 184, 175)",
				              "rgb(244, 204, 204)",
				              "rgb(252, 229, 205)",
				              "rgb(255, 242, 204)",
				              "rgb(217, 234, 211)",
				              "rgb(208, 224, 227)",
				              "rgb(201, 218, 248)",
				              "rgb(207, 226, 243)",
				              "rgb(217, 210, 233)",
				              "rgb(234, 209, 220)",
				              "rgb(221, 126, 107)",
				              "rgb(234, 153, 153)",
				              "rgb(249, 203, 156)",
				              "rgb(255, 229, 153)",
				              "rgb(182, 215, 168)",
				              "rgb(162, 196, 201)",
				              "rgb(164, 194, 244)",
				              "rgb(159, 197, 232)",
				              "rgb(180, 167, 214)",
				              "rgb(213, 166, 189)",
				              "rgb(204, 65, 37)",
				              "rgb(224, 102, 102)",
				              "rgb(246, 178, 107)",
				              "rgb(255, 217, 102)",
				              "rgb(147, 196, 125)",
				              "rgb(118, 165, 175)",
				              "rgb(109, 158, 235)",
				              "rgb(111, 168, 220)",
				              "rgb(142, 124, 195)",
				              "rgb(194, 123, 160)",
				              "rgb(166, 28, 0)",
				              "rgb(204, 0, 0)",
				              "rgb(230, 145, 56)",
				              "rgb(241, 194, 50)",
				              "rgb(106, 168, 79)",
				              "rgb(69, 129, 142)",
				              "rgb(60, 120, 216)",
				              "rgb(61, 133, 198)",
				              "rgb(103, 78, 167)",
				              "rgb(166, 77, 121)",
				              "rgb(91, 15, 0)",
				              "rgb(102, 0, 0)",
				              "rgb(120, 63, 4)",
				              "rgb(127, 96, 0)",
				              "rgb(39, 78, 19)",
				              "rgb(12, 52, 61)",
				              "rgb(28, 69, 135)",
				              "rgb(7, 55, 99)",
				              "rgb(32, 18, 77)",
				              "rgb(76, 17, 48)"
				            ]
				          ]
				        }
				      }
				    ]
				  }
				]';
    }

    /**
     * Get H5P Questionaire Library Semantics
     */
    private function getH5PQuestionaireSemantics()
    {
    	return '[
          {
            "name": "questionnaireElements",
            "label": "Questionnaire elements",
            "importance": "high",
            "type": "list",
            "widgets": [
              {
                "name": "VerticalTabs",
                "label": "Default",
                "importance": "high"
              }
            ],
            "entity": "element",
            "min": 1,
            "defaultNum": 1,
            "field": {
              "name": "libraryGroup",
              "label": "Choose library",
              "importance": "high",
              "type": "group",
              "fields": [
                {
                  "name": "library",
                  "type": "library",
                  "label": "Library",
                  "importance": "high",
                  "description": "Choose a library",
                  "options": [
                    "H5P.OpenEndedQuestion 1.0",
                    "H5P.SimpleMultiChoice 1.1"
                  ]
                },
                {
                  "name": "requiredField",
                  "type": "boolean",
                  "label": "Required field",
                  "importance": "low",
                  "default": false
                }
              ]
            }
          },
          {
            "name": "successScreenOptions",
            "label": "Success screen options",
            "importance": "low",
            "type": "group",
            "fields": [
              {
                "name": "enableSuccessScreen",
                "label": "Enable success screen",
                "importance": "low",
                "type": "boolean",
                "default": true
              },
              {
                "name": "successScreenImage",
                "label": "Add success screen image",
                "importance": "low",
                "type": "group",
                "fields": [
                  {
                    "name": "successScreenImage",
                    "label": "Replace success icon with image",
                    "importance": "low",
                    "type": "library",
                    "optional": true,
                    "options": [
                      "H5P.Image 1.1"
                    ]
                  }
                ]
              },
              {
                "name": "successMessage",
                "type": "text",
                "label": "Text to display on submit",
                "importance": "low",
                "default": "You have completed the questionnaire."
              }
            ]
          },
          {
            "name": "uiElements",
            "label": "UI Elements",
            "importance": "low",
            "type": "group",
            "fields": [
              {
                "name": "buttonLabels",
                "type": "group",
                "label": "Button labels",
                "importance": "low",
                "fields": [
                  {
                    "name": "prevLabel",
                    "type": "text",
                    "label": "Previous button label",
                    "importance": "low",
                    "default": "Back"
                  },
                  {
                    "name": "continueLabel",
                    "type": "text",
                    "label": "Continue button label",
                    "importance": "low",
                    "default": "Continue"
                  },
                  {
                    "name": "nextLabel",
                    "type": "text",
                    "label": "Next button label",
                    "importance": "low",
                    "default": "Next"
                  },
                  {
                    "name": "submitLabel",
                    "type": "text",
                    "label": "Submit button label",
                    "importance": "low",
                    "default": "Submit"
                  }
                ]
              },
              {
                "name": "accessibility",
                "type": "group",
                "label": "Accessibility",
                "importance": "low",
                "fields": [
                  {
                    "name": "requiredTextExitLabel",
                    "type": "text",
                    "label": "Required message exit button label",
                    "importance": "low",
                    "default": "Close error message"
                  },
                  {
                    "name": "progressBarText",
                    "type": "text",
                    "label": "Progress bar text",
                    "importance": "low",
                    "default": "Question %current of %max",
                    "description": "Used to tell assistive technologies what question it is. Variables: [ %current, %max ]"
                  }
                ]
              },
              {
                "name": "requiredMessage",
                "type": "text",
                "label": "Required message",
                "importance": "low",
                "default": "This question requires an answer",
                "description": "Will display if this field is unanswered and required by a wrapper content type"
              },
              {
                "name": "requiredText",
                "type": "text",
                "label": "Required symbol text",
                "importance": "low",
                "default": "required",
                "description": "Text that will accompany an asterisk to signal that a question is required"
              },
              {
                "name": "submitScreenTitle",
                "type": "text",
                "label": "Title for the submit screen",
                "importance": "low",
                "default": "You successfully answered all of the questions"
              },
              {
                "name": "submitScreenSubtitle",
                "type": "text",
                "label": "Subtitle for the submit screen",
                "importance": "low",
                "default": "Click below to submit your answers"
              }
            ]
          },{
            "name": "currikisettings",
            "type": "group",
            "label": "Curriki settings",
            "importance": "low",
            "description": "These options will let you control how the curriki studio behaves.",
            "optional": true,
            "fields": [
              {
                "label": "Do not Show Submit Button",
                "importance": "low",
                "name": "disableSubmitButton",
                "type": "boolean",
                "default": false,
                "optional": true,
                "description": "This option only applies to a standalone activity. The Submit button is required for grade passback to an LMS."
              },
              {
                "label": "Placeholder",
                "importance": "low",
                "name": "placeholder",
                "type": "boolean",
                "default": false,
                "optional": true,
                "description": "This option is a place holder. will be used in future"
              },
              {
                "label": "Curriki Localization",
                "description": "Here you can edit settings or translate texts used in curriki settings",
                "importance": "low",
                "name": "currikil10n",
                "type": "group",
                "fields": [
                  {
                    "label": "Text for \"Submit\" button",
                    "name": "submitLabel",
                    "importance": "low",
                    "type": "text",
                    "default": "Submit",
                    "optional": true
                  },
                  {
                    "label": "Text for \"Placeholder\" button",
                    "importance": "low",
                    "name": "placeholderButton",
                    "type": "text",
                    "default": "Placeholder",
                    "optional": true
                  }
                ]
              }
            ]
          }
        ]';
    }

    /**
     * Get H5P Free Text Question Library Semantics
     */
    private function getH5PFreeTextQuestionSemantics()
    {
    	return '[
				  {
				    "label": "Question",
				    "name": "question",
				    "type": "text",
				    "importance": "high"
				  },
				  {
				    "label": "Placeholder text",
				    "name": "placeholder",
				    "type": "text",
				    "importance": "high",
				    "default": "Enter your response here",
				    "description": "Text that initially will be shown in the input field. Will be removed automatically when the user starts writing."
				  },
				  {
				    "label": "Max score",
				    "name": "maxScore",
				    "type": "number",
				    "importance": "high",
				    "description": "Used for grading and not shown to the learner",
				    "default": 1
				  },
				  {
				    "label": "Required",
				    "name": "isRequired",
				    "type": "boolean",
				    "importance": "high",
				    "description": "Learners must provide an answer in order to proceed"
				  },
				  {
				    "label": "Localize Free Text Question buttons",
				    "name": "i10n",
				    "type": "group",
				    "common": true,
				    "collapsed": true,
				    "importance": "low",
				    "fields": [
				      {
				        "label": "Required text label",
				        "name": "requiredText",
				        "type": "text",
				        "description": "Text shown to notify learner that input is required before proceeding",
				        "default": "required",
				        "importance": "low"
				      },
				      {
				        "label": "Required message",
				        "name": "requiredMessage",
				        "type": "text",
				        "importance": "low",
				        "default": "This question requires an answer",
				        "description": "Will display if this field is unanswered and required by a wrapper content type"
				      },
				      {
				        "label": "Skip button label",
				        "name": "skipButtonLabel",
				        "type": "text",
				        "default": "Skip Question",
				        "importance": "low"
				      },
				      {
				        "label": "Proceed button label",
				        "name": "submitButtonLabel",
				        "type": "text",
				        "default": "Answer and proceed",
				        "importance": "low"
				      },
				      {
				        "name": "language",
				        "type": "select",
				        "label": "Wysiwyg editor language",
				        "importance": "medium",
				        "description": "The language of the wysiwyg user interface",
				        "options": [
				          {
				            "value": "af",
				            "label": "Afrikaans"
				          },
				          {
				            "value": "ar",
				            "label": "Arabic"
				          },
				          {
				            "value": "az",
				            "label": "Azerbaijani"
				          },
				          {
				            "value": "bg",
				            "label": "Bulgarian"
				          },
				          {
				            "value": "bn",
				            "label": "Bengali"
				          },
				          {
				            "value": "bs",
				            "label": "Bosnian"
				          },
				          {
				            "value": "ca",
				            "label": "Catalan"
				          },
				          {
				            "value": "cs",
				            "label": "Czech"
				          },
				          {
				            "value": "cy",
				            "label": "Welsh"
				          },
				          {
				            "value": "da",
				            "label": "Danish"
				          },
				          {
				            "value": "de-ch",
				            "label": "German (Switzerland)"
				          },
				          {
				            "value": "de",
				            "label": "German"
				          },
				          {
				            "value": "el",
				            "label": "Greek"
				          },
				          {
				            "value": "en-au",
				            "label": "English (Australia)"
				          },
				          {
				            "value": "en-ca",
				            "label": "English (Canada)"
				          },
				          {
				            "value": "en-gb",
				            "label": "English (United Kingdom)"
				          },
				          {
				            "value": "en",
				            "label": "English"
				          },
				          {
				            "value": "eo",
				            "label": "Esperanto"
				          },
				          {
				            "value": "es-mx",
				            "label": "Spanish (Mexico)"
				          },
				          {
				            "value": "es",
				            "label": "Spanish (Spain)"
				          },
				          {
				            "value": "et",
				            "label": "Estonian"
				          },
				          {
				            "value": "eu",
				            "label": "Basque"
				          },
				          {
				            "value": "fa",
				            "label": "Farsi"
				          },
				          {
				            "value": "fi",
				            "label": "Finnish"
				          },
				          {
				            "value": "fo",
				            "label": "Faeroese"
				          },
				          {
				            "value": "fr-ca",
				            "label": "French (Canada)"
				          },
				          {
				            "value": "fr",
				            "label": "French (Standard)"
				          },
				          {
				            "value": "gl",
				            "label": "Galician"
				          },
				          {
				            "value": "gu",
				            "label": "Gujarati"
				          },
				          {
				            "value": "he",
				            "label": "Hebrew (modern)"
				          },
				          {
				            "value": "hi",
				            "label": "Hindi"
				          },
				          {
				            "value": "hr",
				            "label": "Croatian"
				          },
				          {
				            "value": "hu",
				            "label": "Hungarian"
				          },
				          {
				            "value": "id",
				            "label": "Indonesian"
				          },
				          {
				            "value": "is",
				            "label": "Icelandic"
				          },
				          {
				            "value": "it",
				            "label": "Italian (Standard)"
				          },
				          {
				            "value": "ja",
				            "label": "Japanese"
				          },
				          {
				            "value": "ka",
				            "label": "Georgian"
				          },
				          {
				            "value": "km",
				            "label": "Central Khmer"
				          },
				          {
				            "value": "ko",
				            "label": "Korean"
				          },
				          {
				            "value": "ku",
				            "label": "Kurdish"
				          },
				          {
				            "value": "lt",
				            "label": "Lithuanian"
				          },
				          {
				            "value": "lv",
				            "label": "Latvian"
				          },
				          {
				            "value": "mk",
				            "label": "Macedonian"
				          },
				          {
				            "value": "mn",
				            "label": "Mongolian"
				          },
				          {
				            "value": "ms",
				            "label": "Malay"
				          },
				          {
				            "value": "nb",
				            "label": "Norwegian Bokmål"
				          },
				          {
				            "value": "nl",
				            "label": "Dutch (Standard)"
				          },
				          {
				            "value": "no",
				            "label": "Norwegian"
				          },
				          {
				            "value": "oc",
				            "label": "Occitan"
				          },
				          {
				            "value": "pl",
				            "label": "Polish"
				          },
				          {
				            "value": "pt-br",
				            "label": "Portuguese (Brazil)"
				          },
				          {
				            "value": "pt",
				            "label": "Portuguese (Portugal)"
				          },
				          {
				            "value": "ro",
				            "label": "Romanian"
				          },
				          {
				            "value": "ru",
				            "label": "Russian"
				          },
				          {
				            "value": "si",
				            "label": "Sinhala/Sinhalese"
				          },
				          {
				            "value": "sk",
				            "label": "Slovak"
				          },
				          {
				            "value": "sl",
				            "label": "Slovenian"
				          },
				          {
				            "value": "sq",
				            "label": "Albanian"
				          },
				          {
				            "value": "sr-latn",
				            "label": "Serbian (Latin)"
				          },
				          {
				            "value": "sr",
				            "label": "Serbian"
				          },
				          {
				            "value": "sv",
				            "label": "Swedish"
				          },
				          {
				            "value": "th",
				            "label": "Thai"
				          },
				          {
				            "value": "tr",
				            "label": "Turkish"
				          },
				          {
				            "value": "tt",
				            "label": "Tatar"
				          },
				          {
				            "value": "ug",
				            "label": "Uighur/Uyghur"
				          },
				          {
				            "value": "uk",
				            "label": "Ukrainian"
				          },
				          {
				            "value": "vi",
				            "label": "Vietnamese"
				          },
				          {
				            "value": "zh-cn",
				            "label": "Chinese (PRC)"
				          }
				        ],
				        "default": "en"
				      }
				    ]
				  }
				]';
    }
}
