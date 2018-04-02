<?php
namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'scrapethat');

// Project repository
set('repository', 'git@gitlab.com:tadaspaplauskas/scrapethat.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', false); 

// Shared files/dirs between deploys 
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server 
add('writable_dirs', []);


// Hosts

host('scrapethat')
    ->user('root')
    ->set('deploy_path', '/var/www/scrapethat');
    
// Tasks

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'artisan:migrate');

after('deploy', 'artisan:queue:restart');
