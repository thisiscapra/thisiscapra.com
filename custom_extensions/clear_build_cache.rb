# Place `require './custom_extensions/clear_build_cache'` (or equivalent) atop config.rb,
# and add `activate :clear_build_cache` (globally- outside environment specific configurations)
#
# by James Cropcho with hat tip to from Steven Sloan
# Licensed under the same license as Middleman; zero warranty provided by the author
#
# The before_build hook is only available branch v3-stable and up. If not defined, we only delete on
# after_build.

class ClearBuildCache < Middleman::Extension
  def initialize(app, options_hash={}, &block)
    super

    if app.methods.include?(:before_build) # branch v3-stable and up (https://github.com/middleman/middleman/commit/8d346e74a57b0446475c5bae671da5e621a45dbb)
      app.before_build do |builder|
        builder.remove_dir './.cache/'
      end
    end
        
    app.after_build do |builder|
      builder.remove_dir './.cache/'
    end
  end
end

::Middleman::Extensions.register(:clear_build_cache, ClearBuildCache)
