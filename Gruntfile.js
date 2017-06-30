module.exports = function (grunt) {
    "use strict";

    var jsLibs = [
        'node_modules/jquery/dist/jquery.js'
    ];

    var jsBootstrap = [
        'node_modules/bootstrap-sass/assets/javascripts/bootstrap/affix.js',
        'node_modules/bootstrap-sass/assets/javascripts/bootstrap/alert.js',
        'node_modules/bootstrap-sass/assets/javascripts/bootstrap/button.js',
        'node_modules/bootstrap-sass/assets/javascripts/bootstrap/carousel.js',
        'node_modules/bootstrap-sass/assets/javascripts/bootstrap/collapse.js',
        'node_modules/bootstrap-sass/assets/javascripts/bootstrap/dropdown.js',
        'node_modules/bootstrap-sass/assets/javascripts/bootstrap/modal.js',
        'node_modules/bootstrap-sass/assets/javascripts/bootstrap/popover.js',
        'node_modules/bootstrap-sass/assets/javascripts/bootstrap/scrollspy.js',
        'node_modules/bootstrap-sass/assets/javascripts/bootstrap/tab.js',
        'node_modules/bootstrap-sass/assets/javascripts/bootstrap/tooltip.js',
        'node_modules/bootstrap-sass/assets/javascripts/bootstrap/transition.js'
    ];

    var cssLibs = [
        'node_modules/bootstrap-sass/assets/stylesheets'
    ];

    var cssApp = [
        'src/va5ja/MailNotesBundle/Resources/public/scss/style.scss'
    ];

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        watch: {
            grunt: {
                files: ['Gruntfile.js']
            },

            sass: {
                files: cssApp,
                tasks: ['sass:dev']
            },

            js: {
                files: [
                    '<%= jshint.dist %>'
                ],
                tasks: ['concat', 'uglify']
            }
        },

        sass: {
            dev: {
                options: {
                    style: 'expanded',
                    loadPath: cssLibs
                },
                files: {
                    'web/static/css/style.css': cssApp
                }
            },
            dist: {
                options: {
                    style: 'compressed',
                    loadPath: cssLibs
                },
                files: {
                    'web/static/css/style.css': cssApp
                }
            }
        },

        jshint: {
            options: {
                jshintrc: '.jshintrc'
            },
            dist: [
                'Gruntfile.js',
                jsLibs,
                jsBootstrap
            ]
        },

        concat: {
            dist: {
                files: {'web/static/js/main.js': [jsLibs, jsBootstrap]}
            }
        },

        uglify: {
            options: {
                sourceMap: true,
                compress: {
                    drop_console: true
                },
                output: {
                    comments: /^!/
                },
                banner: '/*! <%= pkg.name %> - v<%= pkg.version %> - ' +
                '<%= grunt.template.today("yyyy-mm-dd") %> */'
            },
            dist: {
                files: {'web/static/js/main.min.js': ['web/static/js/main.js']}
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.registerTask('build', ['sass:dist', 'concat', 'uglify']);
};