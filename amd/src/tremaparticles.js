define(['theme_trema/particles'], function() {
	
	/* particlesJS.load(@dom-id, @path-json, @callback (optional)); */
	particlesJS.load('page-login-index', 'http://moodle.local/theme/trema/particles.json', function() {
	  console.log('callback - particles.js config loaded');
	});
	
});