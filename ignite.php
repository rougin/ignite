<?php

/**
 * Ignite
 *
 * Deletes unwanted files and folders then installs a dependency package
 * manager for CodeIgniter 3
 *
 * @author 	Rougin Gutib <rougin.royce@gmail.com>
 * @link 	http://github.com/rougin/ignite
 */

/**
 * ---------------------------------------------------------------------------------------------
 * Get the CodeIgniter.php core file
 * ---------------------------------------------------------------------------------------------
 */

$codeigniter_core = file_get_contents('system/core/CodeIgniter.php');

/**
 * ---------------------------------------------------------------------------------------------
 * Contents for the composer.json
 * ---------------------------------------------------------------------------------------------
 */

$composer =
'{
	"description" : "EllisLab\'s Open Source PHP Framework http://codeigniter.com/",
	"name" : "ellislab/codeigniter",
	"require": {
		"doctrine/orm": "2.4.*",
		"rougin/combustor": "dev-master"
	}
}';

/**
 * ---------------------------------------------------------------------------------------------
 * Contents for the doctrine.php in vendor/bin
 * ---------------------------------------------------------------------------------------------
 */

$doctrine_cli =
'<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license. For more information, see
 * <http://www.doctrine-project.org>.
 */

use Symfony\Component\Console\Helper\HelperSet;
use Doctrine\ORM\Tools\Console\ConsoleRunner;

