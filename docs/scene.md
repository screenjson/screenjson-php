# Using Scene Comtainers: Sluglines, Tagging etc

## Basic example

```php
require_once ('vendor/autoload.php');

use ScreenJSON\Screenplay;
use ScreenJSON\Content;
use ScreenJSON\Document\Title;

use ScreenJSON\Document\Scene;
use ScreenJSON\Document\Scene\Heading;

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
);
```

### Adding tagged things for production breakdowns 

```php 

$screenplay->scene (
    $scene = (
        new Scene (
            $heading = new Heading (
                new Content ('INT'), 
                new Content ("DON CORLEONE'S HOME OFFICE"),
                new Content ('DAY'), 
            )
        )
    )   
    ->animals ('cat')
    ->animals (['dog', 'horse'])
    ->cast ('DON CORLEONE')
    ->cast (['BONASERA', 'HENCHMAN A'])
    ->locations ("DON CORLEONE'S HOME OFFICE")
    ->moods (['dark', 'wedding', 'brooding'])
    ->props ('Gun')
    ->sfx ('Blood')
    ->sounds (['Purring', 'Dancing'])
    ->tags (['foo', 'bar'])
    ->vfx ('titling')
    ->wardrobe ('tuxedo')
)
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
);
```

## Example serialised scene output

```json
{
    "id": "746f9765-e108-4317-a721-4a3582d1b74f",
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
        "e72b6904-7194-4796-82e2-5219dafe0238"
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
    "created": "2024-02-17T22:32:56+00:00",
    "modified": "2024-02-17T22:32:56+00:00"
}
```