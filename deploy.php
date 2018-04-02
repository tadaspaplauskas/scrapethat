<?php
namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'datascraper');

// Project repository
set('repository', 'git@gitlab.com:tadaspaplauskas/datascraper.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', false); 

// Shared files/dirs between deploys 
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server 
add('writable_dirs', []);


// Hosts

host('datascraper')
    ->stage('production')
    ->user('root')
    ->set('deploy_path', '/var/www/datascraper');
    
// Tasks

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'artisan:migrate');

after('deploy', 'artisan:queue:restart');
