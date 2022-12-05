<?php

use Knuckles\Scribe\Extracting\Strategies;

return [

    'theme' => 'default',

    /*
     * The HTML <title> for the generated documentation. If this is empty, Scribe will infer it from config('app.name').
     */
    'title' => null,

    /*
     * A short description of your API. Will be included in the docs webpage, Postman collection and OpenAPI spec.
     */
    'description' => '',

    /*
     * The base URL displayed in the docs. If this is empty, Scribe will use the value of config('app.url').
     */
    'base_url' => null,

    /*
     * Tell Scribe what routes to generate documentation for.
     * Each group contains rules defining which routes should be included ('match', 'include' and 'exclude' sections)
     * and settings which should be applied to them ('apply' section).
     */
    'routes' => [
        [
            /*
             * Specify conditions to determine what routes will be a part of this group.
             * A route must fulfill ALL conditions to be included.
             */
            'match' => [
                /*
                 * Match only routes whose paths match this pattern (use * as a wildcard to match any characters). Example: 'users/*'.
                 */
                'prefixes' => ['api/*'],

                /*
                 * Match only routes whose domains match this pattern (use * as a wildcard to match any characters). Example: 'api.*'.
                 */
                'domains' => ['*'],

                /*
                 * [Dingo router only] Match only routes registered under this version. Wildcards are not supported.
                 */
                'versions' => ['v1'],
            ],

            /*
             * Include these routes even if they did not match the rules above.
             * The route can be referenced by name or path here. Wildcards are supported.
             */
            'include' => [
                // 'users.index', 'healthcheck*'
            ],

            /*
             * Exclude these routes even if they matched the rules above.
             * The route can be referenced by name or path here. Wildcards are supported.
             */
            'exclude' => [
                // '/health', 'admin.*'
            ],

            /*
             * Settings to be applied to all the matched routes in this group when generating documentation
             */
            'apply' => [
                /*
                 * Additional headers to be added to the example requests
                 */
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],

                /*
                 * If no @response or @transformer declarations are found for the route,
                 * Scribe will try to get a sample response by attempting an API call.
                 * Configure the settings for the API call here.
                 */
                'response_calls' => [
                    /*
                     * API calls will be made only for routes in this group matching these HTTP methods (GET, POST, etc).
                     * List the methods here or use '*' to mean all methods. Leave empty to disable API calls.
                     */
                    'methods' => ['GET'],

                    /*
                     * Laravel config variables which should be set for the API call.
                     * This is a good place to ensure that notifications, emails and other external services
                     * are not triggered during the documentation API calls.
                     * You can also create a `.env.docs` file and run the generate command with `--env docs`.
                     */
                    'config' => [
                        'app.env' => 'documentation',
                        // 'app.debug' => false,
                    ],

                    /*
                     * Query parameters which should be sent with the API call.
                     */
                    'queryParams' => [
                        // 'key' => 'value',
                    ],

                    /*
                     * Body parameters which should be sent with the API call.
                     */
                    'bodyParams' => [
                        // 'key' => 'value',
                    ],

                    /*
                     * Files which should be sent with the API call.
                     * Each value should be a valid path (absolute or relative to your project directory) to a file on this machine (but not in the project root).
                     */
                    'fileParams' => [
                        // 'key' => 'storage/app/image.png',
                    ],

                    /*
                     * Cookies which should be sent with the API call.
                     */
                    'cookies' => [
                        // 'name' => 'value'
                    ],
                ],
            ],
        ],
    ],

    /*
     * The type of documentation output to generate.
     * - "static" will generate a static HTMl page in the /public/docs folder,
     * - "laravel" will generate the documentation as a Blade view, so you can add routing and authentication.
     */
    'type' => 'static',

    /*
     * Settings for `static` type output.
     */
    'static' => [
        /*
         * HTML documentation, assets and Postman collection will be generated to this folder.
         * Source Markdown will still be in resources/docs.
         */
        'output_path' => 'public/docs',
    ],

    /*
     * Settings for `laravel` type output.
     */
    'laravel' => [
        /*
         * Whether to automatically create a docs endpoint for you to view your generated docs.
         * If this is false, you can still set up routing manually.
         */
        'add_routes' => true,

        /*
         * URL path to use for the docs endpoint (if `add_routes` is true).
         * By default, `/docs` opens the HTML page, `/docs.postman` opens the Postman collection, and `/docs.openapi` the OpenAPI spec.
         */
        'docs_url' => '/docs',

        /*
         * Directory within `public` in which to store CSS and JS assets.
         * By default, assets are stored in `public/vendor/scribe`.
         * If set, assets will be stored in `public/{{assets_directory}}`
         */
        'assets_directory' => null,

        /*
         * Middleware to attach to the docs endpoint (if `add_routes` is true).
         */
        'middleware' => [],
    ],

    'try_it_out' => [
        /**
         * Add a Try It Out button to your endpoints so consumers can test endpoints right from their browser.
         * Don't forget to enable CORS headers for your endpoints.
         */
        'enabled' => true,

        /**
         * The base URL for the API tester to use (for example, you can set this to your staging URL).
         * Leave as null to use the current app URL (config(app.url)).
         */
        'base_url' => null,

        /**
         * Fetch a CSRF token before each request, and add it as an X-XSRF-TOKEN header. Needed if you're using Laravel Sanctum.
         */
        'use_csrf' => false,

        /**
         * The URL to fetch the CSRF token from (if `use_csrf` is true).
         */
        'csrf_url' => '/sanctum/csrf-cookie',
    ],

    /*
     * How is your API authenticated? This information will be used in the displayed docs, generated examples and response calls.
     */
    'auth' => [
        /*
         * Set this to true if any endpoints in your API use authentication.
         */
        'enabled' => false,

        /*
         * Set this to true if your API should be authenticated by default. If so, you must also set `enabled` (above) to true.
         * You can then use @unauthenticated or @authenticated on individual endpoints to change their status from the default.
         */
        'default' => false,

        /*
         * Where is the auth value meant to be sent in a request?
         * Options: query, body, basic, bearer, header (for custom header)
         */
        'in' => 'bearer',

        /*
         * The name of the auth parameter (eg token, key, apiKey) or header (eg Authorization, Api-Key).
         */
        'name' => 'key',

        /*
         * The value of the parameter to be used by Scribe to authenticate response calls.
         * This will NOT be included in the generated documentation.
         * If this value is empty, Scribe will use a random value.
         */
        'use_value' => env('SCRIBE_AUTH_KEY'),

        /*
         * Placeholder your users will see for the auth parameter in the example requests.
         * Set this to null if you want Scribe to use a random value as placeholder instead.
         */
        'placeholder' => '{YOUR_AUTH_KEY}',

        /*
         * Any extra authentication-related info for your users. For instance, you can describe how to find or generate their auth credentials.
         * Markdown and HTML are supported.
         */
        'extra_info' => 'You can retrieve your token by visiting your dashboard and clicking <b>Generate API token</b>.',
    ],

    /*
     * Text to place in the "Introduction" section, right after the `description`. Markdown and HTML are supported.
     */
    'intro_text' => <<<INTRO
