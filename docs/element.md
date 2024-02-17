# Using Scene Element: Action, Character, Dialogue etc

## Basic example

```php
require_once ('vendor/autoload.php');

use ScreenJSON\Screenplay;
use ScreenJSON\Content;
use ScreenJSON\Document\Title;

use ScreenJSON\Document\Scene;

use ScreenJSON\Document\Scene\Heading;
use ScreenJSON\Document\Scene\Elements\Action;

$screenplay = new Screenplay (
    new Title ('My New Screenplay') , [
    'guid'    => 'rfc4122',
    'lang'    => 'en',
    'locale'  => 'en_GB',
    'charset' => 'utf8',
    'dir'     => 'ltr'
]);

$screenplay->scene (
    (
        new Scene (
            new Heading (
                new Content ('INT'), 
                new Content ("DON CORLEONE'S HOME OFFICE"),
                new Content ('DAY'),
            )
        )
    )
    ->element (
        new Action ("Corleone stands, turning his back toward Bonasera.")
    )
);
```
### Joining elements to form the scene body

```php 
$screenplay->scene (
    $scene = (
        new Scene (
            $heading = new Heading (
                new Content ('INT'), 
                new Content ("DON CORLEONE'S HOME OFFICE"), 
                new Content ('DAY'), 1, 8
            )
        )
    )
    ->element (new Action ("Corleone stands, turning his back toward Bonasera."))
    ->element (new Character('DON CORLEONE'))
    ->element (new Parenthetical ('turning, looking around'))
    ->element ((new Dialogue ('O.C', false))->content ([
        'en' => "You come to me on the day of my daughter's wedding, and ask me to do a murder.",
        'fr' => "Tu es venu me voir le jour du mariage de ma fille, et tu m'as demandé de commettre un meurtre.",
    ]))
    ->element ((new Transition)->content ('SLOW PAN TO:'))
    ->element (new Action ("Bonasera kisses his hand.", 'en'))
    ->element ((new Shot)->content ("CLOSE UP ON HAND"))
    ->element (new General ("The End"))
);
```

### Advanced example

```php
use ScreenJSON\Screenplay;
use ScreenJSON\Content;
use ScreenJSON\Document\Title;

use ScreenJSON\Document\Scene;

use ScreenJSON\Document\Scene\Heading;
use ScreenJSON\Document\Scene\Elements\Action;

use Carbon\Carbon;

$screenplay = new Screenplay (
    new Title ('My New Screenplay') , [
    'guid'    => 'rfc4122',
    'lang'    => 'en',
    'locale'  => 'en_GB',
    'charset' => 'utf8',
    'dir'     => 'ltr'
]);

$screenplay->scene (
    $scene = (
        new Scene (
            $heading = new Heading (
                new Content ('INT'), 
                new Content ("DON CORLEONE'S HOME OFFICE"),
                new Content ('DAY'), 
            )->meta (['foo' => 'bar'])
        )
    )
    ->element (
        (new Dialogue ('O.C', false), null, [
            'lang'          => 'en',
            'charset'       => 'utf8',
            'dir'           => 'ltr',
            'perspective'   => '2D',
            'interactivity' => false,
            'fov'           => 40,
            'omitted'       => false,
            'locked'        => false,
            'dom'           => 'p',
            'css'           => 'dialogue-para col-12 m-3',
            'access'        => ['author', 'editor'],
            'styles'        => ['courier-12-normal']
        ])
        ->meta (['foo' => 'bar'])
        ->content ([
            'en' => "You come to me on the day of my daughter's wedding, and ask me to do a murder.",
            'fr' => "Tu es venu me voir le jour du mariage de ma fille, et tu m'as demandé de commettre un meurtre.",
        ])
    )
    ->meta (['foo' => 'bar'])
);
```

## Complex example with its serialised output 

