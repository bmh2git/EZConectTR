<?php
class App_Form_Select extends App_Form
{
	public static function simpleBuild($content, $selected = 0)
	{
		$c = array();
		if(count($content))
		{
			foreach ($content as $k => $v)
			{
				$c[] = array(
					'id' => $k,
					'value' => $v
				);
			}
		}
		
		return self::build($c, array(), $selected);
	}
	
	public static function build($content = array(),$options = array(),$val = 0)
	{

		$key = 'id';
		$value = 'value';
		$selectOptions = array();
		
		isset ( $options['key'] ) && $key = $options['key'];
		isset ( $options['value'] ) && $value = $options['value'];
		
		foreach ( $content as $k => $v )
		{
			if (! empty ( $key ) && ! empty ( $value ))
			{
				$selectOptions[] = array(
						"key"=> $v[$key],
						"value"=> $v[$value]
				);
			} else
			{
				$selectOptions[] = array(
						"key"=> $k,
						"value"=> $v
				);
			}
		}
		if (! isset ( $options['toArray'] ))
		{
			return self::buildHTML ( $selectOptions, $val );
		} else
		{
	
			return self::buildDomArray($selectOptions, $val);
		}
	}
	public static function buildHTML($selectOptions,$val = 0)
	{
		$html = "";
		if (is_array ( $selectOptions ) && count ( $selectOptions ))
		{
			foreach ( $selectOptions as $v )
			{
				$selected = $v['key'] == $val ? "selected" : '';
				$html .= "<option value='$v[key]' $selected>$v[value]</option>";
			}
		}
		return $html;
	}
	
	public static function buildDomArray($selectOptions,$val = 0)
	{
		$return = array();
		$lvl = 0;
		if (is_array ( $selectOptions ) && count ( $selectOptions ))
		{
			foreach ( $selectOptions as $v )
			{
				$return['option'.$lvl] = array(
					'attributes' => array('value' => $v['key']),
					'nodes' => array('text' => $v['value'])
				);
				if(App_Interface_Form::isSelectedValue($val, $v['key']))
				{
					
					$return['option'.$lvl]['attributes']['selected'] = 'selected';
				}
				
				$lvl++;
			}
		}
		return $return;
	}
}