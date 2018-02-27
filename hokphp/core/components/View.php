<?php 

namespace hokphp\core\components;

/**
* Bootstrap class
*/
class View
{
	/**
	 * Renders the specified view within the default layout file.
	 * @param  string 	$viewFile    The view file to be rendered
	 * @param  array  	$params      Values that will be sent to the view
	 * @param  boolean 	$withLayout  Wether to print print the layout aswell
	 * @param  string 	$layout 	 The layout file to use
	 * @return void 	 			 The html of the page or void
	 */
	public static function renderView($viewFile, $params = [], $withLayout = TRUE, $layout = 'main')
	{
		static::printOutput($viewFile, $params, $withLayout, $layout);
	}

	/**
	 * Prints the output of the view and layout if $withLayout is true
	 * @param  string  $viewFile   	The view to render
	 * @param  array   $params     	The params to send to the view
	 * @param  boolean $withLayout 	Wether to use a layout when rendering the view
	 * @param  string 	$layout 	The layout file to use
	 * @return void 			   	Prints the output
	 */
	public static function printOutput($viewFile, $params = [], $withLayout = TRUE, $layout = 'main')
	{
	    $output = NULL;
		$fullLayoutPath = "/var/www/html/hokphp/hokphp/core/views/layouts/".$layout.".php";
	    if(file_exists($fullLayoutPath)){
	        // Start output buffering
	        ob_start();
	        // Include the template file
	        if ($withLayout) {
	        	require $fullLayoutPath;
	        } else {
				$fullViewPath = "/var/www/html/hokphp/hokphp/core/views/".$viewFile.".php";
	        	extract($params);
	        	require $fullViewPath;
	        }
	        // End buffering and return its contents
	        $output = ob_get_clean();
	    } 
        print $output;
	}
}
