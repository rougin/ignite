<?php

/**
 * Ignite
 *
 * Deletes unwanted files and folders then installs a dependency package
 * manager for CodeIgniter 3
 *
 * @author Rougin Gutib <rougin.royce@gmail.com>
 * @link http://github.com/rougin/ignite
 */

/**
 * ---------------------------------------------------------------------------------------------
 * CONTENTS FOR THE composer.json
 * ---------------------------------------------------------------------------------------------
 */

$composer =
'{
	"description" : "EllisLab\'s Open Source PHP Framework http://codeigniter.com/",
	"name" : "ellislab/codeigniter",
	"require": {
		"doctrine/orm": "2.4.*",
		"fzaninotto/Faker": "*"
	}
}';


/**
 * ---------------------------------------------------------------------------------------------
 * CONTENTS FOR THE doctrine.php in vendor/bin
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
 * CONTENTS FOR THE DOCTRINE LIBRARY
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
 * FILES AND FOLDER TO BE DELETED
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
	'.git',
	'tests',
	'user_guide_src'
);

/**
 * ---------------------------------------------------------------------------------------------
 * CONTENTS FOR .htacess
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
 * DELETING UNWANTED FILES
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
 * DELETING UNWANTED FOLDERS
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
 * CREATING A .htacess FOR CLEAN URLS
 * ---------------------------------------------------------------------------------------------
 */

echo 'Creating a .htacess for clean URLs... ', PHP_EOL;

$file = fopen('.htaccess', 'wb');
chmod('.htaccess', 0777);
file_put_contents('.htaccess', $htaccess);
fclose($file);

/**
 * ---------------------------------------------------------------------------------------------
 * ADDING THE COMPOSER AUTOLOAD FILE IN index.php
 * ---------------------------------------------------------------------------------------------
 */

$index = file_get_contents('index.php');
$search = '	define(\'VIEWPATH\', $view_folder);';
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
 * INSTALL COMPOSER
 * ---------------------------------------------------------------------------------------------
 */

$file = fopen('composer.json', 'wb');
file_put_contents('composer.json', $composer);
fclose($file);
system('composer update');

/**
 * ---------------------------------------------------------------------------------------------
 * MODIFY THE CONTENTS OF vendor/bin/doctrine.php
 * AND CREATE THE DOCTRINE LIBRARY
 * ---------------------------------------------------------------------------------------------
 */

file_put_contents('vendor/bin/doctrine.php', $doctrine_cli);

$file = fopen('application/libraries/Doctrine.php', 'wb');
file_put_contents('application/libraries/Doctrine.php', $doctrine);
fclose($file);

/**
 * ---------------------------------------------------------------------------------------------
 * AUTOLOAD DOCTRINE AND EXTEND THE MODEL BASE CLASS TO THE DOCTRINE LIBRARY
 * ---------------------------------------------------------------------------------------------
 */

$autoload = file_get_contents('application/config/autoload.php');
$contents = str_replace(
	'$autoload[\'libraries\'] = array();',
	'$autoload[\'libraries\'] = array(\'doctrine\');',
	$autoload
);
file_put_contents('application/config/autoload.php', $contents);

$model = file_get_contents('system/core/Model.php');
$search = array('class CI_Model {', 'log_message(\'debug\', \'Model Class Initialized\');');
$replace = array(
	'class CI_Model extends Doctrine {',
	'parent::__construct();' . "\n\n\t\t" .
	'log_message(\'debug\', \'Model Class Initialized\');'
);

$contents = str_replace($search, $replace, $model);
file_put_contents('system/core/Model.php', $contents);

/**
 * ---------------------------------------------------------------------------------------------
 * INCLUDE THE BASE MODEL CLASS IN DOCTRINE CLI
 * ---------------------------------------------------------------------------------------------
 */

$abstract_command = file_get_contents('vendor/orm/lib/Doctrine/ORM/Tools/Console/Command/SchemaTool/AbstractCommand.php');
$search = 'use Doctrine\ORM\Tools\SchemaTool;';
$replace = 'use Doctrine\ORM\Tools\SchemaTool;

include BASEPATH . \'core/Model.php\';';

$contents = str_replace($search, $replace, $abstract_command);
file_put_contents('vendor/orm/lib/Doctrine/ORM/Tools/Console/Command/SchemaTool/AbstractCommand.php', $contents);
?>
