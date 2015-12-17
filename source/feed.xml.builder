@articles = app.data.blog.articles
xml.instruct!
xml.feed "xmlns" => "http://www.w3.org/2005/Atom" do
  site_url = "http://thisiscapra.com/"
  xml.title "Capra Design"
  xml.subtitle "Designing with goats"
  xml.id URI.join(site_url, 'blog')
  xml.link "href" => URI.join(site_url, 'blog')
  xml.link "href" => URI.join(site_url, current_page.path), "rel" => "self"
  #xml.updated(@articles.first['date'].to_time.iso8601) unless @articles.empty?
  xml.author { xml.name "Capra Design" }

  @articles.sort_by{ |id,a| a[:date] }.reverse.each do |id, article|
    xml.entry do
      xml.title article[:title]
      xml.link "rel" => "alternate", "href" => URI.join(site_url, "/blog/#{article[:title].parameterize}")
      xml.id URI.join(site_url, "/blog/#{article[:title].parameterize}")
      xml.published article[:date].to_time.iso8601
      #xml.updated File.mtime(article.source_file).iso8601
      xml.author { xml.name "#{article[:author][0][:name]}" }
      xml.content markdown(article[:body]), "type" => "html"
    end
  end
end