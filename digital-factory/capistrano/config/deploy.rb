# config valid only for current version of Capistrano
lock "3.8.1"

set :application, "havasfactory-templatewp"
#set :repo_url, "git@gitlab.havasdigitalfactory.net:havasfactory-templatewp/havasfactory-templatewp.git"
set :repo_url, "https://gitlab-ci-token:glpat-awrziSSxmxxwJq82KCgH@gitlab.havasdigitalfactory.net/havasfactory-templatewp/havasfactory-templatewp.git"


# Default branch is :master
# ask :branch, `git rev-parse --abbrev-ref HEAD`.chomp

# Default deploy_to directory is /var/www/my_app_name
set :deploy_to, "/var/www/www-havasfactory-templatewp/capistrano"

# Default value for :format is :airbrussh.
# set :format, :airbrussh

# You can configure the Airbrussh format using :format_options.
# These are the defaults.
# set :format_options, command_output: true, log_file: "log/capistrano.log", color: :auto, truncate: :auto

# Default value for :pty is false
# set :pty, true

# Default value for :linked_files is []

##### UNCOMMENT FOR COMPLET DEPLOY
append :linked_files, 'htdocs/wp-config.php', 'htdocs/.htaccess', 'htdocs/wp-content/themes/havas-starter-pack/front/.env'
#####

# Default value for linked_dirs is []

##### UNCOMMENT FOR COMPLET DEPLOY
append :linked_dirs, 'htdocs/wp-content/uploads'
#####

# Default value for default_env is {}
# set :default_env, { path: "/opt/ruby/bin:$PATH" }

# Default value for keep_releases is 5
set :keep_releases, 1

# Run automated tasks after autodeploy
namespace :deploy do
  after :published, :custom_script do
    on roles(:app) do
	  execute "cd #{ current_path }/digital-factory/; sh deploy.sh;"
    end
  end
end

# lunch lighthouse test
namespace :lighthouse do
  desc "Lancer l'audit Lighthouse sur le serveur distant"
  task :audit do
    on roles(:app) do
      within "#{current_path}/digital-factory" do
        execute :sh, "lighthouse_audit.sh"
      end
    end
  end
end
