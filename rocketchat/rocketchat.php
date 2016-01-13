<?php

require_once(INCLUDE_DIR . 'class.signal.php');
require_once(INCLUDE_DIR . 'class.plugin.php');
require_once('config.php');

class RocketChatPlugin extends Plugin
{
    var $config_class = "RocketChatPluginConfig";
    function bootstrap()
    {
        Signal::connect('model.created', array(
            $this,
            'onTicketCreated'
        ), 'Ticket');
        Signal::connect('model.created', array(
            $this,
            'onThreadEntryCreated'
        ), 'ThreadEntry');
    }
    function onThreadEntryCreated($entry)
    {
        if ($entry->ht['thread_type'] == 'R') {
            // Responses by staff
            $this->onResponseCreated($entry);
        } elseif ($entry->ht['thread_type'] == 'N') {
            // Notes by staff or system
            $this->onNoteCreated($entry);
        } else {
            // New tickets or responses by users
            $this->onMessageCreated($entry);
        }
    }
    function onResponseCreated($response)
    {
        $this->sendThreadEntryToRocketChat($response, 'Response', $this->getConfig()->get('rocketchat-color-warning'));
    }

    function onNoteCreated($note)
    {
        $this->sendThreadEntryToRocketChat($note, 'Note', $this->getConfig()->get('rocketchat-color-good'));
    }

    function onMessageCreated($message)
    {
        $this->sendThreadEntryToRocketChat($message, 'Message', $this->getConfig()->get('rocketchat-color-danger'));
    }
    function sendThreadEntryToRocketChat($entry, $label, $color)
    {
        global $ost;
        $ticketLink = $ost->getConfig()->getUrl() . 'scp/tickets.php?id=' . $entry->getTicket()->getId();
        $title      = $entry->getTitle() ?: $label;
        $body       = $entry->getBody() ?: $entry->ht['body'] ?: 'No content';
        $this->sendToRocketChat(array(
            'username' => $this->getConfig()->get('rocketchat-username'),
            'icon_emoji' => $this->getConfig()->get('rocketchat-icon_emoji'),
            'text' => $label . ' by ' . $entry->getPoster(),
            'attachments' => array(
                array(
                    'title' => 'Ticket ' . $entry->getTicket()->getNumber() . ': ' . $title,
                    'title_link' => $ticketLink,
                    'text' => $this->escapeText($body),
                    'color' => $color
                )
            )
        ));
 }
    function onTicketCreated($ticket)
    {
        global $ost;

        $ticketLink = $ost->getConfig()->getUrl() . 'scp/tickets.php?id=' . $ticket->getId();

        $title = $ticket->getSubject() ?: 'No subject';
        $body  = $ticket->getLastMessage()->getMessage() ?: 'No content';

        $this->sendToRocketChat(array(
            'username' => $this->getConfig()->get('rocketchat-username'),
            'icon_emoji' => $this->getConfig()->get('rocketchat-icon_emoji'),
            'text' => 'New Ticket <' . $ticketLink . '> by ' . $ticket->getName() . ' (' . $ticket->getEmail() . ')',
            'attachments' => array(
                array(
                    'title' => 'Ticket ' . $ticket->getNumber() . ': ' . $title,
                    'title_link' => $ticketLink,
                    'text' => $this->escapeText($body),
                    'color' => $this->getConfig()->get('rocketchat-color-danger')

                )
            )
        ));
    }
    function sendToRocketChat($payload)
    {
        try {
            global $ost;

            $data_string = utf8_encode(json_encode($payload));
            $url         = $this->getConfig()->get('rocketchat-webhook-url');
            $ch          = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string)
            ));
            if (curl_exec($ch) === false) {
                throw new Exception($url . ' - ' . curl_error($ch));
            } else {
                $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                if ($statusCode != '200') {
                    throw new Exception($url . ' Http code: ' . $statusCode);
                }
            }
            curl_close($ch);
        }
        catch (Exception $e) {
            error_log('Error posting to Rocket.Chat. ' . $e->getMessage());
        }
    }

    function escapeText($text)
    {
        $text = str_replace('<br />', ' ', $text);
        $text = strip_tags($text);
        $text = str_replace('&', '&amp;', $text);
        $text = str_replace('<', '&lt;', $text);
        $text = str_replace('>', '&gt;', $text);
        if (strlen($text) >= $this->getConfig()->get('rocketchat-text-length')) {
            $text = substr($text, 0, $this->getConfig()->get('rocketchat-text-length')) . '...';
        }
        ;
        return $text;
    }
}

