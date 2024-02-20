<?php 

namespace ScreenJSON;

use ScreenJSON\Interfaces\AuthorInterface;

use \UnexpectedValueException;
use Respect\Validation\Validator as v;

class Cop 
{
    protected array $funcs = [
        'blank'     => 'notEmpty',
        'bool_type' => 'boolType',
        'bool_val'  => 'boolVal',
        'exists'    => 'exists',
        'file'      => 'file',
        'hex'       => 'hexRgbColor',
        'readable'  => 'readable',
        'url'       => 'url',
        'writable'  => 'writable',
    ];

    protected array $errors = [
        'above_zero'    => ":field may not be lower than zero.",
        'alpha_dash'    => ":field may only be letters, numbers, dashes, and underscores.",
        'blank'         => ":field can not be blank.",
        'bool_type'     => ':field must be a true/false boolean.',
        'bool_val'      => ':field must be a true/false boolean.',
        'exists'        => ":field must exist on disk.",
        'file'          => ":field must be a valid file.",
        'future'        => ":field can not be in the future. ",
        'hex'           => ":field must be a valid hex color value.",
        'lang'          => ":field must be a valid language/country code.",
        'in'            => ":field must be one of: ",
        'mime_json'     => ":field must have a json mime type",
        'mime_pdf'      => ":field must have a pdf mime type",
        'mime_zip'      => ":field must have a zip mime type",
        'readable'      => ":field must be readable.",
        'url'           => ":field must be a valid URL.",
        'writable'      => ":field must be writable.",
    ];

    public function check (string $field, mixed $value, array $rules = [], array $allowed = [])
    {
        foreach ($rules AS $rule)
        {
            switch ($rule)
            {
                case 'above_zero':

                    if (! v::intVal()->min(0)->validate ($value) )
                    {
                        throw new UnexpectedValueException (str_replace (':field', $field, $this->errors[$rule]));
                    }

                break;

                case 'alpha_dash':

                    if (! v::alnum(' -_')->validate ($value) )
                    {
                        throw new UnexpectedValueException (str_replace (':field', $field, $this->errors[$rule]));
                    }

                break;

                case 'array_lang_content':

                    if (count ($value) < 1 )
                    {
                        throw new UnexpectedValueException ("Content objects must have at least 1 language-keyed entry.");
                    }

                    foreach ($value AS $lang => $text)
                    {
                        if (! v::countryCode('alpha-2')->validate (mb_strtoupper($lang)) )
                        {
                            throw new UnexpectedValueException ($lang." is not a valid ISO country or language.");
                        }

                        if (! v::arrayVal()->notEmpty()->validate ($text) )
                        {
                            throw new UnexpectedValueException ("Content values must not be empty.");
                        }
                    }

                break;

                case 'array_rgb':

                    if (count ($value) != 3)
                    {
                        throw new UnexpectedValueException ("RGB must have 3 integer values.");
                    }

                    foreach ($value AS $v)
                    {
                        if (! v::intVal()->between(0, 255)->validate ($value) )
                        {
                            throw new UnexpectedValueException ("RGB values must be between 0 and 255.");
                        }
                    }

                break;

                case 'array_slugs':
                    
                    if (! v::arrayVal()->notEmpty()->validate ($value) )
                    {
                        throw new UnexpectedValueException ("Array must have items and cannot be empty.");
                    }

                    foreach ($value AS $v)
                    {
                        if (! v::alnum(' -_')->validate ($v) )
                        {
                            throw new UnexpectedValueException ("Array items may only be letters, numbers, dashes, and underscores.");
                        }   
                    }

                break;

                case 'array_uuids':
                    
                    if (! v::arrayVal()->notEmpty()->validate ($value) )
                    {
                        throw new UnexpectedValueException ("Array must have items and cannot be empty.");
                    }

                    foreach ($value AS $v)
                    {
                        if (! v::uuid()->validate ($v) )
                        {
                            throw new UnexpectedValueException ("Array items may UUIDs.");
                        }   
                    }

                break;

                case 'degrees':

                    if (! v::intVal()->between(0, 360)->validate ($value) )
                    {
                        throw new UnexpectedValueException ("Degree values must be between 0 and 360.");
                    }

                break;

                case 'future':

                    if ( v::greaterThan($value->timestamp)->validate(time()) )
                    {
                        throw new UnexpectedValueException (str_replace (':field', $field, $this->errors[$rule]));
                    }

                break;

                case 'in':

                    if ( count ($allowed) )
                    {
                        if (! in_array ($value, $allowed) )
                        {
                            throw new UnexpectedValueException (str_replace (':field', $field, $this->errors[$rule]). implode (', ', $allowed));
                        }
                    }

                break;

                case 'key_slugs':

                    if (count ($value) < 1)
                    {
                        throw new UnexpectedValueException ("Array cannot be empty.");
                    }

                    foreach ($value AS $v)
                    {
                        if (! v::alnum(' -_')->validate ($k) )
                        {
                            throw new UnexpectedValueException ("Array keys may only be letters, numbers, dashes, and underscores.");
                        }   
                    }

                break;

                case 'lang':
                    
                    $langs = ['ab','aa','af','ak','sq','am','ar','an','hy','as','av','ae','ay','az','bm','ba','eu','be','bn','bh','bi','bs','br','bg','my','ca','km','ch','ce','ny','zh','cu','cv','kw','co','cr','hr','cs','da','dv','nl','dz','en','eo','et','ee','fo','fj','fi','fr','ff','gd','gl','lg','ka','de','ki','el','kl','gn','gu','ht','ha','he','hz','hi','ho','hu','is','io','ig','id','ia','ie','iu','ik','ga','it','ja','jv','kn','kr','ks','kk','rw','kv','kg','ko','kj','ku','ky','lo','la','lv','lb','li','ln','lt','lu','mk','mg','ms','ml','mt','gv','mi','mr','mh','ro','mn','na','nv','nd','ng','ne','se','no','nb','nn','ii','oc','oj','or','om','os','pi','pa','ps','fa','pl','pt','qu','rm','rn','ru','sm','sg','sa','sc','sr','sn','sd','si','sk','sl','so','st','nr','es','su','sw','ss','sv','tl','ty','tg','ta','tt','te','th','bo','ti','to','ts','tn','tr','tk','tw','ug','uk','ur','uz','ve','vi','vo','wa','cy','fy','wo','xh','yi','yo','za','zu'];
                
                    if (! in_array ($value, $langs) ) 
                    {
                        throw new UnexpectedValueException (str_replace (':field', $field, $this->errors[$rule]));
                    }

                break;

                case 'mime_json':

                    if (! v::mimetype('application/json')->validate ($value) )
                    {
                        throw new UnexpectedValueException (str_replace (':field', $field, $this->errors[$rule]));
                    }
                
                break;

                case 'mime_pdf':

                    if (! v::mimetype('application/pdf')->validate ($value) )
                    {
                        throw new UnexpectedValueException (str_replace (':field', $field, $this->errors[$rule]));
                    }
                
                break;

                case 'mime_zip':

                    if (! v::mimetype('application/zip')->validate ($value) )
                    {
                        throw new UnexpectedValueException (str_replace (':field', $field, $this->errors[$rule]));
                    }
                
                break;

                default:

                    if (! v::{$this->funcs[$rule]}()->validate ($value) )
                    {
                        throw new UnexpectedValueException (str_replace (':field', $field, $this->errors[$rule] ?? ''));
                    }
                    
                break;
            }

        }

        return true;
    }
}