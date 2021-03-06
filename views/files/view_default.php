<?php

$view = '
<?php if (validation_errors()) : ?>
<div class="notification error">
	<?php echo validation_errors(); ?>
</div>
<?php endif; ?>
<?php // Change the css classes to suit your needs    
if( isset($'.$module_name_lower.') ) {
	$'.$module_name_lower.' = (array)$'.$module_name_lower.';
}
$id = isset($'.$module_name_lower.'[\''.$primary_key_field.'\']) ? "/".$'.$module_name_lower.'[\''.$primary_key_field.'\'] : \'\';
$attributes = array("class" => "constrained ajax-form", "'.$primary_key_field.'" => "'.$controller_name.'_'.$action_name.'");
echo form_open("admin/'.$controller_name.'/'.$module_name_lower.'/'.$action_name.'" . $id, $attributes);
';
$view .= '?>';

for($counter=1; $field_total >= $counter; $counter++)
{
	$maxlength = NULL; // reset this variable

	// only build on fields that have data entered. 
	//Due to the requiredif rule if the first field is set the the others must be

	if (set_value("view_field_label$counter") == NULL)
	{
		continue; 	// move onto next iteration of the loop
	}

	$field_label = set_value("view_field_label$counter");
	$field_name = set_value("view_field_name$counter");
	$field_type = set_value("view_field_type$counter");

	if ($field_type != 'checkbox') // checkbox appears to the left of the checkbox so I can't add now for a checkbox
	{
		
$view .= <<<EOT

{$form_input_delimiters[0]}
        <?php echo form_label('{$field_label}', '{$field_name}'); ?>
EOT;
		
	} else {
$view .= <<<EOT

{$form_input_delimiters[0]}
	
EOT;
	}

	// set a friendly variable name
	$validation_rules = $this->input->post('validation_rules'.$counter);

	if (is_array($validation_rules))
	{       
		// rules have been selected for this fieldset

		foreach($validation_rules as $key => $value)
		{
			if($value == 'required')
			{
				$view .= ' <span class="required">*</span>';
			}
		}
	}
                

	// field type
	switch($field_type)
	{

	// Some consideration has gone into how these should be implemented
	// I came to the conclusion that it should just setup a mere framework
	// and leave helpful comments for the developer
	// Modulebuilder is meant to have a minimium amount of features. 
	// It sets up the parts of the form that are repitive then gets the hell out
	// of the way.

	// This approach maintains these aims/goals

	case('textarea'):
		
		$view .= "
	<?php echo form_textarea( array( 'name' => '$field_name', 'rows' => '5', 'cols' => '80', 'value' => set_value('$field_name', isset(\${$module_name_lower}['{$field_name}']) ? \${$module_name_lower}['{$field_name}'] : '') ) )?>
".$form_input_delimiters[1];
		break;
						
	case('radio'):
                        
		$view .= '
		<?php // Change or Add the radio values/labels/css classes to suit your needs ?>
		<input id="'.$field_name.'" name="'.$field_name.'" type="radio" class="" value="option1" <?php echo $this->CI->form_validation->set_radio(\''.$field_name.'\', \'option1\'); ?> />
		'. form_label('Radio option 1', $field_name) .'

		<input id="'.$field_name.'" name="'.$field_name.'" type="radio" class="" value="option2" <?php echo $this->CI->form_validation->set_radio(\''.$field_name.'\', \'option2\'); ?> />
		'. form_label('Radio option 2', $field_name) .'
'.$form_input_delimiters[1].'

';
		break;                        

	case('select'):
	// decided to use ci form helper here as I think it makes selects/dropdowns a lot easier
	$view .= <<<EOT

        <?php // Change the values in this array to populate your dropdown as required ?>
        
EOT;
	 $view .= '<?php $options = array(';

	 $view .= '
							  \'\'  => \'Please Select\',
							  \'example_value1\'    => \'example option 1\'
							); ?>

        <br /><?php echo form_dropdown(\''.$field_name.'\', $options, set_value(\''.$field_name.'\'))?>
'.$form_input_delimiters[1].'                                             
                        ';
		break;
                        
	case('checkbox'):

	$view .= <<<EOT

        <?php // Change the values/css classes to suit your needs ?>
        <br /><input type="checkbox" id="{$field_name}" name="{$field_name}" value="1" class="" <?php echo set_checkbox('{$field_name}', '1'); ?>> 
    <?php echo form_label('{$field_label}', '{$field_name}'); ?>               
	
{$form_input_delimiters[1]} 
EOT;
		break;

	case('input'):
	case('password'):
	default: // input.. added bit of error detection setting select as default
						
		if ($field_type == 'input')
		{
			$type = 'text';
		}
		else
		{
			$type = 'password';
		}
		if (set_value("db_field_length_value$counter") != NULL)
		{
			$maxlength = 'maxlength="'.set_value("db_field_length_value$counter").'"';
		}

		$view .= <<<EOT

        <input id="{$field_name}" type="{$type}" name="{$field_name}" {$maxlength} value="<?php echo set_value('{$field_name}', isset(\${$module_name_lower}['{$field_name}']) ? \${$module_name_lower}['{$field_name}'] : ''); ?>"  />
{$form_input_delimiters[1]}

EOT;

			break;

	} // end switch


} // end for loop
$view .= <<<EOT


	<div class="text-right">
		<br/>
		<input type="submit" name="submit" value="{$action_label} {$module_name}" /> or <?php echo anchor('admin/{$controller_name}/{$module_name_lower}', lang('{$module_name_lower}_cancel')); ?>
	</div>
EOT;
if($action_name != 'create') {
$view .= '
	<div class="box delete rounded">
		<a class="button" id="delete-me" href="<?php echo site_url(\'admin/'.$controller_name.'/'.$module_name_lower.'/delete/\'. $id); ?>" onclick="return confirm(\'<?php echo lang(\''.$module_name_lower.'_delete_confirm\'); ?>\')"><?php echo lang(\''.$module_name_lower.'_delete_record\'); ?></a>
		
		<h3><?php echo lang(\''.$module_name_lower.'_delete_record\'); ?></h3>
		
		<p><?php echo lang(\''.$module_name_lower.'_edit_text\'); ?></p>
	</div>
<?php echo form_close(); ?>
';
}


echo $view;
?>
