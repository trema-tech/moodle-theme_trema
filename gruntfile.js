module.exports = function(grunt) {
    grunt.initConfig({
        uglify: {
            options: {
                sourceMap: true
            },
            build: {
                files: [{
                    expand: true,
                    cwd: 'amd/src',
                    src: '*.js',
                    dest: 'amd/build',
                    ext: '.min.js'
                }]
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.registerTask('default', ['uglify']);
};
