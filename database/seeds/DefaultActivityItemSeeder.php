<?php

use Illuminate\Support\Facades\File;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultActivityItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $localURL = public_path('storage/activity-items/');
        $storageURL = '/storage/activity-items/';

        $itemsArray = [
            'AudioRecorder' => [
                'Record your voice and play back or download a .wav file of your recording.',
                'Audio changed',
                'H5P.AudioRecorder 1.0',
                '768',
                'https://www.youtube-nocookie.com/embed/O73hIb7yxLg',
                'tnX7ao0hXM7lnPALBEgweoVbKtJ7HJJznlU3fH9Z.png',

            ],
            'InteractiveVideo' => [
                'Add multiple interactions to any video',
                'Multimedia',
                'H5P.InteractiveVideo 1.21',
                '755',
                'https://www.youtube-nocookie.com/embed/7FnoeS8fxXg',
                'mfzc7dF8GW4NToalg6X4WRt4maHZSu5r8lXPjBbj.png',
            ],
            'BranchingScenario' => [
                'Create adaptive scenarios for the learner',
                'Multimedia',
                'H5P.BranchingScenario 1.1',
                '759',
                'https://www.youtube-nocookie.com/embed/6sOoeu_NUU4',
                'b2HYsuQVpbPXXFGUynONxft2U7q1evDCvyvUS8ae.png',

            ],
            'ImmersiveReader' => [
                "Read text documents with Microsoft's Immersive Reader",
                'Multimedia',
                'H5P.ImmersiveReader 1.0',
                '181321',
                'https://www.youtube-nocookie.com/embed/F0P53KBqYSs',
                '1Xos9OhTTVrdpxmIj8qAJYsTl2ZvwNpcR50dnXAR.png',

            ],
            'Accordion' => [
                'An activity that creates accessible (WAI-ARIA enabled) accordions',
                'Informational',
                'H5P.Accordion 1.0',
                '763',
                'https://www.youtube-nocookie.com/embed/dVDFwhy93Vc',
                'lIracKPKHsgt9VFEb6HuAtLrIFrEp3OdVWyj2mz1.png',

            ],
            'Agamotto' => [
                'Learners compare and explore a sequence of images interactively',
                'Photo / Images',
                'H5P.Agamotto 1.5',
                '745',
                'https://www.youtube-nocookie.com/embed/5o9sGAVjnUc',
                '5pOc7tnH4WJ13aoqxYt8BwJk5N518M7GosNFCdBM.png',

            ],
            'AdvancedFillInTheBlanks' => [
                'Fill in missing words with advanced feedback options',
                'Questions',
                'H5P.AdvancedBlanks 1.0',
                '729',
                'https://www.youtube-nocookie.com/embed/f1yuOIUsVHA',
                'siN8SIi0kxBjLpjhLG9iQseAulRNFRQ6zMX5cKiW.png',

            ],
            'Dictation' => [
                'A tool to create dictation exercises',
                'Audio changed',
                'H5P.Dictation 1.0',
                '767',
                'https://www.youtube-nocookie.com/embed/JLYtQpB0JmY',
                'bMWzhTxtPaOou6xCnrcIH3wFMJP270UdSb9S0YZL.png',

            ],
            'Collage' => [
                'Set up multiple photos in a custom layout',
                'Photo / Images',
                'H5P.Collage 0.3',
                '744',
                'https://www.youtube-nocookie.com/embed/TaV_Dj3kous',
                'TihZG22CXrb0g9TO3qJHm1zTGvNgarrL1yo1KrIM.png',

            ],
            'DialogCards' => [
                'Create great interactive language learning resources',
                'Informational',
                'H5P.Dialogcards 1.8',
                '732',
                'https://www.youtube-nocookie.com/embed/Fh5zrkWgAjk',
                'LKVAs3jjSeKcbl3KLUxQaO5nJUQIkY3ujROzqsjz.png',

            ],
            'GeoGebra3D' => [
                'GeoGebra 3D',
                'Mathematics',
                'H5P.GeoGebra3d 1.0',
                '181321',
                'https://www.youtube-nocookie.com/embed/F0P53KBqYSs',
                'bh86dGjxsVxBTvQX47E1MaIScnE28uytlVKC1sS6.png',

            ],
            'ArithmeticQuiz' => [
                'Time based arithmetic exam builder',
                'Mathematics',
                'H5P.ArithmeticQuiz 1.1',
                '730',
                'https://www.youtube-nocookie.com/embed/Z61BUoL6k1Y',
                '55xM0Ep2leuXipkeMvIpFcdPW1spwViDAMRlNgQ5.png',

            ],
            'Chart' => [
                'Create pie and bar charts',
                'Multimedia',
                'H5P.Chart 1.2',
                '756',
                'https://www.youtube-nocookie.com/embed/F0P53KBqYSs',
                'U7dZBvV3edXI8dwio4W1xw2YIzk0xbkTILtjh0WN.png',

            ],
            'GeoGebraGeometry' => [
                'GeoGebra Geometry',
                'Mathematics',
                'H5P.GeoGebraGeometry 1.0',
                '181321',
                'https://www.youtube-nocookie.com/embed/F0P53KBqYSs',
                '7YzhIm11bWa5B0arTVORamNIq9kbFEWMr3Svo7Vh.png',

            ],

            'DragDrop' => [
                'Create many forms of drag and drop interactions',
                'Photo / Images',
                'H5P.DragQuestion 1.13',
                '753',
                'https://www.youtube-nocookie.com/embed/S_4qMIN2hoE',
                'He39vgNm9EcwhNR3iJ2zF4LPI0ZjPXSTDQKmL8dj.png',

            ],
            'SpokenAnswers' => [
                'Voice recognition activity that the learner can answered with their own voice.',
                'Audio changed',
                'H5P.SpeakTheWords 1.3',
                '769',
                'https://www.youtube-nocookie.com/embed/lgzsJDcMvPI',
                'Ydopam5dGlizoM5Ki8LdIWHt6H2CVCVTFUyLEbRA.png',

            ],
            'ColumnLayout' => [
                'Stack multiple interactions in a column layout',
                'Multimedia',
                'H5P.Column 1.11',
                '760',
                'https://www.youtube-nocookie.com/embed/ngXSzWNYzU4',
                'EdE8yAybW0I4IlU8qpEZqrkIdlaou3CDcBAj1M4D.png',

            ],
            'DocumentationTool' => [
                'Create guides for structured writing processes / Form.',
                'Informational',
                'H5P.DocumentationTool 1.8',
                '765',
                'https://www.youtube-nocookie.com/embed/0pVTe7ooaW8',
                'oCf2Qcftx43uQ8xzWsM12tk3j6rRMDGgaKaYgj63.png',

            ],
            'CoursePresentation' => [
                'Add multiple interactions into a slide presentation',
                'Multimedia',
                'H5P.CoursePresentation 1.22',
                '758',
                'https://www.youtube-nocookie.com/embed/b1_-JJWKh3w',
                'rF2Vdw0bT3T7Fx85FZ7pvvZgzr0ka6DLKFLkVnVT.png',

            ],
            'Essay' => [
                'Create interactive essays with instant feedback',
                'Questions',
                'H5P.Essay 1.2',
                '735',
                'https://www.youtube-nocookie.com/embed/d6WhXexBVnI',
                'NRvJ542ajvXQTLvKvCz8zZasz5OFXufdv81LaCL0.png',

            ],
            'IframeEmbedder' => [
                'Create an interactive activity from an existing JavaScript application',
                'Informational',
                'H5P.IFrameEmbed 1.0',
                '764',
                'https://www.youtube-nocookie.com/embed/T_jP_G4nYoY',
                'xhchY8NEuv1ksE3XRvgImKSkMRF7ioLWzPqgMxLs.png',

            ],
            'GeoGebraGraphing' => [
                'GeoGebra Graphing',
                'Mathematics',
                'H5P.GeoGebraGraphing 1.0',
                '181321',
                'https://www.youtube-nocookie.com/embed/F0P53KBqYSs',
                'aHXsbHThA6cSJpCR4RrZKlRPe80veXY5Ljy0PSwA.png',

            ],
            'FlashCards' => [
                'Create stylish and intuitive flashcards that pair images to questions',
                'Photo / Images',
                'H5P.Flashcards 1.5',
                '749',
                'https://www.youtube-nocookie.com/embed/vgco6i3B-yc',
                'ouVFSKGg8dSD2s1Qn4cMWdRCy23Vi7B7OUa4MGbe.png',

            ],
            'MathColumn' => [
                'Math Column',
                'Mathematics',
                'H5P.CurrikiMathColumn 1.0',
                '181321',
                'https://www.youtube-nocookie.com/embed/F0P53KBqYSs',
                'JLYDn0JKLN8jnejQXQh4WS2v0gJA6tDGirRGID3y.png',

            ],
            'Summary' => [
                'Create challenges where the learner chooses statements to reach the correct conclusion.',
                'Informational',
                'H5P.Summary 1.10',
                '762',
                'https://www.youtube-nocookie.com/embed/EuXbu4Y4EkU',
                'KDNpIFJ9182Lhaxg1xSHAk6jyvObJGdU0j1OaQT7.png',

            ],
            'ImpressivePresentation' => [
                'Create interactive 3D presentations',
                'Multimedia',
                'H5P.ImpressPresentation 1.0',
                '181321',
                'https://www.youtube-nocookie.com/embed/F0P53KBqYSs',
                'hG82G9tXwheNQPwj9vbcJTyrLZJGI3BvgsqkxKmX.png',

            ],
            'GuessTheAnswer' => [
                'Create challenges where the user guesses the answer based on a picture',
                'Photo / Images',
                'H5P.GuessTheAnswer 1.4',
                '750',
                'https://www.youtube-nocookie.com/embed/csMLYiMX2zs',
                'RA4r64Ii82tPoV98yUIHFUC21QYQR7YfXxSHPEZE.png',

            ],
            'FillInTheBlanks' => [
                'Create a task with  missing words the learner has to provide',
                'Questions',
                'H5P.Blanks 1.12',
                '742',
                'https://www.youtube-nocookie.com/embed/F0P53KBqYSs',
                '5ZuEZG0vMvUSkFOJ3sOcutPBYqsFuvRf0MAW8g1Z.png',

            ],
            'ImageHotspot' => [
                'Add hotspots to images that reveal text, images or videos when clicked',
                'Photo / Images',
                'H5P.ImageHotspots 1.8',
                '743',
                'https://www.youtube-nocookie.com/embed/5XFXY5pYG-M',
                'kTBub2utoyjCHTkyglrXe8PvboXkws5WFwHCwVVj.png',

            ],
            'MarkTheWords' => [
                'Create a word highlighting activity',
                'Questions',
                'H5P.MarkTheWords 1.9',
                '731',
                'https://www.youtube-nocookie.com/embed/A5B2Py-o1jI',
                'cmQZWRI8Lr4OokOHJGDTKQVIsITzpbnFx0702lQh.png',

            ],
            'DocumentsEmbed' => [
                'Embed documents with preview from different sources i.e google docs, sheets, or any public URL.',
                'Informational',
                'H5P.DocumentsUpload 1.0',
                '43161',
                'https://www.youtube-nocookie.com/embed/F0P53KBqYSs',
                'NElb0l40XKVLmhgJvsScHDxFYwc7UFKb3mlgLpUa.png',

            ],
            'ImagePairing' => [
                'Interactive image matching game',
                'Photo / Images',
                'H5P.ImagePair 1.4',
                '746',
                'https://www.youtube-nocookie.com/embed/LdbtfHFGq5w',
                '61wNcp69Q7P6VEC50h8soQZznk9rCHxhmknVDxgD.png',

            ],
            'Timeline' => [
                'Create a multimedia timeline of events',
                'Multimedia',
                'H5P.Timeline 1.1',
                '757',
                'https://www.youtube-nocookie.com/embed/7JTpDzRgoW4',
                'tamdoGBeiNXziZRAEUE6N17u9UoAXOanjaOhWRSq.png',

            ],
            'ImageJuxtaposition' => [
                'Activity where the learner compare two images interactively',
                'Photo / Images',
                'H5P.ImageJuxtaposition 1.4',
                '748',
                'https://www.youtube-nocookie.com/embed/aNUWJRoCSOY',
                '0wf38khInHAwOQldtyZFhb7mJNJu2Owr6wLPnkVP.png',

            ],
            'VirtualTour' => [
                'Add questions, texts and interactions to multiple 360 environments',
                'Multimedia',
                'H5P.ThreeImage 0.3',
                '761',
                'https://www.youtube-nocookie.com/embed/cZbxGLBOo9M',
                '02cC8JQA3eKy6zRjkjpm1dpCccOONVG9W82fIFhI.png',

            ],
            'PersonalityQuiz' => [
                'Build your own personality quiz',
                'Questions',
                'H5P.PersonalityQuiz 1.0',
                '739',
                'https://www.youtube-nocookie.com/embed/zY8CTNn5LVA',
                '98oDHG6ZnC6O1eN5NboQ8Ya3afOHaJoG46g7wVp7.png',

            ],
            'ImageSequencing' => [
                'Reorder images and place them in their correct sequence',
                'Photo / Images',
                'H5P.ImageSequencing 1.1',
                '752',
                'https://www.youtube-nocookie.com/embed/zrNAd3VMfyQ',
                'Kak84uGk18bkvYBZCQDQWVqtrzQJBKWFovfWfZf1.png',

            ],
            'SingleChoiceSet' => [
                'Create questions with one correct answer',
                'Questions',
                'H5P.SingleChoiceSet 1.11',
                '740',
                'https://www.youtube-nocookie.com/embed/mufRBzsqZEw',
                'onVxb3wM7k5IeA2la7zDp7UxjPFe6DwgBvJr52de.png',

            ],
            'ImageSlider' => [
                'Create attractive image slide shows',
                'Photo / Images',
                'H5P.ImageSlider 1.0',
                '747',
                'https://www.youtube-nocookie.com/embed/CE5SyvPvjxU',
                'vwvuakEKCdUQ5aXpcsPq4VzK2hkPH2QJId1htkbY.png',

            ],
            'TrueFalse' => [
                'Create classic true and false questions',
                'Questions',
                'H5P.TrueFalse 1.6',
                '738',
                'https://www.youtube-nocookie.com/embed/pbjfPVykP1M',
                'OBdqsD9mKmGFrDEpQ7OUMBgcLlHCAhrLuG5c4tX1.png',

            ],
            'MemoryGame' => [
                'Create a classic image pairing game',
                'Photo / Images',
                'H5P.MemoryGame 1.3',
                '754',
                'https://www.youtube-nocookie.com/embed/4FhblZzRh8c',
                'yjoTlHIk3TD8rfnyHp8j45aZDdRSOfvIDXx6HOPU.png',

            ],
            'Questionnaire' => [
                'Receive feedback through an interactive questionnaire',
                'Questions',
                'H5P.Questionnaire 1.2',
                '741',
                'https://www.youtube-nocookie.com/embed/zh3cXnEmLxg',
                'mtBeC6w0OY8JM4cgPetzBafE6iUPry3omWrsPS6k.png',

            ],
            'FindTheHotspot' => [
                'Create an image-based test where the learner is to find the correct spots on an image.',
                'Photo / Images',
                'H5P.ImageHotspotQuestion 1.8',
                '751',
                'https://www.youtube-nocookie.com/embed/Go6mE7mLNU8',
                'anic3lSm8lCNYYO9fCYEF0804myUgJODNJZbszW6.png',

            ],
            'Quiz' => [
                'Create an assessment with multiple question types',
                'Questions',
                'H5P.QuestionSet 1.17',
                '736',
                'https://www.youtube-nocookie.com/embed/t0vsfxiq1zk',
                'Id6BxSkmuVvZBOHNl9Wd5WUfITh4qFq2DEMO6bOJ.png',

            ],
            'DragText' => [
                'Create a text based drag and drop activity',
                'Questions',
                'H5P.DragText 1.8',
                '737',
                'https://www.youtube-nocookie.com/embed/UMbgmnXD5co',
                'jMQ5ZmuR7fdG0jIpQqM61FAkmBcLh71vvY7MqjGe.png',

            ],
            'WordFind' => [
                'Create a word find game based on your selection of words',
                'Questions',
                'H5P.FindTheWords 1.4',
                '734',
                'https://www.youtube-nocookie.com/embed/gx7a8FBvkUM',
                'DjLfcavhsKCOj2xfMvlzUVYtuTk47vivKEtyVlIo.png',

            ],
            'InteractiveBook' => [
                'Interactive Book',
                'Multimedia',
                'H5P.InteractiveBook 1.2',
                '181321',
                'https://www.youtube-nocookie.com/embed/F0P53KBqYSs',
                'CeOAsd4QYEvpgoQpfjrVicxthAP6lM2G7LaTRFyd.png',

            ],
        ];

        $organizations = DB::table('organizations')->pluck('id');
        $currentDate = now();

        foreach ($organizations as $key => $organization) {

            $activity_items = [];
            $activityTypes = DB::table('activity_types')->whereOrganizationId($organization)->pluck('id', 'title');

            foreach ($itemsArray as $itemKey => $itemRow) {

                if (!isset($activityTypes[$itemRow[1]])) {
                    continue;
                }

                if (!File::exists($localURL . $itemRow[5])) {
                    $this->copyImage($itemRow[5]);
                }

                $activityItemInsert = [
                    'title' => $this->makeTitle($itemKey),
                    'image' => $storageURL . $itemRow[5],
                    'description' => $itemRow[0],
                    'activity_type_id' => $activityTypes[$itemRow[1]],
                    'h5pLib' => $itemRow[2],
                    'demo_activity_id' => $itemRow[3],
                    'demo_video_id' => $itemRow[4],
                    'type' => 'h5p',
                    'created_at' => $currentDate,
                    'organization_id' => $organization,
                ];

                $activity_items[] = $activityItemInsert;
            }

            DB::table('activity_items')->insertOrIgnore($activity_items);
        }

    }

    /**
     * Copy image from another server.
     *
     * @return void
     */
    public function copyImage($image)
    {
        $liveURL = 'https://studio.curriki.org/api/storage/activity-items/';
        $localURL = public_path('storage/activity-items/');

        $liveImageSrc = $liveURL . $image;
        $destination = $localURL . $image;

        if (@file_get_contents($liveURL . $image)) {
            copy($liveImageSrc, $destination);
        }
    }

     /**
     * Convert string from camel case to normal words.
     *
     * @return void
     */
    function makeTitle($input)
    {
        if ($input === 'TrueFalse') {
            return 'True & False';
        }
        if ($input === 'DragDrop') {
            return 'Drag & Drop';
        }
        if ($input === 'GeoGebra3D') {
            return 'Geo Gebra 3D';
        }

        $pattern = '!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!';
        preg_match_all($pattern, $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
          $match = $match == strtoupper($match) ?
                strtolower($match) :
              ucfirst($match);
        }
        return implode(' ', $ret);
    }
}
