# config valid only for Capistrano 3.1
# require 'capistrano/ext/multistage'
lock '3.5.0'

set :stages, ["api-production","api-staging"]
set :default_stage, "api-staging"
set :ssh_options, {:forward_agent => true}

set :application, 'formandsystem_api'
set :repo_url, 'git@github.com:lukasoppermann/formandsystem-api.git'
set :user, "lukasoppermann"
set :default_env, { path: "/usr/local/bin:$PATH" }

#set :linked_dirs, %w()

namespace :deploy do


    desc 'Print The Server Name'
    task :print_server_name do
      on roles(:app), in: :groups, limit:1 do
        execute "hostname"
      end
    end

    desc 'Composer install'
    task :composer_install do
        on roles(:app), in: :groups, limit:1 do
            execute "cp #{fetch(:deploy_to)}/shared/.env #{fetch(:release_path)}/.env"
            execute "/usr/local/bin/php5-56STABLE-CLI /kunden/373917_13187/composer.phar install --working-dir #{fetch(:release_path)} --no-scripts --no-dev"
        end
    end

end

after "deploy:updated", "deploy:composer_install"
