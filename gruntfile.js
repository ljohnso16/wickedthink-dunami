module.exports = function(grunt) {
    grunt.initConfig({

        uglify: {
            options: {
                   mangle: false
                },
                dist: {
                    files: {
                        'js/common.min.js': ['js/common.js']
                }
            }
        },
        compass: {
            dist: {
                options: {
                   config: './config.rb'
                }
            }
        },
        watch: {
            files: ['js/common.js', 'sass/*.scss'],
            tasks: ['uglify', 'compass']
        }                  
 
    });    
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-compass');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.registerTask('default', ['uglify', 'compass', 'watch']);


};