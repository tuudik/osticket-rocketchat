osTicket-rocketchat
==============
An plugin for [osTicket](https://osticket.com) which posts notifications to a [Rocket.Chat](https://github.com/RocketChat) channel.

Install
--------
Clone this repo or download the zip file and place the contents into your `include/plugins` folder.
Download Html2Text.php from https://github.com/soundasleep/html2text and copy it in plugin root folder.

Features
--------
* Define Emoji
* Escape Text (Using Html2Text)
* Remove double newlines
* Define how long text to send
* Define colors

Info
------
This plugin uses CURL and tested on osTicket-v1.8 (19292ad).

Forked from
------
This repo is forked from https://github.com/thammanna/osticket-slack and modified to work with Rocket.Chat.
Some additional features added from: https://github.com/Runalyze/osticket-slack.
