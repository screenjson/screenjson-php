# Using the Screenplay Container

## Writing the global basics

```php
require_once ('vendor/autoload.php');

use ScreenJSON\Screenplay;
use ScreenJSON\Document\Title;

$screenplay = new Screenplay (
    new Title ('My New Screenplay') , [
    'guid'    => 'rfc4122',
    'lang'    => 'en',
    'locale'  => 'en_GB',
    'charset' => 'utf8',
    'dir'     => 'ltr'
])
```

## List of all major getter methods

```php
require_once ('vendor/autoload.php');

echo json_encode ( $screenplay->annotations(), JSON_PRETTY_PRINT );
echo json_encode ( $screenplay->authors(), JSON_PRETTY_PRINT );
echo json_encode ( $screenplay->bookmarks(), JSON_PRETTY_PRINT);
echo json_encode ( $screenplay->charset(), JSON_PRETTY_PRINT);
echo json_encode ( $screenplay->colors(), JSON_PRETTY_PRINT );
echo json_encode ( $screenplay->contributors(), JSON_PRETTY_PRINT );
echo json_encode ( $screenplay->cover(), JSON_PRETTY_PRINT );
echo json_encode ( $screenplay->derivations(), JSON_PRETTY_PRINT );
echo json_encode ( $screenplay->dir(), JSON_PRETTY_PRINT );
echo json_encode ( $screenplay->encryption(), JSON_PRETTY_PRINT );
echo json_encode ( $screenplay->encryption()->ciphers(), JSON_PRETTY_PRINT );
echo json_encode ( $screenplay->encryption()->encodings(), JSON_PRETTY_PRINT );
echo json_encode ( $screenplay->encryption()->hashes(), JSON_PRETTY_PRINT );
echo json_encode ( $screenplay->footer(), JSON_PRETTY_PRINT );
echo json_encode ( $screenplay->guid(), JSON_PRETTY_PRINT );
echo json_encode ( $screenplay->header(), JSON_PRETTY_PRINT );
echo json_encode ( $screenplay->lang(), JSON_PRETTY_PRINT );
echo json_encode ( $screenplay->license(), JSON_PRETTY_PRINT );
echo json_encode ( $screenplay->locale(), JSON_PRETTY_PRINT );
echo json_encode ( $screenplay->meta(), JSON_PRETTY_PRINT );
echo json_encode ( $screenplay->registrations(), JSON_PRETTY_PRINT );
echo json_encode ( $screenplay->revisions(), JSON_PRETTY_PRINT );
echo json_encode ( $screenplay->status(), JSON_PRETTY_PRINT );
echo json_encode ( $screenplay->taggable(), JSON_PRETTY_PRINT );
echo json_encode ( $screenplay->title(), JSON_PRETTY_PRINT );
```

## Adding authors and Contributors

```php
require_once ('vendor/autoload.php');

use ScreenJSON\Author;
use ScreenJSON\Contributor;

$author_a = (new Author ('Joe', 'Bloggs', ['writer']))->meta (['imdb' => 'wga123098']);
$author_b = (new Author ('Jane', 'Smith', ['reviser']))->meta (['imdb' => 'wga8765']);

$contributor_a = new Contributor ('Fred', 'Thompson', ['editor']);
$contributor_b = new Contributor ('Alice', 'Bobster', ['doctor']);

$screenplay
    ->author ($author_a)
    ->author ($author_b)
    ->contributor ($contributor_a)
    ->contributor ($contributor_b)

var_dump ($author_a->id());
var_dump ($contributor_b->id());
```

## Adding legal information

```php
require_once ('vendor/autoload.php');

use Carbon\Carbon;
use ScreenJSON\Content;
use ScreenJSON\Derivation;
use ScreenJSON\License;
use ScreenJSON\Registration;

$derivation_a = (new Derivation ('movie', new Content ('The Godfather')))->meta (['imdb' => 'tl64765h']);

$screenplay
    ->registration (
        new Registration (
            'WGA', 'ABC123DEF789', Carbon::parse ('2004-02-12T15:19:21+00:00'), Carbon::parse ('2004-02-12T15:19:21+00:00')
        )
    )
    ->registration (
        new Registration (
            'BBC', '282464849', Carbon::parse ('2008-12-12T15:19:21+00:00'), Carbon::parse ('2008-12-12T15:19:21+00:00')
        )
    )
    ->derivation ($derivation_a)
    ->license (new License ('CC-BY-NC-ND-4.0', 'https://spdx.org/licenses'));

var_dump ($derivation->id());
```

