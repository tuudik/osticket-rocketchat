<?php

require_once INCLUDE_DIR . 'class.plugin.php';

class RocketChatPluginConfig extends PluginConfig {
    function getOptions() {        
        return array(
            'rocketchat' => new SectionBreakField(array(
                'label' => 'Rocket.Chat notifier',
            )),
            'rocketchat-webhook-url' => new TextboxField(array(
                'label' => 'Webhook URL',
                'configuration' => array('size'=>100, 'length'=>200),
            )),
            'rocketchat-username' => new TextboxField(array(
                'label' => 'Username',
                'configuration' => array('size'=>20, 'length'=>20),
            )),
            'rocketchat-icon_emoji' => new TextboxField(array(
                'label' => 'Emoji',
                'configuration' => array('size'=>20, 'length'=>20),
            )),	
            'rocketchat-alert-text' => new TextboxField(array(
                'label' => 'Alert text',
                'configuration' => array('size'=>20, 'length'=>20),
            )),	
            'rocketchat-alert-color' => new TextboxField(array(
                'label' => 'Alert color',
                'configuration' => array('size'=>20, 'length'=>20),
            )),	
        );
    }	
}
