<?php
class ElementValidator
{
    protected $validationOptionData;

    public function __construct()
    {
        $this->validationOptionData = ElementsOptions::getOptionDataForValidation();
    }

    protected function addError(Item $item, $elementName, $message)
    {
        $item->addError($elementName, $message);
    }

    public function validateElement(Item $item, $elementId, $elementName, $text)
    {
        $isValid = true;

        if (array_key_exists($elementId, $this->validationOptionData))
        {
            $definition = $this->validationOptionData[$elementId];

            foreach ($definition['args'] as $argName => $arg)
            {
                if ($arg == false)
                {
                    continue;
                }

                switch ($argName)
                {
//                    case 'required':
//                        break;
//
//                    case 'unique':
//                        break;

                    case 'date':
                        $validator = new DateElementValidator();
                        $isValid = $validator->validateElementDate($item, $elementId, $elementName, $text);
                        break;

                    case 'year':
                        $validator = new DateElementValidator();
                        $isValid = $validator->validateElementYear($item, $elementId, $elementName, $text);
                        break;
                }

                if ($isValid)
                {
                    break;
                }
            }
        }

        return true;
    }
}