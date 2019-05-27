<?php

use Illuminate\Support\Facades\DB;

if (!function_exists('isValidXml')) {
    /**
     * XML string validation.
     *
     * @param $content
     *
     * @return bool
     */
    function isValidXml($content)
    {
        $content = trim($content);
        if (empty($content)) {
            return false;
        }
        if (stripos($content, '<!DOCTYPE html>') !== false) {
            return false;
        }
        libxml_use_internal_errors(true);
        simplexml_load_string($content);
        $errors = libxml_get_errors();
        libxml_clear_errors();

        return empty($errors);
    }
}

if (!function_exists('ddd')) {
    /**
     * Dump the passed variables and end the script.
     * Quick fix for not rendering dd() in browser's network tab.
     *
     * @param array $args
     */
    function ddd($args)
    {
        http_response_code(500);
        call_user_func_array('dd', $args);
    }
}
if (!function_exists('ddd_query')) {
    $_global_query_count = 0;
    /**
     * Dump the next database query.
     * Quick fix for not rendering dd_query() in browser's network tab.
     *
     * @param int $count
     *
     * @return void
     */
    function ddd_query($count = 1)
    {
        DB::listen(function ($query) use ($count) {
            global $_global_query_count;

            while (strpos($query->sql, '?')) {
                $query->sql = preg_replace('/\?/', '"' . array_shift($query->bindings) . '"', $query->sql, 1);
            }

            if (++$_global_query_count == $count) {
                dd($query->sql);
            } else {
                d($query->sql);
            }
        });
    }
}
if (!function_exists('domNodeToArray')) {
    function domNodeToArray($node)
    {
        $output = [];
        switch ($node->nodeType) {
            case XML_CDATA_SECTION_NODE:
            case XML_TEXT_NODE:
                $output = trim($node->textContent);
                break;
            case XML_ELEMENT_NODE:
                for ($i = 0, $m = $node->childNodes->length; $i < $m; $i++) {
                    $child = $node->childNodes->item($i);
                    $v     = domNodeToArray($child);
                    if (isset($child->tagName)) {
                        $t = $child->tagName;
                        if (!isset($output[$t])) {
                            $output[$t] = [];
                        }
                        $output[$t][] = $v;
                    } elseif ($v || $v === '0') {
                        $output = (string)$v;
                    }
                }
                if ($node->attributes->length && !is_array($output)) { // Has attributes but isn't an array
                    $output = ['@content' => $output]; // Change output into an array.
                }
                if (is_array($output)) {
                    if ($node->attributes->length) {
                        $a = [];
                        foreach ($node->attributes as $attrName => $attrNode) {
                            $a[$attrName] = (string)$attrNode->value;
                        }
                        $output['@attributes'] = $a;
                    }
                    foreach ($output as $t => $v) {
                        if (is_array($v) && count($v) === 1 && $t !== '@attributes') {
                            $output[$t] = $v[0];
                        }
                    }
                }
                break;
        }

        return $output;
    }
}

if (!function_exists('xmlToArray')) {

    function xmlToArray(string $xmlstr)
    {
        $doc = new \DOMDocument();
        $doc->loadXML($xmlstr);

        $root   = $doc->documentElement;
        $output = domNodeToArray($root);

        return $output;
    }
}
