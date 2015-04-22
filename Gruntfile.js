/* global module */

module.exports = function( grunt ) {
	var cfg = {
		pkg: grunt.file.readJSON('package.json'),
		phplint: {
			files: [
				'*.php',
				'**/*.php',
				'!node_modules/**/*.php'
			]
		},
		jshint: {
			options: grunt.file.readJSON('.jshintrc'),
			src: [
				'*.js',
				'js/*.js',
				'js/**/*.js'
			]
		},
		sass: {
			expanded: {
				options: {
					style:  'expanded',
					banner: '/*!\n'+
							' * Do not modify this file directly.  It is compiled Sass code.\n'+
							' */'
				},
				files: [{
					expand: true,
					cwd:    'scss',
					src: [
						'*.scss'
					],
					dest:   'css',
					ext:    '.css'
				}]
			},
			minified: {
				options: {
					style:     'compressed'
				},
				files: [{
					expand: true,
					cwd:    'scss',
					src: [
						'*.scss'
					],
					dest:   'css',
					ext:    '.min.css'
				}]
			}
		},
		makepot: {
			goals: {
				options: {
					domainPath: '/languages',
					exclude: [
						'node_modules'
					],
					mainFile:    'goals.php',
					potFilename: 'goals.pot'
				}
			}
		},
		addtextdomain: {
			goals: {
				options: {
					textdomain: 'goals'
				},
				files: {
					src: [
						'*.php',
						'**/*.php',
						'!node_modules/**'
					]
				}
			}
		}
	};

	grunt.initConfig( cfg );

	grunt.loadNpmTasks('grunt-phplint');
	grunt.loadNpmTasks('grunt-contrib-jshint');
	grunt.loadNpmTasks('grunt-contrib-sass');
	grunt.loadNpmTasks('grunt-wp-i18n');

};

