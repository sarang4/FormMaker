<?php
namespace FormBuilder\FBBundle\Controller\services\schema;
class Form{
    protected $action = '';
    protected $description = '';
    protected $enctype = '';
    protected $method = 'post';
    protected $name = '';
    protected $target = '';
    protected $elements = array();
    protected $form_id = '';
    protected $form_alignment = '';
    protected $thankyou_message = '';
    function __construct($action = '', $enctype = 'multipart/form-data', $method = 'post', $name = '', $target = '', $form_id = '', $form_alignment = 'form-vertical', $description = '', $thankyou_message = ''){
	$this->action = $action;
	$this->enctype = $enctype;
	$this->method = $method;
	$this->name = $name;
	$this->target = $target;
	$this->form_id = $form_id;
	$this->form_alignment = $form_alignment;
	$this->description = $description;
	$this->thankyou_message = $thankyou_message;
    }
    function addElement($element){
	array_push($this->elements, $element);
    }
    function generateHTML(){
	$form_HTML = '';
	for($i = 0, $size = count($this->elements); $i < $size; $i++){
	    if ((get_class($this->elements[$i]) == 'FormBuilder\FBBundle\Controller\services\schema\LabelField') and $this->elements[$i]->for){
		$group_html = '';
		$group_html = $this->elements[$i]->getHTML();
		$i++;
		$group_html .= $this->elements[$i]->getHTML();
		$group_html = add_div_with_css($group_html, 'control-group');
		$form_HTML .= $group_html;
	    }else{
		$form_HTML .= $this->elements[$i]->getHTML();
	    }
	}
	$form_title = '<h2  id="form_title">' . $this->name . '</h2>';
	$form_title = add_div_with_css($form_title, 'row-fluid');

	$form_description = '<div class="row-fluid" style="font-size: 16px; margin-bottom: 20px;" id="form_description">' . $this->description . '</div>';
	$thankyou_message = '<div class="row-fluid well" style="font-size: 16px; margin-bottom: 20px; display: none;" id="thankyou_message">' . $this->thankyou_message . '</div>';
	$complete_form_html =  '<form id=' . $this->form_id . ' method="' . $this->method . '" action="' . $this->action . '" class="well ' . $this->form_alignment . '">' . $form_title . $form_description . $form_HTML . '</form>';
	$complete_form_html .= $thankyou_message;
	return $complete_form_html;
    }
}
abstract class FormElement{
    protected $name = '';
    abstract function getHTML();
    function __construct($name = ''){
	$this->name = $name;
    }
}
abstract class Field extends FormElement{
    protected $id = '';
    protected $maxlength = 0;
    protected $size = '';
    protected $value = '';
    protected $hidden = 'false';
    function __construct($name, $id, $hidden = '', $value = '', $maxlength = '40', $size = '40'){
	parent::__construct($name);
	$this->id = $id;
	$this->maxlength = $maxlength;
	$this->size = $size;
	$this->value = $value;
	$this->hidden = $hidden;
    }
}
class SubmitField extends Field{
    function getHTML(){
	$html = '<button type="button" id="' . $this->id . '" name="'. $this->name . '"  class="btn btn-primary" href="javascript:void(0);" onclick="window.save_form_data();">' . $this->value . '</button>';
	$return_html = add_div_with_css($html, 'form-actions');
	return $return_html;
    }	   
}
class ResetField extends Field{
    protected $html_class = 'btn btn-primary';
    function getHTML(){
	$html = '<button type="reset" id="' . $this->id . '" name="'. $this->name . '"  class="' . $this->html_class .' " ' . check_if_empty('hidden', $this->hidden) . ' >' . $this->value . '</button>';
	$return_html = add_div_with_css($html, 'form-actions');
	return $return_html;
    }	    
}
class TextField extends Field{
    function getHTML(){
	$html = '<input type="text" id="' . $this->id . '" name="'. $this->name . '" size="' . $this->size . '" maxlength="' . $this->maxlength . '" ' . check_if_empty('hidden', $this->hidden) . '  />';
	$return_html = add_div_with_css($html, 'controls');
	return $return_html;

    }	    
    function set($maxlength, $size){
	$this->maxlength = $maxlength;
	$this->size = $size;
    }
}
class NumberField extends Field{
    function getHTML(){
	$html = '<input type="text" id="' . $this->id . '" name="'. $this->name . '" value="' . $this->value . '" size="' . $this->size . '" maxlength="' . $this->maxlength . '" ' . check_if_empty('hidden', $this->hidden) . '  />';
	$return_html = add_div_with_css($html, 'controls');
	return $return_html;
    }
    function validate(){
	#Do validation on value
    }
    function set($maxlength, $size){
	$this->maxlength = $maxlength;
	$this->size = $size;
    }
}
class FileField extends Field{
    function getHTML(){
	$html = '<input type="file" id="' . $this->id . '" name="'. $this->name . '" size="' . $this->size . '" maxlength="' . $this->maxlength . '" ' . check_if_empty('hidden', $this->hidden) . '  />';
	$return_html = add_div_with_css($html, 'controls');
	return $return_html;
    }
    function set($maxlength, $size){
	$this->maxlength = $maxlength;
	$this->size = $size;
    }
}
class PasswordField extends Field{
    protected $disabled = '';
    protected $readonly = '';
    function __construct($id = '', $name = '', $hidden = '', $value = '', $maxlength = '40', $size = '40', $disabled = '', $readonly = ''){
	parent::__construct($name, $id, $hidden, $value, $maxlength, $size);
	$this->disabled = $disabled;
	$this->readonly = $readonly;
    }
    function getHTML(){
	$html = '<input type="password" id="' . $this->id . '" name="'. $this->name . '" size="' . $this->size . '" maxlength="' . $this->maxlength . '"' . check_if_empty('disabled', $this->disabled) .  check_if_empty('readonly', $this->readonly) . ' ' . check_if_empty('hidden', $this->hidden) . '  />';
	$return_html = add_div_with_css($html, 'controls');
	return $return_html;
    }
    function set($maxlength, $size, $disabled, $readonly){
	$this->maxlength = $maxlength;
	$this->size = $size;
	$this->disabled = $disabled;
	$this->readonly = $readonly;
    }
}
class RadioField extends Field{
    protected $disabled = '';
    protected $readonly = '';
    protected $checked = '';
    public $label_value = '';
    public $html_class = 'radio';