## Add a cover page

```php
require_once ('vendor/autoload.php');

use ScreenJSON\Content;

use ScreenJSON\Document\{
    Cover,
    Title
};

$author_a = (new Author ('Joe', 'Bloggs', ['writer']))->meta (['imdb' => 'wga123098']);
$author_b = (new Author ('Jane', 'Smith', ['reviser']))->meta (['imdb' => 'wga8765']);

$derivation_a = (new Derivation ('movie', new Content ('The Godfather')))->meta (['imdb' => 'tl64765h']);

$screenplay->cover ( new Cover (
    new Title ('My New Story'), 
    new Content ('Dedicated to JFK'), 
    [$derivation->id()], 
    [$author_a->id(), $author_b->id()])
)
```

## Add header and footer

```php
require_once ('vendor/autoload.php');

use ScreenJSON\Content;

use ScreenJSON\Document\{
    Header,
    Footer
};

$screenplay
    ->header (
        new Header (
            new Content('My New Screenplay: An Adventure'), 1, false, 1, [0]
        )
    )
    ->footer (
        new Footer (
            new Content ('Copyright :YYYY Joe Bloggs. All Rights Reserved/'), 1, false, 1, [0]
        )
    );
```

## Specifying visual information (styles, colors etc)

```php
require_once ('vendor/autoload.php');

use ScreenJSON\Style;
use ScreenJSON\Color;

$screenplay
    ->style (new Style ('courier-normal-12', 'font-family: courier; font-size: 12px;', 1))
    ->style (new Style ('courier-bold-12', 'font-family: courier; font-size: 12px; font-weight: bold;', 0))
    ->color (new Color ('black', [0, 0, 0], '#000'))
    ->color (new Color ('white', [255, 255, 255], '#FFF'))
    ->color (new Color ('pink', [255, 192, 203], '#FFC0CB'))
```

## Adding encryption data

### List all available ciphers

```php
require_once ('vendor/autoload.php');

use ScreenJSON\Encryption;

var_dump ((new Encryption)->ciphers());
```

### List all available encodings

```php
require_once ('vendor/autoload.php');

use ScreenJSON\Encryption;

var_dump ((new Encryption)->encodings());
```

### List all available hashes
```php
require_once ('vendor/autoload.php');

use ScreenJSON\Encryption;

var_dump ((new Encryption)->hashes());
```

### Set the encryption

```php
require_once ('vendor/autoload.php');

use ScreenJSON\Encryption;

$screenplay->encryption (new Encryption ('aes-256-ctr', 'sha256', 'base64'));
```

## Adding metadata: annotations, bookmarks, status etc

```php
require_once ('vendor/autoload.php');

use ScreenJSON\{
    Annotation,
    Bookmark,
    Content,
    Status
};

use Carbon\Carbon;

$author_a = (new Author ('Joe', 'Bloggs', ['writer']))->meta (['imdb' => 'wga123098']);
$author_b = (new Author ('Jane', 'Smith', ['reviser']))->meta (['imdb' => 'wga8765']);

$screenplay
    ->status (new Status ('blue', 2, Carbon::now()))
    ->revision (new Revision ('14', 3, [$author_a->id()]))
    ->bookmark (new Bookmark (new Content ('Important scene'), new Content ('Need to look at this again'), 'action', 11, 0))
    ->bookmark (new Bookmark (new Content ('Middle act'), new Content ('Does this have to be so sleepy?'), 'dialogue', 2, 1))
    ->annotation (new Annotation (new Content ('Not for external use.'), $author_a->id(), 'pink', Carbon::now(), [10, 18]))
    ->taggable (['foo', 'bar', 'abc', 'def', 'something'])
    ->meta (['foo' => 'bar', 'lorem' => 'epsom']);
```

## Full example

### Verbose object building

