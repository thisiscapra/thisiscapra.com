desc "Build the website from source"
task :build do
  puts "## Building website"
  status = system("middleman build")
  puts status ? "OK" : "FAILED"
end

desc "Deploy to "
task :deploy do
  puts "## Building website and deploying to divshot"
  status = system("middleman build && divshot push")
  puts status ? "OK" : "FAILED"
end
