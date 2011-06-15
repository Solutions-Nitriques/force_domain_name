<?php

	if(!defined("__IN_SYMPHONY__")) die("<h2>Error</h2><p>You cannot directly access this file</p>");

	/*
	Copyight: Solutions Nitriques 2011
	License: MIT
	*/
	class extension_force_domain_name extends Extension {
		
		/**
		 * Regular expression for validating a domain name
		 * @var string
		 */
		const REGEXP_DOMAIN = '/^([a-z0-9]([-a-z0-9]*[a-z0-9])?\\.)+((a[cdefgilmnoqrstuwxz]|aero|arpa)|(b[abdefghijmnorstvwyz]|biz)|(c[acdfghiklmnorsuvxyz]|cat|com|coop)|d[ejkmoz]|(e[ceghrstu]|edu)|f[ijkmor]|(g[abdefghilmnpqrstuwy]|gov)|h[kmnrtu]|(i[delmnoqrst]|info|int)|(j[emop]|jobs)|k[eghimnprwyz]|l[abcikrstuvy]|(m[acdghklmnopqrstuvwxyz]|mil|mobi|museum)|(n[acefgilopruz]|name|net)|(om|org)|(p[aefghklmnrstwy]|pro)|qa|r[eouw]|s[abcdeghijklmnortvyz]|(t[cdfghjklmnoprtvwz]|travel)|u[agkmsyz]|v[aceginu]|w[fs]|y[etu]|z[amw])$/i';

		const SETTING_NAME = 'domain';
		const SETTING_GROUP = 'force-domain';
		
		/**
		 * Credits for the extension
		 */
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
		
		/**
		 * Utiliy function that retreives the value of the setting
		 * @return string
		 */
		public function getDomainInUse($context) {
			return Symphony::Configuration()->get($this->SETTING_NAME, $this->SETTING_GROUP);
		}

		/**
		 * Delegate handle that resolve the page's url
		 * @param string $page
		 * @param array $context
		 */
		public function frontendPrePageResolve($context) {
			
		}
		
		/**
		 * Delegate handle that adds Custom Preference Fieldsets
		 * @param string $page
		 * @param array $context
		 */
		public function addCustomPreferenceFieldsets($context) {
			// creates the field set
			$fieldset = new XMLElement('fieldset');
			$fieldset->setAttribute('class', 'settings');
			$fieldset->appendChild(new XMLElement('legend', __('Force Domain Name')));

			// create a paragraph for short intructions
			$p = new XMLElement('p', __('Define here the domain name you wanna use (without http://)'), array('class' => 'help'));
			$fieldset->appendChild($p);
			
			// create the label and the input field
			$label = Widget::Label();
			$input = Widget::Input('settings[force-domain][domain]', $this->getDomainInUse($context), 'text');
			
			// set the input into the label
			$label->setValue(__('Domain Name'). ' ' . $input->generate());
			
			// append label to field set
			$fieldset->appendChild($label);

			
			// adds the field set to the wrapper
			$context['wrapper']->appendChild($fieldset);
		}
		
		/**
		 * Delegate handle that saves the preferences
		 * @param string $page
		 * @param array $context
		 */
		public function save($context){
			//var_dump($context['settings']['force-domain']['domain']);die;
			
			$domain = $context['settings']['force-domain']['domain'];
			
			// verify it is a good domain
			if (preg_match($this->REGEXP_DOMAIN, $domain)) {
				
				// set config                    (name, value, group)
				Symphony::Configuration()->set($this->SETTING_NAME, $domain, $this->SETTING_GROUP);
				
				// save it
				Administration::instance()->saveConfig();
				
			} else {
				// don't save ???
				// how to mark the field as error ???
				// please help me on this...
				echo '"' . $domain . '" is not a valid domain';
				die;
			}
		}

	}
	
?>