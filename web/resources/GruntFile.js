module.exports = function(grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        sass: {
            options: {
                outputStyle: 'compressed',
                sourceMap: true,
            },
            sass: {
                files: {
                    'css/main.css': 'scss/main.scss'
                }
            }
        },

        watch: {
            options: {
                interrupt: true
            },
            sass:{
                files: ['**/*.scss'],
                tasks: ['sass:sass']
            }
        },

        concurrent: {
            options: {
                logConcurrentOutput: true
            },
            everything: {
                tasks: ['watch:sass']
            }
        }
    });

    grunt.loadNpmTasks('grunt-sass');
    grunt.loadNpmTasks('grunt-concurrent');
    grunt.loadNpmTasks('grunt-contrib-watch');
    
    grunt.registerTask("Watch", ["concurrent:everything"]);
    grunt.registerTask("Build", ["sass:sass"]);
};
