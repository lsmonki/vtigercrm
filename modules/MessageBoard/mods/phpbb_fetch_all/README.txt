NAME
    phpBB Fetch All - displays phpBB on any page

VERSION
    2.0.3

SYNOPSIS
    <?php

    $phpbb_root_path = '../../../';

    define ('IN_PHPBB', true);
    include_once ($phpbb_root_path . 'extension.inc');
    include_once ($phpbb_root_path . 'common.' . $phpEx);
    include_once ($phpbb_root_path . 'includes/bbcode.' . $phpEx);
    include_once ($phpbb_root_path . 'mods/phpbb_fetch_all/common.' . $phpEx);
    include_once ($phpbb_root_path . 'mods/phpbb_fetch_all/posts.' . $phpEx);

    $userdata = session_pagestart($user_ip, PAGE_INDEX, $session_length);
    init_userprefs($userdata);

    $posts = phpbb_fetch_posts();

    phpbb_disconnect();

    ?>
    <h3>News</h3>
    <?php

    for ($i = 0; $i < count($posts); $i++) {

    ?>
    <p>
    <b><?php echo $posts[$i]['topic_title']; ?></b>
    <hr>
    <?php echo $posts[$i]['post_text']; ?>
    <?php

    }

    ?>

DESCRIPTION
    This mod lets you display data from the phpBB forum on
    any page of your website. It uses phpBB to connect
    to the forum database and should therefor work if phpBB
    works.

    You can integrate the mod on any (non phpBB) page of your
    homepage using your layout and design. You can also use
    phpBB templates and make the script become a part of your
    forum.

FEATURES
    To name some of the features:

    - displays topic title, post text and much more
    - fetches from one or multiple forums
    - uses phpBB smilies, bbcode and censored words
    - display a post on single page like on www.phpbb.com
    - fetches also topics specified by topic id
    - fetches between two dates
    - trims postings after a given character combination or
      a specific post length
    - determines the kind of posting like normal, sticky or
      announcement
    - fetches recent replies
    - uses span pages
    - fetches polls
    - full control over all options
    - optional phpBB auth check
    - display user online, total posts and so on
    - display member of a specific usergroup
    - display random user
    - display top poster

REQUIREMENTS
    This mod requires phpBB version 2.0.0 or above.

INSTALLATION
    1. Create a directory 'mods' within your phpBB2 directory if it
       does not already exists.

    2. Upload the complete 'phpbb_fetch_all' folder into the 'mods'
       directory.

    3. Open the following file in your browser:
       phpBB2/mods/phpbb_fetch_all/examples/portal.php

    Done. :-)

    TEMPLATE INSTALLATION

    You can use Fetch Posts with phpBB templates. Since this section
    is not yet finished please refer to the support forums for more
    informations about this topic.


SUPPORT
    Please visit the support and development forums at

        http://clanunity.net/portal.php

    All further informations of how to get support or help
    could be find there. There is also a newsletter
    available which will inform you about the latest
    updates and releases. Read this thread

        http://clanunity.net/forum/viewtopic.php?t=528

    about how to subscribe the newsletter.

OPTIONS
    All options are documented in the scripts.

    Nevertheless you can override all options at any time.
    See the examples for further informations how and when
        to do this.

VALUES
    You can output the fetched values in each HTML loop
    by writing

        <?php echo $posts[$i]['VALUE_NAME']; ?>

    in your HTML code.

CREDITS
    Thanks go to the many people who have tested the script
    and posted their feedback, wishes and suggestions in the
    phpBB forum.

AUTHOR
    Volker 'Ca5ey' Rattel <ca5ey@clanunity.net>
    http://clanunity.net/portal.php

COPYRIGHT
    This file is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published
    by the Free Software Foundation; either version 2, or (at your
    option) any later version.

    This file is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
    GNU General Public License for more details.
