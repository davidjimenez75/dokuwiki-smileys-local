# dokuwiki-smileys-local

Recopilation of "smileys" to use in Dokuwiki.

## Quick Start

Create a <code>lib/images/smileys/local/</code> folder and clone this repo there.

Copy the <code>smileys.local.conf</code> to <code>conf/smileys.local.conf</code>

## Some examples

![local smileys](https://raw.githubusercontent.com/davidjimenez75/dokuwiki-smileys-local/master/folder.png)



## Customize smileys (Official Dokuwiki Info)

https://www.dokuwiki.org/smileys

To add your own smileys, and make them upgrade-safe as well, you should follow these instructions instead of mixing them with the default-smileys from Dokuwiki-package:

  - Create a new folder called <code>local</code> inside the smileys-dir <code>lib/images/smileys/local/</code> and put the imagefiles into it (make sure the images are readable by the webserver)
  - Create a custom smiley-config file at <code>conf/smileys.local.conf</code> and prefix each image filename with <code>local/</code>

```
An example configuration file could look like this: <code>
# Custom Smileys
# Images are seen relatively from the smiley directory lib/images/smileys/
# TEXT_TO_REPLACE       FILENAME_OF_IMAGE
#
:MYFACE:                local/i_am_so_pretty.jpg

# or eventually disable a smiley by mentioning the key, without image path.
DELETEME
```

## FAQ

### Problems with cache after editing smileys.local.conf

Editing smileys.local.conf sometimes doesnt not work due to cache issues, try touch the date of conf/local.php al reload Dokuwiki 

- touch local.php
- erase browser cache
- erase dokuwiki cache