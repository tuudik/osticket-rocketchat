<?php
require_once INCLUDE_DIR . 'class.plugin.php';
class RocketChatPluginConfig extends PluginConfig
{
	function getOptions()
	{
		return array(
			'rocketchat' => new SectionBreakField(array(
				'label' => 'Rocket.Chat notifier'
			)),
			'rocketchat-webhook-url' => new TextboxField(array(
				'label' => 'Webhook URL',
				'configuration' => array(
					'size' => 100,
					'length' => 200
				)
			)),
			'rocketchat-username' => new TextboxField(array(
				'label' => 'Username',
				'configuration' => array(
					'size' => 20,
					'length' => 20
				)
			)),
			'rocketchat-icon_emoji' => new TextboxField(array(
				'label' => 'Emoji',
				'configuration' => array(
					'size' => 20,
					'length' => 20
				)
			)),
			'rocketchat-text-escape' => new BooleanField(array(
				'id' => 'rocketchat-text-escape',
				'label' => 'Escape text',
				'configuration' => array(
					'desc' => 'Check to escape text (You must have <a href="https://github.com/soundasleep/html2text/blob/master/src/Html2Text.php">Html2Text</a> in plugin root directory for full functionality)'
				)
			)),
			'rocketchat-text-doublenl' => new BooleanField(array(
				'id' => 'rocketchat-text-doublenl',
				'label' => 'Remove double newlines',
				'configuration' => array(
					'desc' => 'Check to remove double newlines'
				)
			)),
			'rocketchat-text-length' => new TextboxField(array(
				'label' => 'Text length to show',
				'configuration' => array(
					'size' => 20,
					'length' => 20
				)
			)),
			'rocketchat-color-good' => new TextboxField(array(
				'label' => 'Alert good color',
				'configuration' => array(
					'size' => 7,
					'length' => 7
				)
			)),
			'rocketchat-color-warning' => new TextboxField(array(
				'label' => 'Alert warning color',
				'configuration' => array(
					'size' => 7,
					'length' => 7
				)
			)),
			'rocketchat-color-danger' => new TextboxField(array(
                                'label' => 'Alert warning danger',
                                'configuration' => array(
                                        'size' => 7,
                                        'length' => 7
                                )
                        ))
		);
	}
}

