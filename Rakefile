<<<<<<< HEAD
=======
# namespace :assets do
#   task :precompile do
#     sh 'middleman build'
#   end
# end

>>>>>>> cfef1c4e7c9d9e43cfc74b8e72c4b1402e9539cc
desc "Build the website from source"
task :build do
  puts "## Building website"
  status = system("middleman build --clean")
  puts status ? "OK" : "FAILED"
end

desc "Deploy to "
task :deploy do
  puts "## Building website and deploying to divshot"
  status = system("middleman build --clean && divshot push")
  puts status ? "OK" : "FAILED"
end