<?php

/*
 * Output path of the modules
 */
$config[ 'modulebuilder' ]['output_path'] = APPPATH."../modules/";
/*
 * form actions which will appear in the list
 */
$config[ 'modulebuilder' ][ 'form_action_options' ] = array('index' => 'List', 
														'create' => 'Create',
														'edit' => 'Edit', 
														'delete' => 'Delete');

/*
 * default primary key field
 */
$config[ 'modulebuilder' ][ 'primary_key_field' ] = 'id';

/*
 * default form input delimiters
 */
$config[ 'modulebuilder' ][ 'form_input_delimiters' ] = array('<p>','</p>');

/*
 * default form error delimiters
 */
$config[ 'modulebuilder' ][ 'form_error_delimiters' ] = array('<span class="error">', '</span>');
