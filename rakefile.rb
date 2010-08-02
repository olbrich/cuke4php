require 'rubygems'
require 'rake'

task :default => :features

namespace :tests do
  desc "run cucumber features"
  task :features do
      sh "cucumber features"
  end

  desc "run phpunit tests"
  task :phpunit do
    sh "./PHPUnit/phpunit.php tests/"
  end

  task :all => [:phpunit, :features]

end

namespace :server do
    desc "start cuke4php server"
    task :start do

    end
    
    desc "stop cuke4php server"
    task :stop do
      sh "echo 'quit' | nc #{ENV['SERVER'] || 'localhost'} #{ENV['PORT'] || 16816}"
    end
end