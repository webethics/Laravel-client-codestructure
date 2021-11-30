<?php

namespace App\Notifications;

use App\Location;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NotifyAdminOfLocationSubmission extends Notification
{
    use Queueable;

    protected $location;
    protected $request;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Location $location, $request)
    {
        $this->location = $location;
        $this->request = $request;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $request = $this->request;

        return (new MailMessage)
                    ->subject('New Location Submission: ' . $this->location->name)
                    ->greeting('Hi there!')
                    ->line($request->get('submitter_name') . ' has submitted a new location for review.')
                    ->line('Submitter Name: ' . $request->get('submitter_name'))
                    ->line('Submitter Email: ' . $request->get('submitter_email'))
                    ->line('Submitter Phone: ' . $request->get('submitter_phone', 'Not provided'))
                    ->action('Review Location', route('admin.location.edit', $this->location));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
