require 'rubygems'
require 'rake'

task :default => :features

desc "run specs"
task :features do
    sh "cucumber features"
end


namespace :server do
    desc "start cuke4php server"
    task :start do

    end
    
    desc "stop cuke4php server"
    task :stop do

    end
end