# require 'rubygems'
# require 'middleman/rack'

# run Middleman.server

# require 'rack'
# require 'rack/request'

# if ENV['RACK_ENV'] == 'development'

#   require "rubygems"
#   require "middleman-core/load_paths"

#   Middleman.setup_load_paths

#   require "middleman-core"
#   require "middleman-core/preview_server"

#   module Middleman::PreviewServer
#     def self.preview_in_rack
#       @options = { latency: 0.25 }
#       @app = new_app
#       start_file_watcher
#     end
#   end

#   Middleman::PreviewServer.preview_in_rack
#   run Middleman::PreviewServer.app.class.to_rack_app

# end

# if ENV['RACK_ENV'] == 'production'

#   module Rack
#     class TryStatic
#       def initialize(app, options)
#         @app = app
#         @try = ['', *options.delete(:try)]
#         @static = ::Rack::Static.new(lambda { [404, {}, []] }, options)
#       end

#       def call(env)
#         orig_path = env['PATH_INFO']
#         found = nil
        
#         @try.each do |path|
#           resp = @static.call(env.merge!({'PATH_INFO' => orig_path + path}))
#           break if 404 != resp[0] && found = resp
#         end

#         found or @app.call(env.merge!('PATH_INFO' => orig_path))
#       end
#     end
#   end

#   use Rack::Deflater
#   use Rack::TryStatic, :root => "build", :urls => %w[/], :try => ['.html', 'index.html', '/index.html']

#   # Run your own Rack app here or use this one to serve 404 messages:
#   run lambda{ |env|
#     not_found_page = File.expand_path("../build/404/index.html", __FILE__)
#     if File.exist?(not_found_page)
#       [ 404, { 'Content-Type'  => 'text/html'}, [File.read(not_found_page)] ]
#     else
#       [ 404, { 'Content-Type'  => 'text/html' }, ['404 - page not found'] ]
#     end
#   }

# end

require "rubygems"
require "middleman-core/load_paths"

Middleman.setup_load_paths

require "middleman-core"
require "middleman-core/preview_server"

module Middleman::PreviewServer
  def self.preview_in_rack
    @options = { latency: 0.25 }
    @app = new_app
    start_file_watcher
  end
end

Middleman::PreviewServer.preview_in_rack
run Middleman::PreviewServer.app.class.to_rack_app