```php 
require_once ('vendor/autoload.php');

use ScreenJSON\{
    Annotation,
    Author,
    Color,
    Content,
    Contributor,
    Derivation,
    Encryption,
    License,
    Registration,
    Revision,
    Screenplay,
    Status,
    Style
};

use ScreenJSON\Document\{
    Bookmark,
    Cover, 
    Header,
    Footer,
    Title
};

use Carbon\Carbon;

// Basics
$screenplay = new Screenplay (
    new Title ('My New Screenplay') , [
    'guid'    => 'rfc4122',
    'lang'    => 'en',
    'locale'  => 'en_GB',
    'charset' => 'utf8',
    'dir'     => 'ltr'
]);

// Authorship
$screenplay
    ->author (
        $author_a = (
            new Author ('Joe', 'Bloggs', ['writer'])
        )->meta (['imdb' => 'wga123098'])
    )
    ->author (
        $author_b = (
            new Author ('Jane', 'Smith', ['reviser'])
        )->meta (['imdb' => 'wga8765'])
    )
    ->contributor (
        $contributor_a = new Contributor ('Fred', 'Thompson', ['editor'])
    )
    ->contributor (
        $contributor_b = new Contributor ('Alice', 'Bobster', ['doctor'])
    );

// Legalities
$screenplay
    ->registration (
        new Registration (
            'WGA', 'ABC123DEF789', Carbon::parse ('2004-02-12T15:19:21+00:00'), Carbon::parse ('2004-02-12T15:19:21+00:00')
        )
    )
    ->registration (
        new Registration (
            'BBC', '282464849', Carbon::parse ('2008-12-12T15:19:21+00:00'), Carbon::parse ('2008-12-12T15:19:21+00:00')
        )
    )
    ->derivation (
        (new Derivation ('movie', new Content ('The Godfather')))
            ->meta (['imdb' => 'tl64765h'])
    )
    ->license (new License ('CC-BY-NC-ND-4.0', 'https://spdx.org/licenses'))
    ->encryption (new Encryption ('aes-256-ctr', 'sha256', 'base64'));

// Cover, header, footer
$screenplay
    ->cover ( 
        new Cover (
            new Title ('My New Story'), 
            new Content ('Dedicated to JFK'), 
            [$derivation->id()], 
            [$author_a->id(), $author_b->id()]
        )
    )
    ->header (
        new Header (
            new Content('My New Screenplay: An Adventure'), 1, false, 1, [0]
        )
    )
    ->footer (
        new Footer (
            new Content ('Copyright :YYYY Joe Bloggs. All Rights Reserved/'), 1, false, 1, [0]
        )
    );

// Metadata 
$screenplay
    ->status (new Status ('blue', 2, Carbon::now()))
    ->revision (
        new Revision (
            '14', 3, [$author_a->id()]
        )
    )
    ->bookmark (
        new Bookmark (
            new Content ('Important scene'), new Content ('Need to look at this again'), 'action', 11, 0
        )
    )
    ->bookmark (
        new Bookmark (
            new Content ('Middle act'), new Content ('Does this have to be so sleepy?'), 'dialogue', 2, 1
        )
    )
    ->annotation (
        new Annotation (
            new Content ('Not for external use.'), $author_a->id(), 'pink', Carbon::now(), [10, 18]
        )
    )
    ->taggable (['foo', 'bar', 'abc', 'def', 'something'])
    ->meta (['foo' => 'bar', 'lorem' => 'epsom']);

// Styling instructions
$screenplay
    ->style (new Style ('courier-normal-12', 'font-family: courier; font-size: 12px;', 1))
    ->style (new Style ('courier-bold-12', 'font-family: courier; font-size: 12px; font-weight: bold;', 0))
    ->color (new Color ('black', [0, 0, 0], '#000'))
    ->color (new Color ('white', [255, 255, 255], '#FFF'))
    ->color (new Color ('pink', [255, 192, 203], '#FFC0CB'));
```

### Result

