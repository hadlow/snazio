<?php

/*
|--------------------------------------------------------------------------
| Global functions
|--------------------------------------------------------------------------
|
| All global functions that are used throughout the CMS are located
| here.
|
| Last modified: 22/11/14
|
*/

use \Michelf\MarkdownExtra;

function root()
{
	$request_url = (isset($_SERVER['REQUEST_URI'])) ? $_SERVER['REQUEST_URI'] : '';
	$script_url  = (isset($_SERVER['PHP_SELF'])) ? $_SERVER['PHP_SELF'] : '';

	$script_url = str_replace('index.php', '', $script_url);

	return "http://$_SERVER[HTTP_HOST]" . $script_url;
}

/*
	@get_files
	@input string, string
	@output array
*/
function get_files($directory, $ext = '')
{
	$array_items = array();

	if($handle = opendir($directory))
	{
		while(false !== ($file = readdir($handle)))
		{
			if(preg_match("/^(^\.)/", $file) === 0)
			{
				if(is_dir($directory. "/" . $file))
				{
					$array_items = array_merge($array_items, get_files($directory. "/" . $file, $ext));
				} else {
					$file = $directory . "/" . $file;
					if(!$ext || strstr($file, $ext)) $array_items[] = preg_replace("/\/\//si", "/", $file);
				}
			}
		}

		closedir($handle);
	}

	return $array_items;
}

/*
	@get_files_array
	@input string
	@output array
*/
function get_files_array($directory)
{
	$files = scandir($directory);
	$breakdown = array();

    foreach($files as $file)
    {
    	if($file != '.' && $file != '..')
        {
            if(is_dir($directory . '/' . $file))
            {
            	$breakdown[$file] = get_files_array($directory . '/' . $file);
            } else {
            	$breakdown[] = $file;
            }
        }
    }

	return $breakdown;
}

function get_page($base_url, $parse_markdown = false)
{
	global $config;

	if(!isset($config))
	{
		$config = get_config();
	}

	$sorted_pages = array();
	$date_id = 0;
	$url = '';

	$page_content = file_get_contents($base_url . 'index.md');
	$page_meta = read_file_meta($base_url . 'page.json');

	$page_content = parse_content($page_content, $parse_markdown);

	$url = str_replace(FileSystem::path(array('_', 'content')), "", $base_url);
	$path = str_replace('index' . '.md', '', $base_url);

	$data = array(
		'title' => isset($page_meta['title']) ? $page_meta['title'] : '',
		'url' => $url,
		'description' => isset($page_meta['description']) ? $page_meta['description'] : '',
		'author' => isset($page_meta['author']) ? $page_meta['author'] : '',
		'date' => isset($page_meta['date']) ? $page_meta['date'] : '',
		'content' => $page_content,
		'path' => $path,
		'template' => isset($page_meta['template']) ? $page_meta['template'] : '',
		'excerpt' => limit_words($page_content, $config['excerpt_length'])
	);

	return $data;
}

/*
 * Depreciated function! If found to be used anywhere, replace with get_page
 *
function get_post($base_url, $parse_markdown = true)
{
	global $config;
	$sorted_pages = array();
	$date_id = 0;
	$url = '';

	$page_content = file_get_contents($base_url . 'index.md');
	$page_meta = read_file_meta($base_url . 'post.json');

	$page_content = parse_content($page_content, $parse_markdown);

	$url = str_replace(FileSystem::path(array('_', 'content')), "", $base_url);
	$path = str_replace('content' . CONTENT_EXT, '', $base_url);

	$data = array(
		'title' => isset($page_meta['title']) ? $page_meta['title'] : '',
		'author' => isset($page_meta['author']) ? $page_meta['author'] : '',
		'date' => isset($page_meta['date']) ? $page_meta['date'] : '',
		'date_formatted' => isset($page_meta['date']) ? date($config['date_format'], strtotime($page_meta['date'])) : '',
		'content' => $page_content,
		'path' => $path
	);

	return $data;
}
*/

function parse_content($content, $parse_markdown = false)
{
	$content = preg_replace('#/\*.+?\*/#s', '', $content);
	$content = str_replace('%base_url%', ROOT_DIR, $content);

	if($parse_markdown)
	{
		$content = MarkdownExtra::defaultTransform($content);
	}

	return $content;
}

function limit_words($string, $word_limit)
{
	$words = explode(' ',$string);
	$excerpt = trim(implode(' ', array_splice($words, 0, $word_limit)));

	if(count($words) > $word_limit)
	{
		$excerpt .= '&hellip;';
	}

	return $excerpt;
}

function get_config()
{
	return json_decode(file_get_contents(FileSystem::path(array('app', 'config', 'config.json'))), true);
}

function read_file_meta($page)
{
	$headers = array(
		'title' => 'Title',
		'author' => 'Author',
		'description' => 'Description',
		'date' => 'Date',
		'template' => 'Template'
	);

 	$headers = json_decode(file_get_contents($page), true);

	return $headers;
}

