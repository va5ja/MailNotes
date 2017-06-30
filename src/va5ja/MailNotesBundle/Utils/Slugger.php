<?php

namespace va5ja\MailNotesBundle\Utils;

/**
 * Class Slugger
 * @package va5ja\MailNotesBundle\Utils
 */
class Slugger
{
    /**
     * @param $string
     * @return string
     */
    public function slugify($string)
    {
        $rules = 'Any-Latin; Latin-ASCII; Lower();';
        $transliterator = \Transliterator::create($rules);
        $string = $transliterator->transliterate($string);

        return trim(preg_replace(
            ['/[^a-z0-9-]/', '/-{2,}/'],
            '-',
            $string
        ), '-');
    }
}