```json
{
    "id": "75c47af1-f821-4d7b-b630-b2f7aeb6fe1d",
    "guid": "rfc4122",
    "title": {
        "en": "My New Screenplay"
    },
    "lang": "en",
    "locale": "en_GB",
    "charset": "utf8",
    "dir": "ltr",
    "authors": [
        {
            "id": "d054c111-f234-47ce-ad8c-962a4cd4cc1e",
            "given": "Joe",
            "family": "Bloggs",
            "roles": [
                "writer"
            ],
            "meta": {
                "imdb": "wga123098"
            }
        },
        {
            "id": "961e40ca-4f65-48f0-b002-29d3d7768cef",
            "given": "Jane",
            "family": "Smith",
            "roles": [
                "reviser"
            ],
            "meta": {
                "imdb": "wga8765"
            }
        }
    ],
    "colors": [
        {
            "title": "black",
            "rgb": [
                0,
                0,
                0
            ],
            "hex": "##000"
        },
        {
            "title": "white",
            "rgb": [
                255,
                255,
                255
            ],
            "hex": "##FFF"
        },
        {
            "title": "pink",
            "rgb": [
                255,
                192,
                203
            ],
            "hex": "##FFC0CB"
        }
    ],
    "contributors": [
        {
            "id": "8b41e175-d212-4659-a35d-d1db2cbb8872",
            "given": "Fred",
            "family": "Thompson",
            "roles": [
                "editor"
            ]
        },
        {
            "id": "d7fff949-882e-42cb-932f-ad4767b51d7c",
            "given": "Alice",
            "family": "Bobster",
            "roles": [
                "doctor"
            ]
        }
    ],
    "derivations": [
        {
            "id": "52b2badb-953f-4c95-a83b-a8a92e3a58f4",
            "type": "movie",
            "title": {
                "en": "The Godfather"
            },
            "meta": {
                "imdb": "tl64765h"
            }
        }
    ],
    "document": {
        "bookmarks": [
            {
                "id": "e243e41d-ddea-459b-8acd-621121af0d9e",
                "parent": null,
                "scene": 11,
                "type": "action",
                "element": 0,
                "title": {
                    "en": "Important scene"
                },
                "description": {
                    "en": "Need to look at this again"
                }
            },
            {
                "id": "d12e1e67-e457-48f5-8f98-416566414bf9",
                "parent": null,
                "scene": 2,
                "type": "dialogue",
                "element": 1,
                "title": {
                    "en": "Middle act"
                },
                "description": {
                    "en": "Does this have to be so sleepy?"
                }
            }
        ],
        "cover": {
            "title": {
                "en": "My New Story"
            },
            "authors": [
                "d054c111-f234-47ce-ad8c-962a4cd4cc1e",
                "961e40ca-4f65-48f0-b002-29d3d7768cef"
            ],
            "derivations": [
                "52b2badb-953f-4c95-a83b-a8a92e3a58f4"
            ],
            "additional": {
                "en": "Dedicated to JFK"
            }
        },
        "header": {
            "cover": false,
            "display": true,
            "start": 1,
            "omit": [],
            "content": {
                "en": "My New Screenplay: An Adventure"
            }
        },
        "footer": {
            "cover": false,
            "display": true,
            "start": 1,
            "omit": [],
            "content": {
                "en": "Copyright :YYYY Joe Bloggs. All Rights Reserved\/"
            }
        },
        "status": {
            "color": "blue",
            "round": 2,
            "updated": "2024-02-17T21:06:11+00:00"
        },
        "styles": [
            {
                "id": "courier-12",
                "default": true,
                "content": "font-family: courier; font-size: 12px;"
            },
            {
                "id": "courier-normal-12",
                "default": true,
                "content": "font-family: courier; font-size: 12px;"
            },
            {
                "id": "courier-bold-12",
                "default": false,
                "content": "font-family: courier; font-size: 12px; font-weight: bold;"
            }
        ],
        "templates": [],
        "scenes": []
    },
    "encryption": {
        "cipher": "aes-256-ctr",
        "hash": "sha256",
        "encoding": "base64"
    },
    "license": {
        "identifier": "CC-BY-NC-ND-4.0",
        "ref": "https:\/\/spdx.org\/licenses"
    },
    "registrations": [
        {
            "authority": "WGA",
            "identifier": "ABC123DEF789",
            "created": "2004-02-12T15:19:21.000000Z",
            "modified": "2004-02-12T15:19:21.000000Z"
        }
    ],
    "revisions": [
        {
            "id": "f05f67e1-188c-49f2-9a3f-0d901e8e791a",
            "parent": null,
            "index": 3,
            "authors": [
                "d054c111-f234-47ce-ad8c-962a4cd4cc1e"
            ],
            "version": "14",
            "created": "2024-02-17T21:06:11+00:00"
        }
    ],
    "taggable": [
        "foo",
        "bar",
        "abc",
        "def",
        "something"
    ],
    "meta": {
        "foo": "bar",
        "lorem": "epsom"
    }
}
```