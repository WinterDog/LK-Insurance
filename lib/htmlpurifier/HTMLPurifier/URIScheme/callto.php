<?php
	// replicated this class/file from '../htmlpurifier-4_4_0/library/HTMLPurifier/URIScheme/mailto.php'
	
	// VERY RELAXED! Shouldn't cause problems, ... but be careful!
	
	/**
	 * Validates 'callto:' phone number in URI to included only alphanumeric, hyphens, underscore, and optional leading "+"
	 */
	
	class HTMLPurifier_URIScheme_callto extends HTMLPurifier_URIScheme {
	
		public $browsable = false;
		public $may_omit_host = true;
	
		public function doValidate(&$uri, $config, $context) {
			$uri->userinfo = null;
			$uri->host     = null;
			$uri->port     = null;
	
			/* notes:
				where is the actual phone # parked?  Answer in $uri->path.  See here:
	
				echo '<pre style="color:pink;">';
				var_dump($uri);
				echo '</pre>';
	
				object(HTMLPurifier_URI)#490 (7) {
				  ["scheme"]=>
				  string(6) "callto"
				  ["userinfo"]=>
				  NULL
				  ["host"]=>
				  NULL
				  ["port"]=>
				  NULL
				  ["path"]=>
				  string(15) "+1-800-555-1212"
				  ["query"]=>
				  NULL
				  ["fragment"]=>
				  NULL
				}
			*/
	
			// are the characters in the submitted <a> href's (URI) value (callto:)  from amongst a legal/allowed set?
				// my legal phone # chars:  alphanumeric, underscore, hyphen, optional "+" for the first character.  That's it.  But you can allow whatever you want.  Just change this:
				$validCalltoPhoneNumberPattern = '/^\+?[a-zA-Z0-9_-]+$/i'; // <---whatever pattern you want to force phone numbers to match
				$proposedPhoneNumber = $uri->path;
				if (preg_match($validCalltoPhoneNumberPattern, $proposedPhoneNumber) !== 1) {
					// submitted phone # inside the href attribute value looks bad; reject the phone number, and let HTMLpurifier remove the whole href attribute on the submitted <a> tag.
					return FALSE;
				} else {
					// submitted phone # inside the href attribute value looks OK; accept the phone number; HTMLpurifier should NOT strip the href attribute on the submitted <a> tag.
					return TRUE;
				}
		}
	}