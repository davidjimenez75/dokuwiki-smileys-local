# dokuwiki-smileys-local

Recopilation of "smileys" to use in Dokuwiki.

## Quick Start

Create a <code>lib/images/smileys/local/</code> folder and clone this repo there.

Copy the <code>smileys.local.conf</code> to <code>conf/smileys.local.conf</code>

Touch <code>conf/local.php</code> to refresh the Dokuwiki editor cache.



## Included Smileys Packs


### WYSIWYG Editor

![WYSIWYG smileys](https://raw.githubusercontent.com/davidjimenez75/dokuwiki-smileys-local/master/folder.jpg)

### dokuwiki

![dokuwiki smileys](https://raw.githubusercontent.com/davidjimenez75/dokuwiki-smileys-local/master/dokuwiki/folder.jpg)

### emojione

![dokuwiki smileys](https://raw.githubusercontent.com/davidjimenez75/dokuwiki-smileys-local/master/emojione/folder.jpg)

### github (tags)

![dokuwiki smileys](https://raw.githubusercontent.com/davidjimenez75/dokuwiki-smileys-local/master/github/folder.jpg)

### icons8 (svg)

![dokuwiki smileys](https://raw.githubusercontent.com/davidjimenez75/dokuwiki-smileys-local/master/icons8/folder.jpg)

### openmoji32

![dokuwiki smileys](https://raw.githubusercontent.com/davidjimenez75/dokuwiki-smileys-local/master/openmoji32/folder.jpg)



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


## Authors and Licenses

| Smiley pack   | Website | Repo | License |
| ------------- | ------------- |
|icons8    | https://icons8.com           | [github](https://github.com/icons8/flat-color-icons)     | [Good-Boy Licence](https://icons8.com/good-boy-license/) |
|openmoji  | https://openmoji.org/about/  | [github](https://github.com/hfg-gmuend/openmoji)         | [CC BY-SA 4.0](https://creativecommons.org/licenses/by-sa/4.0/) |
|emojione  | https://www.joypixels.com/   | [github archived](https://github.com/joypixels/emojione) - [github](https://github.com/joypixels/emoji-toolkit) | [Free License](https://www.emojione.com/licenses/free) |
|dokuwiki  | Pack created by myself       | [github](github.com/davidjimenez75/dokuwiki-smileys-local/) | [CC BY-SA 4.0](https://creativecommons.org/licenses/by-sa/4.0/) |
|github    | Pack created by myself       | [github](github.com/davidjimenez75/dokuwiki-smileys-local/) | [CC BY-SA 4.0](https://creativecommons.org/licenses/by-sa/4.0/) |



## FAQ

### I can't see the new smileys on the Dokuwiki editor.

Editing <code>smileys.local.conf</code> sometimes doesnt not work due to cache issues, try touch the date of <code>conf/local.php</code> and reload Dokuwiki (Ctrl + F5).


### I want to create my own icon package.

Just create a new folder with your smileys, lauch the config generator (/lib/images/smileys/local/index,php), click on your folder, select option 1 and click search smileys

Copy that inside your <code>conf/smileys.local.conf</code>

You can add a folder.jpg inside your folder for icon pack preview.


### What the #REPEATED text means?

There is another smiley with the same text-to-replace.


### Can I create by own project/personal (TAGS)

By default Dokuwiki uses :TAGS:

But you can edit the index.php to generate the <code>conf/smileys.local.conf</code> with your own symbols.

<code>
// CONFIG 
$smileStringStart =':'; // prefix for smileys by default is :
$smileStringEnd   =':'; // suffix for smileys by default is :
</code>

You can also just manually edit the <code>conf/smileys.local.conf</code> first column text-to-replace. 


### Can is use GTD :TAGS: on my Dokuwiki to keep track of my tasklists?

With the Dokuwiki search tool is easy to keep track of :TAGS:

I have used this on my own Dokuwiki:

<code>
:ACTION:                                     local/dokuwiki/action.gif    
:TO-DO:                                      local/dokuwiki/to-do.gif     
:NEXT:                                       local/dokuwiki/next.gif    
:SOMEDAY:                                    local/dokuwiki/someday.gif     
:WAITING:                                    local/dokuwiki/waiting.gif 
:FINISHED:                                   local/dokuwiki/finished.gif   
</code>

### I want to disable some of the default smileys

Add the text-to-replace to that smileys at the bottom of your  <code>conf/smileys.local.conf</code>

<code>
...
# DISABLED

FIXME
DELETEME
</code>


