###
# Page options, layouts, aliases and proxies
###

Time.zone = "US/Eastern"

# Per-page layout changes:
#
# With no layout
page '/*.xml', layout: false
page '/*.json', layout: false
page '/*.txt', layout: false
page "/404.html", :layout => false

# With alternative layout
# page "/path/to/file.html", layout: :otherlayout

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
# Blog
###

activate :blog do |blog|
  blog.layout = "blog"
  blog.sources = "blog/{year}-{month}-{day}-{title}.html"
  blog.permalink = "blog/{title}.html"
  blog.paginate = true
  blog.per_page = 10
  blog.tag_template = "tag.html"
  blog.calendar_template = "calendar.html"
  blog.taglink = "tags/{tag}.html"
  blog.year_link = "blog/{year}.html"
  blog.month_link = "blog/{year}/{month}.html"
  blog.day_link = "blog/{year}/{month}/{day}.html"
end

set :markdown_engine, :redcarpet
set :markdown, :smartypants => true, :fenced_code_blocks => true

###
# Helpers
###

# Reload the browser automatically whenever files change
configure :development do
  activate :livereload
end

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
    current_page.url.start_with?(page) ? {:class => 'active'} : {}
  end
  # Custom page classes
  def custom_page_classes
    "page-#{page_classes} #{yield_content(:page_class) if content_for?(:page_class)}"
  end
  # Tag lists
  def sentence_tag_list(article)
    if tags = article.tags
      content_tag(:div, class: :tags) do
        "This article was filed under " +
        article.tags.map{|t| link_to t, "/tags/#{t}"}.to_sentence +
        "."
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
end

activate :directory_indexes

# Use relative URLs
activate :relative_assets

#set :fonts_dir, 'fonts'

# Build-specific configuration
configure :build do
  # Minify CSS on build
  activate :minify_css

  # Minify Javascript on build
  activate :minify_javascript

  # Enable cache buster
  activate :asset_hash

  activate :gzip
  
  # Fix bug with build errors and typography fonts
  ignore 'fonts/*'

  after_build do |builder|
    print "After_build fixes... "
    FileUtils.cp_r(Dir['source/fonts/'],'build/')
    puts "done."
  end
end
