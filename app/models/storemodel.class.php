<?php

class StoreModel
{
	private $server = 'http://www.snaziodownloads.com/';
	
	public function get($id)
	{
		$html = array();
		
		// get downloads data from external server
		$data = my_curl($this->server . 'themes.php?type=single&id=' . $id);
		
		// turn data into object
		$data = SimpleXML_Load_String($data);
		$data = $data->items;
		
		foreach($data as $theme)
		{
			$html['title'] = $theme->item->title;
			$html['content'] = htmlentities($theme->item->content->p);
			$html['price'] = $theme->item->price;
			$html['link'] = $theme->item->link;
			$html['image'] = $theme->item->image;
			$html['tags'] = $theme->item->tags;
		}
		
		return $html;
	}
	
	public function get_themes()
	{
		$html = '';
		
		// get downloads data from external server
		$data = my_curl($this->server . 'themes.php');
		
		// turn data into object
		$data = SimpleXML_Load_String($data);
		$data = $data->items->item;
		
		foreach($data as $theme)
		{
			$pos = strpos($theme->data->p,' ',60);
			
			$html .= '<div class="col-sm-6 top">
				<a href="store/product/' . $theme->id . '">
					<div class="theme">
						<div class="image">
							<img src="' . $theme->image . '" width="100%" />
						</div>
						
						<p>
							' . $theme->title . ' - $' . $theme->price . '
						</p>
						
						<span>
							' . substr($theme->data->p,0,$pos) . ' [read more]
						</span>
					</div>
				</a>
			</div>';
		}
		
		return $html;
	}
}
