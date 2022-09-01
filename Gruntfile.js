!(function () {
	'use strict';
	module.exports = function (grunt){
		const sass = require('node-sass');

		grunt.initConfig({
            pkg: grunt.file.readJSON('package.json'),
            
            sass: {
                options: {
                    implementation: sass,
                    sourceMap: true,
                    outputStyle: 'compressed',                   
                },
                dist: {
                    files: {
                        'public/assets/css/custom.min.css': 'public/assets/scss/frontend.scss',
                        'public/assets/css/admin.min.css': 'public/assets/scss/admin.scss',
                    }
                }

            },
           
            watch: {
                scripts: {
                    files: ['public/assets/scss/***/*.scss','public/assets/scss/**/*.scss','public/assets/scss/*/*.scss',
                    ['Gruntfile.js']],
                    tasks: ['sass']

                }
            }

        });

        grunt.loadNpmTasks('grunt-sass');           
        grunt.loadNpmTasks('grunt-contrib-watch');
        // Default task(s).
        grunt.registerTask('default', ['sass','watch']);

	};
})();