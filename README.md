# Kimberli's Raspberry Pi
This Raspberry Pi project uses [pianobar](https://github.com/PromyLOPh/pianobar), the console-based Pandora player, and [heyu](http://heyu.tanj.com/), a console-based X10 controller, to automate music and lighting. It also includes a responsive web interface for access from computers, tablets, and phones.

http://xkcd.com/1296/

## Features
* Pianobar web interface (my Raspberry Pi is hooked up to a speaker system, so I can control output via any web browser on my home network)
  * Start/stop, pause/play, skip song, like song, volume up/down
  * Shows information of song currently playing, volume, and state (playing/paused)
* X10 web interface (X10 is this really old home automation standard which relies on powerline signals)
  * Turn lights on/off
* Cron web interface
  * Add/remove scheduled tasks
* NFC control
  * Activate commands using NFC
* Login page
  * Prevents hijacking by other users on the network

### Future Features
* Keyboard control (on music.php)
* Voice command
* ???

### Known Bugs

### Screenshots
Coming soon

## Usage
This repository is a replica of `/var/www`'s contents. You can plunge right in and look at the code, but I've also included general instructions below (some of which are already included in the code and some of which need to be configured by the user). This was my first project with my Raspberry Pi, so I made an effort to meticulously document stuff I did.

### Materials
* Raspberry Pi, USB Power Adapter, SD Card, Micro USB Cable, USB WiFi dongle, USB keyboard & mouse
* Male-to-male audio cable, speakers
* X10 CM17A and TM751 transceiver (or CM11A), USB/serial adapter, AM466 appliance modules, WS13A wall switches

### Raspberry Pi Setup
See [Quick Start Guide](http://www.raspberrypi.org/wp-content/uploads/2012/12/quick-start-guide-v1.1.pdf).

### Web Server Setup
1. Install Apache and PHP (`apt-get install apache2` and `apt-get install php5`)
2. Change the user Apache runs as and change config file location:
  * In `/etc/apache2/envvars`, add `export XDG_CONFIG_HOME=/var/www`
  * Also in that file, change `export APACHE_RUN_USER` and `export APACHE_RUN_GROUP` from `www-data` to `pi`
  * Then change ownership of `/var/lock/apache2` to `pi` and restart Apache (`service apache2 restart`)
  * This helps mitigate problems with pianobar
3. Set up webpages (see `index.php`, `music.php`, `lighting.php`, `functions.php`, etc.)
4. Set up CSS stylesheet
  * Make a `style.css` file in `/var/www/css`
5. Set up scripts
  * See `/var/www/code` folder
6. Set website login username/password in `login.php`

### Pianobar Setup
1. Install pianobar (https://github.com/PromyLOPh/pianobar; read [this](http://www.engscope.com/pandorabar/02-compiling-pianobar/) if you need instructions)
2. Set up a config file in `~/.config/pianobar/config` (use `mkdir` to make the `pianobar` directory)
  * Add username and password
  * Add `tls_fingerprint = 2D0AFDAFA16F4B5C0A43F3CB1D4752F9535507C0`
  * Make sure paths for `event_command` and `ctl` are set (more on this later)
  * Change `autostart_station` to whatever station you want to automatically start playing (copy and paste number after you start pianobar and choose that station)
3. Make a directory named `pianobar` in `/var/www/` and copy `config` to that folder
  * Add `eventcommand.sh` and `ctl` (`mkfifo`) to that folder as well
  * Add `state` and `volume` files (the `pandora.sh` script will use them later)
4. Since the `config` file contains the username and password, you will want to hide it from browser access: 
  * Create `.htaccess` in the `pianobar` folder and add the following: 

    ```
    <Files config>
    order deny,allow
    deny from all
    </Files>
    ```
  * Then in `/etc/apache2/sites-enabled/000-default`, change `AllowOverride None` to `AllowOverride All` under `<Directory /var/www>` and restart Apache
6. Use the `pandora.sh` script to control pianobar

### X10 Setup
1. [Install heyu](http://x10linux.blogspot.com/2012/08/installing-heyu-on-raspberry-pi.html)
  * Make sure that when it asks you where you want the heyu config files installed, choose option 3 (`in directory /etc/heyu for system-wide access`)
2. Use the `lights.sh` script to control heyu

### Task Scheduler Setup
1. Make sure your Pi is set to the proper time using `dpkg-reconfigure tzdata` (check using the `date` command)
2. As the user `pi`, execute `crontab -e`. Set the first line to `MAILTO=""` (so cron doesn't email you every time it does something)
3. Use the `settings.php` page to add and remove cron jobs
  * This page uses the `cronedit.sh` script to add and remove cron jobs
4. To use the email feature, install [SSMTP](http://rpi.tnet.com/project/faqs/smtp)

### Webpage NFC Control
1. You can set your phone to open up the webpages in the `commands` folder on the server upon NFC activation.
  * For example, if I tap the NFC tag by my bed, I ask the pi to execute `command.sh bed`
2. To personalize times, set the latitude and longitude as well as awake, bed, and delay times in `command.sh`