    function __construct($id = '', $name = '', $hidden = '', $value = '', $disabled = '', $readonly = '', $checked = ''){
	parent::__construct($name, $id, $hidden, $value);
	$this->disabled = $disabled;
	$this->readonly = $readonly;
	$this->checked = $checked;
    }
    function getHTML(){
	$return_html = '<label class="' . $this->html_class .  '"><input type="radio" id="' . $this->id . '" name="'. $this->name . '" value="' . $this->value . '" ' . check_if_empty('disabled', $this->disabled) . '  ' . check_if_empty('readonly', $this->readonly) . ' ' . check_if_empty('hidden', $this->hidden) . '  ' .  check_if_empty('checked', $this->checked) .  ' />' .  $this->label_value . '</label>';
	return $return_html;
    }
}
class CheckBoxField extends Field{
    protected $disabled = '';
    protected $readonly = '';
    protected $checked = '';
    public $label_value = '';
    public $html_class = 'checkbox';
    function __construct($id = '', $name = '', $hidden = '', $value = '', $disabled = '', $readonly = '', $checked = ''){
	parent::__construct($name, $id, $hidden, $value);
	$this->disabled = $disabled;
	$this->readonly = $readonly;
	$this->checked = $checked;
    }
    function getHTML(){
	$return_html =  '<label class="' . $this->html_class .  '"><input type="checkbox" id="' . $this->id . '" name="'. $this->name . '" value="' . $this->value . '"' . check_if_empty('disabled', $this->disabled) .  check_if_empty('readonly', $this->readonly) . ' ' . check_if_empty('checked', $this->checked) . ' ' . check_if_empty('hidden', $this->hidden) . '/>' .  $this->label_value . '</label>';
	return $return_html;
    }
}
class TextAreaField extends Field{
    protected $disabled = '';
    protected $readonly = '';
    protected $rows = 5;
    protected $cols = 5;
    function __construct($id = '', $name = '', $hidden = '', $disabled = '', $readonly = '', $rows = '5', $cols = '5'){
	parent::__construct($name, $id, $hidden);
	$this->disabled = $disabled;
	$this->readonly = $readonly;
	$this->rows = $rows;
	$this->cols = $cols;

    }
    function getHTML(){
	$html = '<textarea id="' . $this->id . '" name="'. $this->name . '"' . check_if_empty('disabled', $this->disabled) .  check_if_empty('readonly', $this->readonly) . ' cols="' . $this->cols . '" rows="' . $this->rows . '" ' . check_if_empty('hidden', $this->hidden) . '  ></textarea>';
	$return_html = add_div_with_css($html, 'controls');
	return $return_html;
    }
}
class LabelField extends Field{
    public $for = '';
    function __construct($id = '', $name = '', $hidden = '', $value = '', $for = ''){
	parent::__construct($name, $id, $hidden, $value);
	$this->for = $for;
    }
    function getHTML(){
	$html = '<label id="' . $this->id . '" for="'. $this->for . '" ' . check_if_empty('hidden', $this->hidden) . '  >' . $this->value . '</label>';
	$return_html = add_div_with_css($html, 'control-label');
	return $return_html;
    }

}
class OptionField extends Field{
    protected $disabled = '';
    protected $selected = '';
    function __construct($id = '',$name = '', $hidden = '', $value = '', $disabled = '', $selected = ''){
	parent::__construct($name, $id, $hidden, $value);
	$this->disabled = $disabled;
	$this->selected = $selected;
    }
    function getHTML(){
	return '<option id="' . $this->id . '" ' . check_if_empty('disabled', $this->disabled) . ' selected="' . $this->selected . '" ' . check_if_empty('hidden', $this->hidden) . ' value="' . $this->value .  '">' . $this->value .  '</option>';
    }
}
class OptGroupField extends Field{
    protected $disabled = '';
    protected $options = array();
    function __construct($id = '', $name = '', $hidden = '', $value = '', $disabled = ''){
	parent::__construct($name, $id, $hidden, $value);
	$this->disabled = $disabled;
    }
    function addoptionField($option){
	array_push($this->options, $option);
    }
    function getHTML(){
	$option_HTML = '';
	foreach($this->options as $option){
	    $option_HTML .= $option->getHTML();
	}
	return '<optgroup id="' . $this->id . '"' . check_if_empty('disabled', $this->disabled) . ' ' . check_if_empty('hidden', $this->hidden) . '  >' . $option_HTML . '</optgroup>';
    }
}
class SelectField extends Field{
    protected $multiple = '';
    protected $options = array();
    protected $optgroups = array();
    function __construct($id = '', $name = '', $hidden = '', $value = '', $multiple){
	parent::__construct($name, $id, $hidden, $value);
	$this->multiple = $multiple;
    }
    function addoptionField($option){
	array_push($this->options, $option);
    }
    function addoptgroupField($optgroup){
	array_push($this->optgroups, $optgroup);
    }
    function getHTML(){
	$option_HTML = '';
	foreach($this->options as $option){
	    $option_HTML .= $option->getHTML();
	}
	$optgroup_HTML = '';
	foreach($this->optgroups as $optgroup){
	    $optgroup_HTML .= $optgroup->getHTML();
	}
	$html = '<select id="' . $this->id . '" name="' . $this->name . '" ' . check_if_empty('hidden', $this->hidden) . '  ' . check_if_empty('multiple', $this->multiple) . '>' . $option_HTML . $optgroup_HTML . '</select>';
	$return_html = add_div_with_css($html, 'controls');
	return $return_html;
    }
}
class CheckboxGroup extends Field{
    protected $checkboxes = array();
    function __construct($id, $name, $hidden, $value = ''){
	parent::__construct($name, $id, $hidden, $value);
    }
    function addcheckboxField($checkbox){
	array_push($this->checkboxes, $checkbox);
    }
    function getHTML(){
	$checkbox_HTML = '';
	foreach($this->checkboxes as $checkbox){
	    $checkbox_HTML .= $checkbox->getHTML();
	}
	$html = '<div id="' . $this->id .  '" ' . check_if_empty('hidden', $this->hidden) . '>' . $checkbox_HTML . '</div>';
	$return_html = add_div_with_css($html, 'controls');
	return $return_html;
    }
}
class RadioboxGroup extends Field{
    protected $radioboxes = array();
    function __construct($id, $name, $hidden, $value = ''){
	parent::__construct($name, $id, $hidden, $value);
    }
    function addradioboxField($radiobox){
	array_push($this->radioboxes, $radiobox);
    }
    function getHTML(){
	$radiobox_HTML = '';
	foreach($this->radioboxes as $radiobox){
	    $radiobox_HTML .= $radiobox->getHTML();
	}
	$html = '<div id="' . $this->id .  '" ' . check_if_empty('hidden', $this->hidden) . '>' . $radiobox_HTML . '</div>';
	$return_html = add_div_with_css($html, 'controls');
	return $return_html;
    }
}
function add_div_with_css($field_html, $css = 'control-group'){
    return '<div class="' . $css . '">' . $field_html . '</div>';
}
function check_if_empty($attr_name, $attr_value){
    if ($attr_value and $attr_value != '' and $attr_value != 'false'){
	return $attr_name . ' = "' . $attr_value . '"';
    }
    return '';
}
?>