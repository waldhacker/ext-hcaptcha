<?php

declare(strict_types=1);

/*
 * This file is part of the hcaptcha extension for TYPO3
 * - (c) 2021 waldhacker UG (haftungsbeschränkt)
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace Waldhacker\Hcaptcha\Tests\Functional\Form;

class DataExtractor
{
    private $html;

    public function __construct(string $html)
    {
        $this->html = $html;
    }

    public function getFormData(string $query = '//form'): array
    {
        $fragment = $this->extractFormFragment($this->html, $query);
        return $this->extractFormData($fragment);
    }

    private function extractFormData(\DOMDocument $document): array
    {
        $data = [
            'actionData' => [],
            'elementData' => [],
        ];
        foreach ($document->getElementsByTagName('form') as $node) {
            $action = $node->getAttribute('action');
            $actionQuery = parse_url($action, PHP_URL_QUERY);
            $queryArray = [];
            parse_str($actionQuery, $queryArray);
            $data['actionData'] = $queryArray;

            break;
        }

        $xpath = new \DomXPath($document);
        $nodesWithName = $xpath->query('//*[@name]');
        foreach ($nodesWithName as $node) {
            $name = $node->getAttribute('name');
            foreach ($node->attributes ?? [] as $attribute) {
                $data['elementData'][$name][$attribute->nodeName] = $attribute->nodeValue;
            }
        }

        return $data;
    }

    private function extractFormFragment(string $html, string $query): \DOMDocument
    {
        $document = new \DOMDocument();
        $document->loadHTML($html, \LIBXML_NOERROR);

        $xpath = new \DomXPath($document);
        $fragment = new \DOMDocument();
        foreach ($xpath->query($query) as $node) {
            $fragment->appendChild($fragment->importNode($node, true));
        }

        return $fragment;
    }
}
