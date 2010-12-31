require 'rubygems'
require 'rake'
require "yard"
require "city"

task :default => :features

namespace :tests do
  desc "run cucumber features"
  task :features do
      sh "cucumber features"
  end

  desc "run phpunit tests"
  task :phpunit do
    sh "phpunit tests/"
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

namespace :doc do

  task :default => [:phpdoc]

  desc "Generate PhpDocs -- requires PhpDocumentor"
  task :phpdoc do
    sh "rm -rf phpdoc/"
    sh "phpdoc -f *.php -d ./lib -t phpdoc/ --title Cuke4Php -dn Cuke4Php -dc Cuke4Php -it @one,@two,@wire"
  end

end