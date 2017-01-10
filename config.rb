###
# Page options, layouts, aliases and proxies
###

#Time.zone = "US/Eastern"

# Per-page layout changes:
#
# With no layout
page '/*.xml', layout: false
page '/*.json', layout: false
page '/*.txt', layout: false
page "/404.html", layout: false

# With alternative layout
# page "/path/to/file.html", layout: :otherlayout

activate :dotenv
activate :sprockets
sprockets.append_path File.join( root, 'source' )

# Proxy pages (http://middlemanapp.com/basics/dynamic-pages/)
# proxy "/this-page-has-no-template.html", "/template-file.html", locals: {
#  which_fake_page: "Rendering a fake page with a local variable" }

app.data.work_items.clients.each do |client|
  proxy "/work/#{client.url}/index.html", "/work-item.html", locals: { 
    client: client,
    work_pages: data.work_items.clients,
    url: "work"
  }, :ignore => true
end

app.data.lab_items.labs.each do |client|
  proxy "/labs/#{client.url}/index.html", "/work-item.html", locals: { 
    client: client,
    work_pages: data.lab_items.labs,
    url: "labs"
  }, :ignore => true
end

###
# Blog / Contentful
###

activate :contentful do |f|
  f.use_preview_api = true
  f.access_token  = ENV['CONTENTFUL_ACCESS_TOKEN']
  f.space         = { blog: ENV['CONTENTFUL_SPACE_ID'] }
  f.content_types = {
    articles: ENV['CONTENTFUL_POST_KEY']
  }
  f.cda_query = {
    content_type: ENV['CONTENTFUL_POST_KEY'],
    include: 1
  }
end

app.data.blog.articles.each do |article|
  proxy "/blog/#{article[1][:slug]}/index.html", "/blog/show.html", locals: { 
    article: article[1]
  }, :ignore => true
end

app.data.blog.articles.map { |article| article[1][:tags] }.flatten.uniq.each do |tag_name|
  proxy "/blog/tags/#{tag_name.downcase}/index.html", "/blog/tag.html", locals: { 
    blog_tag: tag_name
  }, :ignore => true
end

set :markdown_engine, :redcarpet
set :markdown, smartypants: true, fenced_code_blocks: true

activate :syntax, line_numbers: true, lexer_options: { parent: 'plaintext' }

###
# Helpers
###

# Methods defined in the helpers block are available in templates
helpers do
  # Set the page title
  def page_title
    if content_for?(:title)
      "#{yield_content(:title)} - Capra Design"
    else
      "Capra Design"
    end
  end
  # Set the page description
  def page_description
    if content_for?(:description)
      "#{yield_content(:description)}"
    else
      "Capra is a design agency based in Ottawa, Canada run by husband and wife team Ollie and Kat Kavanagh. Our focus is great design. We love interactive work like websites, games and apps because we get involved in building what we design."
    end
  end
  # Active nav items
  def nav_active(page)
    current_page.url.start_with?(page) ? { class: 'active' } : {}
  end
  # Custom page classes
  def custom_page_classes
    "page-#{page_classes} #{yield_content(:page_class) if content_for?(:page_class)}"
  end
  # Tag lists
  def sentence_tag_list(article)
    if tags = article.tags
      content_tag(:div, class: :tags) do
        "This article was filed under: " +
        article.tags.map{|t| link_to t.humanize, "/blog/tags/#{t.downcase}"}.join(', ')
      end
    end
  end
  # Pretty dates
  def pretty_date(date)
    date.strftime('%B %d, %Y')
  end
  # Check if page
  def page(path)
    current_page.path == path
  end
  # If we available for work
  def available?
    return false
  end
  # Markdown
  def markdown(content)
    #Tilt::KramdownTemplate.new { content }.render
    #Redcarpet::Document.new(content).to_html
    Tilt['markdown'].new { content }.render(scope=self)
  end
  # Responsive images helper
  def responsive_image_tag(srcset, **options)
    # Sort the images in the srcset by their value (Their pixel or '1x' width. Either should sort properly).
    sorted_srcset = srcset.sort_by { |_, value| value }

    # Use the smallest image in the given set as the image fallback. Might not be necessary with Picturefill.
    options[:src] = image_path_or_url_for_string(sorted_srcset.first[0])

    # Build the srcset attribute. Iterates through the given srcset hash, if a key contains '//' it
    # is assumed to contain a URL and will not use Rails image_path.
    options[:srcset] = sorted_srcset.map { |k, v| "#{image_path_or_url_for_string(k)} #{v}w" }.join(', ')

    # Creates a little DSL for the sizes option. The gte_sm, gte_md, etc keys line up with
    # Bootstraps default breakpoints. You could define your breakpoints somewhere else that
    # makes sense and adapt this to your needs.
    # 
    # Build the sizes attribute if necessary. If the sizes option is a string, just
    # pass that through to the final img tag.
    if options[:sizes].present? && options[:sizes].is_a?(Hash)
      options[:sizes] = options[:sizes].map do |media_query, length|
        if media_query.is_a? Symbol
          case media_query
          when :gte_sm
            "(min-width: 768px) #{length}"
          when :gte_md
            "(min-width: 992px) #{length}"
          when :gte_lg
            "(min-width: 1200px) #{length}"
          when :default
            length.to_s
          else
            nil
          end
        else
          "#{media_query} #{length}"
        end
      end.join(', ')
    end

    # Return the responsive image tag
    tag(:img, options)
  end

  # Determines if a given string contains a URL or a path by checking 
  # for the presence of //.
  def image_path_or_url_for_string(image_str)
    image_str =~ /\/\// ? image_str : image_path(image_str)
  end
end

activate :directory_indexes

###
# Pagination
###

# activate :pagination do
#   pageable_set :articles do
#     data.blog.articles.sort_by{ |id,a| a[:date] }.reverse
#   end
# end

# Development-specific configuration
configure :development do

  # Reload the browser automatically whenever files change
  activate :livereload, no_swf: true

end

# Build-specific configuration
configure :build do
  
  # Minify CSS on build
  activate :minify_css

  # Minify Javascript on build
  activate :minify_javascript

  # Enable cache buster
  activate :asset_hash

  activate :gzip

  # Use relative URLs
  # activate :relative_assets
  
  # Fix bug with build errors and typography fonts
  ignore 'fonts/*'

  after_build do |builder|
    print "After_build fixes... "
    FileUtils.cp_r(Dir['source/fonts/'],'build/')
    puts "done."
  end
  
end