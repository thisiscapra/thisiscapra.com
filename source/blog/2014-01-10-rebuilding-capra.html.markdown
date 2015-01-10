---
title: Rebuilding Capra
date: '2014-01-10'
author: Ollie Kavanagh
tags:
  - middleman
  - ruby
  - codeship
  - rebuild
---

So when we decided to rebuild the website for our new launch in Canada and show our latest work we had a big decision to make on technology. The old site was built in Wordpress and while we still love that and will use it on client projects ongoing it felt like overkill for our needs. We needed speed, ease of updating, ability to use sass and a backend we were both familiar with. Static HTML generators seemed the way to go but we needed to overcome some hurdles first, the first decision was which one?

##Enter the Middleman

I've been using Middleman App for while now both on personal projects and client sites and I love it. It ticks all the boxes for me: 

* A Ruby/Padrino backend which I'm familiar with.
* Ability to use helpers &amp; YAML data for generating views.
* Built in pre-processors for CSS and Javascript.
* Ability to use the asset pipeline.
* Automically builds with pretty URLs.
* A large community behind it and easily extendable.

Of course using a static site generator comes with some drawbacks which is the ability to run server side code to perform some specfic tasks. I'm going to run through some of the solutions we came up with to solve specific functions we wanted on the site.

###Dynamically generated pages

We wanted to avoid to avoid duplucating lots of code within the application, so we needed a way to dynamically generate the project pages. Luckliy Middleman has the ability to read YAML data files and build HTML pages from these. First we create a `work_items.yml` date file...

```ruby
- name: 'Alfred App'
    featured: true
    title: 'Designed to be productive.'
    client_intro: "See how we designed your computer's butler for OSX."
    url: 'alfred-app'
    link: 'http://alfredapp.com'
- name: '8-50 Sports Digest'
    featured: true
    title: 'Simple sports news.'
    client_intro: "See how we designed a sports app for iPhone and Android."
    url: 'sports-digest-8-50'
    link: 'http://850sportsapp.com'
................
```
Then our our `config.rb` file we can call this YAML file and automically generate pages based on these fields.

```ruby
data.work_items.clients.each_with_index do |client, index|
  proxy "/work/#{client.url}.html", "/work-item.html", locals: { 
    client: client,
    title: client.name,
    description: client.intro
  }, :ignore => true
end
```

Now we only have to use one .erb file to output all our projects.

###Blogging

Middleman has a blogging extension available which is great, but coming from Wordpress we wanted something with a WYSIWYG editor and the ability to automatically add/edit blogposts without rebuilding the site manually every time and redeploying it. Some research led me onto prose.io which describes itself as a 'Prose is a content editor for GitHub designed for managing websites.' Perfect, BUT it was designed for Github pages so how do I use this with Middleman? Luckily Prose has a seperate config file that can be used for just this case.

Firstly create a `_prose.yml` file within your root folder.

``` ruby
prose:
  media: 'source/images/blog'
  rooturl: 'source/blog'
  metadata:
    source/blog:
      - name: "title"
        field:
          element: "text"
          label: "Title"
          value: ""
      - name: date
        field:
          element: "text"
          label: "Date"
          value: ""
      - name: "author"
        field:
          element: "text"
          label: "Author"
          value: ""
      - name: "tags"
        field:
          element: "multiselect"
          label: "Tags"
          alterable: true
```
There are a couple of problems we needed to solve with Prose. Currently it doesn't generate date yml frontmatter which is needed by Middleman so I add a meta field for this, I also include extra meta fields for tags and author.

The image uploading is still rather wonky and although I added the 'media:' line above this doesn't seem to work properly so we are currently hosting our images on dropbox.

So we can now write our Middleman blog posts in Prose and they are automically saved to our Github repo, that solves on problem, but how can I make this update on our live static site?

##Hosting & Continuous Delivery

I'd whittled down my hosting to a choice between Heroku and Divshot. The free hosting is great on Heroku but the dyno's spin down when not in use and you have to use a service such as pingdom to keep it alive wich seemed somewhat wrong to me. Divshot is built for static site hosting and comes with built push deploys and automatic setups with CLI tools.

I had some visibilty on CLI tools from working at Kyan but never set it up for myself. I started playing with Travis but I was having problems getting it playing ball with Middleman and Divshot so I was pointed towards Codeship which has been great. After linking up Githib --> Codeship --> Divshot I just needed to let Codeship know I was deploying a Ruby so I added a setup command to install all the gems needed.

```ruby
bundle install
```
I then run a custom deploy script to install the Divshot CLI, build the site from a Rake task within Middleman and push this to Divshot.

####Rakefile
```ruby
desc "Build the website from source"
task :build do
  puts "## Building website"
  status = system("middleman build --clean")
  puts status ? "OK" : "FAILED"
end
```
####Custom deploy script
```ruby
npm install -g divshot-cli
rake build && divshot push staging --token "${DIVSHOT_TOKEN}"
```
Perfect, now we can write a blog post in Prose, it saves to our Github repo and is automically pushed to our live staging environment.

##Contact page

There are a couple of ways we could approach this, we just need a way to post data to a url and receive it in our email. Middleman does have the option of parsing non HTML files in it so we use some PHP and write a script to deliver our email, it kind of defeats the point of a static site though. After some searching around I stumbled upon which Formkeep which is perfect. It stores all the replies within a nice little UI and have the option of sending them to both Trello and Gmail. Job done!