This documentation aims to provide all the information you need to work with our API.

<aside>As you scroll, you'll see code examples for working with the API in different programming languages in the dark area to the right (or as part of the content on mobile).
You can switch the language used with the tabs at the top right (or from the nav menu at the top left on mobile).</aside>
INTRO
    ,

    /*
     * Example requests for each endpoint will be shown in each of these languages.
     * Supported options are: bash, javascript, php, python
     * To add a language of your own, see https://scribe.knuckles.wtf/laravel/advanced/example-requests
     *
     */
    'example_languages' => [
        'bash',
        'javascript',
        'php',
    ],

    /*
     * Generate a Postman collection (v2.1.0) in addition to HTML docs.
     * For 'static' docs, the collection will be generated to public/docs/collection.json.
     * For 'laravel' docs, it will be generated to storage/app/scribe/collection.json.
     * Setting `laravel.add_routes` to true (above) will also add a route for the collection.
     */
    'postman' => [
        'enabled' => true,

        /*
         * Manually override some generated content in the spec. Dot notation is supported.
         */
        'overrides' => [
            // 'info.version' => '2.0.0',
        ],
    ],

    /*
     * Generate an OpenAPI spec (v3.0.1) in addition to docs webpage.
     * For 'static' docs, the collection will be generated to public/docs/openapi.yaml.
     * For 'laravel' docs, it will be generated to storage/app/scribe/openapi.yaml.
     * Setting `laravel.add_routes` to true (above) will also add a route for the spec.
     */
    'openapi' => [
        'enabled' => true,

        /*
         * Manually override some generated content in the spec. Dot notation is supported.
         */
        'overrides' => [
            // 'info.version' => '2.0.0',
        ],
    ],

    /*
     * Custom logo path. This will be used as the value of the src attribute for the <img> tag,
     * so make sure it points to an accessible URL or path. Set to false to not use a logo.
     *
     * For example, if your logo is in public/img:
     * - 'logo' => '../img/logo.png' // for `static` type (output folder is public/docs)
     * - 'logo' => 'img/logo.png' // for `laravel` type
     *
     */
    'logo' => false,

    /**
     * The strategies Scribe will use to extract information about your routes at each stage.
     * If you create or install a custom strategy, add it here.
     */
    'strategies' => [
        'metadata' => [
            Strategies\Metadata\GetFromDocBlocks::class,
        ],
        'urlParameters' => [
            Strategies\UrlParameters\GetFromLaravelAPI::class,
            Strategies\UrlParameters\GetFromLumenAPI::class,
            Strategies\UrlParameters\GetFromUrlParamTag::class,
        ],
        'queryParameters' => [
            Strategies\QueryParameters\GetFromFormRequest::class,
            Strategies\QueryParameters\GetFromInlineValidator::class,
            Strategies\QueryParameters\GetFromQueryParamTag::class,
        ],
        'headers' => [
            Strategies\Headers\GetFromRouteRules::class,
            Strategies\Headers\GetFromHeaderTag::class,
        ],
        'bodyParameters' => [
            Strategies\BodyParameters\GetFromFormRequest::class,
            Strategies\BodyParameters\GetFromInlineValidator::class,
            Strategies\BodyParameters\GetFromBodyParamTag::class,
        ],
        'responses' => [
            Strategies\Responses\UseTransformerTags::class,
            Strategies\Responses\UseApiResourceTags::class,
            Strategies\Responses\UseResponseTag::class,
            Strategies\Responses\UseResponseFileTag::class,
            Strategies\Responses\ResponseCalls::class,
        ],
        'responseFields' => [
            Strategies\ResponseFields\GetFromResponseFieldTag::class,
        ],
    ],

    'fractal' => [
        /* If you are using a custom serializer with league/fractal, you can specify it here.
         * Leave as null to use no serializer or return simple JSON.
         */
        'serializer' => null,
    ],

    /*
     * [Advanced] Custom implementation of RouteMatcherInterface to customise how routes are matched
     *
     */
    'routeMatcher' => \Knuckles\Scribe\Matching\RouteMatcher::class,

    /**
     * For response calls, API resource responses and transformer responses,
     * Scribe will try to start database transactions, so no changes are persisted to your database.
     * Tell Scribe which connections should be transacted here.
     * If you only use one db connection, you can leave this as is.
     */
    'database_connections_to_transact' => [config('database.default')],
    'groups' => [
        /*
         * Endpoints which don't have a @group will be placed in this default group.
         */
        'default' => 'Endpoints',
        /*
         * By default, Scribe will sort groups alphabetically, and endpoints in the order their routes are defined.
         * You can override this by listing the groups, subgroups and endpoints here in the order you want them.
         *
         * Any groups, subgroups or endpoints you don't list here will be added as usual after the ones here.
         * If an endpoint/subgroup is listed under a group it doesn't belong in, it will be ignored.
         * Note: you must include the initial '/' when writing an endpoint.
         */
        'order' => [
            '1. Authentication' => [
                'Register',
                'Login',
                'Admin Login',
                'Login with Google',
                'Login with LTI SSO 1.0',
                'Login with LTI SSO',
                'Wordpress SSO: Execute wordpress sso authentication',
                'Get Wordpress SSO default settings',
                'Oaut Redirect',
                'Oaut oauthCallBack',
                'Forgot Password'."\n"
                    ."\n"
                    .'Send a password reset link to the given user.',
                'Reset Password'."\n"
                    ."\n"
                    .'Reset the given user\'s password.',
                'Verify an Email Address'."\n"
                    ."\n"
                    .'Mark the authenticated user\'s email address as verified.',
                'Logout',
                'Check if email is already registered',
            ],
            '2. User' => [
                'Download Exported Project',
                'Get All User Organizations',
                'Accept Terms',
                'Get Authenticated User',
                'Get All User Notifications',
                'Get All User Export list',
                'Get All User Export list',
                'Read All Notifications',
                'Read Notification',
                'Delete Notification',
                'Get All Users for Team',
                'Get All Organization Users',
                'Check Organization User',
                'Check User Email',
                'Update Password',
                'Get All Users',
                'Get User',
                'Update User',
                'Delete User',
                'Users Basic Report',
                'Get All Shared Projects',
            ],
            '3. Project' => [
                'Get Shared Project',
                'Get Project Search Preview',
                'Upload thumbnail',
                'Get Recent Projects',
                'Get Default Projects',
                'Get All Projects Detail',
                'GET /api/v1/projects/update-order',
                'Get All Favorite Projects of login user',
                'Reorder Projects',
                'Update Project\'s Order',
                'Share Project',
                'Clone Project',
                'Export Project',
                'Export Noovo Project',
                'Import Project',
                'Remove Share Project',
                'Favorite/Unfavorite Project',
                'Get All Organization Team\'s Projects',
                'Get All Projects of login user',
                'Create Project',
                'Get Project',
                'Update Project',
                'Remove Project',
                'Get Projects by Ids',
                'Get All Organization Projects',
                'Project Indexing',
                'Starter Project Toggle',
            ],
            '4. Playlist' => [
                'Get Shared Playlist',
                'Get Shared Playlist',
                'Get All Shared Playlists of a Project',
                'GET /api/v1/playlists/update-order',
                'Reorder Playlists',
                'Clone Playlist',
                'Get Playlists',
                'Create Playlist',
                'Get Playlist',
                'Update Playlist',
                'Remove Playlist',
                'Get Playlist Search Preview',
                'Share playlist',
                'Remove Share playlist',
                'Get Lti Playlist',
            ],
            '5. Activity' => [
                'Get Activity Search Preview',
                'Clone Activity',
                'Upload Activity thumbnail',
                'Share Activity',
                'GET /api/v1/activities/update-order',
                'Remove Share Activity',
                'Get Activity Detail',
                'H5P Activity',
                'Get H5P Resource Settings',
                'Get H5P Resource Settings (Open)',
                'Get Activities',
                'Create Activity',
                'Get Activity',
                'Update Activity',
                'Remove Activity',
                'Get Stand Alone Activities',
                'Create Activity',
                'Get Stand Alone Activity',
                'Update Activity',
                'Remove Standalone Activity',
                'Get Activity Detail',
                'H5P Activity',
                'Clone Stand Alone Activity',
                'Get H5P Resource Settings (Shared)',
            ],
            '6. Independent Activity' => [
                'Get Independent Activities',
                'Create Independent Activity',
                'Get Independent Activity',
                'Update Independent Activity',
                'Remove Independent Activity',
                'Upload Independent Activity thumbnail',
                'Get Independent Activity Detail',
                'H5P Independent Activity',
                'Share Independent Activity',
                'Remove Share Independent Activity',
                'Get Independent Activity Search Preview',
                'Clone Independent Activity',
                'Export Independent Activity',
                'Import Independent Activity',
                'Copy Independent Activity into Playlist',
                'Move Independent Activity into Playlist',
                'Copy Activity into Independent Activity',
                'GET /api/v1/independent-activities/{id}/h5p-activity',
                'Get All Organization Independent Activities',
                'Independent Activity Indexing',
                'Download XApi File',
                'Get H5P Resource Settings (Shared)',
                'Get H5P Resource Settings',
            ],
            '7. Activity Layout' => [
                'Get Activity Layouts',
                'Create Activity Layout',
                'Get Activity Item',
                'Remove Activity Item',
                'Upload Thumbnail',
            ],
            '8. Activity Item' => [
                'Get Activity Items',
                'Create Activity Item',
                'Get Activity Item',
                'Remove Activity Item',
                'Get Activity Items with pagination and search',
                'Upload Thumbnail',
            ],
            '9. Activity Type' => [
                'Get Activity Type Items',
                'Get Activity Types',
                'Create Activity Type',
                'Get Activity Type',
                'Remove Activity Type',
                'Upload Thumbnail',
            ],
            '10. CurrikiGo' => [
                'Publish Playlist to Canvas',
                'Publish Playlist to Moodle',
                'POST /api/v1/go/{lms}/projects/{project_id}/playlists/{playlist_id}/publish',
            ],
            '11. CurrikiGo Course' => [
                'Fetch a Course',
                'Fetch a Course from Generic',
            ],
            '12. H5P' => [
                'Create H5P Settings',
                'Get H5Ps',
                'Store H5P',
                'Get H5P',
                'Update H5P',
                'Remove H5P',
                'GET /api/v1/h5p/ajax/content-user-data',
                'Get H5P Independent Activity',
                'Get H5P Embed',
                'GET /api/v1/google-classroom/h5p/ajax/content-user-data',
            ],
            '13. Search' => [
                'Deep Linking Search',
                'Advance search',
                'Dashboard search',
                'Independent Activities search',
            ],
            '14. CurrikiGo Outcome' => [
                'Get Student Results Grouped Summary',
            ],
            '15. Google Classroom' => [
                'Save Access Token',
                'Get Courses',
                'Copy project to Google Classroom',
                'Get Courses Topics',
                'Publish playlist To Google Classroom',
                'Publish activity To Google Classroom',
                'Publish independent activity To Google Classroom',
                'TurnIn a student\'s submission',
                'Verify whether Google Classroom user has access to a student\'s submission',
                'Get student\'s submission against a classwork',
                'H5P Google Classroom Resource Settings For Google Classroom',
            ],
            '16. XAPI Cron' => [
                'xAPI extract job script',
            ],
            '17. Organization Permission Type API' => [
                'Get All Organization Permission Type',
            ],
            '18. Organization API' => [
                'Get By Domain',
                'Search organization',
            ],
            '19. Suborganization API' => [
                'User has permission',
                'Get User Permissions',
                'Get Default Permissions',
                'Add Suborganization Role',
                'Add Suborganization Role With UI Permissions',
                'Update Suborganization Role',
                'Get Visibility Types For Suborganization',
                'Get User Roles For Suborganization',
                'Get User Role detail For Suborganization',
                'Get User Role UI Permissions For Suborganization',
                'Upload thumbnail',
                'Upload Favicon',
                'Show Member Options',
                'Add Suborganization User',
                'Invite Organization Member',
                'Update Google/Microsoft Credentials in Suborganization',
                'Update Suborganization User',
                'Create Suborganization',
                'Get Suborganization',
                'Remove Suborganization',
                'Get All Suborganization',
                'Get Organization Media Sources',
                'Update Suborganization Media Sources',
                'Get Media Sources',
            ],
            '20. XAPI' => [
                'Save an xAPI Statement',
            ],
            '21.   Admin/Lti Tool Settings' => [
                'Delete Lti Tool',
                'Get LTI Tool Type List',
            ],
            '22. Team' => [
                'Invite Team',
                'Invite Team Member',
                'Invite Team Members',
                'Remove Team Member',
                'Add Projects to the Team',
                'Remove Project from the Team',
                'Add Members to the Team Project',
                'Remove Member from the Team Project',
                'Get All Organization Teams',
                'Get All Organization Teams for Admin',
                'Get User Team Permissions',
                'Update Team Member Role',
                'Get All Teams',
                'Create Team',
                'Get Team',
                'Update Team',
                'Remove Team',
                'Push Project to Noovo',
            ],
            '23. Author Tag' => [
                'Get Author Tags',
                'Create Author Tag',
                'Get Author Tag',
                'Remove Author Tag',
            ],
            '24. Education Level' => [
                'Get Education Level',
                'Create Education Level',
                'Get Education Level',
                'Remove Education Level',
            ],
            '25. Subject' => [
                'Get Subjects',
                'Create Subject',
                'Get Subject',
                'Remove Subject',
            ],
            '26. LMS Settings' => [
                'Get Projects based on LMS/LTI settings',
                'GET /api/v1/go/lms/project/{project_id}',
                'POST /api/v1/go/lms/activities',
                'Get organizations based on LMS/LTI settings',
                'Get independent Activity based on user_id',
            ],
            '27. Admin/Queues' => [
                'Get All Jobs',
                'Retry All Failed Jobs',
                'Delete All Failed Jobs',
                'Retry Specific Failed Job',
                'Delete Specific Failed Job',
                'Get All Queues Logs',
            ],
            '28. Admin/Whiteboard' => [
                'Get Whiteboard.',
            ],
            '29. Admin/LMS Settings' => [
                'Get All LMS Settings for listing.',
                'Create LMS Setting',
                'Get LMS Setting',
                'Update LMS Setting',
                'Delete LMS Setting',
            ],
            '30. User LMS Settings' => [
                'Authenticated user LMS settings',
            ],
            'Endpoints' => [
                'Authenticate the request for channel access.',
                'Save Access Token',
                'GET /api/v1/activities/{activity_id}/log-view',
                'GET /api/v1/playlists/{playlist_id}/log-view',
                'GET /api/v1/projects/{project_id}/log-view',
                'GET /api/v1/organization-types',
                'GET /api/v1/users/me/redeem/{offerName}',
                'Invite Group Member',
                'Invite Group Member',
                'Invite Group Members',
                'Remove Group Member',
                'Add Projects to the Group',
                'Remove Project from the Group',
                'Add Members to the Group Project',
                'Remove Member from the Group Project',
                'Get All Organization Groups',
                'Get All Groups',
                'Create Group',
                'Get Group',
                'Update Group',
                'Remove Group',
                'GET /api/v1/users/{user_id}/metrics',
                'GET /api/v1/users/{user_id}/membership',
                'Display error',
                'GET /api/v1/project/delete/{project_path}',
                'GET /api/v1/h5p/ajax/libraries',
                'POST /api/v1/h5p/ajax/libraries/load-all-dependencies',
                'GET /api/v1/h5p/ajax/single-libraries',
                'GET /api/v1/h5p/ajax/content-type-cache',
                'GET /api/v1/h5p/ajax/library-install',
                'POST /api/v1/h5p/ajax/library-upload',
                'GET /api/v1/h5p/ajax/filter',
                'GET /api/v1/h5p/ajax/finish',
                'GET /api/v1/h5p/h5p-result/my',
                'GET /api/v1/h5p/ajax/reader/finish',
                'GET /api/v1/h5p/ajax/reader/getScore',
                'Get All Brightcove API Settings for listing.',
                'Create Brightcove API Setting',
                'Update Brightcove API Setting',
                'Delete Brightcove Setting',
                'Upload CSS',
                'Login to Canvas',
                'Save Access Token',
                'Get List of Classes',
                'Create a new Class',
                'Publish project',
                'Publish independent activity/activity',
                'Get a full list of LMS settings available to the user',
                'Display a listing of the resource.',
                'Store a newly created resource in storage.',
                'Display the specified resource.',
                'Update the specified resource in storage.',
                'Remove the specified resource from storage.',
                'XApi File',
                'GET /api/v1/h5p/ajax/files',
                'GET /api/v1/h5p/export/{id}',
                'Canvas Teacher\'s data.',
                'Display error',
                'Users Basic Report',
                'Bulk Import',
                'Change User Role',
                'Get All Users List',
                'Create User',
                'Get Specified User',
                'Update Specified User',
                'Delete Specified User',
                'Projects indexing Bulk',
                'Starter Project Toggle',
                'Project Indexing',
                'Get the shared project',
                'Get All Projects.',
                'Get All LMS Settings for listing.',
                'Create LMS Setting',
                'Get LMS Setting',
                'Update LMS Setting',
                'Delete LMS Setting',
                'Get All Activity Types',
                'Create New Activity Type',
                'Get Specified Activity Type',
                'Update Specified Activity Type',
                'Delete Activity Type',
                'Get All Activity Items',
                'Create New Activity Item',
                'Get Specified Activity Item',
                'Update Specified Activity Item',
                'Delete Activity Item',
                'Get All Organization Types',
                'Create Organization Type',
                'Get Organization Type',
                'Update Organization Type',
                'Delete Organization Type',
                'Get All Jobs',
                'Retry All Failed Jobs',
                'Delete All Failed Jobs',
                'Retry Specific Failed Job',
                'Delete Specific Failed Job',
                'Get All Queues Logs',
                'Organization Basic Report',
                'Get Organizations',
                'Create Organization',
                'Get Organization',
                'Update Organization',
                'Remove Organization',
                'Remove Organization User',
                'Display parent organizations options',
                'Display member options',
                'Download Sample File',
                'H5P H5P Resource Settings',
                'Get Brightcove H5P Resource Settings',
                'Get Kaltura media entries list',
                'Get My Vimeo Videos List',
                'Get My Komodo Videos List',
                'Smithsonian Contents List',
                'Get Smithsonian Content',
                'Get Smithsonian Search Filter Data',
                'All Brightcove Account List.',
                'Get Brightcove Videos List',
                'Get vimeo,komodo direct or playable url',
            ],
        ],
    ],
    /**
     * Customize the "Last updated" value displayed in the docs by specifying tokens and formats.
     * Examples:
     * - {date:F j Y} => March 28, 2022
     * - {git:short} => Short hash of the last Git commit
     *
     * Available tokens are `{date:<format>}` and `{git:<format>}`.
     * The format you pass to `date` will be passed to PhP's `date()` function.
     * The format you pass to `git` can be either "short" or "long".
     */
    'last_updated' => 'Last updated: {date:F j, Y}',
    'examples' => [
        /*
         * If you would like the package to generate the same example values for parameters on each run,
         * set this to any number (eg. 1234)
         */
        'faker_seed' => null,
        /*
         * With API resources and transformers, Scribe tries to generate example models to use in your API responses.
         * By default, Scribe will try the model's factory, and if that fails, try fetching the first from the database.
         * You can reorder or remove strategies here.
         */
        'models_source' => ['factoryCreate', 'factoryMake', 'databaseFirst'],
    ]
];
