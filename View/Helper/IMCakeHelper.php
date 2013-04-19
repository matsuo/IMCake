<?php
/**
 * IMCake Helper
 *
 * Copyright (c) Atsushi Matsuo, Masayuki Nii
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Atsushi Matsuo, Masayuki Nii
 * @link          http://www.famlog.jp/imcake/
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class IMCakeHelper extends AppHelper
{
    protected $defDivider = '|';
    protected $separator = '@';
    protected $noRecordClassName = "_im_for_noresult_";

    protected $linkedNodesCollection = array(); // Collecting linked elements to this array.
    protected $widgetNodesCollection = array();

    protected $titleAsLinkInfo = TRUE;
    protected $classAsLinkInfo = TRUE;
    
    protected $modelClass = "";
    protected $relatedValues = array();
    protected $baseNodes = array();
    
    public function pageConstruct($modelClass, $body, $id)
    {
        $this->modelClass = $modelClass;
    
        $ignoreEnclosureRepeaterClassName = "_im_ignore_enc_rep";
        $rollingRepeaterClassName = "_im_repeater";
        $rollingEnclocureClassName = "_im_enclosure";
        
        $model = ClassRegistry::init($modelClass);
        $record = $model->find("first", array('conditions' => array($modelClass . '.' . $model->primaryKey => $id), 'recursive' => 1));
        
        $doc = new DOMDocument();
        $doc->loadXML($body);
        $xpath = new DOMXPath($doc);
        
        $bodyNodes = $doc->getElementsByTagName('body');
        
        $cloneNodeDataArray = array();
        $appended = array();
        
        foreach ($xpath->query("//*[contains(@class, 'IM[') or contains(@title, '" . $this->separator . "')]") as $currentNode) {
            $attrValue = $currentNode->getAttribute("title");
            if (empty($attrValue)) {
                $attrValue = $currentNode->getAttribute("class");
                if (preg_match('/IM\[(.*)\]/', $attrValue, $matches)) {
                    $attrValue = $matches[1];
                }
            }
            
            if (!empty($attrValue)) {
                $curVal = "";
                
                $modelArray = explode($this->separator, $attrValue);
                $modelName = ucwords($modelArray[0]);
                $fieldName = $modelArray[1];
                
                if (isset($record[$modelName][$fieldName])) {
                    $curVal = $record[$modelName][$fieldName];
                } else if (isset($record[$modelName][0][$fieldName])) {
                    $curVal = $record[$modelName][0][$fieldName];
                }

                $parentEnclosure = $this->_getParentEnclosure($currentNode);
                $parentRepeater = $this->_getParentRepeater($currentNode);
                $repNodeTag = $this->_repeaterTagFromEncTag($parentEnclosure->tagName);
                $repeatersOriginal = $this->_collectRepeatersOriginal($parentEnclosure, $repNodeTag);
                $repeaters = $this->_collectRepeaters($repeatersOriginal);  // Collecting repeaters to this array.
                
                if (isset($record[$modelName][$fieldName])) {
                    $cloneNodeDataArray[$modelName][0][$fieldName] = $record[$modelName][$fieldName];
                }
                
                foreach ($repeaters as $repeater) {
                    if ($modelName != $modelClass) {
                        if (isset($record[$modelName])) {
                            $cloneNodeDataArray[$modelName] = $record[$modelName];
                        }
                    }
                }

                // Set data to the element.
                $curTarget = 'innerHTML';
                $this->_setDataToElement($currentNode, $curTarget, $curVal);
            }
        }
        
        foreach ($xpath->query("//*[contains(@class, 'IM[') or contains(@title, '" . $this->separator . "')]") as $currentNode) {
            $attrValue = $currentNode->getAttribute("title");
            if (empty($attrValue)) {
                $attrValue = $currentNode->getAttribute("class");
                if (preg_match('/IM\[(.*)\]/', $attrValue, $matches)) {
                    $attrValue = $matches[1];
                }
            }

            if (!empty($attrValue)) {
                $modelArray = explode($this->separator, $attrValue);
                $modelName = ucwords($modelArray[0]);
                
                if (isset($cloneNodeDataArray[$modelName])) {;
                    $parentEnclosure = $this->_getParentEnclosure($currentNode);
                    $baseNode = $parentEnclosure->cloneNode(TRUE);
                    foreach ($cloneNodeDataArray[$modelName] as $relatedRecordKey => $relatedRecord) {
                        if ($relatedRecordKey > 0) {
                            $cloneNode = $baseNode->cloneNode(TRUE);
                            if (!in_array($modelName, $appended)) {
                                if ($parentEnclosure->tagName != "select") {
                                    $parentEnclosure->parentNode->appendChild($cloneNode);
                                    if (count($cloneNodeDataArray[$modelName]) == $relatedRecordKey + 1) {
                                        array_push($appended, $modelName);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        
        $settedElements = array();
        foreach ($xpath->query("//*[contains(@class, 'IM[') or contains(@title, '" . $this->separator . "')]") as $currentNode) {
            $attrValue = $currentNode->getAttribute("title");
            if (empty($attrValue)) {
                $attrValue = $currentNode->getAttribute("class");
                if (preg_match('/IM\[(.*)\]/', $attrValue, $matches)) {
                    $attrValue = $matches[1];
                }
            }
            
            if (!empty($attrValue)) {
                $modelArray = explode($this->separator, $attrValue);
                $modelName = ucwords($modelArray[0]);
                $fieldName = $modelArray[1];
                
                if (isset($cloneNodeDataArray[$modelName])) {
                    if ($modelClass != $modelName) {
                        $countArray = array_count_values($settedElements);
                        if (isset($countArray[$attrValue]) && $countArray[$attrValue] > 0) {
                            $relatedCurVal = $cloneNodeDataArray[$modelName][$countArray[$attrValue]][$fieldName];
                            $curTarget = 'innerHTML';
                            $this->_setDataToElement($currentNode, $curTarget, $relatedCurVal);
                        }
                        array_push($settedElements, $attrValue);
                    }
                }
            }
        }
        
        return $doc->saveHTML();
    }
    
    public function partialConstruct($element, $nodeDefs, $curVal)
    {
        $nodeInfo = array();
        
        if (is_array($nodeDefs)) {
            foreach ($nodeDefs as $nodeDef) {
                list($modelName, $fieldName, $type) = explode($this->separator, $nodeDef);
                $nodeInfo[$type] = array('model' => ucwords($modelName), 'field' => $fieldName);
            }
    
            if (!empty($modelName)) {
                $model = ClassRegistry::init(ucwords($modelName));
                if (count($model->hasAndBelongsToMany) > 0) {
                    $records = $model->find("all", array('conditions' => array(), 'recursive' => 1));
                } else {
                    $records = $model->find("all", array('conditions' => array($model->primaryKey => $curVal), 'recursive' => 2));
                }

                $val = array();
                $val2 = array();
                $j = 0;
                $recordCount = count($records);
                if (count($model->hasAndBelongsToMany) > 0) {
                    foreach ($records as $record) {
                        if (isset($record[$nodeInfo['value']['model']])) {
                            $recordId = $record[$nodeInfo['value']['model']][$model->primaryKey];
                            unset($record[$nodeInfo['value']['model']]);
                            $this->relatedValues = $this->relatedValues + array($recordId => $record);
                        }
                    }
                } else {
                    $recordCount = isset($records[0]) ? count($records[0]) : 0;
                }

                for ($i = 0; $i <= $recordCount; $i++) {
                    if (count($model->hasAndBelongsToMany) > 0) {
                        if (isset($records[$i][$nodeInfo['value']['model']][$nodeInfo['value']['field']])) {
                            $val[$j] = $records[$i][$nodeInfo['value']['model']][$nodeInfo['value']['field']];
                        }
                        if (isset($records[$i][$nodeInfo['innerHTML']['model']][$nodeInfo['innerHTML']['field']])) {
                            $val2[$j] = $records[$i][$nodeInfo['innerHTML']['model']][$nodeInfo['innerHTML']['field']];
                        }
                    } else {
                        if (isset($records[0][$nodeInfo['value']['model']][$i][$nodeInfo['value']['field']])) {
                            $val[$j] = $records[0][$nodeInfo['value']['model']][$i][$nodeInfo['value']['field']];
                        }
                        if (isset($records[0][$nodeInfo['value']['model']][$i][$nodeInfo['innerHTML']['model']][$nodeInfo['innerHTML']['field']])) {
                            $val2[$j] = $records[0][$nodeInfo['value']['model']][$i][$nodeInfo['innerHTML']['model']][$nodeInfo['innerHTML']['field']];
                        }
                    }

                    $j++;
                }
                
                $cloneNode = $element->cloneNode(TRUE);
                
                $i = 0;
                foreach ($element->childNodes as $node) {
                    if (!empty($val2[$i])) {
                        $textNode = new DOMText($val2[$i]);
                        if ($i == 0) {
                            if (!empty($val[$i])) {
                                $cloneNode->childNodes->item(1)->setAttribute('value', $val[$i]);
                            }
                            $cloneNode->childNodes->item(1)->nodeValue = $val2[$i];
                            if (isset($val[$i]) && $val[$i] == $curVal) {
                                if ($cloneNode->childNodes->item(1)->nodeType == 1) {
                                    $cloneNode->childNodes->item(1)->setAttribute('value', $val[$i]);
                                    $cloneNode->childNodes->item(1)->setAttribute('selected', 'selected');
                                }
                            } else {
                                $cloneNode->childNodes->item(1)->removeAttribute("selected");
                            }
                        } else {
                            if ($node->nodeType == 1 || $node->nodeType == 3) {
                                $childNode = $element->childNodes->item(1)->cloneNode(TRUE);
                                if (!empty($val[$i])) {
                                    $childNode->setAttribute('value', $val[$i]);
                                }
                                $childNode->nodeValue = $val2[$i];
                                if (isset($val[$i]) && $val[$i] == $curVal) {
                                    if ($childNode->nodeType == 1) {
                                        $childNode->setAttribute('value', $val[$i]);
                                        $childNode->setAttribute('selected', 'selected');
                                    }
                                } else {
                                    $childNode->removeAttribute("selected");
                                }
                                $cloneNode->appendChild($childNode);
                            }
                        }
                    }
                    $i++;
                }
                
                if ($element->hasChildNodes()) {
                    if (is_object($element->parentNode) && empty($element->childNodes->item(3)->tagName)) {
                        $element->parentNode->replaceChild($cloneNode, $element);
                    }
                }

                // set related values
                if (count($model->hasAndBelongsToMany) > 0 && !in_array($element->getNodePath(), $this->baseNodes)) {
                    array_push($this->baseNodes, $element->getNodePath());
                    $parentEnclosure = $this->_getParentEnclosure($element->parentNode);
                    if (!is_null($parentEnclosure)) {
                        $xpath = new DOMXPath($parentEnclosure->ownerDocument);
                        foreach ($model->hasAndBelongsToMany as $relatedKey => $relatedValues) {
                            foreach ($this->relatedValues as $recordId => $record) {
                                if (isset($this->relatedValues[$curVal][$relatedKey]) && $recordId == $curVal) {
                                    $attrValue = $element->getAttribute("title");
                                    if (empty($attrValue)) {
                                        $attrValue = $element->getAttribute("class");
                                        if (preg_match('/IM\[(.*)\]/', $attrValue, $matches)) {
                                            $attrValue = $matches[1];
                                        }
                                    }
    
                                    if (!empty($attrValue)) {
                                        $modelArray = explode($this->separator, $attrValue);
                                        $currentModelName = ucwords($modelArray[0]);
                                    }
                                    $searchTarget = strtolower($currentModelName . $this->separator . $relatedKey);
                                    
                                    foreach ($xpath->query("//*[contains(@class, 'IM[') or contains(@title, '" . $this->separator . "')]") as $currentNode) {
                                        if ($currentNode->nodeType == 1 && strstr($currentNode->getNodePath(), $parentEnclosure->getNodePath())) {
                                            $targetAttrValue = $currentNode->getAttribute("title");
                                            if (empty($targetAttrValue)) {
                                                $targetAttrValue = $currentNode->getAttribute("class");
                                                if (preg_match('/IM\[(.*)\]/', $targetAttrValue, $matches)) {
                                                    $targetAttrValue = $matches[1];
                                                }
                                            }
                                            if ($searchTarget == strtolower($targetAttrValue)) {
                                                $optionTags = $currentNode->getElementsByTagName("option");
                                                foreach ($this->relatedValues[$curVal][$relatedKey] as $relatedRecord) {
                                                    $cloneNode = $optionTags->item(0)->cloneNode(TRUE);
                                                    $cloneNode->setAttribute("value", $relatedRecord['CorWayKind']['kind_id']);
                                                    $textNode = new DOMText($relatedRecord['name']);
                                                    if ($cloneNode->hasChildNodes()) {
                                                        $cloneNode->removeChild($cloneNode->childNodes->item(0));
                                                    }
                                                    $cloneNode->appendChild($textNode);
                                                    $optionTags->item(0)->parentNode->appendChild($cloneNode);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    
    /**
     * Seeking nodes and if a node is an enclosure, proceed repeating.
     */
    protected function _seekEnclosureNode($node, $currentRecord, $currentTable, $parentEnclosure, $objectReference)
    {
        if ($node->nodeType === 1) { // Work for an element
            try {
                if ($this->_isEnclosure($node, FALSE)) { // Linked element and an enclosure
                    $this->_expandEnclosure($node, $currentRecord, $currentTable, $parentEnclosure, $objectReference);
                } else {
                    $children = $node->childNodes; // Check all child nodes.
                    for ($i = 0; $i < $children->length; $i++) {
                        if ($children->item($i)->nodeType === 1) {
                            $this->_seekEnclosureNode($children->item($i), $currentRecord, $currentTable, $parentEnclosure, $objectReference);
                        }
                    }
                }
            } catch (Exception $e) {
            }
        }
    }
    
    protected function _expandEnclosure($node, $currentRecord, $currentTable, $parentEnclosure, $parentObjectInfo)
    {
        $encNodeTag = $node->tagName;
        $repNodeTag = $this->_repeaterTagFromEncTag($encNodeTag);
        $repeatersOriginal = $this->_collectRepeatersOriginal($node, $repNodeTag);  // Collecting repeaters to this array.
        $repeaters = $this->_collectRepeaters($repeatersOriginal);  // Collecting repeaters to this array.
        list($linkedNodes, $widgetNodes) = $this->_collectLinkedElement($repeaters);
        $linkDefs = $this->_collectLinkDefinitions($linkedNodes);
        $voteResult = $this->_tableVoting($linkDefs);

        $currentContext = $voteResult['targettable'];
        $fieldList = $voteResult['fieldlist']; // Create field list for database fetch.
        
        if ($currentContext) {
            $relationValue = NULL;
            $dependObject = array();
            $relationDef = $currentContext['relation'];
            if ($relationDef) {
                $relationValue = array();
            }
        
            $RecordCounter = 0;
            $repeatersOneRec = $this->_cloneEveryNodes($repeatersOriginal);
            list($currentLinkedNodes, $currentWidgetNodes) = $this->_collectLinkedElement($repeatersOneRec);
            
            for ($i = 0; $i < count($currentLinkedNodes); $i++) {
                $nodeTag = $currentLinkedNodes[$i]->tagName;
                $nodeId = $currentLinkedNodes[$i]->getAttribute('id');
                
                // get the tag name of the element
                $typeAttr = $currentLinkedNodes[$i]->getAttribute('type');
                // type attribute
                $linkInfoArray = $this->_getLinkedElementInfo($currentLinkedNodes[$i]);

                // info array for it  set the name attribute of radio button
                // should be different for each group
                if ($typeAttr == 'radio') { // set the value to radio button
                }
            }
            
            $mainModelName = $this->modelClass;
            $currentContext['name'] = $mainModelName;

            for ($i = 0; $i < count($repeatersOneRec); $i++) {
                $newNode = $repeatersOneRec[$i]->cloneNode(TRUE);
                $nodeClass = $this->_getClassAttributeFromNode($newNode);
                if ($nodeClass != $this->noRecordClassName) {
                    $currentModelName = "";
                    foreach ($currentLinkedNodes as $currentLinkedNode) {
                        $attrValue = $currentLinkedNode->getAttribute("title");
                        if (empty($attrValue)) {
                            $attrValue = $currentLinkedNode->getAttribute("class");
                            if (preg_match('/IM\[(.*)\]/', $attrValue, $matches)) {
                                $attrValue = $matches[1];
                            }
                        }
                        
                        if (!empty($attrValue)) {
                            $modelArray = explode($this->separator, $attrValue);
                            $currentModelName = ucwords($modelArray[0]);
                            $fieldName = $modelArray[1];
                        }
                    }
                    
                    if (in_array($repeatersOneRec[$i], $currentLinkedNodes)) {
                        foreach ($repeatersOneRec[$i]->childNodes as $childNode) {
                            if ($childNode->nodeType == 1) {
                                try {
                                    if ($this->_isEnclosure($childNode, FALSE)) { // Linked element and an enclosure
                                    } else {
                                    }
                                } catch (Exception $e) {
                                }
                            }
                        }
                    }
                }
            }
        } else {
            for ($i = 0; $i < count($repeatersOriginal); $i++) {
                $newNode = $node->appendChild($repeatersOriginal[$i]);
                $this->_seekEnclosureNode($newNode, NULL, NULL, $node, NULL);
            }
        }
    }
    
    protected function _collectRepeatersOriginal($node, $repNodeTag)
    {
        $repeatersOriginal = array();

        $children = $node->childNodes; // Check all child node of the enclosure.
        for ($i = 0; $i < $children->length; $i++) {
            if ($children->item($i)->nodeType === 1 && $children->item($i)->tagName == $repNodeTag) {
                // If the element is a repeater.
                array_push($repeatersOriginal, $children->item($i)); // Record it to the array.
            }
        }
        
        return $repeatersOriginal;
    }

    protected function _collectRepeaters($repeatersOriginal)
    {
        $repeaters = array();
        for ($i = 0; $i < count($repeatersOriginal); $i++) {
            $inDocNode = $repeatersOriginal[$i];
            $parentOfRep = $repeatersOriginal[$i]->parentNode;
            $cloneNode = $repeatersOriginal[$i]->cloneNode(true);
            array_push($repeaters, $cloneNode);
        }
        return $repeaters;
    }

    protected function _collectLinkedElement($repeaters)
    {
        for ($i = 0; $i < count($repeaters); $i++) {
            $this->_seekLinkedElement($repeaters[$i]);
        }
        
        return array($this->linkedNodesCollection, $this->widgetNodesCollection);
    }

    protected function _seekLinkedElement($node)
    {
        $nType = $node->nodeType;
        if ($nType === 1) {
            if ($this->_isLinkedElement($node)) {
                $currentEnclosure = $this->_getEnclosure($node);
                if ($currentEnclosure === NULL) {
                    array_push($this->linkedNodesCollection, $node);
                } else {
                    return $currentEnclosure;
                }
            }
            $children = $node->childNodes;
            for ($i = 0; $i < $children->length; $i++) {
                $detectedEnclosure = $this->_seekLinkedElement($children->item($i));
            }
        }
        return NULL;
    }
    
    protected function _collectLinkDefinitions($linkedNodes)
    {
        $linkDefs = array();
        for ($j = 0; $j < count($linkedNodes); $j++) {
            $nodeDefs = $this->_getLinkedElementInfo($linkedNodes[$j]);
            if ($nodeDefs !== NULL) {
                for ($k = 0; $k < count($nodeDefs); $k++) {
                    array_push($linkDefs, $nodeDefs[$k]);
                }
            }
        }
        return $linkDefs;
    }

    protected function _tableVoting($linkDefs)
    {
        $tableName = '';
        $tableVote = array();    // Containing editable elements or not.
        $fieldList = array();    // Create field list for database fetch.

        for ($j = 0; $j < count($linkDefs); $j++) {
            $nodeInfoArray = $this->_getNodeInfoArray($linkDefs[$j]);
            $nodeInfoField = $nodeInfoArray['field'];
            $nodeInfoTable = $nodeInfoArray['table'];
            if ($nodeInfoField != NULL && $nodeInfoTable != NULL && mb_strlen($nodeInfoField) != 0 && mb_strlen($nodeInfoTable) != 0) {
                if (!isset($fieldList[$nodeInfoTable])) {
                    $fieldList[$nodeInfoTable] = array();
                }
                array_push($fieldList[$nodeInfoTable], $nodeInfoField);
                if (!isset($tableVote[$nodeInfoTable])) {
                    $tableVote[$nodeInfoTable] = 1;
                } else {
                    $tableVote[$nodeInfoTable]++;
                }
            } else {
                return NULL;
            }
        }
        $maxVoted = -1;
        $maxTableName = ''; // Which is the maximum voted table name.
        foreach ($tableVote as $tableName => $tableCount) {
            if (isset($tableVote[$tableName]) && $maxVoted < $tableVote[$tableName]) {
                $maxVoted = $tableVote[$tableName];
                $maxTableName = $tableName;
            }
        }
        
        $context = array('relation' => 'contact_to');

        return array('targettable' => $context, 'fieldlist' => isset($fieldList[$maxTableName]) ? $fieldList[$maxTableName] : array());
    }
    
    protected function _getParentRepeater($node)
    {
        $currentNode = $node;
        while ($currentNode != NULL) {
            if ($this->_isRepeater($currentNode, TRUE)) {
                return $currentNode;
            }
            $currentNode = $currentNode->parentNode;
        }
        return NULL;
    }
    
    protected function _getParentEnclosure($node)
    {
        $currentNode = $node;
        while ($currentNode != NULL) {
            if ($this->_isEnclosure($currentNode, TRUE)) {
                return $currentNode;
            }
            $currentNode = $currentNode->parentNode;
        }
        
        return NULL;
    }
    
    protected function _isEnclosure($node, $nodeOnly)
    {
        $ignoreEnclosureRepeaterClassName = "_im_ignore_enc_rep";
        $rollingRepeaterClassName = "_im_repeater";
        
        if (!$node || $node->nodeType !== 1) {
            return FALSE;
        }
        $tagName = $node->tagName;
        $className = $this->_getClassAttributeFromNode($node);
        
        if (in_array($tagName, array("tbody", "ul", "ol", "select", "div", "span"))) {
            if ($nodeOnly) {
                return TRUE;
            } else {
                $children = $node->childNodes;
                for ($k = 0; $k < $children->length; $k++) {
                    if ($this->_isRepeater($children->item($k), TRUE)) {
                        return TRUE;
                    }
                }
            }
        }
        
        return FALSE;
    }
    
    protected function _isRepeater($node, $nodeOnly)
    {
        if (!$node || $node->nodeType !== 1) {
            return FALSE;
        }
        $tagName = $node->tagName;
        $className = $this->_getClassAttributeFromNode($node);

        if (in_array($tagName, array("tr", "li", "option", "div", "span"))) {
            if ($nodeOnly) {
                return TRUE;
            } else {
                return $this->_searchLinkedElement($node);
            }
        }
        
        return FALSE;
    }
    
    protected function _searchLinkedElement($node)
    {
        if ($this->_isLinkedElement($node)) {
            return TRUE;
        }
        $children = $node->childNodes;
        for ($k = 0; $k < $children->length; $k++) {
            if ($children->item($k)->nodeType === 1) { // Work for an element
                if ($this->_isLinkedElement($children->item($k))) {
                    return TRUE;
                } else if ($this->_searchLinkedElement($children->item($k))) {
                    return TRUE;
                }
            }
        }
        
        return FALSE;
    }
    
    protected function _isLinkedElement($node)
    {
        if ($node != NULL) {
            if ($this->titleAsLinkInfo) {
                if ($node->getAttribute("title") != NULL && mb_strlen($node->getAttribute("title")) > 0) {
                    return TRUE;
                }
            }
            if ($this->classAsLinkInfo) {
                $classInfo = $this->_getClassAttributeFromNode($node);
                if ($classInfo != NULL) {
                    if (preg_match('/IM\[.*\]/', $classInfo, $matches)) {
                        return TRUE;
                    }
                }
            }
        }
        
        return FALSE;
    }
    
    protected function _getEnclosure($node)
    {
        $detectedRepeater = NULL;
        
        $currentNode = $node;
        while ($currentNode != NULL) {
            if ($this->_isRepeater($currentNode, TRUE)) {
                $detectedRepeater = $currentNode;
            } else if ($this->_isRepeaterOfEnclosure($detectedRepeater, $currentNode)) {
                $detectedRepeater = NULL;
                return $currentNode;
            }
            $currentNode = $currentNode->parentNode;
        }
        
        return NULL;
    }
    
    /**
     * Check the pair of nodes in argument is valid for repater/enclosure.
     */
    protected function _isRepeaterOfEnclosure($repeater, $enclosure)
    {
        if (!$repeater || !$enclosure) {
            return FALSE;
        }
        $repeaterTag = $repeater->tagName;
        $enclosureTag = $enclosure->tagName;
        if (($repeaterTag === 'tr' && $enclosureTag === 'tbody')
            || ($repeaterTag === 'option' && $enclosureTag === 'select')
            || ($repeaterTag === 'li' && $enclosureTag === 'ol')
            || ($repeaterTag === 'li' && $enclosureTag === 'ul')) {
            return TRUE;
        }
        if (($enclosureTag === 'div' || $enclosureTag === 'span')) {
            $enclosureClass = $this->_getClassAttributeFromNode($enclosure);
            if ($enclosureClass && strpos($enclosureClass, '_im_enclosure') >= 0) {
                $repeaterClass = $this->_getClassAttributeFromNode($repeater);
                if (($repeaterTag === 'div' || $repeaterTag === 'span') && $repeaterClass != NULL && strpos($repeaterClass, '_im_repeater') >= 0) {
                    return TRUE;
                } else if ($repeaterTag === 'input') {
                    $repeaterType = $repeater->getAttribute('type');
                    if ($repeaterType
                        && (($repeaterType.indexOf('radio') >= 0 || strpos($repeaterType, 'check') >= 0))) {
                        return TRUE;
                    }
                }
            }
        }
        
        return FALSE;
    }
    
    protected function _getLinkedElementInfo($node)
    {
        $defs = array();
        if ($this->_isLinkedElement($node)) {
            if ($this->titleAsLinkInfo) {
                if ($node->getAttribute('title') != NULL) {
                    $eachDefs = explode($this->defDivider, $node->getAttribute('title'));
                    for ($i = 0; $i < count($eachDefs); $i++) {
                        array_push($defs, $this->_resolveAlias($eachDefs[$i]));
                    }
                }
            }
            if ($this->classAsLinkInfo) {
                $classAttr = $this->_getClassAttributeFromNode($node);
                if ($classAttr !== NULL && count($classAttr) > 0) {
                    if (preg_match('/IM\[([^\]]*)\]/', $classAttr, $matched)) {
                        $eachDefs = explode($this->defDivider, $matched[1]);
                        for ($i = 0; $i < count($eachDefs); $i++) {
                            array_push($defs, $this->_resolveAlias($eachDefs[$i]));
                        }
                    }
                }
            }
            
            return $defs;
        }
        
        return FALSE;
    }
    
    protected function _resolveAlias($def)
    {
        return $def;
    }
    
    protected function _repeaterTagFromEncTag($tag)
    {
        if ($tag == 'tbody') {
            return 'tr';
        } else if ($tag == 'select') {
            return 'option';
        } else if ($tag == 'ul') {
            return 'li';
        } else if ($tag == 'ol') {
            return 'li';
        } else if ($tag == 'div') {
            return 'div';
        } else if ($tag == 'span') {
            return 'span';
        }
        
        return NULL;
    }
    
    protected function _getNodeInfoArray($nodeInfo)
    {
        if (is_array($nodeInfo)) {
            $comps = $nodeInfo;
        } else {
            $comps = explode($this->separator, $nodeInfo);
        }

        $tableName = '';
        $fieldName = '';
        $targetName = '';

        if (count($comps) == 3) {
            $tableName = $comps[0];
            $fieldName = $comps[1];
            $targetName = $comps[2];
        } else if (count($comps) == 2) {
            $tableName = $comps[0];
            $fieldName = $comps[1];
        } else {
            $fieldName = $nodeInfo;
        }

        return array(
            'table' => $tableName,
            'field' => $fieldName,
            'target' => $targetName
        );
    }
    
    protected function _getClassAttributeFromNode($node)
    {
        $str = '';
        if ($node == NULL) {
            return $str;
        }
        $str = $node->getAttribute('class');
        
        return $str;
    }
    
    protected function _cloneEveryNodes($originalNodes)
    {
        $clonedNodes = array();
        for ($i = 0; $i < count($originalNodes); $i++) {
            $clonedNode = $originalNodes[$i]->cloneNode(true);
            array_push($clonedNodes, $clonedNode);
        }
        
        return $clonedNodes;
    }
    
    protected function _setDataToElement($element, $curTarget, $curVal)
    {
        $needPostValueSet = FALSE;
        
        $nodeTag = $element->tagName;
        
        if ($nodeTag == "input") {
            if (in_array($element->getAttribute("type"), array("checkbox", "radio"))) {
                if ($element->getAttribute("value") == $curVal) {
                    $element->setAttribute("checked", "checked");
                } else {
                    $element->removeAttribute("checked");
                }
            } else { // this node must be text field
                $element->setAttribute("value", $curVal);
            }
        } else if ($nodeTag == "select") {
            $needPostValueSet = TRUE;
            $optionTags = $element->getElementsByTagName("option");
            $hasCurVal = FALSE;
            foreach ($optionTags as $optionTag) {
                $nodeDefs = $this->_getLinkedElementInfo($optionTag);
                $this->partialConstruct($element, $nodeDefs, $curVal);
                
                if ($optionTag->getAttribute("value") == $curVal) {
                    $optionTag->setAttribute("selected", "selected");
                    $hasCurVal = TRUE;
                }
            }
            if ($hasCurVal === FALSE) {
                $cloneNode = $element->childNodes->item(1)->cloneNode(TRUE);
                $cloneNode->setAttribute("value", "");
                $cloneNode->setAttribute("selected", "selected");
                
                $textNode = new DOMText("");
                if ($cloneNode->hasChildNodes()) {
                    $cloneNode->removeChild($cloneNode->childNodes->item(0));
                }
                $cloneNode->appendChild($textNode);
                
                $element->insertBefore($cloneNode, $element->childNodes->item(1));
                $hasCurVal = TRUE;
            }
        } else {
            if ($nodeTag == "textarea") {
                if (function_exists("mb_ereg_replace")) {
                    $curVal = mb_ereg_replace("\n", "\r" , mb_ereg_replace("\r\n", "\r" , $curVal));
                } else {
                    $curVal = str_replace("\n", "\r", str_replace("\r\n", "\r" ,$curVal));
                }
            }
            $textNode = new DOMText($curVal);
            if (!$element->hasChildNodes()) {
                $element->appendChild($textNode);
            }
        }
        
        return $needPostValueSet;
    }
}