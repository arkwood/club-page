module.exports = function(grunt) {
     grunt.initConfig({
         less: {
             development: {
                 
                 files: {
                	 "static/css/clubpage.css": ["static/css/bootstrap.less", 
                	                             "static/css/clubpage.less", 
                	                             "static/css/font-awesome.less", 
                	                             "static/css/helpers.css"],
                	 "static/css/admin.css": ["static/css/bootstrap.less", 
                	               "static/css/clubpage.less", 
                	               "static/css/font-awesome.less", 
                	               "static/css/helpers.less", 
                	               "static/css/admin/admin.less", 
                	               "static/css/admin/cms.less"]
                 }
             }
         }
     });
     grunt.loadNpmTasks('grunt-contrib-less');
     grunt.registerTask('default', ['less']);
 };