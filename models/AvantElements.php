<?php
class AvantElements
{
    public static function addError(Item $item, $elementName, $message)
    {
        $item->addError($elementName, $message);
    }

    public static function getImplicitLink($elmentId, $text)
    {
        $linkBuilder = new LinkBuilder();
        return $linkBuilder->emitImplicitLink($elmentId, $text);
    }

    public static function getSimpleVocabTerms($elementId)
    {
        $vocabulary = array();
        if (plugin_is_active('SimpleVocab'))
        {
            $simpleVocabTerm = get_db()->getTable('SimpleVocabTerm')->findByElementId($elementId);
            if (!empty($simpleVocabTerm))
            {
                $vocabulary = explode("\n", $simpleVocabTerm->terms);
            }
        }
        return $vocabulary;
    }

    public static function emitAdminCss()
    {
        $hideDescriptions = (boolean)get_option(ElementsConfig::OPTION_HIDE_DESCRIPTIONS);

        if ($hideDescriptions)
        {
            echo PHP_EOL . '<style>' . PHP_EOL;

            // Hide large text that repeats what's already highlighted in the tab at the top of the form.
            echo '#item-metadata h2 {display: none;}';

            // Hide the description of the element set (e.g. Dublin Core) that appears right under the tabs.
            echo '#edit-form .element-set-description {display: none;}';

            echo '</style>' . PHP_EOL;
        }
    }

    public static function itemHasErrors($item)
    {
        $errors = $item->getErrors()->get();
        return count($errors) > 0;
    }

    public function orderElementsForDisplay($elementSetsForDisplay)
    {
        $elementsData = ElementsConfig::getOptionDataForDisplayOrder();
        $displayOrder = array();
        foreach ($elementsData as $elementName)
        {
            $displayOrder[$elementName] = null;
        }

        // Copy the elements from the element sets (Dublin Core and others) into the ordered array.
        foreach ($elementSetsForDisplay as $elementSet)
        {
            foreach ($elementSet as $elementName => $elementInfo)
            {
                $displayOrder[$elementName] = $elementInfo;
            }
        }

        // Create another array that excludes any empty elements.
        $elementSet = array();
        foreach ($displayOrder as $elementName => $elementInfo)
        {
            if (empty($elementInfo))
                continue;
            $elementSet[$elementName] = $elementInfo;
        }

        return $elementSet;
    }
}