```php 
require_once ('vendor/autoload.php');

use ScreenJSON\Annotation;
use ScreenJSON\Author;
use ScreenJSON\Contributor;
use ScreenJSON\Screenplay;
use ScreenJSON\Content;
use ScreenJSON\Revision;
use ScreenJSON\Document\Title;
use ScreenJSON\Document\Cover;
use ScreenJSON\Document\Header;
use ScreenJSON\Document\Footer;
use ScreenJSON\Document\Scene;

use ScreenJSON\Document\Scene\Heading;
use ScreenJSON\Document\Scene\Elements\Action;
use ScreenJSON\Document\Scene\Elements\Character;
use ScreenJSON\Document\Scene\Elements\Dialogue;
use ScreenJSON\Document\Scene\Elements\General;
use ScreenJSON\Document\Scene\Elements\Parenthetical;
use ScreenJSON\Document\Scene\Elements\Shot;
use ScreenJSON\Document\Scene\Elements\Transition;

use \Carbon\Carbon;

$screenplay = new Screenplay (
    new Title ('My New Screenplay') , [
    'guid'    => 'rfc4122',
    'lang'    => 'en',
    'locale'  => 'en_GB',
    'charset' => 'utf8',
    'dir'     => 'ltr'
]);

$screenplay
    ->cover (new Cover (new Title (
        'My New Story'
    )))
    ->header (new Header (new Content (
        'Here is some header content'
    )))
    ->footer (new Footer (new Content (
        'Here is some footer content'
    )));

$screenplay
->author ($author_a = (new Author ('Joe', 'Bloggs', ['writer']))->meta (['imdb' => 'wga123098']))
->scene (
    $scene = (
        new Scene (
            $heading = new Heading (
                new Content ('INT'), 
                new Content ("DON CORLEONE'S HOME OFFICE"),
                new Content ('DAY'), 
                1, // production numbering
                8 // page numbering
            ),
            [$author_a->id()],
            Carbon::now(),
            Carbon::now(),
            $animals = ['cat'],
            $casts = ['DON CORLEONE', 'BONASERA'],
            $extra = [],
            $locations = ["DON CORLEONE'S HOME OFFICE"]
        )
    )
    ->element (
        (new Action)
        ->content ("Corleone stands, turning his back toward Bonasera.")
        ->revision (
            new Revision (
                '2.1', 
                0, 
                [$author_a->id()]
            )
        )
    )
    ->element ((new Character)->content ('DON CORLEONE'))
    ->element (
        new Parenthetical (
            new Content ('turning, looking around'), 
            [
                'authors' => [$author_a->id()]
            ]
        )
    )
    ->element (
        (new Dialogue ('O.C', false, null, [
            'lang'          => 'en',
            'charset'       => 'utf8',
            'dir'           => 'ltr',
            'perspective'   => '2D',
            'interactivity' => false,
            'fov'           => 40,
            'omitted'       => false,
            'locked'        => false,
            'dom'           => 'p',
            'css'           => 'dialogue-para col-12 m-3',
            'access'        => ['author', 'editor'],
            'styles'        => ['courier-12-normal']
        ]))
        ->meta (['foo' => 'bar'])
        ->content ([
            'en' => "You come to me on the day of my daughter's wedding, and ask me to do a murder.",
            'fr' => "Tu es venu me voir le jour du mariage de ma fille, et tu m'as demandé de commettre un meurtre.",
        ])
    )
    ->meta (['foo' => 'bar'])
    ->element ((new Transition)->content ('SLOW PAN TO:'))
    ->element ((new Action)->content ("Bonasera kisses his hand.", 'en'))
    ->element (
        (
            new Shot (
                new Content ('CLOSE UP ON HAND'), 
                [
                    'contributors' => [$author_a->id()]
                ]
            )
        )
        ->annotation (
            new Annotation(
                    new Content ('Here is a note'), 
                    $author_a->id(), 
                    'green',
                    Carbon::now()
                )
            )
        )
    ->element ((new General)->content ("The End"))
);
```