/*
	@function clean_url
	@input string
	@output string
*/
function clean_url($url)
{
	$url = preg_replace("/[^a-z0-9\s\-]/i", "", $url);
	$url = preg_replace("/\s\s+/", " ", $url);
	$url = trim($url);
	$url = preg_replace("/\s/", "-", $url);
	$url = preg_replace("/\-\-+/", "-", $url);
	$url = preg_replace("/^\-|\-$/", "", $url);
	$url = strtolower($url);

	return $url;
}

function parse_field($field = 'text', $name = 'undefined', $label = 'Undefined', $value = '')
{
	$html = '';

	$html .= '<div class="form-group">';
	$html .= '<label for="' . $name . '">' . $label . '</label>';

	$value = htmlentities($value);

	switch($field)
	{
		case 'textarea':
			$html .= '<textarea class="form-control" name="' . $name . '" id="' . $name . '">' . $value . '</textarea>';
			break;

		case 'text':
			$html .= '<input type="text" class="form-control" name="' . $name . '" id="' . $name . '" value="' . $value . '" />';
			break;

		case 'image':
			$html .= '<input type="text" class="form-control" name="' . $name . '" id="' . $name . '" value="' . $value . '" />';
			break;

		case 'date':
			$html .= '<input type="date" class="form-control" name="' . $name . '" id="' . $name . '" />';
			break;

		case 'wysiwyg':
			$html .= '<textarea class="form-control wysiwyg" name="' . $name . '" id="' . $name . '">' . $value . '</textarea>';
			break;

		case 'menu':
			$html .= '<textarea class="form-control" name="' . $name . '" id="' . $name . '">' . $value . '</textarea>';
			break;
	}

	$html .= '</div>';

	return $html;
}

function my_curl($url, $get_array = array(), $timeout = 3, $error_report = TRUE)
{
    $get_string = NULL;
    foreach ($get_array as $key => $val)
    {
        $get_string
        = $get_string
        . $key
        . '='
        . urlencode($val)
        . '&';
    }
    $get_string = rtrim($get_string, '&');
    if (!empty($get_string)) $url .= '?' . $get_string;

    $curl = curl_init();

    $header[] = "Accept: text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
    $header[] = "Cache-Control: max-age=0";
    $header[] = "Connection: keep-alive";
    $header[] = "Keep-Alive: 300";
    $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
    $header[] = "Accept-Language: en-us,en;q=0.5";
    $header[] = "Pragma: "; // BROWSERS USUALLY LEAVE THIS BLANK

    curl_setopt( $curl, CURLOPT_URL,            $url  );
    curl_setopt( $curl, CURLOPT_USERAGENT,      'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6'  ); // HISTORY
    curl_setopt( $curl, CURLOPT_USERAGENT,      'Mozilla/5.0 (Windows NT 6.1; rv:22.0) Gecko/20100101 Firefox/22.0'  );
    curl_setopt( $curl, CURLOPT_HTTPHEADER,     $header  );
    curl_setopt( $curl, CURLOPT_REFERER,        'http://www.google.com'  );
    curl_setopt( $curl, CURLOPT_ENCODING,       'gzip,deflate'  );
    curl_setopt( $curl, CURLOPT_AUTOREFERER,    TRUE  );
    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, TRUE  );
    curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, TRUE  );
    curl_setopt( $curl, CURLOPT_TIMEOUT,        $timeout  );

    curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, FALSE );

    $htm = curl_exec($curl);

    if ($htm === FALSE)
    {
        if ($error_report)
        {
            $err = curl_errno($curl);
            $inf = curl_getinfo($curl);
            echo "CURL FAIL: $url TIMEOUT=$timeout, CURL_ERRNO=$err";
            var_dump($inf);
        }
        curl_close($curl);
        return FALSE;
    }

    curl_close($curl);
    return $htm;
}

function delete_contents($dir)
{
	$files = glob($dir . '*');

	foreach($files as $file)
	{
		if(is_file($file))
		{
			unlink($file);
		}
	}
}

function timeago($time)
{
	$periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
	$lengths = array("60","60","24","7","4.35","12","10");

	$now = time();

	$difference = $now - $time;
	$tense = "ago";

	for($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++)
	{
		$difference /= $lengths[$j];
	}

	$difference = round($difference);

	if($difference != 1)
	{
		$periods[$j].= "s";
	}

	return "$difference $periods[$j]";
}

function date_formats($time)
{
	return array(
		'timeago' => timeago($time),
		'worded' => date("F j, Y", $time),
		'numbered' => date("d.m.y", $time)
	);
}

function get_user($username)
{
	$user = array();
	$userfile = FileSystem::path(array('app', 'config', 'users', $username . '.json'));

	if(file_exists($userfile))
	{
		$user = json_decode(file_get_contents($userfile), true);

		// A bit of security
		$user['password'] = '';
	} else {
		$user = array(
			'name' => 'Non existing author'
		);
	}

	return $user;
}
