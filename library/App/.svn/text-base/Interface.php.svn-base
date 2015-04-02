<?php
class App_Interface
{
	
	
	
	public static function toHtml($el)
	{
		return html_entity_decode($el->saveHTML());
	}
	
	public static function makeHtml(array $els)
	{
		$document = new DOMDocument();
		$document->appendChild(self::makeElement($els, $document));
		return $document;
	}
	
	public static function makeElement($els, DOMDocument $document  )
	{
		
		if(count($els))
		{
			foreach ($els as $tagName => $options)
			{
				$tagName = preg_replace('([0-9]+)', '', $tagName);
				if($tagName == 'text')
				{
					$element = $document->createTextNode($options);
				}
				else
				{
					$element = $document->createElement($tagName);
				}
				
				if(isset($options['attributes']) && is_array($options['attributes']) && count($options['attributes']))
				{
					foreach ($options['attributes'] as $attributeName => $atributeValue)
					{
						$element->setAttribute($attributeName, $atributeValue);
					}
				}
					
				
				if(isset($options['nodes']) && is_array($options['nodes']))
				{
					foreach ($options['nodes'] as $nodeTagName => $options)
					{
						$element->appendChild(self::makeElement(array($nodeTagName => $options),$document));
					}
				}
			}
		}
		return $element;
	}
	
	public static function domArrayToHtml(array $domArray)
	{
		return self::toHtml ( self::makeHtml ( $domArray ) );
	}
	
		
}