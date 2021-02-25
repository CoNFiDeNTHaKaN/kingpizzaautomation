<?php

  $url = "http://curiosityapproach.worldsecuresystems.com/FileRetrieveExternal.aspx?OID=6025&OTYPE=54";
  $file = file_get_contents($url);

function tdrows($elements)
{
    $str = "";
    foreach ($elements as $element) {
        $str .= $element->nodeValue . ", ";
    }

    return $str;
}

function getdata($contents)
{

  $DOM = new DOMDocument;
    $DOM->loadHTML($contents);

    $items = $DOM->getElementsByTagName('tr');

    foreach ($items as $node) {
        echo tdrows($node->childNodes) . "<br />";
    }
}

// echo getdata($file);
