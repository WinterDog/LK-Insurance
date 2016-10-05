module.exports = function (grunt)
{
	// 1. Вся настройка находится здесь
	grunt.initConfig(
	{
		pkg:			grunt.file.readJSON('package.json'),
		concat:
		{
			dist:
			{
				src:
				[
					'lib/jquery/jquery-2.2.4.min.js',
					'lib/jquery/jquery-ui-1.11.4/jquery-ui.min.js',
					'lib/jquery/blockui/jquery.blockUI.min.js',
					'lib/jquery/lof-jslider/js/jquery.easing.js',
					'lib/jquery/lof-jslider/js/script.js',
					'lib/jquery/maskedinput-1.4/jquery.maskedinput.min.js',
					'lib/jquery/owl.carousel-1.3.3/owl.carousel.min.js',

					'lib/bootstrap/bootstrap-3.3.7/js/bootstrap.min.js',
					'lib/bootstrap/bootstrap-select-1.11.0/dist/js/bootstrap-select.min.js',
					'lib/bootstrap/bootstrap-select-1.11.0/dist/js/i18n/defaults-ru_RU.min.js',
					'lib/bootstrap/moment-2.11.1/moment.ru.min.js',
					'lib/bootstrap/datetimepicker-4.15.35/js/bootstrap-datetimepicker.min.js',
					'lib/bootstrap/lightbox/js/ekko-lightbox.js',

					'js/core.js',
					'js/date_functions.js',
					'js/common.js',
					'js/app.js',
				],
				dest:   'js/main.js',
			},
		},
		uglify:
		{
			build:
			{
				src:	'js/main.js',
				dest:   'js/main.min.js',
			},
		},
        watch:
        {
            scripts:
            {
                files:          ['js/*.js', 'lib/**/*.js'],
                tasks:          ['concat', 'uglify'],
                options:
                {
                    spawn:      false,
                },
            },
        },
	});
	// 3. Тут мы указываем Grunt, что хотим использовать этот плагин
	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-imagemin');
	grunt.loadNpmTasks('grunt-contrib-watch');
	// 4. Указываем, какие задачи выполняются, когда мы вводим «grunt» в терминале
	grunt.registerTask('default', ['concat', 'uglify']);
};
