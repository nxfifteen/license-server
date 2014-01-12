Git Based License Server
========================
version = 1.0.0.0

## A permalink for your software License
I usually forget to include anything in LICENSE.md files and after a quick Goolge I found this script on (GitHub)[https://raw2.github.com/remy/mit-license] by remy to host your license details online. I do not tend to use the MIT license myself so I needed to enhance the code a little bit. This is my version of his code. I have his code.

Now I can always include http://stuart.nx15.at in all my projects which links `stuart` (the cname) against my copyright holder name `Stuart McCulloch Anderson` - all stored in the `users` directory.

### The user.json file
The `users` directory contains a list of files, each representing a host
on mit-license.org. The minimum requirement for the JSON is that is
contains a `copyright` field - everything else is optional. Remember to ensure
the `user.json` file is [valid JSON](http://jsonlint.com/).

Available fields:

* yourname
* url
* company
* companyurl
* theme
* format
* email
* gravatar
* pgpkey
* pgpid
* pgpurl
* boilerplate
* oslicense

#### yourname
Add your name. This can be your full name, part of it or just the nickname you prefer

    {
      "yourname": "Stuart McCulloch Anderson"
    }
#### email
You can also include a link to your email which is displayed after the
copyright notice using the `email` property (note the `mailto:` is
automatically added):

    {
      "email": "youremail@example.com"
    }
#### url
If you want to make a link from the copyright text, you can include a `url` property:

    {
      "url": "http://nx15.at"
    }
#### gravatar
And if you want to show your gravatar, just add the `gravatar`
boolean property:

    {
      "gravatar": true
    }
Note that the gravatar requires the email property. You also need to check the
compatibility of the chosen theme. Currently, only the default theme supports
Gravatar.
#### pgpkey
You can add a link to your PGP/GPG key if you use one here:

    {
      "pgpkey": "FULL KEY HASH"
      "pgpid": "PARTAL KEY HASH"
      "pgpurl": "URL TO KEY FILE"
    }
#### company
If you work in a company or other organisation which shares the copyright with you, you
can include if the company field:

    {
      "company": "NxFifteen Research"
    }
#### companyurl
You can also use a seperate URL for your company than for you:

    {
      "companyurl": "http://nxfifteen.me.uk"
    }
#### theme
Themes are CSS files. The default is in the same style as 
NxFifteen.me.uk but you can change the CSS or add a new file.
Each user has control over the theme displayed here:

    {
      "theme": "default"
    }
#### format
And if you want your license to appear as plain text or markdown, just add the
`format` property (currently only `txt`, `md` and `html` are supported):

    {
      "format": "html"
    }
#### boilerplate
It is often it is adventagious to add some additional text to the
lience blurb. You can do that here:

    {
      "boilerplate": "Text that will appear under the license"
    }
#### oslicense
The default license is the FreeBSD license, but a user has control
over changing their default here. The license must exsist in the
license folder, else it will revert to FreeBSD:

    {
      "oslicense": "default"
    }

### License version targeting
License version targeting allows you to link your license to a
specific revision in this project - therefore fixing it permanently to
a specific license text.

Though I don't expect the license text to change ever, this is just some
extra assurance for you.

Targeting requires the [sha from the license commit](https://git.research.nxfifteen.me.uk/web-applications/license-server/commits/master/licenses/LICENSE.html). This can be specified on the URL (in your permalink) or in the JSON file.

For example: http://rem.nx15.at/401a52e0b (make sure to view-source)
shows an older version of the LICENSE.html file (compared to the [latest version](https://git.research.nxfifteen.me.uk/web-applications/license-server/blob/master/licenses/LICENSE.html) - the older version didn't have the new themes).

This can also be targeted in my JSON file:

    {
      "version": "401a52e0b"
    }

Note that if no version is supplied, the latest copy of the LICENSE.html
will be displayed with your information included.

### Themes
If you've got an eye for design you can contribute a
theme by adding a CSS file to the `themes` directory. The default theme
is simple and clean, but you can add your own as you like.

To use a theme, add the `theme` property to your `user.json` file, for
example:

    {
      "theme": "default"
    }

Current available themes:

* default - [preview](http://stuart.nx15.me.uk) (by [@nxad](https://git.research.nxfifteen.me.uk/u/nxad)
* double-windsor - [preview](http://jsbin.com/uzubos/5/) (by [@desandro](https://github.com))

## Formats & URLs
The following types of requests can be made to this project:

* [http://stuart.nx15.at/](http://stuart.nx15.at/) FreeBSD HTML, or the default format specified in the json file
* [http://stuart.nx15.at/license.html](http://stuart.nx15.at/license.html) FreeBSD HTML
* [http://stuart.nx15.at/license.txt](http://stuart.nx15.at/license.txt) FreeBSD Text
* [http://stuart.nx15.at/license.md](http://stuart.nx15.at/license.md) FreeBSD MarkDown
* [http://stuart.nx15.at/download.md](http://stuart.nx15.at/download.md) FreeBSD MarkDown - License will be downloaded instead of displayed

You can select the license to use as well. This must be the same as the file name inside the (licenses folder)[https://git.research.nxfifteen.me.uk/web-applications/license-server/tree/master/licenses]

* [http://stuart.nx15.at/mit](http://stuart.nx15.at/mit/) MIT HTML, or the default format specified in the json file
* [http://stuart.nx15.at/mit/license.html](http://stuart.nx15.at/mit/license.html) MIT HTML
* [http://stuart.nx15.at/mit/license.txt](http://stuart.nx15.at/mit/license.txt) MIT Text
* [http://stuart.nx15.at/mit/license.md](http://stuart.nx15.at/mit/license.md) MIT MarkDown
* [http://stuart.nx15.at/mit/download.md](http://stuart.nx15.at/mit/download.md) MIT MarkDown - License will be downloaded instead of displayed

You can also select a specific commit file version to display, you just need the [sha from the license commit](https://git.research.nxfifteen.me.uk/web-applications/license-server/commits/master/licenses/LICENSE.html)

* [http://stuart.nx15.at/57d72ef](http://stuart.nx15.at/a526bf7ad1) 57d72ef version of FreeBSD HTML, or the default format specified in the json file
* [http://stuart.nx15.at/57d72ef/license.html](http://stuart.nx15.at/a526bf7ad1/license.html) 57d72ef version of FreeBSD HTML
* [http://stuart.nx15.at/57d72ef/license.txt](http://stuart.nx15.at/a526bf7ad1/license.txt) 57d72ef version of FreeBSD text
* [http://stuart.nx15.at/57d72ef/license.md](http://stuart.nx15.at/a526bf7ad1/license.txt) 57d72ef version of FreeBSD MarkDown - License will be downloaded instead of displayed
* [http://stuart.nx15.at/57d72ef/download.md](http://stuart.nx15.at/57d72ef/download.md) 57d72ef version of FreeBSD - License will be downloaded instead of displayed

The url also supports including a start year:

* [http://stuart.nx15.at/2009/](http://stuart.nx15.at/2009/) will show a license year range of 2009-2014 (2014 being the current year)
* [http://stuart.nx15.at/2009-2010](http://stuart.nx15.at/2009-2010/) allows me to force the year range
* [http://stuart.nx15.at/57d72ef/2009-2010/license.txt](http://stuart.nx15.at/a526bf7ad1/2009-2010/license.txt) a526bf7ad1 version, with year range of 2009-2010 in plain text

## Ways to contribute

Aside from code contributions that make the project better, there are a
few other specific ways that you can contribute to this project.

Development contributions from:

* [nxad](https://git.research.nxfifteen.me.uk/u/nxad)

## License

And of course:

MIT: [http://stuart.nx15.at/8504faf/mit/2014/license.html](http://stuart.nx15.at/8504faf/mit/2014/license.html)

