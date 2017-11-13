<?php
/**
 * Created by PhpStorm.
 * User: mark.smith
 * Date: 13/11/2017
 * Time: 08:37
 */
namespace AppBundle\Source;

use Symfony\Component\HttpFoundation\Request;

class Source
{
    public static function factory(Request $request)
    {
        $sourceClassName = "\\AppBundle\\Source\\". ucfirst($request->get('storage'));

        return new $sourceClassName;
    }
}
