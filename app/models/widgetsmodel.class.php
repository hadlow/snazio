<?php

class WidgetsModel
{
	public function edit_partials()
	{
		global $theme;
		$partials = '';
		$widgets = $theme->widgets;
		$vars = '';
		$js = '';
		$vardata = '';

		foreach($widgets[0] as $widget)
		{
			// get field data
			if(file_exists(ROOT_DIR . '_/widgets/' . $widget->name . '.md'))
			{
				$filepath = ROOT_DIR . '_/widgets/' . $widget->name . '.md';
				$file = new File($filepath, 'r');

				if(filesize($filepath) != 0)
				{
					$fielddata = $file->read();
					$file->close();
				} else {
					$fielddata = '';
				}
			} else {
				$fielddata = '';
			}
			
			// wysiwyg type
			if($widget->type == "wysiwyg")
			{
				$vars .= 'var ' . $widget->name . ' = $("#' . $widget->name . '").code();' . "\n";
			} else {
				$vars .= 'var ' . $widget->name . ' = $("#' . $widget->name . '").val();' . "\n";
			}
			
			$vardata .= $widget->name . ': ' . $widget->name . ', ';

			$input = parse_field($widget->type, $widget->name, $widget->label, $fielddata);

			$partials .=  $input;
		}

		$js = $vars . "
		
		$.ajax({
			url: '" . ADMIN_URL . "ajax/partial_edit/',
			type: 'post',
			data: {
				" . $vardata . "
			},
			dataType: 'json',
			success: function(data){
				if(data.success !== undefined)
				{
					popup('success');
					console.log(data.data);
				} else {
					popup('error');
				}
			},
			error: function(data){
				popup('error');
			}
		});
		";

		return $data = array_merge(array('partials' => $partials), array('js' => $js));
	}
}