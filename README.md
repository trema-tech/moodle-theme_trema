<img src="pix/logo.png" align="right">

Trema Theme for Moodle LMS
==========================

![PHP](https://img.shields.io/badge/PHP-v7.4%20to%20v8.2-blue.svg)
![Moodle](https://img.shields.io/badge/Moodle-v4.0%20to%20v4.3-orange.svg)
[![GitHub Issues](https://img.shields.io/github/issues/trema-tech/moodle-theme_trema.svg)](https://github.com/trema-tech/moodle-theme_trema/issues)
[![Contributions welcome](https://img.shields.io/badge/contributions-welcome-green.svg)](#contributing)
[![License](https://img.shields.io/badge/License-GPL%20v3-blue.svg)](#license)

# Table of Contents

- [Trema Theme for Moodle LMS](#trema-theme-for-moodle-lms)
- [Table of Contents](#table-of-contents)
- [Basic Overview](#basic-overview)
- [Requirements](#requirements)
- [Download Trema for Moodle LMS](#download-trema-for-moodle-lms)
- [Installation](#installation)
- [Usage](#usage)
- [Updating](#updating)
- [Uninstallation](#uninstallation)
- [Limitations](#limitations)
- [Language Support](#language-support)
- [FAQ](#faq)
  - [Answers to Frequently Asked Questions](#answers-to-frequently-asked-questions)
    - [Are there any security considerations?](#are-there-any-security-considerations)
    - [How can I get answers to other questions?](#how-can-i-get-answers-to-other-questions)
  - [Contributors](#contributors)
- [Motivation for this theme](#motivation-for-this-theme)
- [License](#license)
- [Donate](#donate)

# Basic Overview

![theme-trema](https://user-images.githubusercontent.com/8494871/150807318-57548da5-ea15-45a3-98d9-170f8495b95b.gif)

The Trema theme focused on simplicity, usability and beauty. To achieve this, we work hard to make it clean and easy to use for everyone. The main goal for this theme is to have it provide the basics you need for both your Moodle LMS and information/advertising/marketing without needing a separate website.

Trema Features:

- Based on the boost theme: So it will continue to be compatible with Moodle LMS well into the future.
- Customizable frontpage. You can change the image banner, create up to six beautiful cards with custom content and add custom HTML content with no restrictions.
- Customize the login page: Add your own background image.
- Leverages [Bootstrap](https://getbootstrap.com/) to make the theme fully responsive.
- Highly customizable footer.
- Special footer area blocks used by the site administrator.
- The colour scheme can be changed in the theme settings.
- 15 different font families to choose from for your site base font, front page banner title and the headings.
- Customizable Primary Navigation including support for FilterCodes filters.
- Admin panel on the Dashboard page, so that they can see information about your Moodle site in real time.

![image1](pix/examples/admindashboard.png "Dashboard Admin Screenshot")

[(Back to top)](#table-of-contents)

# Requirements

This theme requires Moodle LMS 4.0+ from https://moodle.org/.

[(Back to top)](#table-of-contents)

# Download Trema for Moodle LMS

The most recent STABLE release of Trema for Moodle LMS is available from:
https://moodle.org/plugins/theme_trema

The most recent DEVELOPMENT release can be found at:
https://github.com/trema-tech/moodle-theme_trema

[(Back to top)](#table-of-contents)

# Installation

Ensure you have a supported version of Moodle LMS as stated above in [Requirements](#requirements). This is necessary as the theme relies on underlying core code.

Install the theme, like any other theme, to the following folder:

    /theme/trema

See https://docs.moodle.org/en/Installing_plugins for details on installing Moodle plugins.

To activate the theme, navigate to **Site Administration > Appearance > Themes (section) > Theme selector**. In recent versions of Moodle LMS, you need only locate the Trema theme and click the associated **Use Theme** button.

[(Back to top)](#table-of-contents)

# Usage

IMPORTANT: This STABLE release has been tested on many Moodle sites. Although we expect everything to work, if you find a problem, please help by reporting it in the [Bug Tracker](https://github.com/trema-tech/moodle-theme_trema/issues).

You can customize the theme by navigating to **Site Administration > Appearance > Themes (section) > Trema**

In addition, you can customize additional settings by overriding SCSS variables in the theme's settings. [See the complete list](https://github.com/trema-tech/moodle-theme_trema/blob/dev/scss/defaultvariables.scss).

# Updating

There are no special considerations required for updating the plugin.

Note: The theme will not be upgradable from within Moodle LMS if you installed it using Git. To enable upgrading, simply delete the .git folder in the /theme/trema/ directory.

[(Back to top)](#table-of-contents)

# Uninstallation

Before you can uninstall Trema, be sure to switch to a different theme.

Then uninstall the theme by navigating to Site Administration > Plugins > Plugins (section) > Plugins Overview. Scroll down to the **Themes** section and Uninstall. If you don't see the uninstall link, it is because you did not first switch Moodle LMS to a different theme. Follow the prompts to complete the uninstallation process. Note that you may also need to manually delete the following folder if your web server does not have the required permissions:

    /theme/trema

Note that, once uninstalled, any customizations that were part of the Trema theme will no longer be displayed.

[(Back to top)](#table-of-contents)

# Limitations

There are no known limitations at this time.

[(Back to top)](#table-of-contents)

# Language Support

This plugin includes support for the English language.

However, it has been translated into about 20 other languages in AMOS by the Moodle community. If you need a different language that is not yet supported, please feel free to contribute using the [Moodle AMOS Translation Toolkit](https://lang.moodle.org/).

This plugin has not been tested for right-to-left (RTL) language support although it has been used successfully in languages like Arabic.

[(Back to top)](#table-of-contents)

# FAQ
## Answers to Frequently Asked Questions

IMPORTANT: Although we expect everything to work, this release has not been fully tested in every possible situation. If you find a problem, please help by reporting it in the [Bug Tracker](https://github.com/trema-tech/moodle-theme_trema/issues).

### Are there any security considerations?

There are no known security considerations at this time.

### How can I get answers to other questions?

Got a burning question that is not covered here? If you can't find your answer, submit your question in the Moodle forums or open a new issue on Github at:

https://github.com/trema-tech/moodle-theme_trema/issues

[(Back to top)](#table-of-contents)

## Contributors

Rodrigo Mady - Lead Developer/Maintainer | [Moodle profile](https://moodle.org/user/profile.php?id=2435964) | [GitHub](https://github.com/rmady)

Michael Milette - Developer/Maintainer - [TNG Consulting Inc.](https://www.tngconsulting.ca) | [Moodle profile](https://moodle.org/user/profile.php?id=1615960) | [GItHub](https://github.com/michael-milette)

Big thank you to [everyone who has contributed](https://github.com/trema-tech/moodle-theme_trema/graphs/contributors) to the development of Trema over the years.

Thank you also to all the people who have requested features, tested and reported bugs.

[(Back to top)](#table-of-contents)

# Motivation for this theme

The development of this theme was motivated by our own experience in Moodle LMS development, features requested by our clients and topics discussed in the Moodle forums.

[(Back to top)](#table-of-contents)

# License

Copyright © 2019-2024 Rodrigo Mady and TNG Consulting Inc.

This file is part of Moodle - http://moodle.org/

Moodle is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Moodle is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

[(Back to top)](#table-of-contents)

# Donate

[Please donate](https://www.paypal.com/donate/?hosted_button_id=6FBZMX8PFPS3Y&source=qr) to help us to improve Theme Trema:

![QR Code](https://user-images.githubusercontent.com/8494871/150807066-84f999f6-5227-4dc7-9781-b949ed485a3a.png)

[(Back to top)](#table-of-contents)
