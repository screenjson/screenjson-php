<?php 

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

    /*
$screenplay->scene (
    $scene = (new Scene ($heading = new Heading (new Content ('INT'), new Content ("DON CORLEONE'S HOME OFFICE"), new Content ('DAY'), 1, 8)))
        ->element ((new Action)->content ("Corleone stands, turning his back toward Bonasera."))
        ->element ((new Character)->content ('DON CORLEONE'))
        ->element ((new Parenthetical)->content ('turning, looking around'))
        ->element ((new Dialogue ('O.C', false))->content ([
            'en' => "You come to me on the day of my daughter's wedding, and ask me to do a murder.",
            'fr' => "Tu es venu me voir le jour du mariage de ma fille, et tu m'as demandé de commettre un meurtre.",
        ]))
        ->element ((new Transition)->content ('SLOW PAN TO:'))
        ->element ((new Action)->content ("Bonasera kisses his hand.", 'en'))
        ->element ((new Shot)->content ("CLOSE UP ON HAND"))
        ->element ((new General)->content ("The End"))
);
*/

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
        ->revision (new Revision ('2.1', 0, [$author_a->id()]))
    )
    ->element ((new Character)->content ('DON CORLEONE'))
    ->element (new Parenthetical(new Content ('turning, looking around'), ['authors' => [$author_a->id()]]))
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
    ->element ((new Shot (new Content ('CLOSE UP ON HAND'), ['contributors' => [$author_a->id()]]))
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

echo json_encode ($screenplay->scenes(), JSON_PRETTY_PRINT);