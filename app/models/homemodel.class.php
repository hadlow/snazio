<?php

class HomeModel
{
	public function widgets()
	{
		global $data;
		$html = '';

		if(isset($data['home_widgets']))
		{
			foreach($data['home_widgets'] as $widget)
			{
				$html .= '<div class="col-sm-6 bg">';
				$html .= $widget;
				$html .= '</div>';
			}
		}

		return $html;
	}
}