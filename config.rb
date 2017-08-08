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

# ignore 'blog/*'

# With alternative layout
# page "/path/to/file.html", layout: :otherlayout

activate :dotenv

activate :external_pipeline,
         name: :webpack,
         command: build? ?
         "./node_modules/webpack/bin/webpack.js --bail -p" :
         "./node_modules/webpack/bin/webpack.js --watch -d --progress --color",
         source: ".tmp/dist",
         latency: 1

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
  f.access_token  = ENV['CONTENTFUL_ACCESS_TOKEN']
  f.space         = { blog: ENV['CONTENTFUL_SPACE_ID'] }
  f.content_types = {
    articles: ENV['CONTENTFUL_POST_KEY']
  }
  #f.rebuild_on_webhook = true
  f.cda_query = {
    content_type: ENV['CONTENTFUL_POST_KEY'],
    include: 1
  }
end

# #if Dir.exist?(config.data_dir)
#   app.data.blog.articles.each do |article|
#     proxy "/blog/#{article.slug}", "/blog/show.html", locals: { article: article }, ignore: true
#   end
# #end

# activate :blog do |blog|
#   # This will add a prefix to all links, template references and source paths
#   blog.prefix = "blog"

#   # blog.permalink = "{year}/{month}/{day}/{title}.html"
#   # Matcher for blog source files
#   # blog.sources = "{year}-{month}-{day}-{title}.html"
#   # blog.taglink = "tags/{tag}.html"
#   # blog.layout = "layout"
#   # blog.summary_separator = /(READMORE)/
#   # blog.summary_length = 50
#   # blog.year_link = "{year}.html"
#   # blog.month_link = "{year}/{month}.html"
#   # blog.day_link = "{year}/{month}/{day}.html"
#   blog.default_extension = ".md"

#   blog.tag_template = "tag.html"
#   blog.calendar_template = "calendar.html"

#   # Enable pagination
#   blog.paginate = true
#   blog.per_page = 10
#   blog.page_link = "page/{num}"
# end

app.data.blog.articles.each do |article|
  proxy "/blog/#{article[1][:slug]}/index.html", "/blog/show.html", locals: { 
    article: article[1]
  }, :ignore => true
end

app.data.blog.articles.map { |article| article[1][:tags] }.flatten.uniq.each do |tag_name|
  unless tag_name.nil?
    proxy "/blog/tags/#{tag_name.downcase}/index.html", "/blog/tag.html", locals: { 
      blog_tag: tag_name
    }, :ignore => true
  end
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
end

###
# Pagination
###

# activate :pagination do
#   pageable_set :articles do
#     data.blog.articles.sort_by{ |id,a| a[:date] }.reverse
#   end
# end

activate :directory_indexes

# Development-specific configuration
configure :development do

  # Reload the browser automatically whenever files change
  # activate :livereload, no_swf: true

end

# Build-specific configuration
configure :build do

  # "Ignore" JS so webpack has full control.
  ignore { |path| path =~ /\/(.*)\.js$/ && $1 != 'all' }
  
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