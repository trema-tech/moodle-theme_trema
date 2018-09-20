define(['theme_trema/particles'], function() {
    return {
        init: function($particles_config) {
            window.particlesJS('page-wrapper', JSON.parse($particles_config));
        }
    };
});