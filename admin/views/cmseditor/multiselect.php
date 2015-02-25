<?php
// $section, $sectionParameter, $sectionParameterType, $editor
echo '	<select multiple name="section' . $section->ID . 'param' . $sectionParameterType->name . '">
	 		<option value="" selected disabled>Please select</option>';
foreach (array_keys($editor->values) as $value) {
	'		<option value="' . $value . '" ' . ($sectionParameter->value == $value ? 'selected="selected"' : '') . '>' . $editor->values[$value] . '</option>';
}
echo '	</select>';
?>