```json
[
    {
        "id": "b2bf6bf2-ef04-4a9f-9e5d-fae94424d810",
        "heading": {
            "numbering": 1,
            "context": {
                "en": "INT"
            },
            "setting": {
                "en": "DON CORLEONE'S HOME OFFICE"   
            },
            "sequence": {
                "en": "DAY"
            },
            "description": null
        },
        "authors": [
            "31fcb1b3-ee46-49cb-a41f-a174ceff8d3f"
        ],
        "body": [
            {
                "id": "776c3f9c-7a61-4c55-a645-e2ec94af9bbf",
                "type": "action",
                "content": {
                    "en": "Corleone stands, turning his back toward Bonasera."
                },
                "revisions": [
                    {
                        "id": "23096aee-ddda-4a89-8012-394543b7d451",
                        "parent": null,
                        "index": 0,
                        "authors": [
                            "31fcb1b3-ee46-49cb-a41f-a174ceff8d3f"
                        ],
                        "version": "2.1",
                        "created": "2024-02-17T23:39:12+00:00"
                    }
                ]
            },
            {
                "id": "b8277fef-65f1-4d7f-9c0f-b386fd447e5e",
                "type": "character",
                "content": {
                    "en": "DON CORLEONE"
                }
            },
            {
                "id": "f40aca0f-4793-4eb7-951d-936135b21311",
                "type": "parenthetical",
                "authors": [
                    "31fcb1b3-ee46-49cb-a41f-a174ceff8d3f"
                ],
                "content": {
                    "en": "turning, looking around"
                }
            },
            {
                "id": "9dd34f80-1460-4a32-b852-ab9439130a70",
                "type": "dialogue",
                "origin": "O.C",
                "dual": false,
                "content": {
                    "en": "You come to me on the day of my daughter's wedding, and ask me to do a murder.",
                    "fr": "Tu es venu me voir le jour du mariage de ma fille, et tu m'as demand\u00e9 de commettre un meurtre."
                },
                "perspective": "2D",
                "interactivity": false,
                "fov": 40,
                "lang": "en",
                "charset": "utf8",
                "dir": "ltr",
                "omitted": false,
                "locked": false,
                "dom": "p",
                "css": "dialogue-para col-12 m-3",
                "access": [
                    "author",
                    "editor"
                ],
                "styles": [
                    "courier-12-normal"
                ],
                "meta": {
                    "foo": "bar"
                }
            },
            {
                "id": "71de4bb5-c543-40c1-a512-10a873bbb799",
                "type": "transition",
                "content": {
                    "en": "SLOW PAN TO:"
                }
            },
            {
                "id": "1f27e2a6-e3fa-41fd-b27f-42983370f07e",
                "type": "action",
                "content": {
                    "en": "Bonasera kisses his hand."
                }
            },
            {
                "id": "c6ab2efc-bc75-481b-b46d-9ad40d7d263f",
                "type": "shot",
                "annotations": [
                    {
                        "id": "3e5390e2-e124-45d8-9e8a-ad3814a78377",
                        "parent": null,
                        "highlight": [],
                        "contributor": "31fcb1b3-ee46-49cb-a41f-a174ceff8d3f",
                        "content": {
                            "en": "Here is a note"
                        },
                        "color": "green",
                        "created": "2024-02-17T23:39:12+00:00"
                    }
                ],
                "content": {
                    "en": "CLOSE UP ON HAND"
                },
                "contributors": [
                    "31fcb1b3-ee46-49cb-a41f-a174ceff8d3f"
                ]
            },
            {
                "id": "7f38f288-c7e9-43a7-96ed-e211e64ff64b",
                "type": "general",
                "content": {
                    "en": "The End"
                }
            }
        ],
        "animals": [
            "cat"
        ],
        "cast": [
            "DON CORLEONE",
            "BONASERA"
        ],
        "locations": [
            "DON CORLEONE'S HOME OFFICE"
        ],
        "created": "2024-02-17T23:39:12+00:00",
        "modified": "2024-02-17T23:39:12+00:00",
        "meta": {
            "foo": "bar"
        }
    }
]
```