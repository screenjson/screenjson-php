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
        'hex'       => 'hexRgbColor',
        'lang'      => 'countryCode',
        'url'       => 'url',
    ];

    protected array $errors = [
        'above_zero'    => ":field may not be lower than zero.",
        'alpha_dash'    => ":field may only be letters, numbers, dashes, and underscores.",
        'blank'         => ":field can not be blank.",
        'bool_type'     => ':field must be a true/false boolean.',
        'bool_val'      => ':field must be a true/false boolean.',
        'future'        => ":field can not be in the future. ",
        'hex'           => ":field must be a valid hex color value.",
        'in'            => ":field must be one of: ",
        'url'           => ":field must be a valid URL.",
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
    
                    continue;
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
                        if ( in_array ($value, $allowed) )
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

                default:

                    if (! v::{$this->funcs[$rule]}()->validate ($value) )
                    {
                        throw new UnexpectedValueException (str_replace (':field', $field, $this->errors[$rule]));
                    }
                    
                break;
            }

        }

        return true;
    }
}