module.exports = function(grunt) {
	grunt.initConfig({
		// running `grunt less` will compile once
		less: {
			development: {
				options: {
					yuicompress: true
				},
				files: {
					"static/css/clubpage.css": [
	                           "static/css/bootstrap.less", 
	                           "static/css/clubpage.less", 
                               "static/css/font-awesome.less", 
                               "static/css/helpers.css"],
                    "static/css/admin.css": [
                               "static/css/bootstrap.less", 
               	               "static/css/clubpage.less", 
               	               "static/css/font-awesome.less", 
               	               "static/css/helpers.less", 
               	               "static/css/admin/admin.less", 
               	               "static/css/admin/cms.less"]
				}
			}
		},
		// running `grunt watch` will watch for changes
		watch: {
			files: ["static/css/*.less", "static/css/admin/*.less"],
			tasks: ["less"]
		}
	});
	grunt.loadNpmTasks('grunt-contrib-less');
	grunt.loadNpmTasks('grunt-contrib-watch');
};