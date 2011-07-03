<?php

	if(!defined("__IN_SYMPHONY__")) die("<h2>Error</h2><p>You cannot directly access this file</p>");

	/*
	Copyight: Solutions Nitriques 2011
	License: MIT
	*/
	class extension_force_domain_name extends Extension {

		const EXT_NAME = 'Force Domain Name';

		/**
		 * Regular expression for validating a domain name
		 * @var string
		 * Credits: http://www.shauninman.com/archive/2006/05/08/validating_domain_names
		 */
		const REGEXP_DOMAIN = '/^([a-z0-9]([-a-z0-9]*[a-z0-9])?\\.)+((a[cdefgilmnoqrstuwxz]|aero|arpa)|(b[abdefghijmnorstvwyz]|biz)|(c[acdfghiklmnorsuvxyz]|cat|com|coop)|d[ejkmoz]|(e[ceghrstu]|edu)|f[ijkmor]|(g[abdefghilmnpqrstuwy]|gov)|h[kmnrtu]|(i[delmnoqrst]|info|int)|(j[emop]|jobs)|k[eghimnprwyz]|l[abcikrstuvy]|(m[acdghklmnopqrstuvwxyz]|mil|mobi|museum)|(n[acefgilopruz]|name|net)|(om|org)|(p[aefghklmnrstwy]|pro)|qa|r[eouw]|s[abcdeghijklmnortvyz]|(t[cdfghjklmnoprtvwz]|travel)|u[agkmsyz]|v[aceginu]|w[fs]|y[etu]|z[amw])$/i';

		/**
		 * Key of the domain setting
		 * @var string
		 */
		const SETTING_NAME = 'domain';

		/**
		 * Key of the group of setting
		 * @var string
		 */
		const SETTING_GROUP = 'force-domain';

		/**
		 * Header to mark the redirection as permanent (301)
		 * @var string
		 */
		const HEADER_MOVE = 'HTTP/1.1 301 Moved Permanently';


		/**
		 * private variable for holding the errors encountered when saving
		 * @var string
		 */
		protected $error = '';

		/**
		 * Credits for the extension
		 */
		public function about() {
			return array(
				'name'			=> self::EXT_NAME,
				'version'		=> '1.0',
				'release-date'	=> '2011-06-15',
				'author'		=> array(
					'name'			=> 'Solutions Nitriques',
					'website'		=> 'http://www.nitriques.com/',
					'email'			=> 'nico (at) nitriques.com'
				),
				'description'	=> 'Really simple ext that force user to a specified domain name',
				'compatibility' => array(
					'2.2.1' => true,
					'2.2' => true
				)
	 		);
		}

		public function getSubscribedDelegates(){
			return array(
				array(
					'page'		=> '/frontend/',
					'delegate'	=> 'FrontendPrePageResolve',
					'callback'	=> 'frontendPrePageResolve'
				),
				array(
					'page'		=> '/system/preferences/',
					'delegate'	=> 'AddCustomPreferenceFieldsets',
					'callback'	=> 'addCustomPreferenceFieldsets'
				),
				array(
					'page'      => '/system/preferences/',
					'delegate'  => 'Save',
					'callback'  => 'save'
				),
			);
		}

		/**
		 * Utiliy function that retreives the value of the setting
		 * @return string
		 */
		public function getDomainInUse() {
			return Symphony::Configuration()->get(self::SETTING_NAME, self::SETTING_GROUP);
		}

		/**
		 * Delegate handle that resolve the page's url
		 * @param string $page
		 * @param array $context
		 */
		public function frontendPrePageResolve($context) {
			// assure we can detect the current domain name
			// and that is it not localhost
			if (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] != 'localhost') {
				// domain in use
				$cur_domain = $_SERVER['HTTP_HOST'];
				// configured domain
				$conf_domain = $this->getDomainInUse();

				// if a domain was configured and domains does not match
				if (strlen($conf_domain) > 0 && $cur_domain != $conf_domain) {
					// redirect to good domain
					// while keeping the url intact

					// get the protocol
					$protocol = ($_SERVER['HTTPS'] ? 'https' : 'http') . '://';

					// get the uri
					$new_location = $_SERVER["REQUEST_URI"];

					$about = $this->about();

					// permanent redirect
					header('X-Redirected-By: ' . self::EXT_NAME);
					header(self::HEADER_MOVE);
					header("Location: $protocol$conf_domain$new_location");
					// stop process immediatly
					exit();
				}
			}
		}

	}

?>