---
published: true
title: Taming parent/child pages in Shopify
date: "2015-04-06"
author: Ollie Kavanagh
tags: 
  - shopify
  - themes
  - liquid
---

Building [Shopify themes](https://themes.shopify.com/) is fun, but it has it's limitations. As long as you go into it aware of some of these you won't be in for a shock when it doesn't do things that might be available in other hosted solutions. There are also a multitude of small/hacks and workarounds that are well documented or you just stumble upon by mistake, one of these I've outlined below.

I've been working on a new [Shopify](http://shopify.com) build for a client that required a parent/child relationship for pages. Even though this structure doesn't actually exist in Shopify, for things like [drop down menus](https://docs.shopify.com/manual/your-website/navigation/create-drop-down-menu) you can use [linklists](https://docs.shopify.com/themes/liquid-documentation/objects/linklist) and naming conventions to work around this and give the impression to the user on the front end that this relationship exists. Essentially a linklist becomes a category to assign a page to.

The client wanted to take this a step further and have an index page for each parent page that would output a list of the child pages beneath it with an image, a title, an excerpt and a link through to the child page. This needed to be dynamic so it didn't require manual updating. Thankfully a workaround exists for this in the form of linklist objects.

## Linklist objects

My approach to this was to create a 'page.index.liquid' layout that would be used for these index pages. I would then create a linklist loop that would capture the page name which matches the linklist name and output the objects(link) below it. 

The first thing to do was capture the page title within a variable and downcase it so it matches the linklist name. I was lucky in that the pages have singular names but you could easily use join to do the same thing.

```liquid
{% assign page_name = page.title | downcase %}
```

Now I can create my linklist for loop using square brackets to capture the `page_name` variable.

```liquid
{% for link in linklists[page_name].links %}

{% endfor %}
```

Each of the child links now has it's own object I can use to output the various bits of content. Firstly I needed to see if an image exists on the page. If so grab it and show it, if not show a place holder image.

```liquid
{% assign page_has_image = false %}
{% assign img_tag = '<' | append: 'img' %}
{% if link.object.content contains img_tag %}
  {% assign src = link.object.content | split: 'src="' %}
  {% assign src = src[1] | split: '"' | first %}
    {% if src %}
      {% assign page_has_image = true %}
      {% assign image_src = src | replace: '_small', '' | replace: '_compact', '' | replace: '_medium', '' | replace: '_large', '' | replace: '_grande', '' %}
    {% endif %}
{% endif %}

{% if page_has_image %}
  <a href="{{ link.object.url }}">
    <img src="{{ image_src }}" alt="{{ link.object.title }}">
  </a>
 {% else %}
  <a href="{{ link.object.url }}">
    {{ 'blank-page-image.jpg' | asset_url | img_tag: shop.name }}
  </a>
{% endif %}
```

Next I need to output the page content. In this case a title, an excerpt and a read more link.

```liquid
<h2><a href="{{ link.object.url }}">{{ link.object.title }}</a></h2>
<p class="sub-head">{{ link.object.page_description }}</p>
<div>
  <p>{{ link.object.content | strip_html | truncatewords: 40 }}</p>
</div>
<a href="{{ link.object.url }}" class="read-more">Continue reading</a>
```

So the loop in full would look like the following.

```liquid
{% assign page_name = page.title | downcase %}
{% for link in linklists[page_name].links %}
  {% assign page_has_image = false %}
  {% assign img_tag = '<' | append: 'img' %}
  {% if link.object.content contains img_tag %}
    {% assign src = link.object.content | split: 'src="' %}
    {% assign src = src[1] | split: '"' | first %}
      {% if src %}
        {% assign page_has_image = true %}
        {% assign image_src = src | replace: '_small', '' | replace: '_compact', '' | replace: '_medium', '' | replace: '_large', '' | replace: '_grande', '' %}
      {% endif %}
  {% endif %}
  {% if page_has_image %}
    <a href="{{ link.object.url }}">
      <img src="{{ image_src }}" alt="{{ link.object.title }}">
    </a>
   {% else %}
    <a href="{{ link.object.url }}">
      {{ 'blank-page-image.jpg' | asset_url | img_tag: shop.name }}
    </a>
  {% endif %}
  <h2><a href="{{ link.object.url }}">{{ link.object.title }}</a></h2>
  <p class="sub-head">{{ link.object.page_description }}</p>
  <div>
    <p>{{ link.object.content | strip_html | truncatewords: 40 }}</p>
  </div>
  <a href="{{ link.object.url }}" class="read-more">Continue reading</a>
{% endfor %}
```

Hopefully this helps anyone out looking for a similar solution. 