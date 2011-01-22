require 'rubygems'
require 'rake'

begin
  require 'jeweler'
  Jeweler::Tasks.new do |gem|
    gem.name = "cuke4php"
    gem.summary = %Q{Implementation of the Cucumber wire protocol for PHP projects}
    gem.description = %Q{Using this protocol it is possible to directly interact with PHP code at any level without the need for a web server.  To accomplish this, when cucumber is running against a directory containing feature files and it cannot resolve a particular step definition, it will ask a known wire server (as defined in a .wire file) to interpret and run those steps.}
    gem.email = "aledalgrande@gmail.com"
    gem.homepage = "http://github.com/olbrich/cuke4php"
    gem.authors = ["Kevin Olbrich", "Alessandro Dal Grande"]
    gem.executables = 'cucumber_php'
    gem.files.exclude 'phpdoc'
    gem.post_install_message =<<eos
********************************************************************************

  Please install PHPUnit >= 3.5 if you've not already done it!
  
  Add PEAR channels:
  pear channel-discover pear.phpunit.de
  pear channel-discover components.ez.no
  pear channel-discover pear.symfony-project.com
  
  Install PHPUnit:
  pear install phpunit/PHPUnit

********************************************************************************
eos
  end
  Jeweler::GemcutterTasks.new
rescue LoadError
  puts "Jeweler (or a dependency) not available. Install it with: gem install jeweler"
end

require 'cucumber/rake/task'
Cucumber::Rake::Task.new(:features)

task :default => :features

namespace :server do
  
    desc "start cuke4php server"
    task :start do
      sh "#{File.dirname(__FILE__)}/php_bin/cuke4php #{ARGV.first ? ARGV.first : 'features'} &"
    end
    
    desc "stop cuke4php server"
    task :stop do
      sh "echo 'quit' | nc #{ENV['SERVER'] || 'localhost'} #{ENV['PORT'] || 16816}"
    end
end

namespace :doc do

  task :default => :phpdoc

  desc "Generate PhpDocs -- requires PhpDocumentor"
  task :phpdoc do
    sh "rm -rf phpdoc/"
    sh "phpdoc -f *.php -d ./lib -t phpdoc/ --title Cuke4Php -dn Cuke4Php -dc Cuke4Php -it @one,@two,@wire"
  end

end
