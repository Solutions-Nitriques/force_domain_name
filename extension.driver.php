<?php

	if(!defined("__IN_SYMPHONY__")) die("<h2>Error</h2><p>You cannot directly access this file</p>");

	/*
	Copyight: Solutions Nitriques 2011
	License: MIT
	*/
	class extension_force_domain_name extends Extension {

		public function about() {
			return array(
				'name'			=> 'Force Domain Name',
				'version'		=> '1.0',
				'release-date'	=> '2011-06-15',
				'author'		=> array(
					'name'			=> 'Solutions Nitriques',
					'website'		=> 'http://www.nitriques.com/',
					'email'			=> 'nico@nitriques.com'
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

		public function frontendPrePageResolve($page='/frontend/', $context) {
			
		}
		
		public function addCustomPreferenceFieldsets($page='/system/preferences/', $context) {
			
		}
		
		public function save($page='/system/preferences/', $context){
			
		}

	}
	
?>