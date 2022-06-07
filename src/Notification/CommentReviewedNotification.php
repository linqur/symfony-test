<?php

namespace App\Notification;

use App\Entity\Comment;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\Notifier\Message\EmailMessage;
use Symfony\Component\Notifier\Notification\EmailNotificationInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\EmailRecipientInterface;

class CommentReviewedNotification extends Notification implements EmailNotificationInterface
{
    private $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;

        parent::__construct();
    }
    
    public function asEmailMessage(EmailRecipientInterface $recipient, ?string $transport = null): ?EmailMessage
    {   
        $message = EmailMessage::fromNotification($this, $recipient, $transport);

        $message->getMessage()
            ->htmlTemplate('emails/comment_reviewed_notification.html.twig')
            ->context([
                'comment' => $this->comment,
                'conference' => $this->comment->getConference(),
            ])
        ;
        
        return $message;
    }
}