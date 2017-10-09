#Navbar menu integration

To integrate navbar into your wordpress installation you can do it by two ways, manual or automatic

##Manual
1. Go to wordpress admin area
1. On the left sidebar-menu click on Pages -> Add New
1. Enter title 'ACT' in the first textfield
1. Press 'Publish' button
1. Repeat steps 2-4 for 'EXPLORE' page
1. On the left sidebar-menu click on Appearance -> Menus
1. Insert the menu name 'Navbar-menu' in the textfield (_Menu Name_)
1. Press 'Create Menu' button
1. In the 'Pages' left block, check pages ACT & EXPLORE
1. In _Menu Settings_ -> _Display location_ select 'Navbar-menu'
1. Press 'Save Menu' button

##Automatic vi wp cli commands
Run below wp cli commands to create the act & explore pages, create the navbar menu and add the pages to the menu
```bash
wp post create --post_type=page --post_title=ACT --post_name=act --post_status=publish page-act.html

wp post create --post_type=page --post_title=EXPLORE --post_name=explore --post_status=publish page-explore.html

wp menu create “Navbar-Menu”

wp menu location assign navbar-menu navbar

idd=$(wp post list --post_type=page --post_name=act --field=ID | head -1); wp menu item add-post navbar-menu $idd --classes=hidden-md-down  --classes=hidden-lg-down

idd=$(wp post list --post_type=page --post_name=explore --field=ID | head -1); wp menu item add-post navbar-menu $idd --classes=hidden-md-down  --classes=hidden-lg-down
```