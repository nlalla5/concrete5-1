module.exports = function(grunt, config, parameters, done) {
	var workFolder = parameters.releaseWorkFolder || './release/source';
	function endForError(error) {
		process.stderr.write(error.message || error);
		done(false);
	}
	try {
		var path = require('path'),
			exec = require('child_process').exec,
			fs = require('fs');
		process.stdout.write('Installing PHP dependencies with Composer... ');
		exec(
			'composer install --no-dev',
			{
				cwd: workFolder
			},
			function(error, stdout, stderr) {
				if(error) {
					endForError(stderr || error);
					return;
				}
				process.stdout.write('done.\n');
				done();
			}
		);
	}
	catch(e) {
		endForError(e);
		return;
	}
};