(@include_once __DIR__ . \'/../vendor/autoload.php\') || @include_once __DIR__ . \'/../../../autoload.php\';

/**
 * Path to the root folder
 */

define(\'ROOT\', str_replace(\'vendor/doctrine/orm/bin/doctrine.php\', \'\', __FILE__) . \'/\');

/**
 * Path to the "system" folder
 */

define(\'BASEPATH\', str_replace(\'\\\\\', \'/\', ROOT . \'system/\'));

/**
 * The path to the "application" folder
 */

define(\'APPPATH\', ROOT . \'application/\');

/**
 * Load the Doctrine Library
 */

require APPPATH . \'/libraries/Doctrine.php\';

$doctrine = new Doctrine();

$helperSet = require $doctrine->cli();

if ( ! ($helperSet instanceof HelperSet)) {
	foreach ($GLOBALS as $helperSetCandidate) {
		if ($helperSetCandidate instanceof HelperSet) {
			$helperSet = $helperSetCandidate;
			break;
		}
	}
}

\Doctrine\ORM\Tools\Console\ConsoleRunner::run($helperSet, $commands);';

/**
 * Contents for the Doctrine library
 */

$doctrine_library = 
'<?php

use Doctrine\Common\ClassLoader,
	Doctrine\ORM\Tools\Setup,
	Doctrine\ORM\EntityManager;

/**
 * Doctrine bootstrap library for CodeIgniter
 *
 * @author	Adam Elsodaney  <archfizz.co.uk>
 * @author  Rougin Gutib <rougin.royce@gmail.com>
 * @link	http://stackoverflow.com/questions/17121997/integrating-doctrine-with-codeigniter
 */

class Doctrine
{

	/**
	 * The variable for handling the entity manager
	 */
	public $em;

	/**
	 * Load the entity manager and load the classes
	 */
	public function __construct()
	{
		/**
		 * Load the database configuration from CodeIgniter
		 */
		
		require APPPATH . \'config/database.php\';

		$connection_options = array(
			\'driver\'        => \'pdo_mysql\',
			\'user\'          => $db[\'default\'][\'username\'],
			\'password\'      => $db[\'default\'][\'password\'],
			\'host\'          => $db[\'default\'][\'hostname\'],
			\'dbname\'        => $db[\'default\'][\'database\'],
			\'charset\'       => $db[\'default\'][\'char_set\'],
			\'driverOptions\' => array(
				\'charset\'   => $db[\'default\'][\'char_set\'],
			),
		);

		/**
		 * With this configuration, your model files need to be in application/models/
		 * e.g. Creating a new \User loads the class from application/models/User.php
		 */
		
		$models_namespace = \'\';
		$models = APPPATH . \'models\';
		$proxies = APPPATH . \'models/proxies\';
		$metadata_paths = array(APPPATH . \'models\');

		/**
		 * Set $dev_mode to TRUE to disable caching while you develop
		 */
		
		$config = Setup::createAnnotationMetadataConfiguration($metadata_paths, $dev_mode = true, $proxies);
		$this->em = EntityManager::create($connection_options, $config);

		$loader = new ClassLoader($models_namespace, $models);
		$loader->register();
	}

	/**
	 * The Command Line Interface (CLI) configuration for Doctrine
	 * 
	 * @return object
	 */
	public function cli()
	{
		foreach ($GLOBALS as $helperSetCandidate) {
			if ($helperSetCandidate instanceof \Symfony\Component\Console\Helper\HelperSet) {
				$helperSet = $helperSetCandidate;
				break;
			}
		}

		$helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
			\'db\' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($this->em->getConnection()),
			\'em\' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($this->em)
		));

		return \Doctrine\ORM\Tools\Console\ConsoleRunner::run($helperSet);
	}

}';

/**
 * ---------------------------------------------------------------------------------------------
 * The content for MY_Pagination.php
 * ---------------------------------------------------------------------------------------------
 */

$my_pagination =
'<?php if (! defined(\'BASEPATH\')) exit(\'No direct script access allowed\');

/**
 * @name    	MY_Pagination.php
 * @version 	1.0
 * @author  	Joost van Veen www.accentinteractive.nl
 * @created 	Sun Jul 27 16:27:26 GMT 2008 16:27:26
 *
 * A simple Pagination extension to make working with pagination a bit easier.
 * I created this lib because I had URIs in my app in which the paging element 
 * was not always in the same segment, which makes it a pain if you work with 
 * the default pagination class.
 * 
 * This simple lib accomplishes the following:
 * - It determines waht the \'base_url\' is, so you don\'t have to set it yourself
 * - It removes the need for you setting the infamous \'uri_segment\' setting
 * 
 * Basically,it sets paging at the end of the uri, without having to pass a uri 
 * segment. The library relies on a unique pagination selector, which it uses to 
 * determine if and where the pagnition offset is located in the URI. 
 * 
 * E.g. /example/pagination/Page/3
 * 
 * The lib searches for the pagination_selector (\'Page\', in the above example) 
 * and retracts the proper offset value (in this case 3)
 * 
 * The pagination links are automatically created, just as in CI\'s default 
 * pagination lib. 
 *
 * Requirements
 * Codeigniter 2+
 * PHP 5
 * A *unique* pagination selector (default is \'Page\') - unique meaning a string 
 * you are sure will never appear in the uri, except for pagination.
 * 
 * If there we use pagination, it must ALWAYS follow the following syntax and be
 * located at the END of the URI:
 * PAGINATION_SELECTOR/offset
 *
 * The PAGINATION_SELECTOR is a special string which we know will ONLY be in the
 * URI when paging is set. Let\'s say the PAGINATION_SELECTOR is \'Page\' (since most
 * coders never use any capitals in the URI, most of the times any string with
 * a single capital character in it will suffice). 
 *
 * Example use (in controller):
 * 
 * // Initialize pagination
 * $config[\'total_rows\'] = $this->db->count_all_results(\'my_table\');
 * $config[\'per_page\'] = 10; // You\'d best set this in a config file, but hey
 * $this->pagination->initialize($config);
 * $this->data[\'pagination\'] = $this->pagination->create_links();
 *
 * // Retrieve paginated results, using the dynamically determined offset
 * $this->db->limit($config[\'per_page\'], $this->pagination->offset);
 * $query = $this->db->get(\'my_table\');
 *
 */
class MY_Pagination extends CI_Pagination
{

	public $offset = 0;
	public $pagination_selector = \'page\';
	public $index_page;

	public function MY_Pagination() {
		parent::__construct();

		log_message(\'debug\', "MY_Pagination Class Initialized");
		
		$this->index_page = config_item(\'index_page\') != \'\' ? config_item(\'index_page\') . \'/\' : \'\';
		$this->_set_pagination_offset();
	}

	/**
	 * Set dynamic pagination variables in $CI->data[\'pagvars\']
	 */
	public function _set_pagination_offset() {
		/**
		 * Instantiate the CI super object so we have access to the uri class
		 */
		
		$CI = & get_instance();
		
		/**
		 * Store pagination offset if it is set
		 */

		if (strstr($CI->uri->uri_string(), $this->pagination_selector)) {
			/**
			 * Get the segment offset for the pagination selector
			 */

			$segments = $CI->uri->segment_array();
			
			/**
			 * Loop through segments to retrieve pagination offset
			 */

			foreach ($segments as $key => $value) {
				/**
				 * Find the pagination_selector and work from there
				 */
				
				if ($value == $this->pagination_selector) {
					
					/**
					 * Store pagination offset
					 */
					
					$this->offset = $CI->uri->segment($key + 1);
					
					/**
					 * Store pagination segment
					 */
					
					$this->uri_segment = $key + 1;
					
					/**
					 * Set base url for paging. This only works if the
					 * pagination_selector and paging offset are AT THE END of
					 * the URI!
					 */
					
					$uri = $CI->uri->uri_string();
					$pos = strpos($uri, $this->pagination_selector);
					$this->base_url = config_item(\'base_url\') . $this->index_page . substr($uri, 0, $pos + strlen($this->pagination_selector));
				}
			
			}
		
		} else {
			/**
			 * Pagination selector was not found in URI string. So offset is 0
			 */
			
			$this->offset = 0;
			$this->uri_segment = 0;
			$this->base_url = config_item(\'base_url\') . $this->index_page . $CI->uri->uri_string() . \'/\' . $this->pagination_selector;
		
		}
	
	}

}';

/**
 * ---------------------------------------------------------------------------------------------
 * Files and folder to be deleted
 * ---------------------------------------------------------------------------------------------
 */

$files_to_be_deleted = array(
	'.gitignore',
	'.travis.yml',
	'composer.json',
	'contributing.md',
	'DCO.txt',
	'license.txt',
	'license_afl.txt',
	'phpdoc.dist.xml',
	'readme.rst'
);

$folders_to_be_deleted = array(
	'tests',
	'user_guide_src'
);

/**
 * ---------------------------------------------------------------------------------------------
 * Contents for .htacess
 * ---------------------------------------------------------------------------------------------
 */

$htaccess =
'RewriteEngine on
Options +FollowSymLinks
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-l
RewriteRule .* index.php/$0 [PT,L]';

/**
 * Remove the directory including its files
 * 
 * @param  string $dir
 */
function remove_directory($dir) {
	if (is_dir($dir)) {
		$objects = scandir($dir);
		
		foreach ($objects as $object) {
			if ($object != "." && $object != "..") {
				if (filetype($dir."/".$object) == "dir") {
					remove_directory($dir."/".$object);
				} else {
					unlink($dir."/".$object);
				}
			}
		}

		reset($objects);
		rmdir($dir);
	}
}

/**
 * ---------------------------------------------------------------------------------------------
 * Deleting unwanted files
 * ---------------------------------------------------------------------------------------------
 */

echo 'Deleting unwanted files... ', PHP_EOL;

foreach ($files_to_be_deleted as $file) {
	if (file_exists($file)) {
		echo '  Deleting ', $file, '... ', PHP_EOL;
		
		unlink($file);
	}
}

/**
 * ---------------------------------------------------------------------------------------------
 * Deleting unwanted folders
 * ---------------------------------------------------------------------------------------------
 */

echo 'Deleting unwanted folders... ', PHP_EOL;

foreach ($folders_to_be_deleted as $folder) {
	if (is_dir($folder)) {
		echo '  Deleting ', $folder, '... ', PHP_EOL;
		
		remove_directory($folder);
	}
}

/**
 * ---------------------------------------------------------------------------------------------
 * Creates a .htacess for clean urls, removes index.php and adds an encryption key from the 
 * configuration
 * ---------------------------------------------------------------------------------------------
 */

echo 'Creating a .htacess for clean URLs... ', PHP_EOL;

$file = fopen('.htaccess', 'wb');
chmod('.htaccess', 0777);
file_put_contents('.htaccess', $htaccess);
fclose($file);

$file = file_get_contents('application/config/config.php');
$search = array('$config[\'index_page\'] = \'index.php\';', '$config[\'encryption_key\'] = \'\';');
$replace = array('$config[\'index_page\'] = \'\';', '$config[\'encryption_key\'] = \'' . md5('rougin') . '\';');
$file = str_replace($search, $replace, $file);
file_put_contents('application/config/config.php', $file);

/**
 * ---------------------------------------------------------------------------------------------
 * Extended the Pagination class and add a pagination selector in the routes.php
 * ---------------------------------------------------------------------------------------------
 */

$file = fopen('application/libraries/MY_Pagination.php', 'wb');
chmod('application/libraries/MY_Pagination.php', 0777);
file_put_contents('application/libraries/MY_Pagination.php', $my_pagination);
fclose($file);

$routes = file_get_contents('application/config/routes.php');
$search = '$route[\'default_controller\'] = \'welcome\';
$route[\'404_override\'] = \'\';';
$replace = '$route[\'default_controller\'] = \'welcome\';
$route[\'(:any)/page/(:any)\'] = \'$1/index/page/$2\';
$route[\'(:any)/page\'] = \'$1\';
$route[\'404_override\'] = \'\';';
$routes = str_replace($search, $replace, $routes);

file_put_contents('application/libraries/MY_Pagination.php', $routes);

/**
 * ---------------------------------------------------------------------------------------------
 * Adding the composer autoload file in index.php
 * ---------------------------------------------------------------------------------------------
 */

$index = file_get_contents('index.php');
$search = '	define(\'VIEWPATH\', $view_folder);

/*
 * --------------------------------------------------------------------
 * LOAD THE BOOTSTRAP FILE
 * --------------------------------------------------------------------';
$replace =
'	define(\'VIEWPATH\', $view_folder);

/*
 * --------------------------------------------------------------------
 * LOAD THE COMPOSER AUTOLOAD FILE
 * --------------------------------------------------------------------
 */
include_once \'vendor/autoload.php\';';

$index = str_replace($search, $replace, $index);

$file = fopen('index.php', 'wb');
file_put_contents('index.php', $index);
fclose($file);

/**
 * ---------------------------------------------------------------------------------------------
 * Install Composer and its dependencies
 * ---------------------------------------------------------------------------------------------
 */

$file = fopen('composer.json', 'wb');
file_put_contents('composer.json', $composer);
fclose($file);
system('composer update');

/**
 * ---------------------------------------------------------------------------------------------
 * Modify the contents of vendor/bin/doctrine.php and create the Doctrine library
 * ---------------------------------------------------------------------------------------------
 */

file_put_contents('vendor/bin/doctrine.php', $doctrine_cli);

$file = fopen('application/libraries/Doctrine.php', 'wb');
file_put_contents('application/libraries/Doctrine.php', $doctrine_library);
fclose($file);

/**
 * ---------------------------------------------------------------------------------------------
 * Autoload the Doctrine library and the other libraries and helpers
 * ---------------------------------------------------------------------------------------------
 */

$session = (strpos($codeigniter_core, 'define(\'CI_VERSION\', \'3.0-dev\')') === FALSE) ? ', \'session\'' : '';

$autoload = file_get_contents('application/config/autoload.php');
$search = array('$autoload[\'libraries\'] = array();', '$autoload[\'helpers\'] = array();');
$replace = array('$autoload[\'libraries\'] = array(\'doctrine\'' . $session . ');', '$autoload[\'libraries\'] = array(\'url\', \'form\');');

if (strpos($codeigniter_core, 'define(\'CI_VERSION\', \'3.0-dev\')') !== FALSE) {
	$search[] = '$autoload[\'drivers\'] = array(\'\');';
	$replace[] = '$autoload[\'drivers\'] = array(\'session\');';
}

$contents = str_replace($search, $replace, $autoload);
file_put_contents('application/config/autoload.php', $contents);

/**
 * ---------------------------------------------------------------------------------------------
 * Include the Base Model class in Doctrine CLI
 * ---------------------------------------------------------------------------------------------
 */

$abstract_command = file_get_contents('vendor/doctrine/orm/lib/Doctrine/ORM/Tools/Console/Command/SchemaTool/AbstractCommand.php');
$search = 'use Doctrine\ORM\Tools\SchemaTool;';
$replace = 'use Doctrine\ORM\Tools\SchemaTool;

include BASEPATH . \'core/Model.php\';';

$contents = str_replace($search, $replace, $abstract_command);
file_put_contents('vendor/doctrine/orm/lib/Doctrine/ORM/Tools/Console/Command/SchemaTool/AbstractCommand.php', $contents);