module.exports = function (grunt) {

	grunt.initConfig({

		pkg: grunt.file.readJSON('package.json'),
		banner: '/**' + '\r\n'
		      + ' * <%= pkg.name %>' + '\r\n'
		      + ' * ' + '\r\n'
		      + ' * <%= pkg.author.name %>' + '\r\n'
		      + ' * Copyright (c) 2015 <%= pkg.author.name %>. All rights reserved.' + '\r\n'
		      + ' * <%= pkg.homepage %>' + '\r\n'
		      + ' * <%= pkg.version %>' + '\r\n'
		      + ' */' + '\r\n',

		less: {
			options: {
				sourceMap: true,
				banner: '<%= banner %>'
			},
			bootstrap: {
				options: {
					sourceMapFilename: 'resources/css/bootstrap.css.map'
				},
				files: {
					'resources/css/bootstrap.css': 'resources/less/bootstrap/bootstrap.less'
				}
			},
			alt: {
				options: {
					sourceMapFilename: 'resources/css/alt.css.map'
				},
				files: {
					'resources/css/alt.css': 'resources/less/alt/alt.less'
				}
			}
		},

		autoprefixer: {
			options: {
				browsers: ['last 2 versions', '> 2%'],
				map: true
			},
			bootstrap: {
				src: 'resources/css/bootstrap.css'
			},
			alt: {
				src: 'resources/css/alt.css'
			}
		},

		cssmin: {
			bootstrap: {
				files: {
					'resources/css/bootstrap.min.css': 'resources/css/bootstrap.css'
				}
			},
			alt: {
				files: {
					'resources/css/alt.min.css': 'resources/css/alt.css'
				}
			}
		},

		watch: {
			alt: {
				files: 'resources/less/alt/*.less',
				tasks: [
					'less:alt',
					'autoprefixer:alt',
					'cssmin:alt'
				]
			}
		}
	});

	grunt.loadNpmTasks('grunt-autoprefixer');
	grunt.loadNpmTasks('grunt-contrib-clean');
	grunt.loadNpmTasks('grunt-contrib-cssmin');
	grunt.loadNpmTasks('grunt-contrib-less');
	grunt.loadNpmTasks('grunt-contrib-watch');

	grunt.registerTask('default', [
		'less:alt',
		'autoprefixer:alt',
		'cssmin:alt',
	]);
};