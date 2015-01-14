---
published: false
title: "Wordpress Multisite sub-domains with Pow & Apache"
date: "2015-01-14"
author: Ollie Kavanagh
tags: 
  - Apache
  - Wordpress
  - Pow
---

I have my local dev environment set up with Pow and I then make Apache listen on Port 81 for any PHP apps I'm doing on `.dev`. I'm currently working on a Wordpress Sub-domain multisite yet I was stumped as the sub-domain alias wasn't working for me locally using the following...

```
<Directory "/Users/olliekav/Sites/">
  AllowOverride All
  Require all granted
</Directory>

<VirtualHost 127.0.0.1:81>
  ServerName any.dev
  ServerAlias *.dev
  VirtualDocumentRoot "/Users/olliekav/Sites/%1"
</VirtualHost>
```

It turns out I needed to change the VirtualDocumentRoot to the following...

```
VirtualDocumentRoot "/Users/olliekav/Sites/%-2"
```

And Voilà, the sub-domain is now working.

**%-2 in the VirtualDocumentRoot represents the penultimate dot-separated part of foo.com, i.e. foo. You could then have directories as you wish that map to the sites**

[From this post on Stackoverflow](https://stackoverflow.com/questions/26575276/wildcard-domains-with-virtualhost-with-apache-on-mac/26636481#26636481)
