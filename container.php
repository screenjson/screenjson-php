<?php 

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

$screenplay = new Screenplay (
    new Title ('My New Screenplay') , [
    'guid'    => 'rfc4122',
    'lang'    => 'en',
    'locale'  => 'en_GB',
    'charset' => 'utf8',
    'dir'     => 'ltr'
]);

$screenplay
->author ($author_a = (new Author ('Joe', 'Bloggs', ['writer']))->meta (['imdb' => 'wga123098']))
->author ($author_b = (new Author ('Jane', 'Smith', ['reviser']))->meta (['imdb' => 'wga8765']))
->contributor ($contributor_a = new Contributor ('Fred', 'Thompson', ['editor']))
->contributor ($contributor_b = new Contributor ('Alice', 'Bobster', ['doctor']))
->registration (new Registration ('WGA', 'ABC123DEF789', Carbon::parse ('2004-02-12T15:19:21+00:00'), Carbon::parse ('2004-02-12T15:19:21+00:00')))
->derivation ($derivation = (new Derivation ('movie', new Content ('The Godfather')))->meta (['imdb' => 'tl64765h']))
->license (new License ('CC-BY-NC-ND-4.0', 'https://spdx.org/licenses'))
->encryption (new Encryption ('aes-256-ctr', 'sha256', 'base64'))
->status (new Status ('blue', 2, Carbon::now()))
->revision (new Revision ('14', 3, [$author_a->id()]))
->bookmark (new Bookmark (new Content ('Important scene'), new Content ('Need to look at this again'), 'action', 11, 0))
->bookmark (new Bookmark (new Content ('Middle act'), new Content ('Does this have to be so sleepy?'), 'dialogue', 2, 1))
->style (new Style ('courier-normal-12', 'font-family: courier; font-size: 12px;', 1))
->style (new Style ('courier-bold-12', 'font-family: courier; font-size: 12px; font-weight: bold;', 0))
->taggable (['foo', 'bar', 'abc', 'def', 'something'])
->color (new Color ('black', [0, 0, 0], '#000'))
->color (new Color ('white', [255, 255, 255], '#FFF'))
->color (new Color ('pink', [255, 192, 203], '#FFC0CB'))
->annotation (new Annotation (new Content ('Not for external use.'), $author_a->id(), 'pink', Carbon::now(), [10, 18]))
->meta (['foo' => 'bar', 'lorem' => 'epsom'])
->cover ( new Cover (
    new Title ('My New Story'), 
    new Content ('Dedicated to JFK'), 
    [$derivation->id()], 
    [$author_a->id(), $author_b->id()])
)
->header (new Header (new Content('My New Screenplay: An Adventure')), 1, false, 1, [0])
->footer (new Footer (new Content ('Copyright :YYYY Joe Bloggs. All Rights Reserved/')), 1, false, 1, [0]);


/*
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
*/
echo json_encode ( $screenplay, JSON_PRETTY_PRINT );
die();